<?php
session_start();

$pageTitle = 'Webinars - TQRS';
$pageDescription = 'Explore our collection of qualitative research webinars, workshops, and training sessions.';

include_once __DIR__ . '/includes/header.php';

$texts = [
    'webinarsTitle' => 'Qualitative Research Webinars',
    'webinarsSubtitle' => 'Learn from experts in qualitative research methodology, data analysis, and research design.',
    'upcomingWebinars' => 'Upcoming Webinars',
    'pastWebinars' => 'Past Webinars',
    'featuredWebinars' => 'Featured Webinars',
    'allWebinars' => 'All Webinars',
    'filterBy' => 'Filter by',
    'category' => 'Category',
    'allCategories' => 'All Categories',
    'sortBy' => 'Sort by',
    'date' => 'Date',
    'title' => 'Title',
    'popularity' => 'Popularity',
    'duration' => 'Duration',
    'speaker' => 'Speaker',
    'register' => 'Register',
    'viewDetails' => 'View Details',
    'watchNow' => 'Watch Now',
    'free' => 'Free',
    'paid' => 'Paid',
    'noWebinars' => 'No webinars found',
    'noWebinarsText' => 'Try adjusting your filters or check back later for new webinars.',
    'loading' => 'Loading webinars...',
    'error' => 'Error loading webinars',
    'retry' => 'Retry',
    'searchWebinars' => 'Search webinars...',
    'clearFilters' => 'Clear Filters',
    'webinarStats' => 'Webinar Statistics',
    'totalWebinars' => 'Total Webinars',
    'upcomingCount' => 'Upcoming',
    'completedCount' => 'Completed',
    'totalViews' => 'Total Views',
    'totalRegistrations' => 'Total Registrations'
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
$webinars = [];
$categories = [];
$stats = [];
$totalWebinars = 0;
$totalPages = 1;
$loading = true;
$error = null;

// Load data from API
try {
    // Load webinars
    $apiUrl = 'http://localhost:8000/api/webinars?' . http_build_query([
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
            $webinars = $data['data'];
            $totalWebinars = $data['total'] ?? count($webinars);
            $totalPages = $data['last_page'] ?? 1;
        }
    }
    
    // Load categories
    $categoriesUrl = 'http://localhost:8000/api/search/filters';
    $categoriesResponse = file_get_contents($categoriesUrl, false, $context);
    
    if ($categoriesResponse !== false) {
        $categoriesData = json_decode($categoriesResponse, true);
        if ($categoriesData && isset($categoriesData['data']['categories']['webinars'])) {
            $categories = $categoriesData['data']['categories']['webinars'];
        }
    }
    
    // Load stats
    $statsUrl = 'http://localhost:8000/api/webinars/stats';
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
    $webinars = getMockWebinars();
    $categories = getMockCategories();
    $stats = getMockStats();
}

/**
 * Get mock webinars for fallback
 */
function getMockWebinars() {
    return [
        [
            'id' => 1,
            'title' => 'Advanced Grounded Theory Methodology',
            'description' => 'Explore advanced techniques in grounded theory methodology for qualitative research.',
            'speaker_name' => 'Dr. Sarah Johnson',
            'category' => 'methodology',
            'duration' => '90 minutes',
            'scheduled_at' => '2024-02-15T14:00:00Z',
            'status' => 'scheduled',
            'is_free' => false,
            'price' => 49.99,
            'image' => 'assets/images/webinar-1.jpg',
            'views' => 1250,
            'registrations' => 89
        ],
        [
            'id' => 2,
            'title' => 'NVivo Software Masterclass',
            'description' => 'Master NVivo software for qualitative data analysis with hands-on examples.',
            'speaker_name' => 'Dr. Michael Chen',
            'category' => 'software',
            'duration' => '120 minutes',
            'scheduled_at' => '2024-02-20T15:00:00Z',
            'status' => 'scheduled',
            'is_free' => true,
            'price' => 0,
            'image' => 'assets/images/webinar-2.jpg',
            'views' => 2100,
            'registrations' => 156
        ],
        [
            'id' => 3,
            'title' => 'Conducting Effective Qualitative Interviews',
            'description' => 'Learn the art of qualitative interviewing with practical tips and techniques.',
            'speaker_name' => 'Dr. Emily Rodriguez',
            'category' => 'interviews',
            'duration' => '75 minutes',
            'scheduled_at' => '2024-02-25T13:00:00Z',
            'status' => 'scheduled',
            'is_free' => false,
            'price' => 29.99,
            'image' => 'assets/images/webinar-3.jpg',
            'views' => 890,
            'registrations' => 67
        ]
    ];
}

/**
 * Get mock categories for fallback
 */
function getMockCategories() {
    return ['methodology', 'software', 'interviews', 'analysis', 'ethics', 'writing'];
}

/**
 * Get mock stats for fallback
 */
function getMockStats() {
    return [
        'total_webinars' => 45,
        'upcoming_webinars' => 12,
        'completed_webinars' => 33,
        'total_views' => 15600,
        'total_registrations' => 2340
    ];
}
?>

<!-- Page Header -->
<div class="bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-5 fw-bold mb-3"><?= htmlspecialchars($texts['webinarsTitle']) ?></h1>
                <p class="lead mb-4"><?= htmlspecialchars($texts['webinarsSubtitle']) ?></p>
                
                <!-- Search Bar -->
                <div class="row g-3">
                    <div class="col-md-8">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-0">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input type="text" class="form-control border-0" id="searchInput" 
                                   placeholder="<?= htmlspecialchars($texts['searchWebinars']) ?>" 
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
                <!-- Webinar Stats -->
                <div class="row text-center">
                    <div class="col-4">
                        <div class="bg-white bg-opacity-10 rounded p-3">
                            <h4 class="mb-1"><?= $stats['total_webinars'] ?? 0 ?></h4>
                            <small><?= htmlspecialchars($texts['totalWebinars']) ?></small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="bg-white bg-opacity-10 rounded p-3">
                            <h4 class="mb-1"><?= $stats['upcoming_webinars'] ?? 0 ?></h4>
                            <small><?= htmlspecialchars($texts['upcomingCount']) ?></small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="bg-white bg-opacity-10 rounded p-3">
                            <h4 class="mb-1"><?= $stats['total_views'] ?? 0 ?></h4>
                            <small><?= htmlspecialchars($texts['totalViews']) ?></small>
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

            <!-- Webinars Content -->
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

                <!-- Webinars Grid -->
                <div id="webinarsGrid" style="display: <?= !$loading && !$error ? 'block' : 'none' ?>;">
                    <?php if (empty($webinars)): ?>
                        <!-- No Results -->
                        <div class="text-center py-5">
                            <i class="bi bi-camera-video-off display-1 text-muted mb-4"></i>
                            <h3><?= htmlspecialchars($texts['noWebinars']) ?></h3>
                            <p class="text-muted"><?= htmlspecialchars($texts['noWebinarsText']) ?></p>
                        </div>
                    <?php else: ?>
                        <!-- Webinars List -->
                        <div class="row">
                            <?php foreach ($webinars as $webinar): ?>
                                <div class="col-lg-4 col-md-6 mb-4">
                                    <div class="card h-100 border-0 shadow-sm webinar-card">
                                        <!-- Webinar Image -->
                                        <div class="position-relative">
                                            <img src="<?= htmlspecialchars($webinar['image'] ?? 'assets/images/webinar-default.jpg') ?>" 
                                                 class="card-img-top" alt="<?= htmlspecialchars($webinar['title']) ?>"
                                                 style="height: 200px; object-fit: cover;">
                                            
                                            <!-- Status Badge -->
                                            <div class="position-absolute top-0 start-0 m-2">
                                                <?php if ($webinar['is_free'] ?? false): ?>
                                                    <span class="badge bg-success"><?= htmlspecialchars($texts['free']) ?></span>
                                                <?php else: ?>
                                                    <span class="badge bg-warning"><?= htmlspecialchars($texts['paid']) ?></span>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <!-- Category Badge -->
                                            <div class="position-absolute top-0 end-0 m-2">
                                                <span class="badge bg-primary"><?= htmlspecialchars(ucfirst($webinar['category'] ?? 'general')) ?></span>
                                            </div>
                                        </div>
                                        
                                        <div class="card-body d-flex flex-column">
                                            <!-- Title and Description -->
                                            <h5 class="card-title mb-2"><?= htmlspecialchars($webinar['title']) ?></h5>
                                            <p class="card-text text-muted flex-grow-1">
                                                <?= htmlspecialchars(truncateText($webinar['description'] ?? '', 100)) ?>
                                            </p>
                                            
                                            <!-- Speaker -->
                                            <p class="text-muted small mb-2">
                                                <i class="bi bi-person me-1"></i>
                                                <?= htmlspecialchars($webinar['speaker_name'] ?? 'Unknown Speaker') ?>
                                            </p>
                                            
                                            <!-- Webinar Details -->
                                            <div class="row text-muted small mb-3">
                                                <div class="col-6">
                                                    <i class="bi bi-clock me-1"></i>
                                                    <?= htmlspecialchars($webinar['duration'] ?? 'N/A') ?>
                                                </div>
                                                <div class="col-6">
                                                    <i class="bi bi-calendar me-1"></i>
                                                    <?= formatDate($webinar['scheduled_at'] ?? '') ?>
                                                </div>
                                            </div>
                                            
                                            <!-- Stats -->
                                            <div class="row text-muted small mb-3">
                                                <div class="col-6">
                                                    <i class="bi bi-eye me-1"></i>
                                                    <?= number_format($webinar['views'] ?? 0) ?> views
                                                </div>
                                                <div class="col-6">
                                                    <i class="bi bi-people me-1"></i>
                                                    <?= number_format($webinar['registrations'] ?? 0) ?> registered
                                                </div>
                                            </div>
                                            
                                            <!-- Action Buttons -->
                                            <div class="mt-auto">
                                                <?php if (($webinar['is_free'] ?? false) || isset($_SESSION['user_id'])): ?>
                                                    <a href="webinar-details.php?id=<?= $webinar['id'] ?>&lang=<?= urlencode($lang) ?>" 
                                                       class="btn btn-primary w-100">
                                                        <?= htmlspecialchars($texts['viewDetails']) ?>
                                                    </a>
                                                <?php else: ?>
                                                    <div class="d-flex gap-2">
                                                        <a href="webinar-details.php?id=<?= $webinar['id'] ?>&lang=<?= urlencode($lang) ?>" 
                                                           class="btn btn-outline-primary flex-grow-1">
                                                            <?= htmlspecialchars($texts['viewDetails']) ?>
                                                        </a>
                                                        <button class="btn btn-primary" onclick="registerWebinar(<?= $webinar['id'] ?>)">
                                                            <?= htmlspecialchars($texts['register']) ?>
                                                        </button>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Pagination -->
                        <?php if ($totalPages > 1): ?>
                        <nav aria-label="Webinars pagination" class="mt-4">
                            <ul class="pagination justify-content-center">
                                <?php if ($page > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?= buildWebinarsUrl($category, $sort, $search, $page - 1) ?>">
                                            <i class="bi bi-chevron-left"></i>
                                        </a>
                                    </li>
                                <?php endif; ?>
                                
                                <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                                    <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                                        <a class="page-link" href="<?= buildWebinarsUrl($category, $sort, $search, $i) ?>">
                                            <?= $i ?>
                                        </a>
                                    </li>
                                <?php endfor; ?>
                                
                                <?php if ($page < $totalPages): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?= buildWebinarsUrl($category, $sort, $search, $page + 1) ?>">
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

<!-- Registration Modal -->
<div class="modal fade" id="registrationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Register for Webinar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>You need to be logged in to register for this webinar.</p>
                <p>Please <a href="login.php?lang=<?= urlencode($lang) ?>">log in</a> or <a href="register.php?lang=<?= urlencode($lang) ?>">create an account</a> to continue.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="login.php?lang=<?= urlencode($lang) ?>" class="btn btn-primary">Log In</a>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script src="assets/js/api.js"></script>
<script>
    let currentWebinarId = null;

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
        
        const url = buildWebinarsUrl(category, sort, search, 1);
        window.location.href = url;
    }

    function performSearch() {
        const search = document.getElementById('searchInput').value;
        const category = document.getElementById('categoryFilter').value;
        const sort = document.getElementById('sortFilter').value;
        
        const url = buildWebinarsUrl(category, sort, search, 1);
        window.location.href = url;
    }

    function clearFilters() {
        const url = buildWebinarsUrl('', 'date', '', 1);
        window.location.href = url;
    }

    function registerWebinar(webinarId) {
        currentWebinarId = webinarId;
        
        // Check if user is logged in
        if (typeof api !== 'undefined' && api.isAuthenticated()) {
            // User is logged in, proceed with registration
            window.location.href = `webinar-details.php?id=${webinarId}&lang=<?= urlencode($lang) ?>`;
        } else {
            // Show login modal
            const modal = new bootstrap.Modal(document.getElementById('registrationModal'));
            modal.show();
        }
    }

    function retryLoading() {
        window.location.reload();
    }

    function buildWebinarsUrl(category, sort, search, page) {
        const params = new URLSearchParams();
        
        if (category) params.set('category', category);
        if (sort) params.set('sort', sort);
        if (search) params.set('search', search);
        if (page > 1) params.set('page', page);
        params.set('lang', '<?= urlencode($lang) ?>');
        
        return 'webinars.php?' + params.toString();
    }

    function truncateText(text, maxLength) {
        if (text.length <= maxLength) {
            return text;
        }
        return text.substring(0, maxLength) + '...';
    }

    function formatDate(dateString) {
        if (!dateString) return 'TBD';
        
        const date = new Date(dateString);
        const now = new Date();
        const diffTime = Math.abs(now - date);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        
        if (diffDays === 0) {
            return 'Today';
        } else if (diffDays === 1) {
            return 'Tomorrow';
        } else if (diffDays <= 7) {
            return `In ${diffDays} days`;
        } else {
            return date.toLocaleDateString();
        }
    }
</script>

<?php
/**
 * Build webinars URL with parameters
 */
function buildWebinarsUrl($category, $sort, $search, $page = 1) {
    $params = [
        'lang' => $GLOBALS['lang']
    ];
    
    if ($category) $params['category'] = $category;
    if ($sort) $params['sort'] = $sort;
    if ($search) $params['search'] = $search;
    if ($page > 1) $params['page'] = $page;
    
    return 'webinars.php?' . http_build_query($params);
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
        return 'TBD';
    }
    
    $date = new DateTime($dateString);
    $now = new DateTime();
    $diff = $date->diff($now);
    
    if ($diff->days === 0) {
        return 'Today';
    } elseif ($diff->days === 1) {
        return 'Tomorrow';
    } elseif ($diff->days <= 7) {
        return 'In ' . $diff->days . ' days';
    } else {
        return $date->format('M j, Y');
    }
}
?>

<?php include_once __DIR__ . '/includes/footer.php'; ?> 