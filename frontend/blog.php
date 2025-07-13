<?php
session_start();

$pageTitle = 'Blog - TQRS';
$pageDescription = 'Read the latest articles, research insights, and expert perspectives on qualitative research methodology.';

include_once __DIR__ . '/includes/header.php';

$texts = [
    'blogTitle' => 'Qualitative Research Blog',
    'blogSubtitle' => 'Insights, methodologies, and expert perspectives on qualitative research',
    'featuredArticles' => 'Featured Articles',
    'latestArticles' => 'Latest Articles',
    'popularArticles' => 'Popular Articles',
    'allArticles' => 'All Articles',
    'filterBy' => 'Filter by',
    'category' => 'Category',
    'allCategories' => 'All Categories',
    'sortBy' => 'Sort by',
    'date' => 'Date',
    'title' => 'Title',
    'popularity' => 'Popularity',
    'author' => 'Author',
    'readMore' => 'Read More',
    'readTime' => 'Read Time',
    'published' => 'Published',
    'noArticles' => 'No articles found',
    'noArticlesText' => 'Try adjusting your filters or check back later for new articles.',
    'loading' => 'Loading articles...',
    'error' => 'Error loading articles',
    'retry' => 'Retry',
    'searchArticles' => 'Search articles...',
    'clearFilters' => 'Clear Filters',
    'blogStats' => 'Blog Statistics',
    'totalArticles' => 'Total Articles',
    'totalViews' => 'Total Views',
    'totalComments' => 'Total Comments',
    'monthlyReaders' => 'Monthly Readers'
];
if ($lang !== 'en') {
    foreach ($texts as $k => $v) {
        $texts[$k] = translateText($v, $lang, 'en');
    }
}

// Get filter parameters
$category = $_GET['category'] ?? '';
$sort = $_GET['sort'] ?? 'date';
$search = $_GET['search'] ?? '';
$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 12;

// Initialize data
$articles = [];
$categories = [];
$stats = [];
$totalArticles = 0;
$totalPages = 1;
$loading = true;
$error = null;

// Load data from API
try {
    // Load articles
    $apiUrl = 'http://localhost:8000/api/articles?' . http_build_query([
        'category' => $category,
        'sort' => $sort,
        'search' => $search,
        'page' => $page,
        'per_page' => $perPage
    ]);
    
    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'header' => 'Content-Type: application/json'
        ]
    ]);
    
    $response = file_get_contents($apiUrl, false, $context);
    
    if ($response !== false) {
        $data = json_decode($response, true);
        if ($data && isset($data['data'])) {
            $articles = $data['data'];
            $totalArticles = $data['total'] ?? count($articles);
            $totalPages = $data['last_page'] ?? 1;
        }
    }
    
    // Load categories
    $categoriesUrl = 'http://localhost:8000/api/search/filters';
    $categoriesResponse = file_get_contents($categoriesUrl, false, $context);
    
    if ($categoriesResponse !== false) {
        $categoriesData = json_decode($categoriesResponse, true);
        if ($categoriesData && isset($categoriesData['data']['categories']['articles'])) {
            $categories = $categoriesData['data']['categories']['articles'];
        }
    }
    
    // Load stats
    $statsUrl = 'http://localhost:8000/api/articles/stats';
    $statsResponse = file_get_contents($statsUrl, false, $context);
    
    if ($statsResponse !== false) {
        $statsData = json_decode($statsResponse, true);
        if ($statsData && isset($statsData['data'])) {
            $stats = $statsData['data'];
        }
    }
    
    $loading = false;
    
} catch (Exception $e) {
    $error = $e->getMessage();
    $loading = false;
    
    // Fallback to mock data
    $articles = getMockArticles();
    $categories = getMockCategories();
    $stats = getMockStats();
}

/**
 * Get mock articles for fallback
 */
