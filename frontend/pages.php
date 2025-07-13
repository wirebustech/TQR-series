<?php
session_start();

$lang = $_GET['lang'] ?? 'en';
$search = $_GET['search'] ?? '';
$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 12;

// Load translations
require_once __DIR__ . '/includes/translation.php';
$translations = getTranslations($lang);

// Helper function to get translated text
function t($key, $fallback = '') {
    global $translations;
    return $translations[$key] ?? $fallback;
}

// API configuration
$apiBaseUrl = 'http://localhost:8000/api';

// Function to make API calls
function apiCall($endpoint, $method = 'GET', $data = null) {
    global $apiBaseUrl;
    
    $url = $apiBaseUrl . '/' . ltrim($endpoint, '/');
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json'
    ]);
    
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode !== 200) {
        return null;
    }
    
    return json_decode($response, true);
}

// Build query parameters
$queryParams = [
    'language' => $lang,
    'order_by' => 'created_at',
    'order_direction' => 'desc'
];

if ($search) {
    $queryParams['search'] = $search;
}

$queryString = http_build_query($queryParams);

// Fetch pages from API
$response = apiCall("pages/published?{$queryString}");

$pages = [];
if ($response && $response['success']) {
    $pages = $response['data'];
}

// Set page metadata
$pageTitle = t('pages_title', 'All Pages') . ' - TQRS';
$pageDescription = t('pages_description', 'Browse all published pages on The Qualitative Research Series platform.');

// Format date
function formatDate($date) {
    return date('F j, Y', strtotime($date));
}

// Truncate text
function truncateText($text, $length = 150) {
    if (strlen($text) <= $length) {
        return $text;
    }
    return substr($text, 0, $length) . '...';
}
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <meta name="description" content="<?= htmlspecialchars($pageDescription) ?>">
    
    <!-- Open Graph -->
    <meta property="og:title" content="<?= htmlspecialchars($pageTitle) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($pageDescription) ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= htmlspecialchars("http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}") ?>">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="/assets/css/main.css" rel="stylesheet">
</head>
<body>
    <?php include_once __DIR__ . '/includes/header.php'; ?>
    
    <main class="container my-5">
        <!-- Page Header -->
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 fw-bold mb-3"><?= t('pages_title', 'All Pages') ?></h1>
                <p class="lead text-muted"><?= t('pages_subtitle', 'Explore all published content on our platform') ?></p>
            </div>
        </div>
        
        <!-- Search and Filters -->
        <div class="row mb-4">
            <div class="col-lg-8 mx-auto">
                <form method="GET" class="d-flex gap-2">
                    <input type="hidden" name="lang" value="<?= htmlspecialchars($lang) ?>">
                    <div class="flex-grow-1">
                        <input type="text" name="search" class="form-control" 
                               placeholder="<?= t('search_pages', 'Search pages...') ?>" 
                               value="<?= htmlspecialchars($search) ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> <?= t('search', 'Search') ?>
                    </button>
                    <?php if ($search): ?>
                    <a href="?lang=<?= htmlspecialchars($lang) ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i> <?= t('clear', 'Clear') ?>
                    </a>
                    <?php endif; ?>
                </form>
            </div>
        </div>
        
        <!-- Results Count -->
        <?php if ($search): ?>
        <div class="row mb-4">
            <div class="col-lg-8 mx-auto">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <?= count($pages) ?> <?= t('results_found', 'results found for') ?> 
                    "<strong><?= htmlspecialchars($search) ?></strong>"
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Pages Grid -->
        <div class="row">
            <?php if (empty($pages)): ?>
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                    <h3 class="text-muted"><?= t('no_pages_found', 'No pages found') ?></h3>
                    <p class="text-muted">
                        <?= $search ? t('no_search_results', 'Try adjusting your search terms') : t('no_pages_available', 'No pages are currently available') ?>
                    </p>
                    <?php if ($search): ?>
                    <a href="?lang=<?= htmlspecialchars($lang) ?>" class="btn btn-primary">
                        <?= t('view_all_pages', 'View All Pages') ?>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php else: ?>
            <?php foreach ($pages as $pageItem): ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="/page/<?= htmlspecialchars($pageItem['slug']) ?>?lang=<?= htmlspecialchars($lang) ?>" 
                               class="text-decoration-none">
                                <?= htmlspecialchars($pageItem['title']) ?>
                            </a>
                        </h5>
                        
                        <?php if ($pageItem['description']): ?>
                        <p class="card-text text-muted">
                            <?= htmlspecialchars(truncateText($pageItem['description'])) ?>
                        </p>
                        <?php endif; ?>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="fas fa-calendar-alt me-1"></i>
                                <?= formatDate($pageItem['created_at']) ?>
                            </small>
                            <?php if ($pageItem['creator']): ?>
                            <small class="text-muted">
                                <i class="fas fa-user me-1"></i>
                                <?= htmlspecialchars($pageItem['creator']['name']) ?>
                            </small>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-transparent">
                        <a href="/page/<?= htmlspecialchars($pageItem['slug']) ?>?lang=<?= htmlspecialchars($lang) ?>" 
                           class="btn btn-primary btn-sm">
                            <?= t('read_more', 'Read More') ?> <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <!-- Language Switcher -->
        <div class="row mt-5">
            <div class="col-lg-8 mx-auto text-center">
                <div class="border-top pt-4">
                    <h6 class="text-muted mb-3"><?= t('view_in_language', 'View in other languages') ?>:</h6>
                    <div class="btn-group" role="group">
                        <a href="?<?= http_build_query(array_merge($_GET, ['lang' => 'en'])) ?>" 
                           class="btn btn-outline-primary <?= $lang === 'en' ? 'active' : '' ?>">
                            <i class="fas fa-flag me-1"></i> English
                        </a>
                        <a href="?<?= http_build_query(array_merge($_GET, ['lang' => 'fr'])) ?>" 
                           class="btn btn-outline-primary <?= $lang === 'fr' ? 'active' : '' ?>">
                            <i class="fas fa-flag me-1"></i> Français
                        </a>
                        <a href="?<?= http_build_query(array_merge($_GET, ['lang' => 'es'])) ?>" 
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