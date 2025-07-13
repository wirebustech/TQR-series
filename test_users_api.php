<?php

// Simple test script for users API
$baseUrl = 'http://localhost:8000/api';

echo "Testing Users API...\n\n";

// Test 1: Get users (requires auth)
echo "1. Testing GET /api/users (requires auth):\n";
$response = file_get_contents($baseUrl . '/users');
$data = json_decode($response, true);
if ($data && isset($data['success'])) {
    echo "✓ Success: Found " . count($data['data']) . " users\n";
    foreach ($data['data'] as $user) {
        echo "  - " . $user['name'] . " (" . $user['email'] . ") - " . $user['role'] . "\n";
    }
} else {
    echo "✗ Failed to get users or requires authentication\n";
}

echo "\n2. Testing GET /api/users/stats (requires auth):\n";
$response = file_get_contents($baseUrl . '/users/stats');
$data = json_decode($response, true);
if ($data && isset($data['success'])) {
    echo "✓ Success: Stats retrieved\n";
    echo "  - Total: " . $data['data']['total'] . "\n";
    echo "  - Active: " . $data['data']['active'] . "\n";
    echo "  - Admin: " . $data['data']['admin'] . "\n";
    echo "  - New this month: " . $data['data']['new_this_month'] . "\n";
} else {
    echo "✗ Failed to get stats or requires authentication\n";
}

echo "\n3. Testing individual user (requires auth):\n";
$response = file_get_contents($baseUrl . '/users/1');
$data = json_decode($response, true);
if ($data && isset($data['success'])) {
    echo "✓ Success: User details retrieved\n";
    echo "  - Name: " . $data['data']['name'] . "\n";
    echo "  - Email: " . $data['data']['email'] . "\n";
    echo "  - Role: " . $data['data']['role'] . "\n";
} else {
    echo "✗ Failed to get user details or requires authentication\n";
}

echo "\nAPI Test completed!\n";
echo "Note: These endpoints require authentication. The admin portal will handle auth automatically.\n";
?> 