function getMockArticles() {
    return [
        [
            'id' => 1,
            'title' => 'Understanding Grounded Theory: A Comprehensive Guide',
            'excerpt' => 'Explore the fundamentals of grounded theory methodology and its applications in qualitative research.',
            'author_name' => 'Dr. Sarah Johnson',
            'category' => 'methodology',
            'read_time' => 8,
            'published_at' => '2024-02-10T10:00:00Z',
            'image' => 'assets/images/article-1.jpg',
            'views' => 1250,
            'comments_count' => 23,
            'featured' => true
        ],
        [
            'id' => 2,
            'title' => 'Best Practices for Qualitative Interviewing',
            'excerpt' => 'Learn essential techniques for conducting effective qualitative interviews that yield rich data.',
            'author_name' => 'Dr. Michael Chen',
            'category' => 'interviews',
            'read_time' => 6,
            'published_at' => '2024-02-08T14:30:00Z',
            'image' => 'assets/images/article-2.jpg',
            'views' => 890,
            'comments_count' => 15,
            'featured' => false
        ],
        [
            'id' => 3,
            'title' => 'NVivo vs MAXQDA: Choosing the Right Software',
            'excerpt' => 'Compare the leading qualitative data analysis software tools to find the best fit for your research.',
            'author_name' => 'Dr. Emily Rodriguez',
            'category' => 'software',
            'read_time' => 10,
            'published_at' => '2024-02-05T09:15:00Z',
            'image' => 'assets/images/article-3.jpg',
            'views' => 2100,
            'comments_count' => 34,
            'featured' => true
        ]
    ];
}

/**
 * Get mock categories for fallback
 */
function getMockCategories() {
    return ['methodology', 'interviews', 'software', 'analysis', 'ethics', 'writing', 'case-studies'];
}

/**
 * Get mock stats for fallback
 */
function getMockStats() {
    return [
        'total_articles' => 156,
        'total_views' => 45600,
        'total_comments' => 2340,
        'monthly_readers' => 8900
    ];
}
?>

<!-- Page Header -->
<div class="bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-5 fw-bold mb-3"><?= htmlspecialchars($texts['blogTitle']) ?></h1>
                <p class="lead mb-4"><?= htmlspecialchars($texts['blogSubtitle']) ?></p>
                
                <!-- Search Bar -->
                <div class="row g-3">
                    <div class="col-md-8">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-0">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input type="text" class="form-control border-0" id="searchInput" 
                                   placeholder="<?= htmlspecialchars($texts['searchArticles']) ?>" 
                                   value="<?= htmlspecialchars($search) ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-light w-100" onclick="performSearch()">
                            <i class="bi bi-search me-2"></i>Search
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 text-lg-end">
                <!-- Blog Stats -->
                <div class="row text-center">
                    <div class="col-4">
                        <div class="bg-white bg-opacity-10 rounded p-3">
                            <h4 class="mb-1"><?= $stats['total_articles'] ?? 0 ?></h4>
                            <small><?= htmlspecialchars($texts['totalArticles']) ?></small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="bg-white bg-opacity-10 rounded p-3">
                            <h4 class="mb-1"><?= $stats['total_views'] ?? 0 ?></h4>
                            <small><?= htmlspecialchars($texts['totalViews']) ?></small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="bg-white bg-opacity-10 rounded p-3">
                            <h4 class="mb-1"><?= $stats['monthly_readers'] ?? 0 ?></h4>
                            <small><?= htmlspecialchars($texts['monthlyReaders']) ?></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters and Content -->
