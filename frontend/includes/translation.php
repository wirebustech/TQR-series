<?php
// Google Translate API utility with file cache
function translateText($text, $target, $source = 'en') {
    // If target is English, return original text
    if ($target === 'en') {
        return $text;
    }
    
    $cacheDir = __DIR__ . '/cache';
    if (!is_dir($cacheDir)) mkdir($cacheDir, 0777, true);
    $cacheKey = md5($text . $target . $source);
    $cacheFile = "$cacheDir/$cacheKey.txt";
    if (file_exists($cacheFile)) {
        return file_get_contents($cacheFile);
    }
    
    // For now, return original text if no API key
    $apiKey = getenv('GOOGLE_TRANSLATE_API_KEY');
    if (!$apiKey) {
        return $text;
    }
    
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

// Get translations for a specific language
function getTranslations($lang = 'en') {
    $translations = [
        'en' => [
            'home' => 'Home',
            'webinars' => 'Webinars',
            'blog' => 'Blog',
            'about' => 'About',
            'contact' => 'Contact',
            'login' => 'Login',
            'register' => 'Register',
            'dashboard' => 'Dashboard',
            'search' => 'Search',
            'research_ai' => 'Research AI'
        ],
        'fr' => [
            'home' => 'Accueil',
            'webinars' => 'Webinaires',
            'blog' => 'Blog',
            'about' => 'À propos',
            'contact' => 'Contact',
            'login' => 'Connexion',
            'register' => 'S\'inscrire',
            'dashboard' => 'Tableau de bord',
            'search' => 'Recherche',
            'research_ai' => 'IA de recherche'
        ],
        'es' => [
            'home' => 'Inicio',
            'webinars' => 'Seminarios web',
            'blog' => 'Blog',
            'about' => 'Acerca de',
            'contact' => 'Contacto',
            'login' => 'Iniciar sesión',
            'register' => 'Registrarse',
            'dashboard' => 'Panel de control',
            'search' => 'Buscar',
            'research_ai' => 'IA de investigación'
        ]
    ];
    
    return $translations[$lang] ?? $translations['en'];
}
?> 