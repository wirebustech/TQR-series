<?php
// Google Translate API utility with file cache
function translateText($text, $target, $source = 'en') {
    $cacheDir = __DIR__ . '/cache';
    if (!is_dir($cacheDir)) mkdir($cacheDir, 0777, true);
    $cacheKey = md5($text . $target . $source);
    $cacheFile = "$cacheDir/$cacheKey.txt";
    if (file_exists($cacheFile)) {
        return file_get_contents($cacheFile);
    }
    $apiKey = getenv('GOOGLE_TRANSLATE_API_KEY');
    $url = 'https://translation.googleapis.com/language/translate/v2';
    $fields = [
        'q' => $text,
        'target' => $target,
        'source' => $source,
        'format' => 'text',
        'key' => $apiKey
    ];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
    $response = curl_exec($ch);
    curl_close($ch);
    $result = json_decode($response, true);
    $translated = $result['data']['translations'][0]['translatedText'] ?? $text;
    file_put_contents($cacheFile, $translated);
    return $translated;
} 