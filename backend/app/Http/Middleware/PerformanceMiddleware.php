<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

class PerformanceMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Start performance monitoring
        $startTime = microtime(true);
        $startMemory = memory_get_usage(true);
        $startQueries = $this->getQueryCount();
        
        // Check for cached response
        $cacheKey = $this->getCacheKey($request);
        $cachedResponse = $this->getCachedResponse($request, $cacheKey);
        
        if ($cachedResponse) {
            $this->logPerformanceMetrics($request, $startTime, $startMemory, $startQueries, true);
            return $cachedResponse;
        }
        
        // Process the request
        $response = $next($request);
        
        // Cache the response if applicable
        $this->cacheResponse($request, $response, $cacheKey);
        
        // Log performance metrics
        $this->logPerformanceMetrics($request, $startTime, $startMemory, $startQueries, false);
        
        // Add performance headers
        $this->addPerformanceHeaders($response, $startTime, $startMemory);
        
        return $response;
    }
    
    /**
     * Get cache key for the request
     */
    private function getCacheKey(Request $request): string
    {
        $key = 'api_response_' . $request->getMethod() . '_' . $request->getPathInfo();
        
        // Include query parameters in cache key
        if ($request->query()) {
            $key .= '_' . md5(http_build_query($request->query()));
        }
        
        // Include user ID for authenticated requests
        if ($request->user()) {
            $key .= '_user_' . $request->user()->id;
        }
        
        return $key;
    }
    
    /**
     * Get cached response if available
     */
    private function getCachedResponse(Request $request, string $cacheKey): ?Response
    {
        if (!Config::get('performance.api_cache.enabled', false)) {
            return null;
        }
        
        // Don't cache write operations
        if (in_array($request->getMethod(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            return null;
        }
        
        // Check if endpoint is cacheable
        $ttl = $this->getCacheTtl($request);
        if ($ttl <= 0) {
            return null;
        }
        
        $cached = Cache::get($cacheKey);
        if ($cached) {
            // Reconstruct response from cached data
            $response = new Response($cached['content'], $cached['status']);
            $response->headers->add($cached['headers']);
            $response->headers->set('X-Cache-Status', 'HIT');
            
            return $response;
        }
        
        return null;
    }
    
    /**
     * Cache the response if applicable
     */
    private function cacheResponse(Request $request, Response $response, string $cacheKey): void
    {
        if (!Config::get('performance.api_cache.enabled', false)) {
            return;
        }
        
        // Only cache successful GET requests
        if ($request->getMethod() !== 'GET' || $response->getStatusCode() !== 200) {
            return;
        }
        
        $ttl = $this->getCacheTtl($request);
        if ($ttl <= 0) {
            return;
        }
        
        // Cache the response
        $cacheData = [
            'content' => $response->getContent(),
            'status' => $response->getStatusCode(),
            'headers' => $response->headers->all(),
            'cached_at' => now()->toISOString(),
        ];
        
        Cache::put($cacheKey, $cacheData, $ttl);
        
        // Add cache headers
        $response->headers->set('X-Cache-Status', 'MISS');
        $response->headers->set('X-Cache-TTL', $ttl);
    }
    
    /**
     * Get cache TTL for the request
     */
    private function getCacheTtl(Request $request): int
    {
        $path = $request->getPathInfo();
        $isAuthenticated = $request->user() !== null;
        
        if ($isAuthenticated) {
            $endpoints = Config::get('performance.api_cache.authenticated_endpoints', []);
        } else {
            $endpoints = Config::get('performance.api_cache.public_endpoints', []);
        }
        
        // Check for specific endpoint configuration
        foreach ($endpoints as $endpoint => $ttl) {
            if (strpos($path, $endpoint) !== false) {
                return $ttl;
            }
        }
        
        // Default TTL
        return Config::get('performance.api_cache.default_ttl', 0);
    }
    
    /**
     * Log performance metrics
     */
    private function logPerformanceMetrics(Request $request, float $startTime, int $startMemory, int $startQueries, bool $fromCache): void
    {
        if (!Config::get('performance.monitoring.enabled', false)) {
            return;
        }
        
        $endTime = microtime(true);
        $endMemory = memory_get_usage(true);
        $endQueries = $this->getQueryCount();
        
        $metrics = [
            'url' => $request->url(),
            'method' => $request->getMethod(),
            'user_id' => $request->user()?->id,
            'response_time' => round(($endTime - $startTime) * 1000, 2), // milliseconds
            'memory_usage' => $endMemory - $startMemory,
            'memory_peak' => memory_get_peak_usage(true),
            'query_count' => $endQueries - $startQueries,
            'from_cache' => $fromCache,
            'timestamp' => now()->toISOString(),
        ];
        
        // Log slow requests
        $slowThreshold = Config::get('performance.monitoring.slow_request_threshold', 2000);
        if ($metrics['response_time'] > $slowThreshold) {
            Log::channel(Config::get('performance.monitoring.performance_log_channel', 'single'))
                ->warning('Slow request detected', $metrics);
        }
        
        // Log all requests in debug mode
        if (Config::get('app.debug', false)) {
            Log::channel(Config::get('performance.monitoring.performance_log_channel', 'single'))
                ->info('Request performance', $metrics);
        }
        
        // Store metrics for analytics
        $this->storeMetrics($metrics);
    }
    
    /**
     * Add performance headers to response
     */
    private function addPerformanceHeaders(Response $response, float $startTime, int $startMemory): void
    {
        if (!Config::get('app.debug', false)) {
            return;
        }
        
        $endTime = microtime(true);
        $endMemory = memory_get_usage(true);
        
        $response->headers->set('X-Response-Time', round(($endTime - $startTime) * 1000, 2) . 'ms');
        $response->headers->set('X-Memory-Usage', $this->formatBytes($endMemory - $startMemory));
        $response->headers->set('X-Memory-Peak', $this->formatBytes(memory_get_peak_usage(true)));
        $response->headers->set('X-Query-Count', $this->getQueryCount());
    }
    
    /**
     * Get current query count
     */
    private function getQueryCount(): int
    {
        return count(DB::getQueryLog());
    }
    
    /**
     * Format bytes to human readable format
     */
    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        
        return round($bytes, 2) . ' ' . $units[$pow];
    }
    
    /**
     * Store metrics for analytics
     */
    private function storeMetrics(array $metrics): void
    {
        // Store metrics in cache for later processing
        $metricsKey = 'performance_metrics_' . date('Y-m-d-H');
        $existingMetrics = Cache::get($metricsKey, []);
        $existingMetrics[] = $metrics;
        
        // Keep only last 1000 metrics per hour
        if (count($existingMetrics) > 1000) {
            $existingMetrics = array_slice($existingMetrics, -1000);
        }
        
        Cache::put($metricsKey, $existingMetrics, 3600); // 1 hour
    }
    
    /**
     * Get performance metrics for analytics
     */
    public static function getMetrics(string $period = 'hour'): array
    {
        $metrics = [];
        
        switch ($period) {
            case 'hour':
                $key = 'performance_metrics_' . date('Y-m-d-H');
                $metrics = Cache::get($key, []);
                break;
                
            case 'day':
                for ($i = 0; $i < 24; $i++) {
                    $key = 'performance_metrics_' . date('Y-m-d-H', strtotime("-{$i} hours"));
                    $hourMetrics = Cache::get($key, []);
                    $metrics = array_merge($metrics, $hourMetrics);
                }
                break;
                
            case 'week':
                for ($i = 0; $i < 7; $i++) {
                    for ($j = 0; $j < 24; $j++) {
                        $key = 'performance_metrics_' . date('Y-m-d-H', strtotime("-{$i} days -{$j} hours"));
                        $hourMetrics = Cache::get($key, []);
                        $metrics = array_merge($metrics, $hourMetrics);
                    }
                }
                break;
        }
        
        return $metrics;
    }
    
    /**
     * Get performance summary
     */
    public static function getPerformanceSummary(string $period = 'hour'): array
    {
        $metrics = self::getMetrics($period);
        
        if (empty($metrics)) {
            return [
                'total_requests' => 0,
                'avg_response_time' => 0,
                'max_response_time' => 0,
                'min_response_time' => 0,
                'avg_memory_usage' => 0,
                'avg_query_count' => 0,
                'cache_hit_rate' => 0,
                'slow_requests' => 0,
            ];
        }
        
        $responseTimes = array_column($metrics, 'response_time');
        $memoryUsages = array_column($metrics, 'memory_usage');
        $queryCounts = array_column($metrics, 'query_count');
        $cacheHits = array_filter($metrics, fn($m) => $m['from_cache'] ?? false);
        $slowRequests = array_filter($metrics, fn($m) => $m['response_time'] > Config::get('performance.monitoring.slow_request_threshold', 2000));
        
        return [
            'total_requests' => count($metrics),
            'avg_response_time' => round(array_sum($responseTimes) / count($responseTimes), 2),
            'max_response_time' => max($responseTimes),
            'min_response_time' => min($responseTimes),
            'avg_memory_usage' => round(array_sum($memoryUsages) / count($memoryUsages)),
            'avg_query_count' => round(array_sum($queryCounts) / count($queryCounts), 2),
            'cache_hit_rate' => round((count($cacheHits) / count($metrics)) * 100, 2),
            'slow_requests' => count($slowRequests),
            'period' => $period,
        ];
    }
    
    /**
     * Clear performance metrics
     */
    public static function clearMetrics(): void
    {
        $patterns = [
            'performance_metrics_*',
            'api_response_*',
        ];
        
        foreach ($patterns as $pattern) {
            $keys = Cache::getRedis()->keys($pattern);
            if ($keys) {
                Cache::getRedis()->del($keys);
            }
        }
    }
} 