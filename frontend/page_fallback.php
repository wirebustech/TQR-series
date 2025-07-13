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

// Database connection (SQLite)
$dbPath = __DIR__ . '/../backend/database/database.sqlite';
if (!file_exists($dbPath)) {
    header('Location: /404.php');
    exit;
}

try {
    $pdo = new PDO("sqlite:$dbPath");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Fetch page data directly from database
    $stmt = $pdo->prepare("
        SELECT * FROM pages 
        WHERE slug = :slug 
        AND (language = :lang OR language IS NULL) 
        AND is_published = 1 
        ORDER BY language = :lang DESC
        LIMIT 1
    ");
    
    $stmt->execute([
        'slug' => $slug,
        'lang' => $lang
    ]);
    
    $page = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$page) {
        header('Location: /404.php');
        exit;
    }
    
} catch (Exception $e) {
    error_log("Database error: " . $e->getMessage());
    header('Location: /404.php');
    exit;
}

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
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="/assets/css/main.css" rel="stylesheet">
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