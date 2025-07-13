<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Middleware\PerformanceMiddleware;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PerformanceController extends Controller
{
    /**
     * Get performance summary
     */
    public function summary(Request $request): JsonResponse
    {
        $period = $request->get('period', 'hour');
        
        $summary = PerformanceMiddleware::getPerformanceSummary($period);
        
        return response()->json([
            'success' => true,
            'data' => $summary,
            'message' => 'Performance summary retrieved successfully'
        ]);
    }
    
    /**
     * Get detailed performance metrics
     */
    public function metrics(Request $request): JsonResponse
    {
        $period = $request->get('period', 'hour');
        $limit = $request->get('limit', 100);
        
        $metrics = PerformanceMiddleware::getMetrics($period);
        
        // Limit results if requested
        if ($limit > 0) {
            $metrics = array_slice($metrics, -$limit);
        }
        
        return response()->json([
            'success' => true,
            'data' => $metrics,
            'count' => count($metrics),
            'period' => $period,
            'message' => 'Performance metrics retrieved successfully'
        ]);
    }
    
    /**
     * Get database performance statistics
     */
    public function database(): JsonResponse
    {
        $stats = [
            'connection_count' => DB::getConnections(),
            'query_log_count' => count(DB::getQueryLog()),
            'slow_query_threshold' => Config::get('performance.database.slow_query_threshold', 1000),
            'max_connections' => Config::get('performance.database.max_connections', 100),
            'query_cache_enabled' => Config::get('performance.query_cache.enabled', false),
        ];
        
        return response()->json([
            'success' => true,
            'data' => $stats,
            'message' => 'Database performance statistics retrieved successfully'
        ]);
    }
    
    /**
     * Get cache performance statistics
     */
    public function cache(): JsonResponse
    {
        $stats = [
            'cache_enabled' => Config::get('performance.api_cache.enabled', false),
            'default_ttl' => Config::get('performance.api_cache.default_ttl', 1800),
            'query_cache_enabled' => Config::get('performance.query_cache.enabled', false),
            'query_cache_ttl' => Config::get('performance.query_cache.ttl', 3600),
        ];
        
        // Get cache hit rates from recent metrics
        $metrics = PerformanceMiddleware::getMetrics('hour');
        if (!empty($metrics)) {
            $cacheHits = array_filter($metrics, fn($m) => $m['from_cache'] ?? false);
            $stats['cache_hit_rate'] = round((count($cacheHits) / count($metrics)) * 100, 2);
        } else {
            $stats['cache_hit_rate'] = 0;
        }
        
        return response()->json([
            'success' => true,
            'data' => $stats,
            'message' => 'Cache performance statistics retrieved successfully'
        ]);
    }
    
    /**
     * Get system resource usage
     */
    public function system(): JsonResponse
    {
        $stats = [
            'memory_usage' => memory_get_usage(true),
            'memory_peak' => memory_get_peak_usage(true),
            'memory_limit' => ini_get('memory_limit'),
            'execution_time' => round(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'], 3),
            'php_version' => PHP_VERSION,
            'server_time' => date('Y-m-d H:i:s'),
        ];
        
        return response()->json([
            'success' => true,
            'data' => $stats,
            'message' => 'System resource usage retrieved successfully'
        ]);
    }
    
    /**
     * Get slow queries from performance logs
     */
    public function slowQueries(): JsonResponse
    {
        $slowQueries = [];
        
        // Get recent metrics with high query counts or response times
        $metrics = PerformanceMiddleware::getMetrics('hour');
        $slowThreshold = Config::get('performance.monitoring.slow_request_threshold', 2000);
        
        foreach ($metrics as $metric) {
            if ($metric['response_time'] > $slowThreshold || $metric['query_count'] > 20) {
                $slowQueries[] = [
                    'url' => $metric['url'],
                    'method' => $metric['method'],
                    'response_time' => $metric['response_time'],
                    'query_count' => $metric['query_count'],
                    'memory_usage' => $metric['memory_usage'],
                    'timestamp' => $metric['timestamp'],
                ];
            }
        }
        
        // Sort by response time (descending)
        usort($slowQueries, fn($a, $b) => $b['response_time'] <=> $a['response_time']);
        
        return response()->json([
            'success' => true,
            'data' => array_slice($slowQueries, 0, 50), // Top 50 slow queries
            'count' => count($slowQueries),
            'message' => 'Slow queries retrieved successfully'
        ]);
    }
    
    /**
     * Get endpoint performance breakdown
     */
    public function endpoints(): JsonResponse
    {
        $metrics = PerformanceMiddleware::getMetrics('hour');
        $endpointStats = [];
        
        foreach ($metrics as $metric) {
            $url = $metric['url'];
            $method = $metric['method'];
            $key = $method . ' ' . $url;
            
            if (!isset($endpointStats[$key])) {
                $endpointStats[$key] = [
                    'url' => $url,
                    'method' => $method,
                    'requests' => 0,
                    'total_response_time' => 0,
                    'total_memory' => 0,
                    'total_queries' => 0,
                    'cache_hits' => 0,
                    'max_response_time' => 0,
                    'min_response_time' => PHP_INT_MAX,
                ];
            }
            
            $endpointStats[$key]['requests']++;
            $endpointStats[$key]['total_response_time'] += $metric['response_time'];
            $endpointStats[$key]['total_memory'] += $metric['memory_usage'];
            $endpointStats[$key]['total_queries'] += $metric['query_count'];
            $endpointStats[$key]['max_response_time'] = max($endpointStats[$key]['max_response_time'], $metric['response_time']);
            $endpointStats[$key]['min_response_time'] = min($endpointStats[$key]['min_response_time'], $metric['response_time']);
            
            if ($metric['from_cache'] ?? false) {
                $endpointStats[$key]['cache_hits']++;
            }
        }
        
        // Calculate averages
        foreach ($endpointStats as &$stats) {
            $stats['avg_response_time'] = round($stats['total_response_time'] / $stats['requests'], 2);
            $stats['avg_memory'] = round($stats['total_memory'] / $stats['requests']);
            $stats['avg_queries'] = round($stats['total_queries'] / $stats['requests'], 2);
            $stats['cache_hit_rate'] = round(($stats['cache_hits'] / $stats['requests']) * 100, 2);
        }
        
        // Sort by average response time
        uasort($endpointStats, fn($a, $b) => $b['avg_response_time'] <=> $a['avg_response_time']);
        
        return response()->json([
            'success' => true,
            'data' => array_values($endpointStats),
            'count' => count($endpointStats),
            'message' => 'Endpoint performance breakdown retrieved successfully'
        ]);
    }
    
    /**
     * Clear performance metrics cache
     */
    public function clearMetrics(): JsonResponse
    {
        try {
            PerformanceMiddleware::clearMetrics();
            
            return response()->json([
                'success' => true,
                'message' => 'Performance metrics cleared successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear performance metrics: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get performance configuration
     */
    public function config(): JsonResponse
    {
        $config = [
            'monitoring_enabled' => Config::get('performance.monitoring.enabled', false),
            'slow_request_threshold' => Config::get('performance.monitoring.slow_request_threshold', 2000),
            'api_cache_enabled' => Config::get('performance.api_cache.enabled', false),
            'api_cache_default_ttl' => Config::get('performance.api_cache.default_ttl', 1800),
            'query_cache_enabled' => Config::get('performance.query_cache.enabled', false),
            'rate_limiting_enabled' => Config::get('performance.rate_limiting.enabled', false),
            'memory_limit' => Config::get('performance.memory.limit', '256M'),
            'max_execution_time' => Config::get('performance.memory.max_execution_time', 30),
        ];
        
        return response()->json([
            'success' => true,
            'data' => $config,
            'message' => 'Performance configuration retrieved successfully'
        ]);
    }
    
    /**
     * Generate performance report
     */
    public function report(Request $request): JsonResponse
    {
        $period = $request->get('period', 'hour');
        $format = $request->get('format', 'json');
        
        $summary = PerformanceMiddleware::getPerformanceSummary($period);
        $metrics = PerformanceMiddleware::getMetrics($period);
        
        $report = [
            'generated_at' => now()->toISOString(),
            'period' => $period,
            'summary' => $summary,
            'total_requests' => count($metrics),
            'recommendations' => $this->generateRecommendations($summary, $metrics),
            'top_slow_endpoints' => $this->getTopSlowEndpoints($metrics),
            'cache_performance' => $this->getCachePerformance($metrics),
            'memory_usage_analysis' => $this->getMemoryUsageAnalysis($metrics),
        ];
        
        if ($format === 'json') {
            return response()->json([
                'success' => true,
                'data' => $report,
                'message' => 'Performance report generated successfully'
            ]);
        }
        
        // For other formats, we could add PDF, CSV, etc.
        return response()->json([
            'success' => false,
            'message' => 'Format not supported. Available formats: json'
        ], 400);
    }
    
    /**
     * Generate performance recommendations
     */
    private function generateRecommendations(array $summary, array $metrics): array
    {
        $recommendations = [];
        
        if ($summary['avg_response_time'] > 1000) {
            $recommendations[] = [
                'priority' => 'high',
                'category' => 'response_time',
                'message' => 'Average response time is high (' . $summary['avg_response_time'] . 'ms). Consider optimizing slow endpoints.',
                'action' => 'Review slow queries and implement caching'
            ];
        }
        
        if ($summary['cache_hit_rate'] < 50) {
            $recommendations[] = [
                'priority' => 'medium',
                'category' => 'caching',
                'message' => 'Cache hit rate is low (' . $summary['cache_hit_rate'] . '%). Consider implementing more aggressive caching.',
                'action' => 'Review cache configuration and TTL settings'
            ];
        }
        
        if ($summary['avg_query_count'] > 15) {
            $recommendations[] = [
                'priority' => 'high',
                'category' => 'database',
                'message' => 'Average query count is high (' . $summary['avg_query_count'] . '). Consider optimizing database queries.',
                'action' => 'Implement eager loading and query optimization'
            ];
        }
        
        if ($summary['slow_requests'] > $summary['total_requests'] * 0.1) {
            $recommendations[] = [
                'priority' => 'high',
                'category' => 'performance',
                'message' => 'High number of slow requests (' . $summary['slow_requests'] . '). System may be under stress.',
                'action' => 'Scale resources or optimize critical paths'
            ];
        }
        
        return $recommendations;
    }
    
    /**
     * Get top slow endpoints
     */
    private function getTopSlowEndpoints(array $metrics): array
    {
        $endpointTimes = [];
        
        foreach ($metrics as $metric) {
            $key = $metric['method'] . ' ' . $metric['url'];
            if (!isset($endpointTimes[$key])) {
                $endpointTimes[$key] = [];
            }
            $endpointTimes[$key][] = $metric['response_time'];
        }
        
        $avgTimes = [];
        foreach ($endpointTimes as $endpoint => $times) {
            $avgTimes[$endpoint] = array_sum($times) / count($times);
        }
        
        arsort($avgTimes);
        
        return array_slice($avgTimes, 0, 10, true);
    }
    
    /**
     * Get cache performance analysis
     */
    private function getCachePerformance(array $metrics): array
    {
        $totalRequests = count($metrics);
        $cacheHits = array_filter($metrics, fn($m) => $m['from_cache'] ?? false);
        $cacheMisses = $totalRequests - count($cacheHits);
        
        return [
            'total_requests' => $totalRequests,
            'cache_hits' => count($cacheHits),
            'cache_misses' => $cacheMisses,
            'hit_rate' => $totalRequests > 0 ? round((count($cacheHits) / $totalRequests) * 100, 2) : 0,
            'avg_response_time_cached' => count($cacheHits) > 0 ? round(array_sum(array_column($cacheHits, 'response_time')) / count($cacheHits), 2) : 0,
            'avg_response_time_uncached' => $cacheMisses > 0 ? round(array_sum(array_column(array_filter($metrics, fn($m) => !($m['from_cache'] ?? false)), 'response_time')) / $cacheMisses, 2) : 0,
        ];
    }
    
    /**
     * Get memory usage analysis
     */
    private function getMemoryUsageAnalysis(array $metrics): array
    {
        $memoryUsages = array_column($metrics, 'memory_usage');
        $memoryPeaks = array_column($metrics, 'memory_peak');
        
        return [
            'avg_memory_usage' => count($memoryUsages) > 0 ? round(array_sum($memoryUsages) / count($memoryUsages)) : 0,
            'max_memory_usage' => count($memoryUsages) > 0 ? max($memoryUsages) : 0,
            'min_memory_usage' => count($memoryUsages) > 0 ? min($memoryUsages) : 0,
            'avg_memory_peak' => count($memoryPeaks) > 0 ? round(array_sum($memoryPeaks) / count($memoryPeaks)) : 0,
            'max_memory_peak' => count($memoryPeaks) > 0 ? max($memoryPeaks) : 0,
        ];
    }
} 