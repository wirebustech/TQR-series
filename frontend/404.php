<?php
session_start();

$pageTitle = 'Page Not Found - TQRS';
$pageDescription = 'The page you are looking for could not be found.';

include_once __DIR__ . '/includes/header.php';

$texts = [
    'pageNotFound' => 'Page Not Found',
    'notFoundMessage' => 'The page you are looking for could not be found.',
    'errorCode' => 'Error 404',
    'suggestions' => 'Here are some suggestions:',
    'checkUrl' => 'Check the URL for typos',
    'useNavigation' => 'Use the navigation menu above',
    'trySearch' => 'Try searching for what you need',
    'popularPages' => 'Popular Pages',
    'recentArticles' => 'Recent Articles',
    'upcomingWebinars' => 'Upcoming Webinars',
    'searchPlaceholder' => 'Search for articles, webinars, or resources...',
    'searchBtn' => 'Search',
    'goHome' => 'Go to Homepage',
    'contactSupport' => 'Contact Support',
    'reportIssue' => 'Report this Issue',
    'backToPrevious' => 'Go Back',
    'helpfulLinks' => 'Helpful Links',
    'aboutUs' => 'About Us',
    'contactUs' => 'Contact Us',
    'helpCenter' => 'Help Center',
    'sitemap' => 'Sitemap'
];
if ($lang !== 'en') {
    foreach ($texts as $k => $v) {
        $texts[$k] = translateText($v, $lang, 'en');
    }
}

// Get the requested URL for debugging
$requestedUrl = $_SERVER['REQUEST_URI'] ?? '';
$referrer = $_SERVER['HTTP_REFERER'] ?? '';

// Mock popular pages
$popularPages = [
    ['title' => 'Home', 'url' => 'index.php', 'icon' => 'bi-house'],
    ['title' => 'Webinars', 'url' => 'webinars.php', 'icon' => 'bi-camera-video'],
    ['title' => 'Blog', 'url' => 'blog.php', 'icon' => 'bi-journal-text'],
    ['title' => 'About Us', 'url' => 'about.php', 'icon' => 'bi-info-circle'],
    ['title' => 'Contact', 'url' => 'contact.php', 'icon' => 'bi-envelope'],
    ['title' => 'Search', 'url' => 'search.php', 'icon' => 'bi-search']
];

// Mock recent articles
$recentArticles = [
    ['title' => 'Understanding Grounded Theory: A Comprehensive Guide', 'url' => 'article.php?id=1'],
    ['title' => 'Conducting Effective Qualitative Interviews', 'url' => 'article.php?id=2'],
    ['title' => 'Using NVivo for Qualitative Data Analysis', 'url' => 'article.php?id=3']
];

// Mock upcoming webinars
$upcomingWebinars = [
    ['title' => 'Advanced Grounded Theory Methodology', 'url' => 'webinar-details.php?id=1'],
    ['title' => 'NVivo Software Masterclass', 'url' => 'webinar-details.php?id=2']
];
?>

<!-- 404 Header -->
<div class="bg-light py-5">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <div class="mb-4">
                    <i class="bi bi-exclamation-triangle text-warning" style="font-size: 4rem;"></i>
                </div>
                <h1 class="display-1 fw-bold text-muted mb-3"><?= htmlspecialchars($texts['errorCode']) ?></h1>
                <h2 class="h3 mb-3"><?= htmlspecialchars($texts['pageNotFound']) ?></h2>
                <p class="lead text-muted mb-4"><?= htmlspecialchars($texts['notFoundMessage']) ?></p>
                
                <!-- Search Bar -->
                <div class="row justify-content-center mb-4">
                    <div class="col-md-8">
                        <form action="search.php" method="GET" class="d-flex">
                            <input type="hidden" name="lang" value="<?= htmlspecialchars($lang) ?>">
                            <input type="text" class="form-control form-control-lg" name="q" 
                                   placeholder="<?= htmlspecialchars($texts['searchPlaceholder']) ?>" required>
                            <button type="submit" class="btn btn-primary btn-lg ms-2">
                                <i class="bi bi-search"></i> <?= htmlspecialchars($texts['searchBtn']) ?>
                            </button>
                        </form>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="d-flex justify-content-center gap-3 mb-4">
                    <a href="index.php?lang=<?= urlencode($lang) ?>" class="btn btn-primary">
                        <i class="bi bi-house"></i> <?= htmlspecialchars($texts['goHome']) ?>
                    </a>
                    <button class="btn btn-outline-secondary" onclick="goBack()">
                        <i class="bi bi-arrow-left"></i> <?= htmlspecialchars($texts['backToPrevious']) ?>
                    </button>
                    <a href="contact.php?lang=<?= urlencode($lang) ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-headset"></i> <?= htmlspecialchars($texts['contactSupport']) ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Helpful Content -->
