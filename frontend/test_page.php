<?php
// Simple test page to verify dynamic page system
$slug = $_GET['slug'] ?? '';
$lang = $_GET['lang'] ?? 'en';

if (empty($slug)) {
    die("No slug provided");
}

// Database connection (SQLite)
$dbPath = __DIR__ . '/../backend/database/database.sqlite';
if (!file_exists($dbPath)) {
    die("Database not found");
}

try {
    $pdo = new PDO("sqlite:$dbPath");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Fetch page data directly from database
    $stmt = $pdo->prepare("
        SELECT * FROM pages 
        WHERE slug = :slug 
        AND is_published = 1 
        LIMIT 1
    ");
    
    $stmt->execute(['slug' => $slug]);
    $page = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$page) {
        die("Page not found");
    }
    
} catch (Exception $e) {
    die("Database error: " . $e->getMessage());
}

// Format content
function formatContent($content) {
    $content = nl2br($content);
    $content = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $content);
    $content = preg_replace('/\*(.*?)\*/', '<em>$1</em>', $content);
    $content = preg_replace('/# (.*?)\n/', '<h1>$1</h1>', $content);
    $content = preg_replace('/## (.*?)\n/', '<h2>$1</h2>', $content);
    return $content;
}
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page['title']) ?> - TQRS</title>
    <meta name="description" content="<?= htmlspecialchars($page['description']) ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <h1 class="display-4 fw-bold mb-3"><?= htmlspecialchars($page['title']) ?></h1>
                <p class="lead text-muted mb-4"><?= htmlspecialchars($page['description']) ?></p>
                
                <div class="content">
                    <?= formatContent($page['content']) ?>
                </div>
                
                <div class="mt-5 pt-4 border-top">
                    <h6>Test URLs:</h6>
                    <ul>
                        <li><a href="?slug=test-dynamic-page&lang=en">English</a></li>
                        <li><a href="?slug=test-dynamic-page&lang=fr">French</a></li>
                        <li><a href="?slug=test-dynamic-page&lang=es">Spanish</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 