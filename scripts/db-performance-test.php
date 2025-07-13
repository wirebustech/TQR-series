<?php
/**
 * TQRS Database Performance Testing Script
 * 
 * This script tests database performance with various query patterns
 * and loads to identify bottlenecks and optimization opportunities.
 */

require_once __DIR__ . '/../backend/bootstrap/app.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class DatabasePerformanceTest
{
    private $results = [];
    private $testStartTime;
    
    public function __construct()
    {
        $this->testStartTime = microtime(true);
    }
    
    /**
     * Run all database performance tests
     */
    public function runAllTests()
    {
        $this->log("Starting Database Performance Tests");
        
        // Test suite
        $this->testConnectionPerformance();
        $this->testQueryPerformance();
        $this->testInsertPerformance();
        $this->testUpdatePerformance();
        $this->testDeletePerformance();
        $this->testJoinPerformance();
        $this->testIndexPerformance();
        $this->testPaginationPerformance();
        $this->testSearchPerformance();
        $this->testCachePerformance();
        
        $this->generateReport();
    }
    
    /**
     * Test database connection performance
     */
    private function testConnectionPerformance()
    {
        $this->log("Testing Database Connection Performance");
        
        $iterations = 100;
        $times = [];
        
        for ($i = 0; $i < $iterations; $i++) {
            $start = microtime(true);
            DB::connection()->getPdo();
            $end = microtime(true);
            $times[] = ($end - $start) * 1000; // Convert to milliseconds
        }
        
        $this->results['connection'] = [
            'iterations' => $iterations,
            'avg_time' => array_sum($times) / count($times),
            'min_time' => min($times),
            'max_time' => max($times),
            'total_time' => array_sum($times)
        ];
    }
    
    /**
     * Test various query patterns
     */
    private function testQueryPerformance()
    {
        $this->log("Testing Query Performance");
        
        $queries = [
            'select_all_users' => 'SELECT * FROM users',
            'select_with_limit' => 'SELECT * FROM users LIMIT 10',
            'select_with_where' => 'SELECT * FROM users WHERE created_at > ?',
            'select_with_join' => 'SELECT u.*, p.* FROM users u LEFT JOIN pages p ON u.id = p.created_by',
            'count_query' => 'SELECT COUNT(*) FROM users',
            'complex_query' => 'SELECT u.name, COUNT(p.id) as page_count FROM users u LEFT JOIN pages p ON u.id = p.created_by GROUP BY u.id ORDER BY page_count DESC'
        ];
        
        foreach ($queries as $name => $sql) {
            $times = [];
            $iterations = 50;
            
            for ($i = 0; $i < $iterations; $i++) {
                $start = microtime(true);
                
                if ($name === 'select_with_where') {
                    DB::select($sql, [now()->subDays(30)]);
                } else {
                    DB::select($sql);
                }
                
                $end = microtime(true);
                $times[] = ($end - $start) * 1000;
            }
            
            $this->results['queries'][$name] = [
                'iterations' => $iterations,
                'avg_time' => array_sum($times) / count($times),
                'min_time' => min($times),
                'max_time' => max($times),
                'sql' => $sql
            ];
        }
    }
    
    /**
     * Test insert performance
     */
    private function testInsertPerformance()
    {
        $this->log("Testing Insert Performance");
        
        // Single inserts
        $times = [];
        $iterations = 100;
        
        for ($i = 0; $i < $iterations; $i++) {
            $start = microtime(true);
            
            $id = DB::table('users')->insertGetId([
                'name' => 'Test User ' . $i,
                'email' => 'test' . $i . '@perftest.com',
                'password' => bcrypt('password'),
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            $end = microtime(true);
            $times[] = ($end - $start) * 1000;
            
            // Clean up
            DB::table('users')->where('id', $id)->delete();
        }
        
        $this->results['insert']['single'] = [
            'iterations' => $iterations,
            'avg_time' => array_sum($times) / count($times),
            'min_time' => min($times),
            'max_time' => max($times)
        ];
        
        // Batch inserts
        $batchData = [];
        for ($i = 0; $i < 1000; $i++) {
            $batchData[] = [
                'name' => 'Batch User ' . $i,
                'email' => 'batch' . $i . '@perftest.com',
                'password' => bcrypt('password'),
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        
        $start = microtime(true);
        DB::table('users')->insert($batchData);
        $end = microtime(true);
        
        $this->results['insert']['batch'] = [
            'records' => 1000,
            'time' => ($end - $start) * 1000,
            'records_per_second' => 1000 / ($end - $start)
        ];
        
        // Clean up batch data
        DB::table('users')->where('email', 'like', 'batch%@perftest.com')->delete();
    }
    
    /**
     * Test update performance
     */
    private function testUpdatePerformance()
    {
        $this->log("Testing Update Performance");
        
        // Create test data
        $testUsers = [];
        for ($i = 0; $i < 100; $i++) {
            $testUsers[] = [
                'name' => 'Update Test User ' . $i,
                'email' => 'update' . $i . '@perftest.com',
                'password' => bcrypt('password'),
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        DB::table('users')->insert($testUsers);
        
        // Get user IDs
        $userIds = DB::table('users')->where('email', 'like', 'update%@perftest.com')->pluck('id');
        
        // Test single updates
        $times = [];
        foreach ($userIds as $userId) {
            $start = microtime(true);
            
            DB::table('users')
                ->where('id', $userId)
                ->update(['name' => 'Updated User ' . $userId]);
            
            $end = microtime(true);
            $times[] = ($end - $start) * 1000;
        }
        
        $this->results['update']['single'] = [
            'records' => count($userIds),
            'avg_time' => array_sum($times) / count($times),
            'min_time' => min($times),
            'max_time' => max($times)
        ];
        
        // Test batch update
        $start = microtime(true);
        DB::table('users')
            ->where('email', 'like', 'update%@perftest.com')
            ->update(['name' => 'Batch Updated User']);
        $end = microtime(true);
        
        $this->results['update']['batch'] = [
            'records' => count($userIds),
            'time' => ($end - $start) * 1000,
            'records_per_second' => count($userIds) / ($end - $start)
        ];
        
        // Clean up
        DB::table('users')->where('email', 'like', 'update%@perftest.com')->delete();
    }
    
    /**
     * Test delete performance
     */
    private function testDeletePerformance()
    {
        $this->log("Testing Delete Performance");
        
        // Create test data
        $testUsers = [];
        for ($i = 0; $i < 100; $i++) {
            $testUsers[] = [
                'name' => 'Delete Test User ' . $i,
                'email' => 'delete' . $i . '@perftest.com',
                'password' => bcrypt('password'),
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        DB::table('users')->insert($testUsers);
        
        // Get user IDs
        $userIds = DB::table('users')->where('email', 'like', 'delete%@perftest.com')->pluck('id');
        
        // Test single deletes
        $times = [];
        $halfCount = count($userIds) / 2;
        
        for ($i = 0; $i < $halfCount; $i++) {
            $start = microtime(true);
            
            DB::table('users')->where('id', $userIds[$i])->delete();
            
            $end = microtime(true);
            $times[] = ($end - $start) * 1000;
        }
        
        $this->results['delete']['single'] = [
            'records' => $halfCount,
            'avg_time' => array_sum($times) / count($times),
            'min_time' => min($times),
            'max_time' => max($times)
        ];
        
        // Test batch delete
        $start = microtime(true);
        DB::table('users')
            ->where('email', 'like', 'delete%@perftest.com')
            ->delete();
        $end = microtime(true);
        
        $this->results['delete']['batch'] = [
            'records' => $halfCount,
            'time' => ($end - $start) * 1000,
            'records_per_second' => $halfCount / ($end - $start)
        ];
    }
    
    /**
     * Test join performance
     */
    private function testJoinPerformance()
    {
        $this->log("Testing Join Performance");
        
        $joins = [
            'left_join' => "SELECT u.*, p.title FROM users u LEFT JOIN pages p ON u.id = p.created_by",
            'inner_join' => "SELECT u.*, p.title FROM users u INNER JOIN pages p ON u.id = p.created_by",
            'multiple_joins' => "SELECT u.*, p.title, b.title as blog_title FROM users u LEFT JOIN pages p ON u.id = p.created_by LEFT JOIN blogs b ON u.id = b.author_id"
        ];
        
        foreach ($joins as $name => $sql) {
            $times = [];
            $iterations = 20;
            
            for ($i = 0; $i < $iterations; $i++) {
                $start = microtime(true);
                DB::select($sql);
                $end = microtime(true);
                $times[] = ($end - $start) * 1000;
            }
            
            $this->results['joins'][$name] = [
                'iterations' => $iterations,
                'avg_time' => array_sum($times) / count($times),
                'min_time' => min($times),
                'max_time' => max($times),
                'sql' => $sql
            ];
        }
    }
    
    /**
     * Test index performance
     */
    private function testIndexPerformance()
    {
        $this->log("Testing Index Performance");
        
        // Test queries with and without indexes
        $queries = [
            'indexed_email' => "SELECT * FROM users WHERE email = 'test@example.com'",
            'indexed_created_at' => "SELECT * FROM users WHERE created_at > ?",
            'non_indexed_name' => "SELECT * FROM users WHERE name LIKE '%test%'",
        ];
        
        foreach ($queries as $name => $sql) {
            $times = [];
            $iterations = 50;
            
            for ($i = 0; $i < $iterations; $i++) {
                $start = microtime(true);
                
                if ($name === 'indexed_created_at') {
                    DB::select($sql, [now()->subDays(30)]);
                } else {
                    DB::select($sql);
                }
                
                $end = microtime(true);
                $times[] = ($end - $start) * 1000;
            }
            
            $this->results['indexes'][$name] = [
                'iterations' => $iterations,
                'avg_time' => array_sum($times) / count($times),
                'min_time' => min($times),
                'max_time' => max($times)
            ];
        }
    }
    
    /**
     * Test pagination performance
     */
    private function testPaginationPerformance()
    {
        $this->log("Testing Pagination Performance");
        
        $pageSizes = [10, 25, 50, 100];
        $pages = [1, 10, 50, 100];
        
        foreach ($pageSizes as $pageSize) {
            foreach ($pages as $page) {
                $offset = ($page - 1) * $pageSize;
                
                $start = microtime(true);
                DB::table('users')
                    ->offset($offset)
                    ->limit($pageSize)
                    ->get();
                $end = microtime(true);
                
                $this->results['pagination'][$pageSize][$page] = [
                    'time' => ($end - $start) * 1000,
                    'offset' => $offset,
                    'limit' => $pageSize
                ];
            }
        }
    }
    
    /**
     * Test search performance
     */
    private function testSearchPerformance()
    {
        $this->log("Testing Search Performance");
        
        $searches = [
            'exact_match' => "SELECT * FROM users WHERE email = 'test@example.com'",
            'like_start' => "SELECT * FROM users WHERE name LIKE 'test%'",
            'like_contains' => "SELECT * FROM users WHERE name LIKE '%test%'",
            'like_end' => "SELECT * FROM users WHERE name LIKE '%test'",
            'full_text' => "SELECT * FROM blogs WHERE MATCH(title, content) AGAINST('research' IN BOOLEAN MODE)"
        ];
        
        foreach ($searches as $name => $sql) {
            $times = [];
            $iterations = 30;
            
            for ($i = 0; $i < $iterations; $i++) {
                $start = microtime(true);
                
                try {
                    DB::select($sql);
                } catch (Exception $e) {
                    // Skip if table doesn't exist or query fails
                    continue;
                }
                
                $end = microtime(true);
                $times[] = ($end - $start) * 1000;
            }
            
            if (!empty($times)) {
                $this->results['search'][$name] = [
                    'iterations' => count($times),
                    'avg_time' => array_sum($times) / count($times),
                    'min_time' => min($times),
                    'max_time' => max($times)
                ];
            }
        }
    }
    
    /**
     * Test cache performance
     */
    private function testCachePerformance()
    {
        $this->log("Testing Cache Performance");
        
        // Test cache vs database performance
        $cacheKey = 'perf_test_users';
        $iterations = 100;
        
        // Database query times
        $dbTimes = [];
        for ($i = 0; $i < $iterations; $i++) {
            $start = microtime(true);
            $users = DB::table('users')->limit(10)->get();
            $end = microtime(true);
            $dbTimes[] = ($end - $start) * 1000;
        }
        
        // Cache the data
        Cache::put($cacheKey, $users, 3600);
        
        // Cache retrieval times
        $cacheTimes = [];
        for ($i = 0; $i < $iterations; $i++) {
            $start = microtime(true);
            $users = Cache::get($cacheKey);
            $end = microtime(true);
            $cacheTimes[] = ($end - $start) * 1000;
        }
        
        $this->results['cache'] = [
            'database' => [
                'iterations' => $iterations,
                'avg_time' => array_sum($dbTimes) / count($dbTimes),
                'min_time' => min($dbTimes),
                'max_time' => max($dbTimes)
            ],
            'cache' => [
                'iterations' => $iterations,
                'avg_time' => array_sum($cacheTimes) / count($cacheTimes),
                'min_time' => min($cacheTimes),
                'max_time' => max($cacheTimes)
            ],
            'performance_gain' => (array_sum($dbTimes) / count($dbTimes)) / (array_sum($cacheTimes) / count($cacheTimes))
        ];
        
        // Clean up
        Cache::forget($cacheKey);
    }
    
    /**
     * Generate performance report
     */
    private function generateReport()
    {
        $totalTime = microtime(true) - $this->testStartTime;
        
        $report = [
            'timestamp' => date('Y-m-d H:i:s'),
            'total_test_time' => $totalTime,
            'results' => $this->results,
            'recommendations' => $this->generateRecommendations()
        ];
        
        $reportFile = __DIR__ . '/../performance-results/db_performance_' . date('Ymd_His') . '.json';
        $reportDir = dirname($reportFile);
        
        if (!is_dir($reportDir)) {
            mkdir($reportDir, 0755, true);
        }
        
        file_put_contents($reportFile, json_encode($report, JSON_PRETTY_PRINT));
        
        $this->log("Database performance report generated: " . $reportFile);
        $this->printSummary();
    }
    
    /**
     * Generate recommendations based on test results
     */
    private function generateRecommendations()
    {
        $recommendations = [];
        
        // Connection performance
        if (isset($this->results['connection']['avg_time']) && $this->results['connection']['avg_time'] > 10) {
            $recommendations[] = "Connection time is high (" . round($this->results['connection']['avg_time'], 2) . "ms). Consider connection pooling.";
        }
        
        // Query performance
        if (isset($this->results['queries'])) {
            foreach ($this->results['queries'] as $name => $data) {
                if ($data['avg_time'] > 100) {
                    $recommendations[] = "Query '$name' is slow (" . round($data['avg_time'], 2) . "ms). Consider optimization or indexing.";
                }
            }
        }
        
        // Join performance
        if (isset($this->results['joins'])) {
            foreach ($this->results['joins'] as $name => $data) {
                if ($data['avg_time'] > 200) {
                    $recommendations[] = "Join '$name' is slow (" . round($data['avg_time'], 2) . "ms). Consider indexing join columns.";
                }
            }
        }
        
        // Cache performance
        if (isset($this->results['cache']['performance_gain']) && $this->results['cache']['performance_gain'] > 5) {
            $recommendations[] = "Cache provides " . round($this->results['cache']['performance_gain'], 1) . "x performance improvement. Consider implementing query caching.";
        }
        
        return $recommendations;
    }
    
    /**
     * Print summary to console
     */
    private function printSummary()
    {
        echo "\n========================================\n";
        echo "DATABASE PERFORMANCE TEST SUMMARY\n";
        echo "========================================\n";
        echo "Total Test Time: " . round(microtime(true) - $this->testStartTime, 2) . " seconds\n\n";
        
        // Connection performance
        if (isset($this->results['connection'])) {
            echo "Connection Performance:\n";
            echo "  Average: " . round($this->results['connection']['avg_time'], 2) . "ms\n";
            echo "  Min: " . round($this->results['connection']['min_time'], 2) . "ms\n";
            echo "  Max: " . round($this->results['connection']['max_time'], 2) . "ms\n\n";
        }
        
        // Query performance
        if (isset($this->results['queries'])) {
            echo "Query Performance (Average Times):\n";
            foreach ($this->results['queries'] as $name => $data) {
                echo "  $name: " . round($data['avg_time'], 2) . "ms\n";
            }
            echo "\n";
        }
        
        // Cache performance
        if (isset($this->results['cache'])) {
            echo "Cache Performance:\n";
            echo "  Database: " . round($this->results['cache']['database']['avg_time'], 2) . "ms\n";
            echo "  Cache: " . round($this->results['cache']['cache']['avg_time'], 2) . "ms\n";
            echo "  Performance Gain: " . round($this->results['cache']['performance_gain'], 1) . "x\n\n";
        }
        
        // Recommendations
        $recommendations = $this->generateRecommendations();
        if (!empty($recommendations)) {
            echo "Recommendations:\n";
            foreach ($recommendations as $i => $recommendation) {
                echo "  " . ($i + 1) . ". $recommendation\n";
            }
        }
        
        echo "\n========================================\n";
    }
    
    /**
     * Log message with timestamp
     */
    private function log($message)
    {
        echo "[" . date('Y-m-d H:i:s') . "] $message\n";
    }
}

// Run the tests
try {
    $tester = new DatabasePerformanceTest();
    $tester->runAllTests();
    echo "\nDatabase performance testing completed successfully!\n";
} catch (Exception $e) {
    echo "Error running database performance tests: " . $e->getMessage() . "\n";
    exit(1);
} 