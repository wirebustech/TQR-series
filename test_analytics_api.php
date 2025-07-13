<?php
// Test Analytics API Endpoints

$baseUrl = 'http://localhost:8000/api';
$token = 'your_admin_token_here'; // Replace with actual admin token

echo "Testing Analytics API Endpoints\n";
echo "===============================\n\n";

// Test endpoints
$endpoints = [
    'analytics/overview' => 'Get Analytics Overview',
    'analytics/user-growth?days=30' => 'Get User Growth Data',
    'analytics/user-distribution' => 'Get User Distribution',
    'analytics/webinar-performance?days=30' => 'Get Webinar Performance',
    'analytics/contribution-status' => 'Get Contribution Status',
    'analytics/recent-activity?limit=5' => 'Get Recent Activity',
    'analytics/top-content?limit=5' => 'Get Top Content',
    'analytics/export-report?days=30' => 'Export Report'
];

foreach ($endpoints as $endpoint => $description) {
    echo "Testing: $description\n";
    echo "Endpoint: $endpoint\n";
    
    $url = "$baseUrl/$endpoint";
    $headers = [
        'Authorization: Bearer ' . $token,
        'Accept: application/json',
        'Content-Type: application/json'
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200) {
        $data = json_decode($response, true);
        if ($data && isset($data['success']) && $data['success']) {
            echo "✅ SUCCESS\n";
            if (isset($data['data'])) {
                if (is_array($data['data'])) {
                    echo "   Data keys: " . implode(', ', array_keys($data['data'])) . "\n";
                } else {
                    echo "   Data type: " . gettype($data['data']) . "\n";
                }
            }
        } else {
            echo "❌ FAILED - Invalid response format\n";
            echo "   Response: " . substr($response, 0, 200) . "...\n";
        }
    } else {
        echo "❌ FAILED - HTTP $httpCode\n";
        echo "   Response: " . substr($response, 0, 200) . "...\n";
    }
    
    echo "\n";
}

echo "Analytics API Testing Complete!\n";
?> 