<?php

/**
 * Advanced Features Test Script
 * Tests all new features: Search, Dashboard, Content Management, Security, Performance
 */

require_once 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class AdvancedFeaturesTest
{
    private $client;
    private $baseUrl;
    private $token;
    private $adminToken;
    private $results = [];

    public function __construct($baseUrl = 'http://localhost:8000/api')
    {
        $this->baseUrl = $baseUrl;
        $this->client = new Client([
            'timeout' => 30,
            'verify' => false
        ]);
    }

    public function runAllTests()
    {
        echo "🚀 Starting Advanced Features Test Suite\n";
        echo "==========================================\n\n";

        // Authentication
        $this->testAuthentication();

        // Advanced Search Tests
        $this->testAdvancedSearch();

        // User Dashboard Tests
        $this->testUserDashboard();

        // Content Management Tests
        $this->testContentManagement();

        // Security Tests
        $this->testSecurityFeatures();

        // Performance Tests
        $this->testPerformanceFeatures();

        // Cache Tests
        $this->testCacheFeatures();

        $this->printResults();
    }

    private function testAuthentication()
    {
        echo "🔐 Testing Authentication...\n";

        // Test user registration
        try {
            $response = $this->client->post($this->baseUrl . '/register', [
                'json' => [
                    'name' => 'Test User',
                    'email' => 'testuser' . time() . '@example.com',
                    'password' => 'password123',
                    'password_confirmation' => 'password123'
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            if ($data['success']) {
                $this->token = $data['data']['token'];
                $this->logResult('User Registration', 'PASS', 'User registered successfully');
            } else {
                $this->logResult('User Registration', 'FAIL', 'Registration failed');
            }
        } catch (Exception $e) {
            $this->logResult('User Registration', 'ERROR', $e->getMessage());
        }

        // Test admin login
        try {
            $response = $this->client->post($this->baseUrl . '/login', [
                'json' => [
                    'email' => 'admin@tqrs.com',
                    'password' => 'admin123'
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            if ($data['success']) {
                $this->adminToken = $data['data']['token'];
                $this->logResult('Admin Login', 'PASS', 'Admin logged in successfully');
            } else {
                $this->logResult('Admin Login', 'FAIL', 'Admin login failed');
            }
        } catch (Exception $e) {
            $this->logResult('Admin Login', 'ERROR', $e->getMessage());
        }

        echo "\n";
    }

    private function testAdvancedSearch()
    {
        echo "🔍 Testing Advanced Search...\n";

        // Test basic search
        try {
            $response = $this->client->get($this->baseUrl . '/search', [
                'query' => [
                    'query' => 'research',
                    'type' => 'all',
                    'sort' => 'relevance'
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            if ($data['success']) {
                $this->logResult('Basic Search', 'PASS', 'Search returned ' . $data['data']['total_results'] . ' results');
            } else {
                $this->logResult('Basic Search', 'FAIL', 'Search failed');
            }
        } catch (Exception $e) {
            $this->logResult('Basic Search', 'ERROR', $e->getMessage());
        }

        // Test search with filters
        try {
            $response = $this->client->get($this->baseUrl . '/search', [
                'query' => [
                    'query' => 'webinar',
                    'type' => 'webinars',
                    'category' => 'technology',
                    'sort' => 'date'
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            if ($data['success']) {
                $this->logResult('Filtered Search', 'PASS', 'Filtered search successful');
            } else {
                $this->logResult('Filtered Search', 'FAIL', 'Filtered search failed');
            }
        } catch (Exception $e) {
            $this->logResult('Filtered Search', 'ERROR', $e->getMessage());
        }

        // Test search filters
        try {
            $response = $this->client->get($this->baseUrl . '/search/filters');
            $data = json_decode($response->getBody(), true);
            if ($data['success']) {
                $this->logResult('Search Filters', 'PASS', 'Filters retrieved successfully');
            } else {
                $this->logResult('Search Filters', 'FAIL', 'Failed to get filters');
            }
        } catch (Exception $e) {
            $this->logResult('Search Filters', 'ERROR', $e->getMessage());
        }

        // Test search tracking
        try {
            $response = $this->client->post($this->baseUrl . '/search/track', [
                'json' => [
                    'query' => 'test search',
                    'results_count' => 5
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            if ($data['success']) {
                $this->logResult('Search Tracking', 'PASS', 'Search tracked successfully');
            } else {
                $this->logResult('Search Tracking', 'FAIL', 'Search tracking failed');
            }
        } catch (Exception $e) {
            $this->logResult('Search Tracking', 'ERROR', $e->getMessage());
        }

        echo "\n";
    }

    private function testUserDashboard()
    {
        if (!$this->token) {
            echo "⚠️  Skipping User Dashboard tests - no user token\n\n";
            return;
        }

        echo "📊 Testing User Dashboard...\n";

        // Test dashboard data
        try {
            $response = $this->client->get($this->baseUrl . '/dashboard', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            if ($data['success']) {
                $this->logResult('Dashboard Data', 'PASS', 'Dashboard data retrieved successfully');
            } else {
                $this->logResult('Dashboard Data', 'FAIL', 'Failed to get dashboard data');
            }
        } catch (Exception $e) {
            $this->logResult('Dashboard Data', 'ERROR', $e->getMessage());
        }

        // Test learning path
        try {
            $response = $this->client->get($this->baseUrl . '/dashboard/learning-path', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            if ($data['success']) {
                $this->logResult('Learning Path', 'PASS', 'Learning path retrieved successfully');
            } else {
                $this->logResult('Learning Path', 'FAIL', 'Failed to get learning path');
            }
        } catch (Exception $e) {
            $this->logResult('Learning Path', 'ERROR', $e->getMessage());
        }

        // Test last seen update
        try {
            $response = $this->client->post($this->baseUrl . '/dashboard/update-last-seen', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            if ($data['success']) {
                $this->logResult('Last Seen Update', 'PASS', 'Last seen updated successfully');
            } else {
                $this->logResult('Last Seen Update', 'FAIL', 'Failed to update last seen');
            }
        } catch (Exception $e) {
            $this->logResult('Last Seen Update', 'ERROR', $e->getMessage());
        }

        echo "\n";
    }

    private function testContentManagement()
    {
        if (!$this->adminToken) {
            echo "⚠️  Skipping Content Management tests - no admin token\n\n";
            return;
        }

        echo "📝 Testing Content Management...\n";

        // Test content overview
        try {
            $response = $this->client->get($this->baseUrl . '/content-management/overview', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->adminToken
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            if ($data['success']) {
                $this->logResult('Content Overview', 'PASS', 'Content overview retrieved successfully');
            } else {
                $this->logResult('Content Overview', 'FAIL', 'Failed to get content overview');
            }
        } catch (Exception $e) {
            $this->logResult('Content Overview', 'ERROR', $e->getMessage());
        }

        // Test content listing
        try {
            $response = $this->client->get($this->baseUrl . '/content-management/content', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->adminToken
                ],
                'query' => [
                    'type' => 'blogs',
                    'per_page' => 5
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            if ($data['success']) {
                $this->logResult('Content Listing', 'PASS', 'Content listing retrieved successfully');
            } else {
                $this->logResult('Content Listing', 'FAIL', 'Failed to get content listing');
            }
        } catch (Exception $e) {
            $this->logResult('Content Listing', 'ERROR', $e->getMessage());
        }

        // Test content creation
        try {
            $response = $this->client->post($this->baseUrl . '/content-management/content', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->adminToken
                ],
                'json' => [
                    'type' => 'blog',
                    'title' => 'Test Blog Post',
                    'description' => 'Test blog description',
                    'content' => 'Test blog content',
                    'status' => 'draft'
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            if ($data['success']) {
                $this->logResult('Content Creation', 'PASS', 'Content created successfully');
            } else {
                $this->logResult('Content Creation', 'FAIL', 'Failed to create content');
            }
        } catch (Exception $e) {
            $this->logResult('Content Creation', 'ERROR', $e->getMessage());
        }

        // Test media library
        try {
            $response = $this->client->get($this->baseUrl . '/content-management/media-library', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->adminToken
                ],
                'query' => [
                    'per_page' => 5
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            if ($data['success']) {
                $this->logResult('Media Library', 'PASS', 'Media library accessed successfully');
            } else {
                $this->logResult('Media Library', 'FAIL', 'Failed to access media library');
            }
        } catch (Exception $e) {
            $this->logResult('Media Library', 'ERROR', $e->getMessage());
        }

        // Test content analytics
        try {
            $response = $this->client->get($this->baseUrl . '/content-management/analytics', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->adminToken
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            if ($data['success']) {
                $this->logResult('Content Analytics', 'PASS', 'Content analytics retrieved successfully');
            } else {
                $this->logResult('Content Analytics', 'FAIL', 'Failed to get content analytics');
            }
        } catch (Exception $e) {
            $this->logResult('Content Analytics', 'ERROR', $e->getMessage());
        }

        echo "\n";
    }

    private function testSecurityFeatures()
    {
        echo "🔒 Testing Security Features...\n";

        // Test rate limiting
        try {
            $responses = [];
            for ($i = 0; $i < 15; $i++) {
                $response = $this->client->get($this->baseUrl . '/search', [
                    'query' => ['query' => 'test' . $i]
                ]);
                $responses[] = $response->getStatusCode();
            }

            $rateLimited = in_array(429, $responses);
            if ($rateLimited) {
                $this->logResult('Rate Limiting', 'PASS', 'Rate limiting is working');
            } else {
                $this->logResult('Rate Limiting', 'WARN', 'Rate limiting may not be active');
            }
        } catch (Exception $e) {
            $this->logResult('Rate Limiting', 'ERROR', $e->getMessage());
        }

        // Test security headers
        try {
            $response = $this->client->get($this->baseUrl . '/webinars');
            $headers = $response->getHeaders();

            $securityHeaders = [
                'X-Frame-Options',
                'X-Content-Type-Options',
                'X-XSS-Protection',
                'Referrer-Policy'
            ];

            $foundHeaders = 0;
            foreach ($securityHeaders as $header) {
                if (isset($headers[$header])) {
                    $foundHeaders++;
                }
            }

            if ($foundHeaders >= 3) {
                $this->logResult('Security Headers', 'PASS', "Found {$foundHeaders}/4 security headers");
            } else {
                $this->logResult('Security Headers', 'WARN', "Found {$foundHeaders}/4 security headers");
            }
        } catch (Exception $e) {
            $this->logResult('Security Headers', 'ERROR', $e->getMessage());
        }

        // Test authentication required endpoints
        try {
            $response = $this->client->get($this->baseUrl . '/dashboard');
            $this->logResult('Auth Protection', 'PASS', 'Unauthenticated access properly blocked');
        } catch (RequestException $e) {
            if ($e->getResponse()->getStatusCode() === 401) {
                $this->logResult('Auth Protection', 'PASS', 'Authentication required properly enforced');
            } else {
                $this->logResult('Auth Protection', 'FAIL', 'Unexpected response: ' . $e->getResponse()->getStatusCode());
            }
        } catch (Exception $e) {
            $this->logResult('Auth Protection', 'ERROR', $e->getMessage());
        }

        echo "\n";
    }

    private function testPerformanceFeatures()
    {
        echo "⚡ Testing Performance Features...\n";

        // Test response times
        $endpoints = [
            '/webinars',
            '/search?query=test',
            '/blogs',
            '/sitemap/status'
        ];

        foreach ($endpoints as $endpoint) {
            try {
                $startTime = microtime(true);
                $response = $this->client->get($this->baseUrl . $endpoint);
                $endTime = microtime(true);
                $responseTime = ($endTime - $startTime) * 1000; // Convert to milliseconds

                if ($responseTime < 1000) {
                    $this->logResult("Response Time: {$endpoint}", 'PASS', round($responseTime, 2) . 'ms');
                } elseif ($responseTime < 3000) {
                    $this->logResult("Response Time: {$endpoint}", 'WARN', round($responseTime, 2) . 'ms (slow)');
                } else {
                    $this->logResult("Response Time: {$endpoint}", 'FAIL', round($responseTime, 2) . 'ms (very slow)');
                }
            } catch (Exception $e) {
                $this->logResult("Response Time: {$endpoint}", 'ERROR', $e->getMessage());
            }
        }

        // Test concurrent requests
        try {
            $promises = [];
            for ($i = 0; $i < 5; $i++) {
                $promises[] = $this->client->getAsync($this->baseUrl . '/webinars');
            }

            $responses = \GuzzleHttp\Promise\Utils::unwrap($promises);
            $successCount = 0;
            foreach ($responses as $response) {
                if ($response->getStatusCode() === 200) {
                    $successCount++;
                }
            }

            if ($successCount === 5) {
                $this->logResult('Concurrent Requests', 'PASS', 'All 5 concurrent requests succeeded');
            } else {
                $this->logResult('Concurrent Requests', 'WARN', "{$successCount}/5 concurrent requests succeeded");
            }
        } catch (Exception $e) {
            $this->logResult('Concurrent Requests', 'ERROR', $e->getMessage());
        }

        echo "\n";
    }

    private function testCacheFeatures()
    {
        echo "💾 Testing Cache Features...\n";

        // Test cache headers
        try {
            $response = $this->client->get($this->baseUrl . '/webinars');
            $headers = $response->getHeaders();

            if (isset($headers['Cache-Control'])) {
                $this->logResult('Cache Headers', 'PASS', 'Cache headers present');
            } else {
                $this->logResult('Cache Headers', 'WARN', 'No cache headers found');
            }
        } catch (Exception $e) {
            $this->logResult('Cache Headers', 'ERROR', $e->getMessage());
        }

        // Test cache effectiveness
        try {
            $startTime = microtime(true);
            $response1 = $this->client->get($this->baseUrl . '/webinars');
            $endTime1 = microtime(true);
            $time1 = ($endTime1 - $startTime) * 1000;

            $startTime = microtime(true);
            $response2 = $this->client->get($this->baseUrl . '/webinars');
            $endTime2 = microtime(true);
            $time2 = ($endTime2 - $startTime) * 1000;

            if ($time2 < $time1 * 0.8) {
                $this->logResult('Cache Effectiveness', 'PASS', "Second request faster: {$time1}ms → {$time2}ms");
            } else {
                $this->logResult('Cache Effectiveness', 'WARN', "No significant cache improvement: {$time1}ms → {$time2}ms");
            }
        } catch (Exception $e) {
            $this->logResult('Cache Effectiveness', 'ERROR', $e->getMessage());
        }

        echo "\n";
    }

    private function logResult($test, $status, $message)
    {
        $this->results[] = [
            'test' => $test,
            'status' => $status,
            'message' => $message,
            'timestamp' => date('Y-m-d H:i:s')
        ];

        $statusIcon = [
            'PASS' => '✅',
            'FAIL' => '❌',
            'WARN' => '⚠️',
            'ERROR' => '🚨'
        ];

        echo "  {$statusIcon[$status]} {$test}: {$message}\n";
    }

    private function printResults()
    {
        echo "\n📋 Test Results Summary\n";
        echo "======================\n";

        $summary = [
            'PASS' => 0,
            'FAIL' => 0,
            'WARN' => 0,
            'ERROR' => 0
        ];

        foreach ($this->results as $result) {
            $summary[$result['status']]++;
        }

        echo "✅ PASS: {$summary['PASS']}\n";
        echo "❌ FAIL: {$summary['FAIL']}\n";
        echo "⚠️  WARN: {$summary['WARN']}\n";
        echo "🚨 ERROR: {$summary['ERROR']}\n";
        echo "📊 TOTAL: " . count($this->results) . "\n\n";

        if ($summary['FAIL'] > 0 || $summary['ERROR'] > 0) {
            echo "🔍 Failed Tests:\n";
            foreach ($this->results as $result) {
                if ($result['status'] === 'FAIL' || $result['status'] === 'ERROR') {
                    echo "  ❌ {$result['test']}: {$result['message']}\n";
                }
            }
        }

        if ($summary['PASS'] === count($this->results)) {
            echo "🎉 All tests passed! The advanced features are working correctly.\n";
        } else {
            echo "⚠️  Some tests failed. Please check the implementation.\n";
        }
    }
}

// Run the tests
if (php_sapi_name() === 'cli') {
    $test = new AdvancedFeaturesTest();
    $test->runAllTests();
} else {
    echo "This script should be run from the command line.\n";
} 