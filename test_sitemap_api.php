<?php

// Test Sitemap API Endpoints
$baseUrl = 'http://localhost:8000/api';
$token = 'your_admin_token_here'; // Replace with actual admin token

echo "Testing Enhanced Sitemap API Endpoints\n";
echo "=====================================\n\n";

// Test 1: Get sitemap status (public)
echo "1. Testing GET /api/sitemap/status (public):\n";
$response = file_get_contents($baseUrl . '/sitemap/status');
$data = json_decode($response, true);
if ($data && isset($data['success'])) {
    echo "✓ Success: Sitemap status retrieved\n";
    echo "  - Total sitemaps: " . $data['summary']['total_sitemaps'] . "\n";
    echo "  - Total URLs: " . $data['summary']['total_urls'] . "\n";
    echo "  - Total size: " . number_format($data['summary']['total_size']) . " bytes\n";
    
    foreach ($data['sitemaps'] as $type => $sitemap) {
        if ($sitemap['exists']) {
            echo "  - {$type}: {$sitemap['total_urls']} URLs, {$sitemap['hours_old']} hours old\n";
        } else {
            echo "  - {$type}: Not found\n";
        }
    }
} else {
    echo "✗ Failed to get sitemap status\n";
}

echo "\n2. Testing GET /api/sitemap/stats (public):\n";
$response = file_get_contents($baseUrl . '/sitemap/stats');
$data = json_decode($response, true);
if ($data && isset($data['success'])) {
    echo "✓ Success: Sitemap statistics retrieved\n";
    echo "  - Total sitemaps: " . $data['stats']['total_sitemaps'] . "\n";
    echo "  - Total URLs: " . $data['stats']['total_urls'] . "\n";
    echo "  - Total size: " . number_format($data['stats']['total_size']) . " bytes\n";
    
    if ($data['stats']['newest_sitemap']) {
        echo "  - Newest: " . $data['stats']['newest_sitemap']['type'] . " (" . $data['stats']['newest_sitemap']['date'] . ")\n";
    }
    if ($data['stats']['oldest_sitemap']) {
        echo "  - Oldest: " . $data['stats']['oldest_sitemap']['type'] . " (" . $data['stats']['oldest_sitemap']['date'] . ")\n";
    }
} else {
    echo "✗ Failed to get sitemap statistics\n";
}

// Test 3: Generate main sitemap (requires auth)
echo "\n3. Testing POST /api/sitemap/generate (main) - requires auth:\n";
$url = "$baseUrl/sitemap/generate";
$headers = [
    'Authorization: Bearer ' . $token,
    'Accept: application/json',
    'Content-Type: application/json'
];

$postData = json_encode(['type' => 'main']);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200) {
    $data = json_decode($response, true);
    if ($data && isset($data['success']) && $data['success']) {
        echo "✓ Success: Main sitemap generated\n";
        if (isset($data['results']['main'])) {
            $result = $data['results']['main'];
            echo "  - Type: " . $result['type'] . "\n";
            echo "  - Filename: " . $result['filename'] . "\n";
            echo "  - URLs: " . $result['total_urls'] . "\n";
            echo "  - Size: " . number_format($result['file_size']) . " bytes\n";
        }
    } else {
        echo "✗ Failed to generate main sitemap\n";
        echo "  Response: " . substr($response, 0, 200) . "...\n";
    }
} else {
    echo "✗ Failed - HTTP $httpCode\n";
    echo "  Response: " . substr($response, 0, 200) . "...\n";
}

// Test 4: Generate image sitemap (requires auth)
echo "\n4. Testing POST /api/sitemap/generate (images) - requires auth:\n";
$postData = json_encode(['type' => 'images']);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200) {
    $data = json_decode($response, true);
    if ($data && isset($data['success']) && $data['success']) {
        echo "✓ Success: Image sitemap generated\n";
        if (isset($data['results']['images'])) {
            $result = $data['results']['images'];
            echo "  - Type: " . $result['type'] . "\n";
            echo "  - Filename: " . $result['filename'] . "\n";
            echo "  - URLs: " . $result['total_urls'] . "\n";
            echo "  - Size: " . number_format($result['file_size']) . " bytes\n";
        }
    } else {
        echo "✗ Failed to generate image sitemap\n";
    }
} else {
    echo "✗ Failed - HTTP $httpCode\n";
}

