<?php
session_start();

$pageTitle = 'Search Results - TQRS';
$pageDescription = 'Search results for qualitative research content, webinars, and resources.';

include_once __DIR__ . '/includes/header.php';

$texts = [
    'searchResults' => 'Search Results',
    'searchFor' => 'Search for',
    'resultsFound' => 'results found',
    'noResults' => 'No results found',
    'noResultsText' => 'Try adjusting your search terms or browse our categories.',
    'filterBy' => 'Filter by',
    'contentType' => 'Content Type',
    'allContent' => 'All Content',
    'webinars' => 'Webinars',
    'articles' => 'Articles',
    'resources' => 'Resources',
    'methodologies' => 'Methodologies',
    'sortBy' => 'Sort by',
    'relevance' => 'Relevance',
    'date' => 'Date',
    'popularity' => 'Popularity',
    'title' => 'Title',
    'author' => 'Author',
    'category' => 'Category',
    'duration' => 'Duration',
    'readTime' => 'min read',
    'published' => 'Published',
    'viewDetails' => 'View Details',
    'searchAgain' => 'Search Again',
    'suggestions' => 'Search Suggestions',
    'tryThese' => 'Try these search terms:',
    'relatedSearches' => 'Related Searches'
];
if ($lang !== 'en') {
    foreach ($texts as $k => $v) {
        $texts[$k] = translateText($v, $lang, 'en');
    }
}

// Get search parameters
$query = $_GET['q'] ?? '';
$type = $_GET['type'] ?? 'all';
$category = $_GET['category'] ?? '';
$sort = $_GET['sort'] ?? 'relevance';
$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 10;

// Initialize search results
$searchData = [
    'results' => [],
    'total_results' => 0,
    'suggestions' => [],
    'filters' => []
];

// Perform search if query is provided
if ($query) {
    try {
        // Prepare search parameters
        $searchParams = [
            'query' => $query,
            'type' => $type,
            'sort' => $sort,
            'page' => $page,
            'per_page' => $perPage
        ];
        
        if ($category) {
            $searchParams['category'] = $category;
        }
        
        // Make API request
        $apiUrl = 'http://localhost:8000/api/search?' . http_build_query($searchParams);
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => 'Content-Type: application/json'
            ]
        ]);
        
        $response = file_get_contents($apiUrl, false, $context);
        
        if ($response !== false) {
            $searchData = json_decode($response, true);
            if (!$searchData['success']) {
                throw new Exception('Search failed');
            }
            $searchData = $searchData['data'];
        } else {
            throw new Exception('API request failed');
        }
        
    } catch (Exception $e) {
        // Fallback to mock data if API fails
        $searchData = getMockSearchData($query, $type, $sort);
    }
}

// Get search filters
$filters = [];
try {
    $filtersUrl = 'http://localhost:8000/api/search/filters';
    $filtersResponse = file_get_contents($filtersUrl, false, $context);
    
    if ($filtersResponse !== false) {
        $filtersData = json_decode($filtersResponse, true);
        if ($filtersData['success']) {
            $filters = $filtersData['data'];
        }
    }
} catch (Exception $e) {
    // Fallback filters
    $filters = getMockFilters();
}

// Extract results for display
$allResults = [];
$totalResults = $searchData['total_results'] ?? 0;

if (isset($searchData['results'])) {
    foreach ($searchData['results'] as $contentType => $contentData) {
        if (isset($contentData['data'])) {
            foreach ($contentData['data'] as $item) {
                $item['contentType'] = $contentType;
                $allResults[] = $item;
            }
        }
    }
}

// Pagination
$totalPages = ceil($totalResults / $perPage);
$currentPage = $page;

// Search suggestions
$suggestions = $searchData['suggestions'] ?? [];

/**
 * Get mock search data for fallback
 */
