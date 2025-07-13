<?php
session_start();

// Get the page slug from URL parameter
$slug = $_GET['slug'] ?? '';
$lang = $_GET['lang'] ?? 'en';

if (empty($slug)) {
    header('Location: /404.php');
    exit;
}

// Load translations
require_once __DIR__ . '/includes/translation.php';

// API configuration
$apiBaseUrl = 'http://localhost:8000/api';

// Function to make API calls
function apiCall($endpoint, $method = 'GET', $data = null) {
    global $apiBaseUrl;
    
    $url = $apiBaseUrl . '/' . ltrim($endpoint, '/');
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5); // Reduced timeout
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3); // Connection timeout
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json'
    ]);
    
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
    } elseif ($method === 'PUT') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    // Debug logging
    error_log("API Call: $url - HTTP: $httpCode - Error: $error");
    
    if ($response === false || $httpCode !== 200) {
        error_log("API call failed: $url - HTTP: $httpCode - Error: $error");
        return null;
    }
    
    return json_decode($response, true);
}

// Fetch page data from API
$response = apiCall("pages/slug/{$slug}?language={$lang}");

if (!$response || !$response['success']) {
    header('Location: /404.php');
    exit;
}

$page = $response['data'];

// Set page metadata
$pageTitle = $page['meta_title'] ?: $page['title'];
$pageDescription = $page['meta_description'] ?: $page['description'];
$pageKeywords = $page['meta_keywords'] ?: '';

// Get translations
$translations = getTranslations($lang);

// Helper function to get translated text
function t($key, $fallback = '') {
    global $translations;
    return $translations[$key] ?? $fallback;
}

// Format date
function formatDate($date) {
    return date('F j, Y', strtotime($date));
}

// Format content (basic markdown-like formatting)
function formatContent($content) {
    // Convert line breaks to paragraphs
    $content = nl2br($content);
    
    // Basic markdown-like formatting
    $content = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $content);
    $content = preg_replace('/\*(.*?)\*/', '<em>$1</em>', $content);
    $content = preg_replace('/`(.*?)`/', '<code>$1</code>', $content);
    
    // Convert URLs to links
    $content = preg_replace('/(https?:\/\/[^\s]+)/', '<a href="$1" target="_blank" rel="noopener">$1</a>', $content);
    
    return $content;
}
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> - TQRS</title>
    <meta name="description" content="<?= htmlspecialchars($pageDescription) ?>">
    <?php if ($pageKeywords): ?>
    <meta name="keywords" content="<?= htmlspecialchars($pageKeywords) ?>">
    <?php endif; ?>
    
    <!-- Open Graph -->
    <meta property="og:title" content="<?= htmlspecialchars($pageTitle) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($pageDescription) ?>">
    <meta property="og:type" content="article">
    <meta property="og:url" content="<?= htmlspecialchars("http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}") ?>">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= htmlspecialchars($pageTitle) ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($pageDescription) ?>">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="<?= htmlspecialchars("http://{$_SERVER['HTTP_HOST']}/page.php?slug={$slug}&lang={$lang}") ?>">
    
    <!-- Alternate language versions -->
    <link rel="alternate" hreflang="en" href="<?= htmlspecialchars("http://{$_SERVER['HTTP_HOST']}/page.php?slug={$slug}&lang=en") ?>">
    <link rel="alternate" hreflang="fr" href="<?= htmlspecialchars("http://{$_SERVER['HTTP_HOST']}/page.php?slug={$slug}&lang=fr") ?>">
    <link rel="alternate" hreflang="es" href="<?= htmlspecialchars("http://{$_SERVER['HTTP_HOST']}/page.php?slug={$slug}&lang=es") ?>">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="/assets/css/main.css" rel="stylesheet">
    
    <!-- Structured Data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Article",
        "headline": "<?= htmlspecialchars($pageTitle) ?>",
        "description": "<?= htmlspecialchars($pageDescription) ?>",
        "datePublished": "<?= $page['created_at'] ?>",
        "dateModified": "<?= $page['updated_at'] ?>",
        "author": {
            "@type": "Person",
            "name": "<?= htmlspecialchars($page['creator']['name'] ?? 'TQRS Team') ?>"
        },
        "publisher": {
            "@type": "Organization",
            "name": "The Qualitative Research Series",
            "logo": {
                "@type": "ImageObject",
                "url": "http://<?= $_SERVER['HTTP_HOST'] ?>/assets/images/logo.png"
            }
        }
    }
    </script>
</head>
<body>
    <?php include_once __DIR__ . '/includes/header.php'; ?>
    
    <main class="container my-5">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/"><?= t('home', 'Home') ?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($page['title']) ?></li>
            </ol>
        </nav>
        
        <!-- Page Header -->
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto">
                <header class="text-center">
                    <h1 class="display-4 fw-bold mb-3"><?= htmlspecialchars($page['title']) ?></h1>
                    <?php if ($page['description']): ?>
                    <p class="lead text-muted mb-4"><?= htmlspecialchars($page['description']) ?></p>
                    <?php endif; ?>
                    
                    <!-- Page Meta -->
                    <div class="d-flex justify-content-center align-items-center text-muted small mb-4">
                        <span class="me-3">
                            <i class="fas fa-calendar-alt me-1"></i>
                            <?= t('published', 'Published') ?>: <?= formatDate($page['created_at']) ?>
                        </span>
                        <?php if ($page['updated_at'] !== $page['created_at']): ?>
                        <span class="me-3">
                            <i class="fas fa-edit me-1"></i>
                            <?= t('updated', 'Updated') ?>: <?= formatDate($page['updated_at']) ?>
                        </span>
                        <?php endif; ?>
                        <?php if ($page['creator']): ?>
                        <span>
                            <i class="fas fa-user me-1"></i>
                            <?= t('by', 'By') ?>: <?= htmlspecialchars($page['creator']['name']) ?>
                        </span>
                        <?php endif; ?>
                    </div>
                </header>
            </div>
        </div>
        
        <!-- Page Content -->
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <article class="content">
                    <?php if ($page['content']): ?>
                    <div class="page-content">
                        <?= formatContent($page['content']) ?>
                    </div>
                    <?php else: ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <?= t('no_content', 'No content available for this page.') ?>
                    </div>
                    <?php endif; ?>
                </article>
                
                <!-- Language Switcher -->
                <div class="mt-5 pt-4 border-top">
                    <h6 class="text-muted mb-3"><?= t('available_languages', 'Available Languages') ?>:</h6>
                    <div class="btn-group" role="group">
                        <a href="?slug=<?= urlencode($slug) ?>&lang=en" 
                           class="btn btn-outline-primary <?= $lang === 'en' ? 'active' : '' ?>">
                            <i class="fas fa-flag me-1"></i> English
                        </a>
                        <a href="?slug=<?= urlencode($slug) ?>&lang=fr" 
                           class="btn btn-outline-primary <?= $lang === 'fr' ? 'active' : '' ?>">
                            <i class="fas fa-flag me-1"></i> Français
                        </a>
                        <a href="?slug=<?= urlencode($slug) ?>&lang=es" 
                           class="btn btn-outline-primary <?= $lang === 'es' ? 'active' : '' ?>">
                            <i class="fas fa-flag me-1"></i> Español
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <?php include_once __DIR__ . '/includes/footer.php'; ?>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/main.js"></script>
</body>
</html> 