// Test 5: Generate news sitemap (requires auth)
echo "\n5. Testing POST /api/sitemap/generate (news) - requires auth:\n";
$postData = json_encode(['type' => 'news']);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200) {
    $data = json_decode($response, true);
    if ($data && isset($data['success']) && $data['success']) {
        echo "✓ Success: News sitemap generated\n";
        if (isset($data['results']['news'])) {
            $result = $data['results']['news'];
            echo "  - Type: " . $result['type'] . "\n";
            echo "  - Filename: " . $result['filename'] . "\n";
            echo "  - URLs: " . $result['total_urls'] . "\n";
            echo "  - Size: " . number_format($result['file_size']) . " bytes\n";
        }
    } else {
        echo "✗ Failed to generate news sitemap\n";
    }
} else {
    echo "✗ Failed - HTTP $httpCode\n";
}

// Test 6: Generate all sitemaps (requires auth)
echo "\n6. Testing POST /api/sitemap/generate (all) - requires auth:\n";
$postData = json_encode(['type' => 'all']);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200) {
    $data = json_decode($response, true);
    if ($data && isset($data['success']) && $data['success']) {
        echo "✓ Success: All sitemaps generated\n";
        foreach ($data['results'] as $type => $result) {
            echo "  - {$type}: {$result['total_urls']} URLs, " . number_format($result['file_size']) . " bytes\n";
        }
    } else {
        echo "✗ Failed to generate all sitemaps\n";
    }
} else {
    echo "✗ Failed - HTTP $httpCode\n";
}

// Test 7: Validate sitemap (requires auth)
echo "\n7. Testing POST /api/sitemap/validate (main) - requires auth:\n";
$url = "$baseUrl/sitemap/validate";
$postData = json_encode(['type' => 'main']);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200) {
    $data = json_decode($response, true);
    if ($data && isset($data['success']) && $data['success']) {
        echo "✓ Success: Sitemap validation completed\n";
        $validation = $data['validation'];
        echo "  - Valid: " . ($validation['valid'] ? 'Yes' : 'No') . "\n";
        echo "  - URL count: " . $validation['url_count'] . "\n";
        echo "  - File size: " . number_format($validation['file_size']) . " bytes\n";
        
        if (!empty($validation['errors'])) {
            echo "  - Errors: " . implode(', ', $validation['errors']) . "\n";
        }
        if (!empty($validation['warnings'])) {
            echo "  - Warnings: " . implode(', ', $validation['warnings']) . "\n";
        }
    } else {
        echo "✗ Failed to validate sitemap\n";
    }
} else {
    echo "✗ Failed - HTTP $httpCode\n";
}

// Test 8: Test force generation (requires auth)
echo "\n8. Testing POST /api/sitemap/generate (force) - requires auth:\n";
$url = "$baseUrl/sitemap/generate";
$postData = json_encode(['type' => 'main', 'force' => true]);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200) {
    $data = json_decode($response, true);
    if ($data && isset($data['success']) && $data['success']) {
        echo "✓ Success: Forced sitemap generation completed\n";
        if (isset($data['results']['main'])) {
            $result = $data['results']['main'];
            echo "  - URLs: " . $result['total_urls'] . "\n";
            echo "  - Size: " . number_format($result['file_size']) . " bytes\n";
        }
    } else {
        echo "✗ Failed to force generate sitemap\n";
    }
} else {
    echo "✗ Failed - HTTP $httpCode\n";
}

echo "\nEnhanced Sitemap API Testing Complete!\n";
echo "=====================================\n";
echo "Features tested:\n";
echo "- Multiple sitemap types (main, images, news)\n";
echo "- Sitemap validation\n";
echo "- Statistics and status reporting\n";
echo "- Force generation option\n";
echo "- Error handling and validation\n";
echo "- XML structure validation\n";
echo "\nNote: Authentication required for generation and validation endpoints.\n";
?> 