function getMockSearchData($query, $type, $sort) {
    $mockData = [
        'webinars' => [
            [
                'id' => 1,
                'type' => 'webinar',
                'title' => 'Advanced Grounded Theory Methodology',
                'description' => 'Explore advanced techniques in grounded theory methodology for qualitative research.',
                'author' => 'Dr. Sarah Johnson',
                'category' => 'methodology',
                'duration' => '90 minutes',
                'date' => '2024-02-15',
                'image' => 'assets/images/webinar-1.jpg',
                'relevance' => 95
            ],
            [
                'id' => 2,
                'type' => 'webinar',
                'title' => 'NVivo Software Masterclass',
                'description' => 'Master NVivo software for qualitative data analysis with hands-on examples.',
                'author' => 'Dr. Michael Chen',
                'category' => 'software',
                'duration' => '120 minutes',
                'date' => '2024-02-20',
                'image' => 'assets/images/webinar-2.jpg',
                'relevance' => 88
            ]
        ],
        'blogs' => [
            [
                'id' => 1,
                'type' => 'blog',
                'title' => 'Understanding Grounded Theory: A Comprehensive Guide',
                'description' => 'Explore the fundamentals of grounded theory methodology and learn how to apply it effectively.',
                'author' => 'Dr. Sarah Johnson',
                'category' => 'methodology',
                'readTime' => 8,
                'date' => '2024-02-10',
                'image' => 'assets/images/blog/grounded-theory.jpg',
                'relevance' => 92
            ],
            [
                'id' => 2,
                'type' => 'blog',
                'title' => 'Conducting Effective Qualitative Interviews',
                'description' => 'Master the art of qualitative interviewing with practical tips and techniques.',
                'author' => 'Dr. Michael Chen',
                'category' => 'interviews',
                'readTime' => 12,
                'date' => '2024-02-08',
                'image' => 'assets/images/blog/interviews.jpg',
                'relevance' => 85
            ]
        ]
    ];

    // Filter by type
    if ($type !== 'all' && isset($mockData[$type])) {
        $mockData = [$type => $mockData[$type]];
    }

    // Filter by query
    foreach ($mockData as $contentType => &$items) {
        $items = array_filter($items, function($item) use ($query) {
            return stripos($item['title'], $query) !== false || 
                   stripos($item['description'], $query) !== false ||
                   stripos($item['author'], $query) !== false ||
                   stripos($item['category'], $query) !== false;
        });
    }

    // Sort results
    $allResults = [];
    foreach ($mockData as $contentType => $items) {
        foreach ($items as $item) {
            $item['contentType'] = $contentType;
            $allResults[] = $item;
        }
    }

    usort($allResults, function($a, $b) use ($sort) {
        switch ($sort) {
            case 'date':
                return strtotime($b['date']) - strtotime($a['date']);
            case 'popularity':
                return ($b['relevance'] ?? 0) - ($a['relevance'] ?? 0);
            case 'relevance':
            default:
                return ($b['relevance'] ?? 0) - ($a['relevance'] ?? 0);
        }
    });

    return [
        'results' => [
            'webinars' => ['data' => array_filter($allResults, fn($item) => $item['contentType'] === 'webinars')],
            'blogs' => ['data' => array_filter($allResults, fn($item) => $item['contentType'] === 'blogs')]
        ],
        'total_results' => count($allResults),
        'suggestions' => [
            'grounded theory',
            'qualitative interviews',
            'NVivo software',
            'data analysis',
            'research methodology'
        ]
    ];
}

/**
 * Get mock filters for fallback
 */
function getMockFilters() {
    return [
        'categories' => [
            'webinars' => ['methodology', 'software', 'interviews', 'analysis'],
            'blogs' => ['methodology', 'interviews', 'software', 'analysis'],
            'contributions' => ['methodology', 'interviews', 'software']
        ],
        'popular_tags' => [
            'grounded theory',
            'qualitative interviews',
            'NVivo software',
            'data analysis',
            'research methodology'
        ],
        'sort_options' => [
            'relevance' => 'Most Relevant',
            'date' => 'Newest First',
            'title' => 'Alphabetical',
            'popularity' => 'Most Popular'
        ]
    ];
}
?>

