<?php

// Simple test script for webinars API
$baseUrl = 'http://localhost:8000/api';

echo "Testing Webinars API...\n\n";

// Test 1: Get webinars (should work without auth for public endpoints)
echo "1. Testing GET /api/webinars (public):\n";
$response = file_get_contents($baseUrl . '/webinars');
$data = json_decode($response, true);
if ($data && isset($data['success'])) {
    echo "✓ Success: Found " . count($data['data']) . " webinars\n";
    foreach ($data['data'] as $webinar) {
        echo "  - " . $webinar['title'] . " (" . $webinar['status'] . ")\n";
    }
} else {
    echo "✗ Failed to get webinars\n";
}

echo "\n2. Testing GET /api/webinars/stats (public):\n";
$response = file_get_contents($baseUrl . '/webinars/stats');
$data = json_decode($response, true);
if ($data && isset($data['success'])) {
    echo "✓ Success: Stats retrieved\n";
    echo "  - Total: " . $data['data']['total'] . "\n";
    echo "  - Published: " . $data['data']['published'] . "\n";
    echo "  - Draft: " . $data['data']['draft'] . "\n";
    echo "  - Upcoming: " . $data['data']['upcoming'] . "\n";
} else {
    echo "✗ Failed to get stats\n";
}

echo "\n3. Testing individual webinar (public):\n";
$response = file_get_contents($baseUrl . '/webinars/1');
$data = json_decode($response, true);
if ($data && isset($data['success'])) {
    echo "✓ Success: Webinar details retrieved\n";
    echo "  - Title: " . $data['data']['title'] . "\n";
    echo "  - Scheduled: " . $data['data']['scheduled_at'] . "\n";
    echo "  - Duration: " . $data['data']['duration'] . " minutes\n";
} else {
    echo "✗ Failed to get webinar details\n";
}

echo "\nAPI Test completed!\n";
?> 