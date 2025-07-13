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
$type = $_GET['type'] ?? '';
$sort = $_GET['sort'] ?? 'relevance';
$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 10;

// Mock search data
$searchData = [
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
    'articles' => [
        [
            'id' => 1,
            'type' => 'article',
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
            'type' => 'article',
            'title' => 'Conducting Effective Qualitative Interviews',
            'description' => 'Master the art of qualitative interviewing with practical tips and techniques.',
            'author' => 'Dr. Michael Chen',
            'category' => 'interviews',
            'readTime' => 12,
            'date' => '2024-02-08',
            'image' => 'assets/images/blog/interviews.jpg',
            'relevance' => 85
        ]
    ],
    'resources' => [
        [
            'id' => 1,
            'type' => 'resource',
            'title' => 'Qualitative Research Methods Handbook',
            'description' => 'A comprehensive guide to qualitative research methodologies and best practices.',
            'author' => 'TQRS Team',
            'category' => 'methodology',
            'fileType' => 'PDF',
            'fileSize' => '2.5 MB',
            'date' => '2024-01-15',
            'image' => 'assets/images/resources/handbook.jpg',
            'relevance' => 78
        ]
    ]
];

// Filter and search results
$allResults = [];
foreach ($searchData as $contentType => $items) {
    if (!$type || $type === $contentType || $type === 'all') {
        foreach ($items as $item) {
            $item['contentType'] = $contentType;
            $allResults[] = $item;
        }
    }
}

// Filter by search query
if ($query) {
    $allResults = array_filter($allResults, function($item) use ($query) {
        return stripos($item['title'], $query) !== false || 
               stripos($item['description'], $query) !== false ||
               stripos($item['author'], $query) !== false ||
               stripos($item['category'], $query) !== false;
    });
}

// Sort results
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

$totalResults = count($allResults);
$totalPages = ceil($totalResults / $perPage);
$offset = ($page - 1) * $perPage;
$paginatedResults = array_slice($allResults, $offset, $perPage);

