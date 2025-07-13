<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Blog;
use App\Models\Webinar;
use App\Models\Page;
use App\Models\MediaLibrary;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate {--type=main : Type of sitemap to generate (main, images, news)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate XML sitemap for SEO';

    /**
     * Maximum URLs per sitemap file
     */
    const MAX_URLS_PER_SITEMAP = 50000;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->option('type');
        
        switch ($type) {
            case 'images':
                $this->generateImageSitemap();
                break;
            case 'news':
                $this->generateNewsSitemap();
                break;
            default:
                $this->generateMainSitemap();
                break;
        }
    }

    /**
     * Generate main sitemap
     */
    private function generateMainSitemap()
    {
        $this->info('Generating main sitemap...');

        $urls = [];
        $urlCount = 0;

        // Add static pages
        $staticPages = [
            '/' => ['priority' => '1.0', 'changefreq' => 'daily'],
            '/blogs' => ['priority' => '0.8', 'changefreq' => 'daily'],
            '/webinars' => ['priority' => '0.8', 'changefreq' => 'daily'],
            '/about' => ['priority' => '0.6', 'changefreq' => 'monthly'],
            '/contact' => ['priority' => '0.5', 'changefreq' => 'monthly'],
            '/privacy' => ['priority' => '0.3', 'changefreq' => 'yearly'],
            '/terms' => ['priority' => '0.3', 'changefreq' => 'yearly'],
            '/research' => ['priority' => '0.7', 'changefreq' => 'weekly'],
            '/contributions' => ['priority' => '0.6', 'changefreq' => 'weekly'],
        ];

        foreach ($staticPages as $url => $settings) {
            $urls[] = $this->generateUrlTag($url, $settings['priority'], $settings['changefreq']);
            $urlCount++;
        }

        // Add blog posts
        $blogs = Blog::where('is_published', true)->get();
        foreach ($blogs as $blog) {
            $urls[] = $this->generateUrlTag(
                '/blog/' . $blog->slug,
                '0.7',
                'weekly',
                $blog->updated_at
            );
            $urlCount++;
        }

        // Add webinars
        $webinars = Webinar::where('is_public', true)->get();
        foreach ($webinars as $webinar) {
            $urls[] = $this->generateUrlTag(
                '/webinar/' . $webinar->id,
                '0.6',
                'weekly',
                $webinar->updated_at
            );
            $urlCount++;
        }

        // Add dynamic pages
        $pages = Page::where('is_published', true)->get();
        foreach ($pages as $page) {
            $urls[] = $this->generateUrlTag(
                '/' . $page->slug,
                '0.5',
                'monthly',
                $page->updated_at
            );
            $urlCount++;
        }

        // Check if we need to split into multiple sitemaps
        if ($urlCount > self::MAX_URLS_PER_SITEMAP) {
            $this->generateSitemapIndex($urls);
        } else {
            $this->writeSitemap($urls, 'sitemap.xml');
        }

        $this->info("Main sitemap generated successfully with {$urlCount} URLs");
    }

    /**
     * Generate image sitemap
     */
    private function generateImageSitemap()
    {
        $this->info('Generating image sitemap...');

        $urls = [];
        $urlCount = 0;

        // Get images from media library
        $images = MediaLibrary::where('type', 'image')
            ->where('is_public', true)
            ->get();

        foreach ($images as $image) {
            $urls[] = $this->generateImageUrlTag($image);
            $urlCount++;
        }

        // Get images from blog posts
        $blogs = Blog::where('is_published', true)->get();
        foreach ($blogs as $blog) {
            if ($blog->featured_image) {
                $urls[] = $this->generateImageUrlTag($blog, 'blog');
                $urlCount++;
            }
        }

        $this->writeSitemap($urls, 'sitemap-images.xml');
        $this->info("Image sitemap generated successfully with {$urlCount} images");
    }

    /**
     * Generate news sitemap
     */
    private function generateNewsSitemap()
    {
        $this->info('Generating news sitemap...');

        $urls = [];
        $urlCount = 0;

        // Get recent blog posts (last 2 days)
        $recentBlogs = Blog::where('is_published', true)
            ->where('created_at', '>=', now()->subDays(2))
            ->get();

        foreach ($recentBlogs as $blog) {
            $urls[] = $this->generateNewsUrlTag($blog);
            $urlCount++;
        }

        $this->writeSitemap($urls, 'sitemap-news.xml');
        $this->info("News sitemap generated successfully with {$urlCount} news articles");
    }

    /**
     * Generate sitemap index for large sites
     */
    private function generateSitemapIndex($urls)
    {
        $chunks = array_chunk($urls, self::MAX_URLS_PER_SITEMAP);
        
        $indexXml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $indexXml .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($chunks as $index => $chunk) {
            $filename = "sitemap-" . ($index + 1) . ".xml";
            $this->writeSitemap($chunk, $filename);
            
            $indexXml .= '  <sitemap>' . "\n";
            $indexXml .= '    <loc>' . URL::to($filename) . '</loc>' . "\n";
            $indexXml .= '    <lastmod>' . now()->toISOString() . '</lastmod>' . "\n";
            $indexXml .= '  </sitemap>' . "\n";
        }

        $indexXml .= '</sitemapindex>';
        
        File::put(public_path('sitemap.xml'), $indexXml);
        $this->info('Sitemap index generated with ' . count($chunks) . ' sitemap files');
    }

    /**
     * Write sitemap to file
     */
    private function writeSitemap($urls, $filename)
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        
        foreach ($urls as $url) {
            $xml .= $url;
        }
        
        $xml .= '</urlset>';
        
        File::put(public_path($filename), $xml);
    }

    /**
     * Generate a URL tag for the sitemap.
     */
    private function generateUrlTag($url, $priority, $changefreq, $lastmod = null)
    {
        $xml = '  <url>' . "\n";
        $xml .= '    <loc>' . URL::to($url) . '</loc>' . "\n";
        
        if ($lastmod) {
            $xml .= '    <lastmod>' . $lastmod->toISOString() . '</lastmod>' . "\n";
        }
        
        $xml .= '    <changefreq>' . $changefreq . '</changefreq>' . "\n";
        $xml .= '    <priority>' . $priority . '</priority>' . "\n";
        $xml .= '  </url>' . "\n";
        
        return $xml;
    }

    /**
     * Generate an image URL tag for the sitemap.
     */
    private function generateImageUrlTag($item, $type = 'media')
    {
        $xml = '  <url>' . "\n";
        
        if ($type === 'blog') {
            $xml .= '    <loc>' . URL::to('/blog/' . $item->slug) . '</loc>' . "\n";
            $imageUrl = $item->featured_image;
        } else {
            $xml .= '    <loc>' . URL::to('/media/' . $item->id) . '</loc>' . "\n";
            $imageUrl = $item->file_path;
        }
        
        $xml .= '    <image:image>' . "\n";
        $xml .= '      <image:loc>' . URL::to($imageUrl) . '</image:loc>' . "\n";
        if ($item->title) {
            $xml .= '      <image:title>' . htmlspecialchars($item->title) . '</image:title>' . "\n";
        }
        if ($item->description) {
            $xml .= '      <image:caption>' . htmlspecialchars($item->description) . '</image:caption>' . "\n";
        }
        $xml .= '    </image:image>' . "\n";
        $xml .= '  </url>' . "\n";
        
        return $xml;
    }

    /**
     * Generate a news URL tag for the sitemap.
     */
    private function generateNewsUrlTag($blog)
    {
        $xml = '  <url>' . "\n";
        $xml .= '    <loc>' . URL::to('/blog/' . $blog->slug) . '</loc>' . "\n";
        $xml .= '    <news:news>' . "\n";
        $xml .= '      <news:publication>' . "\n";
        $xml .= '        <news:name>The Qualitative Research Series</news:name>' . "\n";
        $xml .= '        <news:language>en</news:language>' . "\n";
        $xml .= '      </news:publication>' . "\n";
        $xml .= '      <news:publication_date>' . $blog->created_at->toISOString() . '</news:publication_date>' . "\n";
        $xml .= '      <news:title>' . htmlspecialchars($blog->title) . '</news:title>' . "\n";
        if ($blog->description) {
            $xml .= '      <news:keywords>' . htmlspecialchars($blog->description) . '</news:keywords>' . "\n";
        }
        $xml .= '    </news:news>' . "\n";
        $xml .= '  </url>' . "\n";
        
        return $xml;
    }
} 