<div class="py-5">
    <div class="container">
        <div class="row">
            <!-- Suggestions -->
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-lightbulb text-warning"></i> <?= htmlspecialchars($texts['suggestions']) ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                <?= htmlspecialchars($texts['checkUrl']) ?>
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                <?= htmlspecialchars($texts['useNavigation']) ?>
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                <?= htmlspecialchars($texts['trySearch']) ?>
                            </li>
                        </ul>
                        
                        <?php if ($requestedUrl): ?>
                            <div class="mt-3 p-3 bg-light rounded">
                                <small class="text-muted">
                                    <strong>Requested URL:</strong><br>
                                    <?= htmlspecialchars($requestedUrl) ?>
                                </small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Popular Pages -->
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-star text-warning"></i> <?= htmlspecialchars($texts['popularPages']) ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <?php foreach ($popularPages as $page): ?>
                                <a href="<?= $page['url'] ?>?lang=<?= urlencode($lang) ?>" 
                                   class="list-group-item list-group-item-action border-0 px-0">
                                    <i class="bi <?= htmlspecialchars($page['icon']) ?> me-2"></i>
                                    <?= htmlspecialchars($page['title']) ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Articles -->
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-journal-text text-primary"></i> <?= htmlspecialchars($texts['recentArticles']) ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <?php foreach ($recentArticles as $article): ?>
                                <a href="<?= $article['url'] ?>&lang=<?= urlencode($lang) ?>" 
                                   class="list-group-item list-group-item-action border-0 px-0">
                                    <small class="text-muted"><?= htmlspecialchars($article['title']) ?></small>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Upcoming Webinars -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-camera-video text-danger"></i> <?= htmlspecialchars($texts['upcomingWebinars']) ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php foreach ($upcomingWebinars as $webinar): ?>
                                <div class="col-md-6">
                                    <a href="<?= $webinar['url'] ?>&lang=<?= urlencode($lang) ?>" 
                                       class="text-decoration-none">
                                        <div class="d-flex align-items-center p-3 border rounded">
                                            <i class="bi bi-camera-video text-danger fs-4 me-3"></i>
                                            <div>
                                                <h6 class="mb-1"><?= htmlspecialchars($webinar['title']) ?></h6>
                                                <small class="text-muted">Join our upcoming webinar</small>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Helpful Links -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-link-45deg text-info"></i> <?= htmlspecialchars($texts['helpfulLinks']) ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <a href="about.php?lang=<?= urlencode($lang) ?>" class="text-decoration-none">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-info-circle text-primary me-2"></i>
                                        <span><?= htmlspecialchars($texts['aboutUs']) ?></span>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="contact.php?lang=<?= urlencode($lang) ?>" class="text-decoration-none">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-envelope text-primary me-2"></i>
                                        <span><?= htmlspecialchars($texts['contactUs']) ?></span>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="help.php?lang=<?= urlencode($lang) ?>" class="text-decoration-none">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-question-circle text-primary me-2"></i>
                                        <span><?= htmlspecialchars($texts['helpCenter']) ?></span>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="sitemap.php?lang=<?= urlencode($lang) ?>" class="text-decoration-none">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-diagram-3 text-primary me-2"></i>
                                        <span><?= htmlspecialchars($texts['sitemap']) ?></span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Report Issue -->
        <div class="row mt-4">
            <div class="col-12 text-center">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="mb-3"><?= htmlspecialchars($texts['reportIssue']) ?></h6>
                        <p class="text-muted mb-3">
                            Help us improve by reporting this broken link or missing page.
                        </p>
                        <button class="btn btn-outline-primary" onclick="reportIssue()">
                            <i class="bi bi-bug"></i> <?= htmlspecialchars($texts['reportIssue']) ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Report Issue Modal -->