// Search suggestions
$suggestions = [
    'grounded theory',
    'qualitative interviews',
    'NVivo software',
    'data analysis',
    'research methodology',
    'case studies',
    'phenomenology',
    'thematic analysis'
];
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
                    <div class="card-body">
                        <h5 class="card-title mb-3"><?= htmlspecialchars($texts['filterBy']) ?></h5>
                        
                        <!-- Content Type Filter -->
                        <div class="mb-4">
                            <label class="form-label fw-bold"><?= htmlspecialchars($texts['contentType']) ?></label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="contentType" id="allContent" value="all" 
                                       <?= !$type || $type === 'all' ? 'checked' : '' ?> onchange="filterResults()">
                                <label class="form-check-label" for="allContent">
                                    <?= htmlspecialchars($texts['allContent']) ?>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="contentType" id="webinars" value="webinars" 
                                       <?= $type === 'webinars' ? 'checked' : '' ?> onchange="filterResults()">
                                <label class="form-check-label" for="webinars">
                                    <?= htmlspecialchars($texts['webinars']) ?>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="contentType" id="articles" value="articles" 
                                       <?= $type === 'articles' ? 'checked' : '' ?> onchange="filterResults()">
                                <label class="form-check-label" for="articles">
                                    <?= htmlspecialchars($texts['articles']) ?>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="contentType" id="resources" value="resources" 
                                       <?= $type === 'resources' ? 'checked' : '' ?> onchange="filterResults()">
                                <label class="form-check-label" for="resources">
                                    <?= htmlspecialchars($texts['resources']) ?>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Sort Options -->
                        <div class="mb-4">
                            <label class="form-label fw-bold"><?= htmlspecialchars($texts['sortBy']) ?></label>
                            <select class="form-select" id="sortSelect" onchange="sortResults()">
                                <option value="relevance"<?= $sort === 'relevance' ? ' selected' : '' ?>><?= htmlspecialchars($texts['relevance']) ?></option>
                                <option value="date"<?= $sort === 'date' ? ' selected' : '' ?>><?= htmlspecialchars($texts['date']) ?></option>
                                <option value="popularity"<?= $sort === 'popularity' ? ' selected' : '' ?>><?= htmlspecialchars($texts['popularity']) ?></option>
                            </select>
                        </div>
                        
                        <!-- Search Suggestions -->
                        <?php if ($query && $totalResults === 0): ?>
                            <div class="border-top pt-3">
                                <h6><?= htmlspecialchars($texts['suggestions']) ?></h6>
                                <p class="small text-muted"><?= htmlspecialchars($texts['tryThese']) ?></p>
                                <div class="d-flex flex-wrap gap-1">
                                    <?php foreach (array_slice($suggestions, 0, 4) as $suggestion): ?>
                                        <a href="?q=<?= urlencode($suggestion) ?>&lang=<?= urlencode($lang) ?>" 
                                           class="badge bg-light text-dark text-decoration-none">
                                            <?= htmlspecialchars($suggestion) ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Search Results -->
            <div class="col-lg-9">
                <?php if (empty($paginatedResults)): ?>
                    <div class="text-center py-5">
                        <i class="bi bi-search-x fs-1 text-muted mb-3"></i>
                        <h4 class="text-muted"><?= htmlspecialchars($texts['noResults']) ?></h4>
                        <p class="text-muted"><?= htmlspecialchars($texts['noResultsText']) ?></p>
                        
                        <!-- Related Searches -->
                        <div class="mt-4">
                            <h6><?= htmlspecialchars($texts['relatedSearches']) ?></h6>
                            <div class="d-flex flex-wrap gap-2 justify-content-center">
                                <?php foreach ($suggestions as $suggestion): ?>
                                    <a href="?q=<?= urlencode($suggestion) ?>&lang=<?= urlencode($lang) ?>" 
                                       class="btn btn-outline-primary btn-sm">
                                        <?= htmlspecialchars($suggestion) ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Results List -->
                    <?php foreach ($paginatedResults as $result): ?>
                        <div class="card mb-3 border-0 shadow-sm">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <img src="<?= htmlspecialchars($result['image']) ?>" 
                                             class="img-fluid rounded" 
                                             alt="<?= htmlspecialchars($result['title']) ?>"
                                             style="height: 120px; object-fit: cover;">
                                    </div>
                                    <div class="col-md-9">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <span class="badge bg-<?= $result['type'] === 'webinar' ? 'primary' : ($result['type'] === 'article' ? 'success' : 'warning') ?>">
                                                <?= htmlspecialchars($texts[$result['type']] ?? $result['type']) ?>
                                            </span>
                                            <small class="text-muted">
                                                <?= date('M j, Y', strtotime($result['date'])) ?>
                                            </small>
                                        </div>
                                        
                                        <h5 class="card-title">
                                            <a href="<?= $result['type'] === 'webinar' ? 'webinar-details.php' : ($result['type'] === 'article' ? 'article.php' : 'resource.php') ?>?id=<?= $result['id'] ?>&lang=<?= urlencode($lang) ?>" 
                                               class="text-decoration-none">
                                                <?= htmlspecialchars($result['title']) ?>
                                            </a>
                                        </h5>
                                        
                                        <p class="card-text text-muted"><?= htmlspecialchars($result['description']) ?></p>
                                        
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <small class="text-muted me-3">
                                                    <i class="bi bi-person"></i> <?= htmlspecialchars($result['author']) ?>
                                                </small>
                                                <small class="text-muted me-3">
                                                    <i class="bi bi-tag"></i> <?= htmlspecialchars($texts[$result['category']] ?? $result['category']) ?>
                                                </small>
                                                <?php if (isset($result['duration'])): ?>
                                                    <small class="text-muted me-3">
                                                        <i class="bi bi-clock"></i> <?= htmlspecialchars($result['duration']) ?>
                                                    </small>
                                                <?php endif; ?>
                                                <?php if (isset($result['readTime'])): ?>
                                                    <small class="text-muted me-3">
                                                        <i class="bi bi-book"></i> <?= htmlspecialchars($result['readTime']) ?> <?= htmlspecialchars($texts['readTime']) ?>
                                                    </small>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <a href="<?= $result['type'] === 'webinar' ? 'webinar-details.php' : ($result['type'] === 'article' ? 'article.php' : 'resource.php') ?>?id=<?= $result['id'] ?>&lang=<?= urlencode($lang) ?>" 
                                               class="btn btn-outline-primary btn-sm">
                                                <?= htmlspecialchars($texts['viewDetails']) ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    
                    <!-- Pagination -->
                    <?php if ($totalPages > 1): ?>
                        <nav aria-label="Search results pagination" class="mt-4">
                            <ul class="pagination justify-content-center">
                                <?php if ($page > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?q=<?= urlencode($query) ?>&type=<?= urlencode($type) ?>&sort=<?= urlencode($sort) ?>&page=<?= $page - 1 ?>&lang=<?= urlencode($lang) ?>">
                                            <i class="bi bi-chevron-left"></i>
                                        </a>
                                    </li>
                                <?php endif; ?>
                                
                                <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                                    <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                                        <a class="page-link" href="?q=<?= urlencode($query) ?>&type=<?= urlencode($type) ?>&sort=<?= urlencode($sort) ?>&page=<?= $i ?>&lang=<?= urlencode($lang) ?>">
                                            <?= $i ?>
                                        </a>
                                    </li>
                                <?php endfor; ?>
                                
                                <?php if ($page < $totalPages): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?q=<?= urlencode($query) ?>&type=<?= urlencode($type) ?>&sort=<?= urlencode($sort) ?>&page=<?= $page + 1 ?>&lang=<?= urlencode($lang) ?>">
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

<script>
function filterResults() {
    const contentType = document.querySelector('input[name="contentType"]:checked').value;
    const currentUrl = new URL(window.location);
    
    if (contentType && contentType !== 'all') {
        currentUrl.searchParams.set('type', contentType);
    } else {
        currentUrl.searchParams.delete('type');
    }
    
    currentUrl.searchParams.delete('page'); // Reset to first page
    window.location.href = currentUrl.toString();
}

function sortResults() {
    const sort = document.getElementById('sortSelect').value;
    const currentUrl = new URL(window.location);
    
    if (sort) {
        currentUrl.searchParams.set('sort', sort);
    }
    
    currentUrl.searchParams.delete('page'); // Reset to first page
    window.location.href = currentUrl.toString();
}

// Highlight search terms in results
document.addEventListener('DOMContentLoaded', function() {
    const query = '<?= addslashes($query) ?>';
    if (query) {
        const results = document.querySelectorAll('.card-title, .card-text');
        results.forEach(element => {
            const text = element.innerHTML;
            const highlightedText = text.replace(
                new RegExp(query, 'gi'),
                match => `<mark class="bg-warning">${match}</mark>`
            );
            element.innerHTML = highlightedText;
        });
    }
});
</script>

<?php include_once __DIR__ . '/includes/footer.php'; ?> 