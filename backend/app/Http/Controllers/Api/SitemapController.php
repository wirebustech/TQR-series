<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Console\Commands\GenerateSitemap;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class SitemapController extends Controller
{
    /**
     * Generate sitemap via API
     */
    public function generate(Request $request): JsonResponse
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'type' => 'string|in:main,images,news,all',
                'force' => 'boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $type = $request->input('type', 'main');
            $force = $request->boolean('force', false);

            // Check if sitemap exists and is recent (unless forced)
            if (!$force && $type === 'main') {
                $sitemapPath = public_path('sitemap.xml');
                if (File::exists($sitemapPath)) {
                    $lastModified = filemtime($sitemapPath);
                    $hoursSinceLastUpdate = (time() - $lastModified) / 3600;
                    
                    if ($hoursSinceLastUpdate < 24) {
                        return response()->json([
                            'success' => true,
                            'message' => 'Sitemap is recent (less than 24 hours old)',
                            'last_updated' => date('Y-m-d H:i:s', $lastModified),
                            'hours_old' => round($hoursSinceLastUpdate, 2),
                            'skipped' => true
                        ]);
                    }
                }
            }

            $results = [];
            
            if ($type === 'all') {
                // Generate all sitemap types
                $types = ['main', 'images', 'news'];
                foreach ($types as $sitemapType) {
                    $results[$sitemapType] = $this->generateSitemapType($sitemapType);
                }
            } else {
                $results[$type] = $this->generateSitemapType($type);
            }

            return response()->json([
                'success' => true,
                'message' => 'Sitemap(s) generated successfully',
                'results' => $results,
                'generated_at' => now()->toISOString()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error generating sitemap: ' . $e->getMessage(),
                'trace' => config('app.debug') ? $e->getTraceAsString() : null
            ], 500);
        }
    }

    /**
     * Generate specific sitemap type
     */
    private function generateSitemapType(string $type): array
    {
        $command = new GenerateSitemap();
        $command->setLaravel(app());
        
        // Capture command output
        ob_start();
        $command->run(new \Symfony\Component\Console\Input\ArrayInput(['--type' => $type]), new \Symfony\Component\Console\Output\BufferedOutput());
        $output = ob_get_clean();

        $filename = $this->getSitemapFilename($type);
        $sitemapPath = public_path($filename);
        
        if (!File::exists($sitemapPath)) {
            throw new \Exception("Failed to generate {$type} sitemap");
        }

        // Count URLs in the generated sitemap
        $xml = file_get_contents($sitemapPath);
        $urlCount = substr_count($xml, '<url>');
        
        return [
            'type' => $type,
            'filename' => $filename,
            'file_path' => $sitemapPath,
            'total_urls' => $urlCount,
            'file_size' => filesize($sitemapPath),
            'output' => trim($output)
        ];
    }

    /**
     * Get sitemap status
     */
    public function status(): JsonResponse
    {
        try {
            $sitemaps = [
                'main' => 'sitemap.xml',
                'images' => 'sitemap-images.xml',
                'news' => 'sitemap-news.xml'
            ];

            $status = [];
            $totalUrls = 0;
            $totalSize = 0;

            foreach ($sitemaps as $type => $filename) {
                $sitemapPath = public_path($filename);
                
                if (File::exists($sitemapPath)) {
                    $xml = file_get_contents($sitemapPath);
                    $urlCount = substr_count($xml, '<url>');
                    $fileSize = filesize($sitemapPath);
                    $lastModified = filemtime($sitemapPath);
                    
                    $status[$type] = [
                        'exists' => true,
                        'filename' => $filename,
                        'total_urls' => $urlCount,
                        'file_size' => $fileSize,
                        'last_modified' => date('Y-m-d H:i:s', $lastModified),
                        'file_path' => $sitemapPath,
                        'hours_old' => round((time() - $lastModified) / 3600, 2)
                    ];
                    
                    $totalUrls += $urlCount;
                    $totalSize += $fileSize;
                } else {
                    $status[$type] = [
                        'exists' => false,
                        'filename' => $filename,
                        'message' => 'Sitemap not found'
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'sitemaps' => $status,
                'summary' => [
                    'total_sitemaps' => count(array_filter($status, fn($s) => $s['exists'])),
                    'total_urls' => $totalUrls,
                    'total_size' => $totalSize,
                    'last_updated' => $this->getLastUpdatedTime($status)
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error checking sitemap status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validate sitemap XML
     */
    public function validate(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'type' => 'string|in:main,images,news'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $type = $request->input('type', 'main');
            $filename = $this->getSitemapFilename($type);
            $sitemapPath = public_path($filename);

            if (!File::exists($sitemapPath)) {
                return response()->json([
                    'success' => false,
                    'message' => "Sitemap {$filename} not found"
                ], 404);
            }

            $xml = file_get_contents($sitemapPath);
            $validation = $this->validateSitemapXml($xml);

            return response()->json([
                'success' => true,
                'type' => $type,
                'filename' => $filename,
                'validation' => $validation
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error validating sitemap: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get sitemap statistics
     */
    public function stats(): JsonResponse
    {
        try {
            $stats = [
                'total_sitemaps' => 0,
                'total_urls' => 0,
                'total_size' => 0,
                'oldest_sitemap' => null,
                'newest_sitemap' => null,
                'sitemap_types' => []
            ];

            $sitemaps = [
                'main' => 'sitemap.xml',
                'images' => 'sitemap-images.xml',
                'news' => 'sitemap-news.xml'
            ];

            foreach ($sitemaps as $type => $filename) {
                $sitemapPath = public_path($filename);
                
                if (File::exists($sitemapPath)) {
                    $xml = file_get_contents($sitemapPath);
                    $urlCount = substr_count($xml, '<url>');
                    $fileSize = filesize($sitemapPath);
                    $lastModified = filemtime($sitemapPath);
                    
                    $stats['total_sitemaps']++;
                    $stats['total_urls'] += $urlCount;
                    $stats['total_size'] += $fileSize;
                    
                    $stats['sitemap_types'][$type] = [
                        'urls' => $urlCount,
                        'size' => $fileSize,
                        'last_modified' => date('Y-m-d H:i:s', $lastModified),
                        'age_hours' => round((time() - $lastModified) / 3600, 2)
                    ];
                    
                    // Track oldest and newest
                    if (!$stats['oldest_sitemap'] || $lastModified < $stats['oldest_sitemap']['timestamp']) {
                        $stats['oldest_sitemap'] = [
                            'type' => $type,
                            'timestamp' => $lastModified,
                            'date' => date('Y-m-d H:i:s', $lastModified)
                        ];
                    }
                    
                    if (!$stats['newest_sitemap'] || $lastModified > $stats['newest_sitemap']['timestamp']) {
                        $stats['newest_sitemap'] = [
                            'type' => $type,
                            'timestamp' => $lastModified,
                            'date' => date('Y-m-d H:i:s', $lastModified)
                        ];
                    }
                }
            }

            return response()->json([
                'success' => true,
                'stats' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error getting sitemap statistics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get sitemap filename for type
     */
    private function getSitemapFilename(string $type): string
    {
        return match($type) {
            'images' => 'sitemap-images.xml',
            'news' => 'sitemap-news.xml',
            default => 'sitemap.xml'
        };
    }

    /**
     * Validate sitemap XML structure
     */
    private function validateSitemapXml(string $xml): array
    {
        $errors = [];
        $warnings = [];

        // Basic XML validation
        libxml_use_internal_errors(true);
        $dom = new \DOMDocument();
        $dom->loadXML($xml);
        $xmlErrors = libxml_get_errors();
        libxml_clear_errors();

        if (!empty($xmlErrors)) {
            foreach ($xmlErrors as $error) {
                $errors[] = "XML Error: " . trim($error->message);
            }
        }

        // Check for required elements
        if (!str_contains($xml, '<?xml version="1.0"')) {
            $errors[] = "Missing XML declaration";
        }

        if (!str_contains($xml, '<urlset')) {
            $errors[] = "Missing urlset element";
        }

        if (!str_contains($xml, '</urlset>')) {
            $errors[] = "Missing closing urlset tag";
        }

        // Count URLs
        $urlCount = substr_count($xml, '<url>');
        if ($urlCount === 0) {
            $warnings[] = "No URLs found in sitemap";
        } elseif ($urlCount > 50000) {
            $warnings[] = "Sitemap contains more than 50,000 URLs (consider splitting)";
        }

        // Check for required URL elements
        $locCount = substr_count($xml, '<loc>');
        if ($locCount !== $urlCount) {
            $errors[] = "URL count mismatch: {$urlCount} URLs but {$locCount} locations";
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'warnings' => $warnings,
            'url_count' => $urlCount,
            'file_size' => strlen($xml)
        ];
    }

    /**
     * Get last updated time from sitemaps
     */
    private function getLastUpdatedTime(array $status): ?string
    {
        $latest = null;
        foreach ($status as $sitemap) {
            if ($sitemap['exists'] && isset($sitemap['last_modified'])) {
                if (!$latest || strtotime($sitemap['last_modified']) > strtotime($latest)) {
                    $latest = $sitemap['last_modified'];
                }
            }
        }
        return $latest;
    }
} 