<div class="modal fade" id="reportIssueModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= htmlspecialchars($texts['reportIssue']) ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="reportForm">
                    <div class="mb-3">
                        <label for="issueType" class="form-label">Issue Type</label>
                        <select class="form-select" id="issueType" required>
                            <option value="">Select issue type</option>
                            <option value="broken_link">Broken Link</option>
                            <option value="missing_page">Missing Page</option>
                            <option value="wrong_redirect">Wrong Redirect</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="issueDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="issueDescription" rows="3" 
                                  placeholder="Please describe what you were trying to access and what happened..." required></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="userEmail" class="form-label">Your Email (optional)</label>
                        <input type="email" class="form-control" id="userEmail" 
                               placeholder="Enter your email if you'd like us to follow up">
                    </div>
                    
                    <div class="alert alert-info">
                        <small>
                            <strong>Technical Details:</strong><br>
                            URL: <?= htmlspecialchars($requestedUrl) ?><br>
                            Referrer: <?= htmlspecialchars($referrer) ?><br>
                            User Agent: <?= htmlspecialchars($_SERVER['HTTP_USER_AGENT'] ?? 'Unknown') ?>
                        </small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitReport()">Submit Report</button>
            </div>
        </div>
    </div>
</div>

<script>
// Go back functionality
function goBack() {
    if (document.referrer) {
        window.history.back();
    } else {
        window.location.href = 'index.php?lang=<?= urlencode($lang) ?>';
    }
}

// Report issue functionality
function reportIssue() {
    const modal = new bootstrap.Modal(document.getElementById('reportIssueModal'));
    modal.show();
}

function submitReport() {
    const form = document.getElementById('reportForm');
    const formData = new FormData(form);
    
    // Add technical details
    formData.append('url', '<?= addslashes($requestedUrl) ?>');
    formData.append('referrer', '<?= addslashes($referrer) ?>');
    formData.append('user_agent', '<?= addslashes($_SERVER['HTTP_USER_AGENT'] ?? '') ?>');
    formData.append('timestamp', new Date().toISOString());
    
    // In a real app, this would submit to your backend
    console.log('Submitting report:', Object.fromEntries(formData));
    
    // Show success message
    showToast('Success', 'Thank you for reporting this issue. We\'ll investigate and fix it.');
    
    // Close modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('reportIssueModal'));
    modal.hide();
    
    // Reset form
    form.reset();
}

// Search functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.querySelector('form[action="search.php"]');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            const searchInput = this.querySelector('input[name="q"]');
            if (!searchInput.value.trim()) {
                e.preventDefault();
                searchInput.focus();
            }
        });
    }
});

// Track 404 errors for analytics
document.addEventListener('DOMContentLoaded', function() {
    // In a real app, this would send analytics data
    console.log('404 Error tracked:', {
        url: '<?= addslashes($requestedUrl) ?>',
        referrer: '<?= addslashes($referrer) ?>',
        timestamp: new Date().toISOString()
    });
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + K to focus search
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
        e.preventDefault();
        const searchInput = document.querySelector('input[name="q"]');
        if (searchInput) {
            searchInput.focus();
        }
    }
    
    // Escape to go back
    if (e.key === 'Escape') {
        goBack();
    }
});
</script>

<style>
.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
}

.list-group-item:hover {
    background-color: #f8f9fa;
}

.bi {
    transition: transform 0.2s ease-in-out;
}

.list-group-item:hover .bi {
    transform: scale(1.1);
}

/* Custom 404 styling */
.display-1 {
    font-size: 6rem;
    font-weight: 900;
    color: #6c757d;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
}

@media (max-width: 768px) {
    .display-1 {
        font-size: 4rem;
    }
    
    .d-flex.gap-3 {
        flex-direction: column;
        gap: 0.5rem !important;
    }
    
    .d-flex.gap-3 .btn {
        width: 100%;
    }
}
</style>

<?php include_once __DIR__ . '/includes/footer.php'; ?> 