<!-- Search Header -->
<div class="bg-light py-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="h3 mb-2"><?= htmlspecialchars($texts['searchResults']) ?></h1>
                <?php if ($query): ?>
                    <p class="text-muted mb-0">
                        <?= htmlspecialchars($texts['searchFor']) ?>: <strong>"<?= htmlspecialchars($query) ?>"</strong>
                        <span class="ms-2">(<?= $totalResults ?> <?= htmlspecialchars($texts['resultsFound']) ?>)</span>
                    </p>
                <?php endif; ?>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="index.php?lang=<?= urlencode($lang) ?>" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left"></i> <?= htmlspecialchars($texts['searchAgain']) ?>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Filters and Results -->
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
                        <!-- Content Type Filter -->
                        <div class="mb-4">
                            <h6 class="mb-3"><?= htmlspecialchars($texts['contentType']) ?></h6>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type" id="type-all" value="all" 
                                       <?= $type === 'all' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="type-all">
                                    <?= htmlspecialchars($texts['allContent']) ?>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type" id="type-webinars" value="webinars" 
                                       <?= $type === 'webinars' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="type-webinars">
                                    <?= htmlspecialchars($texts['webinars']) ?>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type" id="type-blogs" value="blogs" 
                                       <?= $type === 'blogs' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="type-blogs">
                                    <?= htmlspecialchars($texts['articles']) ?>
                                </label>
                            </div>
                        </div>

                        <!-- Category Filter -->
                        <?php if (!empty($filters['categories'])): ?>
                        <div class="mb-4">
                            <h6 class="mb-3"><?= htmlspecialchars($texts['category']) ?></h6>
                            <select class="form-select" name="category" id="category-filter">
                                <option value=""><?= htmlspecialchars($texts['allContent']) ?></option>
                                <?php foreach ($filters['categories']['webinars'] ?? [] as $cat): ?>
                                    <option value="<?= htmlspecialchars($cat) ?>" <?= $category === $cat ? 'selected' : '' ?>>
                                        <?= htmlspecialchars(ucfirst($cat)) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php endif; ?>

                        <!-- Sort Options -->
                        <div class="mb-4">
                            <h6 class="mb-3"><?= htmlspecialchars($texts['sortBy']) ?></h6>
                            <select class="form-select" name="sort" id="sort-filter">
                                <option value="relevance" <?= $sort === 'relevance' ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($texts['relevance']) ?>
                                </option>
                                <option value="date" <?= $sort === 'date' ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($texts['date']) ?>
                                </option>
                                <option value="popularity" <?= $sort === 'popularity' ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($texts['popularity']) ?>
                                </option>
                                <option value="title" <?= $sort === 'title' ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($texts['title']) ?>
                                </option>
                            </select>
                        </div>

                        <!-- Apply Filters Button -->
                        <button type="button" class="btn btn-primary w-100" onclick="applyFilters()">
                            <i class="bi bi-search me-2"></i>Apply Filters
                        </button>
                    </div>
                </div>

                <!-- Search Suggestions -->
                <?php if (!empty($suggestions)): ?>
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-header bg-transparent">
                        <h6 class="mb-0">
                            <i class="bi bi-lightbulb me-2"></i><?= htmlspecialchars($texts['suggestions']) ?>
                        </h6>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small mb-3"><?= htmlspecialchars($texts['tryThese']) ?></p>
                        <div class="d-flex flex-wrap gap-2">
                            <?php foreach (array_slice($suggestions, 0, 6) as $suggestion): ?>
                                <a href="search.php?q=<?= urlencode($suggestion) ?>&lang=<?= urlencode($lang) ?>" 
                                   class="btn btn-outline-secondary btn-sm">
                                    <?= htmlspecialchars($suggestion) ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Search Results -->
            <div class="col-lg-9">
                <?php if (empty($allResults)): ?>
                    <!-- No Results -->
                    <div class="text-center py-5">
                        <i class="bi bi-search display-1 text-muted mb-4"></i>
                        <h3><?= htmlspecialchars($texts['noResults']) ?></h3>
                        <p class="text-muted"><?= htmlspecialchars($texts['noResultsText']) ?></p>
                        <a href="index.php?lang=<?= urlencode($lang) ?>" class="btn btn-primary">
                            <i class="bi bi-house me-2"></i>Go Home
                        </a>
                    </div>
                <?php else: ?>
                    <!-- Results List -->
                    <div class="search-results">
                        <?php foreach ($allResults as $result): ?>
                            <div class="card border-0 shadow-sm mb-4 animate-on-scroll">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="d-flex align-items-start mb-2">
                                                <span class="badge bg-primary me-2">
                                                    <?= htmlspecialchars(ucfirst($result['contentType'])) ?>
                                                </span>
                                                <small class="text-muted">
                                                    <?= htmlspecialchars($result['category'] ?? '') ?>
                                                </small>
                                            </div>
                                            
                                            <h5 class="card-title mb-2">
                                                <a href="<?= getResultUrl($result) ?>" class="text-decoration-none">
                                                    <?= htmlspecialchars($result['title']) ?>
                                                </a>
                                            </h5>
                                            
                                            <p class="card-text text-muted mb-3">
                                                <?= htmlspecialchars(truncateText($result['description'] ?? '', 150)) ?>
                                            </p>
                                            
                                            <div class="d-flex align-items-center text-muted small">
                                                <span class="me-3">
                                                    <i class="bi bi-person me-1"></i>
                                                    <?= htmlspecialchars($result['author'] ?? 'Unknown') ?>
                                                </span>
                                                <span class="me-3">
                                                    <i class="bi bi-calendar me-1"></i>
                                                    <?= formatDate($result['date'] ?? '') ?>
                                                </span>
                                                <?php if (isset($result['duration'])): ?>
                                                    <span class="me-3">
                                                        <i class="bi bi-clock me-1"></i>
                                                        <?= htmlspecialchars($result['duration']) ?>
                                                    </span>
                                                <?php endif; ?>
                                                <?php if (isset($result['readTime'])): ?>
                                                    <span>
                                                        <i class="bi bi-book me-1"></i>
                                                        <?= $result['readTime'] ?> <?= htmlspecialchars($texts['readTime']) ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        
                                        <?php if (isset($result['image'])): ?>
                                        <div class="col-md-4">
                                            <img src="<?= htmlspecialchars($result['image']) ?>" 
                                                 alt="<?= htmlspecialchars($result['title']) ?>"
                                                 class="img-fluid rounded">
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="mt-3">
                                        <a href="<?= getResultUrl($result) ?>" class="btn btn-outline-primary btn-sm">
                                            <?= htmlspecialchars($texts['viewDetails']) ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Pagination -->
                    <?php if ($totalPages > 1): ?>
                    <nav aria-label="Search results pagination">
                        <ul class="pagination justify-content-center">
                            <?php if ($currentPage > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= buildSearchUrl($query, $type, $category, $sort, $currentPage - 1) ?>">
                                        <i class="bi bi-chevron-left"></i>
                                    </a>
                                </li>
                            <?php endif; ?>
                            
                            <?php for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++): ?>
                                <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                                    <a class="page-link" href="<?= buildSearchUrl($query, $type, $category, $sort, $i) ?>">
                                        <?= $i ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                            
                            <?php if ($currentPage < $totalPages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= buildSearchUrl($query, $type, $category, $sort, $currentPage + 1) ?>">
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

<!-- JavaScript for filter functionality -->
<script>
function applyFilters() {
    const type = document.querySelector('input[name="type"]:checked')?.value || 'all';
    const category = document.getElementById('category-filter').value;
    const sort = document.getElementById('sort-filter').value;
    
    const url = new URL(window.location);
    url.searchParams.set('type', type);
    if (category) {
        url.searchParams.set('category', category);
    } else {
        url.searchParams.delete('category');
    }
    url.searchParams.set('sort', sort);
    url.searchParams.delete('page'); // Reset to first page
    
    window.location.href = url.toString();
}

// Auto-apply filters when changed
document.querySelectorAll('input[name="type"], #category-filter, #sort-filter').forEach(element => {
    element.addEventListener('change', applyFilters);
});
</script>

<?php
/**
 * Get URL for search result
 */
function getResultUrl($result) {
    $baseUrl = '';
    switch ($result['contentType']) {
        case 'webinars':
            $baseUrl = 'webinar-details.php';
            break;
        case 'blogs':
            $baseUrl = 'article.php';
            break;
        case 'pages':
            $baseUrl = 'page.php';
            break;
        case 'contributions':
            $baseUrl = 'contribution.php';
            break;
        default:
            $baseUrl = 'index.php';
    }
    
    $params = ['id' => $result['id'], 'lang' => $GLOBALS['lang']];
    return $baseUrl . '?' . http_build_query($params);
}

/**
 * Build search URL with parameters
 */
function buildSearchUrl($query, $type, $category, $sort, $page = 1) {
    $params = [
        'q' => $query,
        'type' => $type,
        'sort' => $sort,
        'page' => $page,
        'lang' => $GLOBALS['lang']
    ];
    
    if ($category) {
        $params['category'] = $category;
    }
    
    return 'search.php?' . http_build_query($params);
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
        return '';
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