<div class="py-5">
    <div class="container">
        <div class="row">
            <!-- Filters Sidebar -->
            <div class="col-lg-3 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent">
                        <h5 class="mb-0">
                            <i class="bi bi-funnel me-2"></i><?= htmlspecialchars($texts['filterBy']) ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Category Filter -->
                        <div class="mb-4">
                            <label class="form-label"><?= htmlspecialchars($texts['category']) ?></label>
                            <select class="form-select" id="categoryFilter">
                                <option value=""><?= htmlspecialchars($texts['allCategories']) ?></option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= htmlspecialchars($cat) ?>" <?= $category === $cat ? 'selected' : '' ?>>
                                        <?= htmlspecialchars(ucfirst($cat)) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Sort Options -->
                        <div class="mb-4">
                            <label class="form-label"><?= htmlspecialchars($texts['sortBy']) ?></label>
                            <select class="form-select" id="sortFilter">
                                <option value="date" <?= $sort === 'date' ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($texts['date']) ?>
                                </option>
                                <option value="title" <?= $sort === 'title' ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($texts['title']) ?>
                                </option>
                                <option value="popularity" <?= $sort === 'popularity' ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($texts['popularity']) ?>
                                </option>
                            </select>
                        </div>

                        <!-- Clear Filters -->
                        <button class="btn btn-outline-secondary w-100" onclick="clearFilters()">
                            <i class="bi bi-x-circle me-2"></i><?= htmlspecialchars($texts['clearFilters']) ?>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Articles Content -->
            <div class="col-lg-9">
                <!-- Loading State -->
                <div id="loadingState" class="text-center py-5" style="display: <?= $loading ? 'block' : 'none' ?>;">
                    <div class="spinner-border text-primary mb-3" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="text-muted"><?= htmlspecialchars($texts['loading']) ?></p>
                </div>

                <!-- Error State -->
                <div id="errorState" class="text-center py-5" style="display: <?= $error ? 'block' : 'none' ?>;">
                    <i class="bi bi-exclamation-triangle display-1 text-warning mb-3"></i>
                    <h4><?= htmlspecialchars($texts['error']) ?></h4>
                    <p class="text-muted mb-4"><?= htmlspecialchars($error) ?></p>
                    <button class="btn btn-primary" onclick="retryLoading()">
                        <i class="bi bi-arrow-clockwise me-2"></i><?= htmlspecialchars($texts['retry']) ?>
                    </button>
                </div>

                <!-- Articles Grid -->
                <div id="articlesGrid" style="display: <?= !$loading && !$error ? 'block' : 'none' ?>;">
                    <?php if (empty($articles)): ?>
                        <!-- No Results -->
                        <div class="text-center py-5">
                            <i class="bi bi-journal-x display-1 text-muted mb-4"></i>
                            <h3><?= htmlspecialchars($texts['noArticles']) ?></h3>
                            <p class="text-muted"><?= htmlspecialchars($texts['noArticlesText']) ?></p>
                        </div>
                    <?php else: ?>
                        <!-- Featured Articles (if any) -->
                        <?php 
                        $featuredArticles = array_filter($articles, function($article) {
                            return $article['featured'] ?? false;
                        });
                        ?>
                        
                        <?php if (!empty($featuredArticles)): ?>
                        <div class="mb-5">
                            <h3 class="mb-4">
                                <i class="bi bi-star-fill text-warning me-2"></i>
                                <?= htmlspecialchars($texts['featuredArticles']) ?>
                            </h3>
                            <div class="row">
                                <?php foreach (array_slice($featuredArticles, 0, 2) as $article): ?>
                                    <div class="col-lg-6 mb-4">
                                        <div class="card border-0 shadow-sm h-100 featured-article">
                                            <div class="position-relative">
                                                <img src="<?= htmlspecialchars($article['image'] ?? 'assets/images/article-default.jpg') ?>" 
                                                     class="card-img-top" alt="<?= htmlspecialchars($article['title']) ?>"
                                                     style="height: 200px; object-fit: cover;">
                                                <div class="position-absolute top-0 start-0 m-2">
                                                    <span class="badge bg-warning">Featured</span>
                                                </div>
                                            </div>
                                            <div class="card-body d-flex flex-column">
                                                <h4 class="card-title"><?= htmlspecialchars($article['title']) ?></h4>
                                                <p class="card-text text-muted flex-grow-1">
                                                    <?= htmlspecialchars(truncateText($article['excerpt'] ?? '', 120)) ?>
                                                </p>
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <small class="text-muted">
                                                        <i class="bi bi-person me-1"></i>
                                                        <?= htmlspecialchars($article['author_name'] ?? 'Unknown Author') ?>
                                                    </small>
                                                    <small class="text-muted">
                                                        <i class="bi bi-clock me-1"></i>
                                                        <?= htmlspecialchars($article['read_time'] ?? 0) ?> min read
                                                    </small>
                                                </div>
                                                <a href="article.php?id=<?= $article['id'] ?>&lang=<?= urlencode($lang) ?>" 
                                                   class="btn btn-primary">
                                                    <?= htmlspecialchars($texts['readMore']) ?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- All Articles -->
                        <div class="mb-4">
                            <h3 class="mb-4">
                                <i class="bi bi-journal-text me-2"></i>
                                <?= htmlspecialchars($texts['allArticles']) ?>
                            </h3>
                        </div>
                        
                        <div class="row">
                            <?php foreach ($articles as $article): ?>
                                <div class="col-lg-4 col-md-6 mb-4">
                                    <div class="card h-100 border-0 shadow-sm article-card">
                                        <!-- Article Image -->
                                        <div class="position-relative">
                                            <img src="<?= htmlspecialchars($article['image'] ?? 'assets/images/article-default.jpg') ?>" 
                                                 class="card-img-top" alt="<?= htmlspecialchars($article['title']) ?>"
                                                 style="height: 180px; object-fit: cover;">
                                            
                                            <!-- Category Badge -->
                                            <div class="position-absolute top-0 end-0 m-2">
                                                <span class="badge bg-primary"><?= htmlspecialchars(ucfirst($article['category'] ?? 'general')) ?></span>
                                            </div>
                                        </div>
                                        
                                        <div class="card-body d-flex flex-column">
                                            <!-- Title and Excerpt -->
                                            <h5 class="card-title mb-2"><?= htmlspecialchars($article['title']) ?></h5>
                                            <p class="card-text text-muted flex-grow-1">
                                                <?= htmlspecialchars(truncateText($article['excerpt'] ?? '', 100)) ?>
                                            </p>
                                            
                                            <!-- Author and Date -->
                                            <div class="row text-muted small mb-3">
                                                <div class="col-6">
                                                    <i class="bi bi-person me-1"></i>
                                                    <?= htmlspecialchars($article['author_name'] ?? 'Unknown Author') ?>
                                                </div>
                                                <div class="col-6">
                                                    <i class="bi bi-calendar me-1"></i>
                                                    <?= formatDate($article['published_at'] ?? '') ?>
                                                </div>
                                            </div>
                                            
                                            <!-- Stats -->
                                            <div class="row text-muted small mb-3">
                                                <div class="col-4">
                                                    <i class="bi bi-eye me-1"></i>
                                                    <?= number_format($article['views'] ?? 0) ?>
                                                </div>
                                                <div class="col-4">
                                                    <i class="bi bi-chat me-1"></i>
                                                    <?= number_format($article['comments_count'] ?? 0) ?>
                                                </div>
                                                <div class="col-4">
                                                    <i class="bi bi-clock me-1"></i>
                                                    <?= htmlspecialchars($article['read_time'] ?? 0) ?> min
                                                </div>
                                            </div>
                                            
                                            <!-- Read More Button -->
                                            <div class="mt-auto">
                                                <a href="article.php?id=<?= $article['id'] ?>&lang=<?= urlencode($lang) ?>" 
                                                   class="btn btn-outline-primary w-100">
                                                    <?= htmlspecialchars($texts['readMore']) ?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Pagination -->
                        <?php if ($totalPages > 1): ?>
                        <nav aria-label="Articles pagination" class="mt-4">
                            <ul class="pagination justify-content-center">
                                <?php if ($page > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?= buildBlogUrl($category, $sort, $search, $page - 1) ?>">
                                            <i class="bi bi-chevron-left"></i>
                                        </a>
                                    </li>
                                <?php endif; ?>
                                
                                <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                                    <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                                        <a class="page-link" href="<?= buildBlogUrl($category, $sort, $search, $i) ?>">
                                            <?= $i ?>
                                        </a>
                                    </li>
                                <?php endfor; ?>
                                
                                <?php if ($page < $totalPages): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?= buildBlogUrl($category, $sort, $search, $page + 1) ?>">
                                            <i class="bi bi-chevron-right"></i>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script src="assets/js/api.js"></script>
<script>
    // Initialize when page loads
    document.addEventListener('DOMContentLoaded', function() {
        // Set up filter change handlers
        document.getElementById('categoryFilter').addEventListener('change', applyFilters);
        document.getElementById('sortFilter').addEventListener('change', applyFilters);
        
        // Set up search input handler
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                performSearch();
            }
        });
    });

    function applyFilters() {
        const category = document.getElementById('categoryFilter').value;
        const sort = document.getElementById('sortFilter').value;
        const search = document.getElementById('searchInput').value;
        
        const url = buildBlogUrl(category, sort, search, 1);
        window.location.href = url;
    }

    function performSearch() {
        const search = document.getElementById('searchInput').value;
        const category = document.getElementById('categoryFilter').value;
        const sort = document.getElementById('sortFilter').value;
        
        const url = buildBlogUrl(category, sort, search, 1);
        window.location.href = url;
    }

    function clearFilters() {
        const url = buildBlogUrl('', 'date', '', 1);
        window.location.href = url;
    }

    function retryLoading() {
        window.location.reload();
    }

    function buildBlogUrl(category, sort, search, page) {
        const params = new URLSearchParams();
        
        if (category) params.set('category', category);
        if (sort) params.set('sort', sort);
        if (search) params.set('search', search);
        if (page > 1) params.set('page', page);
        params.set('lang', '<?= urlencode($lang) ?>');
        
        return 'blog.php?' + params.toString();
    }

    function truncateText(text, maxLength) {
        if (text.length <= maxLength) {
            return text;
        }
        return text.substring(0, maxLength) + '...';
    }

    function formatDate(dateString) {
        if (!dateString) return 'Unknown';
        
        const date = new Date(dateString);
        const now = new Date();
        const diffTime = Math.abs(now - date);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        
        if (diffDays === 0) {
            return 'Today';
        } else if (diffDays === 1) {
            return 'Yesterday';
        } else if (diffDays <= 7) {
            return `${diffDays} days ago`;
        } else {
            return date.toLocaleDateString();
        }
    }
</script>

<?php
/**
 * Build blog URL with parameters
 */
function buildBlogUrl($category, $sort, $search, $page = 1) {
    $params = [
        'lang' => $GLOBALS['lang']
    ];
    
    if ($category) $params['category'] = $category;
    if ($sort) $params['sort'] = $sort;
    if ($search) $params['search'] = $search;
    if ($page > 1) $params['page'] = $page;
    
    return 'blog.php?' . http_build_query($params);
}

/**
 * Truncate text to specified length
 */
function truncateText($text, $maxLength) {
    if (strlen($text) <= $maxLength) {
        return $text;
    }
    return substr($text, 0, $maxLength) . '...';
}

/**
 * Format date for display
 */
function formatDate($dateString) {
    if (empty($dateString)) {
        return 'Unknown';
    }
    
    $date = new DateTime($dateString);
    $now = new DateTime();
    $diff = $date->diff($now);
    
    if ($diff->days === 0) {
        return 'Today';
    } elseif ($diff->days === 1) {
        return 'Yesterday';
    } elseif ($diff->days <= 7) {
        return $diff->days . ' days ago';
    } else {
        return $date->format('M j, Y');
    }
}
?>

<?php include_once __DIR__ . '/includes/footer.php'; ?> 