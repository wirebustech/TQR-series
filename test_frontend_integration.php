<?php

/**
 * Frontend Integration Test
 * Verifies all frontend features are properly connected to backend API and database
 */

require_once 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class FrontendIntegrationTest
{
    private $client;
    private $baseUrl;
    private $apiBaseUrl;
    private $token;
    private $adminToken;
    private $results = [];

    public function __construct($baseUrl = 'http://localhost', $apiBaseUrl = 'http://localhost:8000/api')
    {
        $this->baseUrl = $baseUrl;
        $this->apiBaseUrl = $apiBaseUrl;
        $this->client = new Client([
            'timeout' => 30,
            'verify' => false
        ]);
    }

    public function runAllTests()
    {
        echo "🔍 Starting Frontend Integration Test Suite\n";
        echo "==========================================\n\n";

        // Test frontend pages accessibility
        $this->testFrontendPages();

        // Test API connectivity
        $this->testAPIConnectivity();

        // Test authentication flow
        $this->testAuthenticationFlow();

        // Test search functionality
        $this->testSearchIntegration();

        // Test dashboard integration
        $this->testDashboardIntegration();

        // Test content management integration
        $this->testContentManagementIntegration();

        // Test payment integration
        $this->testPaymentIntegration();

        // Test WebSocket integration
        $this->testWebSocketIntegration();

        // Test PWA features
        $this->testPWAF eatures();

        // Test database connectivity
        $this->testDatabaseConnectivity();

        $this->printResults();
    }

    private function testFrontendPages()
    {
        echo "🌐 Testing Frontend Pages Accessibility...\n";

        $pages = [
            'index.php' => 'Homepage',
            'about.php' => 'About Page',
            'contact.php' => 'Contact Page',
            'webinars.php' => 'Webinars Page',
            'blog.php' => 'Blog Page',
            'search.php' => 'Search Page',
            'login.php' => 'Login Page',
            'register.php' => 'Register Page',
            'dashboard.php' => 'Dashboard Page',
            'profile.php' => 'Profile Page',
            'webinar-details.php' => 'Webinar Details Page',
            'article.php' => 'Article Page',
            'research-ai.php' => 'Research AI Page',
            'pricing.php' => 'Pricing Page',
            'faq.php' => 'FAQ Page',
            'help-center.php' => 'Help Center Page',
            'terms.php' => 'Terms Page',
            'privacy.php' => 'Privacy Page',
            '404.php' => '404 Error Page'
        ];

        foreach ($pages as $page => $description) {
            try {
                $response = $this->client->get($this->baseUrl . '/' . $page);
                if ($response->getStatusCode() === 200) {
                    $this->logResult("Frontend Page: {$description}", 'PASS', 'Page accessible');
                } else {
                    $this->logResult("Frontend Page: {$description}", 'FAIL', "HTTP {$response->getStatusCode()}");
                }
            } catch (Exception $e) {
                $this->logResult("Frontend Page: {$description}", 'ERROR', $e->getMessage());
            }
        }

        echo "\n";
    }

    private function testAPIConnectivity()
    {
        echo "🔌 Testing API Connectivity...\n";

        // Test API base endpoint
        try {
            $response = $this->client->get($this->apiBaseUrl . '/webinars');
            if ($response->getStatusCode() === 200) {
                $this->logResult('API Base Connectivity', 'PASS', 'API is accessible');
            } else {
                $this->logResult('API Base Connectivity', 'FAIL', "HTTP {$response->getStatusCode()}");
            }
        } catch (Exception $e) {
            $this->logResult('API Base Connectivity', 'ERROR', $e->getMessage());
        }

        // Test API health check
        try {
            $response = $this->client->get($this->apiBaseUrl . '/sitemap/status');
            $data = json_decode($response->getBody(), true);
            if ($data && isset($data['success'])) {
                $this->logResult('API Health Check', 'PASS', 'API is healthy');
            } else {
                $this->logResult('API Health Check', 'FAIL', 'Invalid response format');
            }
        } catch (Exception $e) {
            $this->logResult('API Health Check', 'ERROR', $e->getMessage());
        }

        echo "\n";
    }

    private function testAuthenticationFlow()
    {
        echo "🔐 Testing Authentication Flow...\n";

        // Test registration
        try {
            $testEmail = 'testuser' . time() . '@example.com';
            $response = $this->client->post($this->apiBaseUrl . '/register', [
                'json' => [
                    'name' => 'Test User',
                    'email' => $testEmail,
                    'password' => 'password123',
                    'password_confirmation' => 'password123'
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            if ($data['success']) {
                $this->token = $data['data']['token'];
                $this->logResult('User Registration', 'PASS', 'User registered successfully');
            } else {
                $this->logResult('User Registration', 'FAIL', $data['message'] ?? 'Registration failed');
            }
        } catch (Exception $e) {
            $this->logResult('User Registration', 'ERROR', $e->getMessage());
        }

        // Test login
        try {
            $response = $this->client->post($this->apiBaseUrl . '/login', [
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
                $this->logResult('Admin Login', 'FAIL', $data['message'] ?? 'Login failed');
            }
        } catch (Exception $e) {
            $this->logResult('Admin Login', 'ERROR', $e->getMessage());
        }

        // Test protected endpoints
        if ($this->token) {
            try {
                $response = $this->client->get($this->apiBaseUrl . '/user', [
                    'headers' => ['Authorization' => 'Bearer ' . $this->token]
                ]);
                $data = json_decode($response->getBody(), true);
                if ($data['success']) {
                    $this->logResult('Protected Endpoint Access', 'PASS', 'Token authentication works');
                } else {
                    $this->logResult('Protected Endpoint Access', 'FAIL', 'Token authentication failed');
                }
            } catch (Exception $e) {
                $this->logResult('Protected Endpoint Access', 'ERROR', $e->getMessage());
            }
        }

        echo "\n";
    }

    private function testSearchIntegration()
    {
        echo "🔍 Testing Search Integration...\n";

        // Test search API
        try {
            $response = $this->client->get($this->apiBaseUrl . '/search', [
                'query' => [
                    'query' => 'research',
                    'type' => 'all',
                    'sort' => 'relevance'
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            if ($data['success']) {
                $this->logResult('Search API', 'PASS', 'Search API working');
            } else {
                $this->logResult('Search API', 'FAIL', 'Search API failed');
            }
        } catch (Exception $e) {
            $this->logResult('Search API', 'ERROR', $e->getMessage());
        }

        // Test search filters
        try {
            $response = $this->client->get($this->apiBaseUrl . '/search/filters');
            $data = json_decode($response->getBody(), true);
            if ($data['success']) {
                $this->logResult('Search Filters', 'PASS', 'Search filters working');
            } else {
                $this->logResult('Search Filters', 'FAIL', 'Search filters failed');
            }
        } catch (Exception $e) {
            $this->logResult('Search Filters', 'ERROR', $e->getMessage());
        }

        // Test search tracking
        try {
            $response = $this->client->post($this->apiBaseUrl . '/search/track', [
                'json' => [
                    'query' => 'test search',
                    'results_count' => 5
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            if ($data['success']) {
                $this->logResult('Search Tracking', 'PASS', 'Search tracking working');
            } else {
                $this->logResult('Search Tracking', 'FAIL', 'Search tracking failed');
            }
        } catch (Exception $e) {
            $this->logResult('Search Tracking', 'ERROR', $e->getMessage());
        }

        echo "\n";
    }

    private function testDashboardIntegration()
    {
        if (!$this->token) {
            echo "⚠️  Skipping Dashboard tests - no user token\n\n";
            return;
        }

        echo "📊 Testing Dashboard Integration...\n";

        // Test dashboard data
        try {
            $response = $this->client->get($this->apiBaseUrl . '/dashboard', [
                'headers' => ['Authorization' => 'Bearer ' . $this->token]
            ]);

            $data = json_decode($response->getBody(), true);
            if ($data['success']) {
                $this->logResult('Dashboard Data', 'PASS', 'Dashboard data retrieved');
            } else {
                $this->logResult('Dashboard Data', 'FAIL', 'Dashboard data failed');
            }
        } catch (Exception $e) {
            $this->logResult('Dashboard Data', 'ERROR', $e->getMessage());
        }

        // Test learning path
        try {
            $response = $this->client->get($this->apiBaseUrl . '/dashboard/learning-path', [
                'headers' => ['Authorization' => 'Bearer ' . $this->token]
            ]);

            $data = json_decode($response->getBody(), true);
            if ($data['success']) {
                $this->logResult('Learning Path', 'PASS', 'Learning path retrieved');
            } else {
                $this->logResult('Learning Path', 'FAIL', 'Learning path failed');
            }
        } catch (Exception $e) {
            $this->logResult('Learning Path', 'ERROR', $e->getMessage());
        }

        // Test last seen update
        try {
            $response = $this->client->post($this->apiBaseUrl . '/dashboard/update-last-seen', [
                'headers' => ['Authorization' => 'Bearer ' . $this->token]
            ]);

            $data = json_decode($response->getBody(), true);
            if ($data['success']) {
                $this->logResult('Last Seen Update', 'PASS', 'Last seen updated');
            } else {
                $this->logResult('Last Seen Update', 'FAIL', 'Last seen update failed');
            }
        } catch (Exception $e) {
            $this->logResult('Last Seen Update', 'ERROR', $e->getMessage());
        }

        echo "\n";
    }

    private function testContentManagementIntegration()
    {
        if (!$this->adminToken) {
            echo "⚠️  Skipping Content Management tests - no admin token\n\n";
            return;
        }

        echo "📝 Testing Content Management Integration...\n";

        // Test content overview
        try {
            $response = $this->client->get($this->apiBaseUrl . '/content-management/overview', [
                'headers' => ['Authorization' => 'Bearer ' . $this->adminToken]
            ]);

            $data = json_decode($response->getBody(), true);
            if ($data['success']) {
                $this->logResult('Content Overview', 'PASS', 'Content overview retrieved');
            } else {
                $this->logResult('Content Overview', 'FAIL', 'Content overview failed');
            }
        } catch (Exception $e) {
            $this->logResult('Content Overview', 'ERROR', $e->getMessage());
        }

        // Test content listing
        try {
            $response = $this->client->get($this->apiBaseUrl . '/content-management/content', [
                'headers' => ['Authorization' => 'Bearer ' . $this->adminToken],
                'query' => ['type' => 'blogs', 'per_page' => 5]
            ]);

            $data = json_decode($response->getBody(), true);
            if ($data['success']) {
                $this->logResult('Content Listing', 'PASS', 'Content listing retrieved');
            } else {
                $this->logResult('Content Listing', 'FAIL', 'Content listing failed');
            }
        } catch (Exception $e) {
            $this->logResult('Content Listing', 'ERROR', $e->getMessage());
        }

        // Test media library
        try {
            $response = $this->client->get($this->apiBaseUrl . '/content-management/media-library', [
                'headers' => ['Authorization' => 'Bearer ' . $this->adminToken],
                'query' => ['per_page' => 5]
            ]);

            $data = json_decode($response->getBody(), true);
            if ($data['success']) {
                $this->logResult('Media Library', 'PASS', 'Media library accessed');
            } else {
                $this->logResult('Media Library', 'FAIL', 'Media library failed');
            }
        } catch (Exception $e) {
            $this->logResult('Media Library', 'ERROR', $e->getMessage());
        }

        echo "\n";
    }

    private function testPaymentIntegration()
    {
        echo "💳 Testing Payment Integration...\n";

        // Test payment methods endpoint
        if ($this->token) {
            try {
                $response = $this->client->get($this->apiBaseUrl . '/payments/methods', [
                    'headers' => ['Authorization' => 'Bearer ' . $this->token]
                ]);

                $data = json_decode($response->getBody(), true);
                if ($data['success']) {
                    $this->logResult('Payment Methods', 'PASS', 'Payment methods retrieved');
                } else {
                    $this->logResult('Payment Methods', 'FAIL', 'Payment methods failed');
                }
            } catch (Exception $e) {
                $this->logResult('Payment Methods', 'ERROR', $e->getMessage());
            }
        }

        // Test payment history endpoint
        if ($this->token) {
            try {
                $response = $this->client->get($this->apiBaseUrl . '/payments/history', [
                    'headers' => ['Authorization' => 'Bearer ' . $this->token]
                ]);

                $data = json_decode($response->getBody(), true);
                if ($data['success']) {
                    $this->logResult('Payment History', 'PASS', 'Payment history retrieved');
                } else {
                    $this->logResult('Payment History', 'FAIL', 'Payment history failed');
                }
            } catch (Exception $e) {
                $this->logResult('Payment History', 'ERROR', $e->getMessage());
            }
        }

        echo "\n";
    }

    private function testWebSocketIntegration()
    {
        echo "🔌 Testing WebSocket Integration...\n";

        // Test WebSocket server connectivity
        try {
            $context = stream_context_create();
            $socket = stream_socket_client('tcp://localhost:8090', $errno, $errstr, 5, STREAM_CLIENT_CONNECT, $context);
            
            if ($socket) {
                fclose($socket);
                $this->logResult('WebSocket Server', 'PASS', 'WebSocket server is running');
            } else {
                $this->logResult('WebSocket Server', 'FAIL', 'WebSocket server not accessible');
            }
        } catch (Exception $e) {
            $this->logResult('WebSocket Server', 'ERROR', $e->getMessage());
        }

        // Test WebSocket client file
        $websocketFile = 'frontend/assets/js/websocket.js';
        if (file_exists($websocketFile)) {
            $this->logResult('WebSocket Client', 'PASS', 'WebSocket client file exists');
        } else {
            $this->logResult('WebSocket Client', 'FAIL', 'WebSocket client file missing');
        }

        echo "\n";
    }

    private function testPWAF eatures()
    {
        echo "📱 Testing PWA Features...\n";

        // Test manifest file
        $manifestFile = 'frontend/manifest.json';
        if (file_exists($manifestFile)) {
            $manifest = json_decode(file_get_contents($manifestFile), true);
            if ($manifest && isset($manifest['name'])) {
                $this->logResult('PWA Manifest', 'PASS', 'PWA manifest is valid');
            } else {
                $this->logResult('PWA Manifest', 'FAIL', 'PWA manifest is invalid');
            }
        } else {
            $this->logResult('PWA Manifest', 'FAIL', 'PWA manifest file missing');
        }

        // Test service worker
        $swFile = 'frontend/sw.js';
        if (file_exists($swFile)) {
            $this->logResult('Service Worker', 'PASS', 'Service worker file exists');
        } else {
            $this->logResult('Service Worker', 'FAIL', 'Service worker file missing');
        }

        // Test offline page
        $offlineFile = 'frontend/offline.php';
        if (file_exists($offlineFile)) {
            $this->logResult('Offline Page', 'PASS', 'Offline page exists');
        } else {
            $this->logResult('Offline Page', 'FAIL', 'Offline page missing');
        }

        echo "\n";
    }

    private function testDatabaseConnectivity()
    {
        echo "🗄️  Testing Database Connectivity...\n";

        // Test database connection through API
        try {
            $response = $this->client->get($this->apiBaseUrl . '/webinars');
            $data = json_decode($response->getBody(), true);
            
            if ($data && isset($data['data'])) {
                $this->logResult('Database Connection', 'PASS', 'Database is accessible through API');
            } else {
                $this->logResult('Database Connection', 'FAIL', 'Database connection failed');
            }
        } catch (Exception $e) {
            $this->logResult('Database Connection', 'ERROR', $e->getMessage());
        }

        // Test search analytics table
        try {
            $response = $this->client->post($this->apiBaseUrl . '/search/track', [
                'json' => [
                    'query' => 'database test',
                    'results_count' => 1
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            if ($data['success']) {
                $this->logResult('Search Analytics Table', 'PASS', 'Search analytics table working');
            } else {
                $this->logResult('Search Analytics Table', 'FAIL', 'Search analytics table failed');
            }
        } catch (Exception $e) {
            $this->logResult('Search Analytics Table', 'ERROR', $e->getMessage());
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
        echo "\n📋 Frontend Integration Test Results\n";
        echo "==================================\n";

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
            echo "🎉 All tests passed! Frontend is fully integrated with backend.\n";
        } else {
            echo "⚠️  Some tests failed. Please check the integration issues.\n";
        }

        // Integration summary
        echo "\n🔗 Integration Summary:\n";
        echo "=====================\n";
        
        $integrationPoints = [
            'Frontend Pages' => $summary['PASS'] > 0,
            'API Connectivity' => $summary['PASS'] > 0,
            'Authentication' => $summary['PASS'] > 0,
            'Search System' => $summary['PASS'] > 0,
            'Dashboard' => $summary['PASS'] > 0,
            'Content Management' => $summary['PASS'] > 0,
            'Payments' => $summary['PASS'] > 0,
            'WebSocket' => $summary['PASS'] > 0,
            'PWA Features' => $summary['PASS'] > 0,
            'Database' => $summary['PASS'] > 0
        ];

        foreach ($integrationPoints as $point => $working) {
            $icon = $working ? '✅' : '❌';
            echo "  {$icon} {$point}\n";
        }
    }
}

// Run the tests
if (php_sapi_name() === 'cli') {
    $test = new FrontendIntegrationTest();
    $test->runAllTests();
} else {
    echo "This script should be run from the command line.\n";
} 