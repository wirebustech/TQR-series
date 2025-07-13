<?php
session_start();

$pageTitle = 'Blog - TQRS';
$pageDescription = 'Explore qualitative research articles, methodologies, case studies, and insights from the TQRS community.';

include_once __DIR__ . '/includes/header.php';

$texts = [
    'blogTitle' => 'Research Blog',
    'blogSubtitle' => 'Insights, methodologies, and case studies in qualitative research',
    'featured' => 'Featured',
    'latest' => 'Latest Articles',
    'popular' => 'Popular',
    'categories' => 'Categories',
    'allCategories' => 'All Categories',
    'methodology' => 'Methodology',
    'caseStudies' => 'Case Studies',
    'theory' => 'Theory',
    'software' => 'Software',
    'interviews' => 'Interviews',
    'dataAnalysis' => 'Data Analysis',
    'searchArticles' => 'Search articles...',
    'readMore' => 'Read More',
    'readTime' => 'min read',
    'author' => 'Author',
    'published' => 'Published',
    'tags' => 'Tags',
    'noArticles' => 'No articles found',
    'loadMore' => 'Load More Articles',
    'subscribe' => 'Subscribe to Blog',
    'subscribeText' => 'Get the latest articles delivered to your inbox',
    'emailPlaceholder' => 'Enter your email address',
    'subscribeBtn' => 'Subscribe'
];
if ($lang !== 'en') {
    foreach ($texts as $k => $v) {
        $texts[$k] = translateText($v, $lang, 'en');
    }
}

// Mock blog data
$articles = [
    [
        'id' => 1,
        'title' => 'Understanding Grounded Theory: A Comprehensive Guide',
        'excerpt' => 'Explore the fundamentals of grounded theory methodology and learn how to apply it effectively in your qualitative research projects.',
        'content' => 'Grounded theory is a systematic methodology that has been widely used in qualitative research...',
        'author' => 'Dr. Sarah Johnson',
        'author_avatar' => 'assets/images/authors/sarah.jpg',
        'published_date' => '2024-02-10',
        'read_time' => 8,
        'category' => 'methodology',
        'tags' => ['Grounded Theory', 'Methodology', 'Qualitative Research'],
        'featured' => true,
        'image' => 'assets/images/blog/grounded-theory.jpg',
        'views' => 1247,
        'likes' => 89
    ],
    [
        'id' => 2,
        'title' => 'Conducting Effective Qualitative Interviews',
        'excerpt' => 'Master the art of qualitative interviewing with practical tips and techniques for gathering rich, meaningful data.',
        'content' => 'Qualitative interviews are one of the most powerful tools in the researcher\'s toolkit...',
        'author' => 'Dr. Michael Chen',
        'author_avatar' => 'assets/images/authors/michael.jpg',
        'published_date' => '2024-02-08',
        'read_time' => 12,
        'category' => 'interviews',
        'tags' => ['Interviews', 'Data Collection', 'Research Methods'],
        'featured' => false,
        'image' => 'assets/images/blog/interviews.jpg',
        'views' => 892,
        'likes' => 67
    ],
    [
        'id' => 3,
        'title' => 'Using NVivo for Qualitative Data Analysis',
        'excerpt' => 'A step-by-step guide to using NVivo software for organizing, coding, and analyzing qualitative data.',
        'content' => 'NVivo is a powerful software tool designed specifically for qualitative data analysis...',
        'author' => 'Emily Rodriguez',
        'author_avatar' => 'assets/images/authors/emily.jpg',
        'published_date' => '2024-02-05',
        'read_time' => 15,
        'category' => 'software',
        'tags' => ['NVivo', 'Software', 'Data Analysis'],
        'featured' => true,
        'image' => 'assets/images/blog/nvivo.jpg',
        'views' => 1567,
        'likes' => 123
    ],
    [
        'id' => 4,
        'title' => 'Case Study: Educational Research in Rural Communities',
        'excerpt' => 'An in-depth case study exploring qualitative research methods in educational settings within rural communities.',
        'content' => 'This case study examines the challenges and opportunities of conducting educational research...',
        'author' => 'Dr. David Kim',
        'author_avatar' => 'assets/images/authors/david.jpg',
        'published_date' => '2024-02-03',
        'read_time' => 10,
        'category' => 'caseStudies',
        'tags' => ['Case Study', 'Education', 'Rural Research'],
        'featured' => false,
        'image' => 'assets/images/blog/education-case.jpg',
        'views' => 734,
        'likes' => 45
    ],
    [
        'id' => 5,
        'title' => 'Phenomenology in Healthcare Research',
        'excerpt' => 'Understanding how phenomenological approaches can enhance healthcare research and patient care.',
        'content' => 'Phenomenology offers a unique perspective for understanding human experiences in healthcare...',
        'author' => 'Dr. Lisa Wang',
        'author_avatar' => 'assets/images/authors/lisa.jpg',
        'published_date' => '2024-02-01',
        'read_time' => 11,
        'category' => 'theory',
        'tags' => ['Phenomenology', 'Healthcare', 'Theory'],
        'featured' => false,
        'image' => 'assets/images/blog/healthcare.jpg',
        'views' => 623,
        'likes' => 38
    ],
    [
        'id' => 6,
        'title' => 'Thematic Analysis: A Practical Guide',
        'excerpt' => 'Learn how to conduct thematic analysis effectively with this comprehensive step-by-step guide.',
        'content' => 'Thematic analysis is one of the most widely used methods for analyzing qualitative data...',
        'author' => 'Dr. Sarah Johnson',
        'author_avatar' => 'assets/images/authors/sarah.jpg',
        'published_date' => '2024-01-28',
        'read_time' => 9,
        'category' => 'dataAnalysis',
        'tags' => ['Thematic Analysis', 'Data Analysis', 'Methods'],
        'featured' => false,
        'image' => 'assets/images/blog/thematic-analysis.jpg',
        'views' => 987,
        'likes' => 72
    ]
];

// Filter articles based on query parameters
$category = $_GET['category'] ?? '';
$search = $_GET['search'] ?? '';
$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 6;

$filteredArticles = $articles;

if ($category) {
    $filteredArticles = array_filter($filteredArticles, function($article) use ($category) {
        return $article['category'] === $category;
    });
}

if ($search) {
    $filteredArticles = array_filter($filteredArticles, function($article) use ($search) {
        return stripos($article['title'], $search) !== false || 
               stripos($article['excerpt'], $search) !== false ||
               stripos(implode(' ', $article['tags']), $search) !== false;
    });
}

$totalArticles = count($filteredArticles);
$totalPages = ceil($totalArticles / $perPage);
$offset = ($page - 1) * $perPage;
$paginatedArticles = array_slice($filteredArticles, $offset, $perPage);

// Get featured articles
$featuredArticles = array_filter($articles, function($article) {
    return $article['featured'];
});
?>

<!-- Hero Section -->
<div class="bg-primary text-white py-5">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3"><?= htmlspecialchars($texts['blogTitle']) ?></h1>
                <p class="lead"><?= htmlspecialchars($texts['blogSubtitle']) ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filters -->
<div class="py-4 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-md-6 mb-3">
                <form method="GET" action="blog.php">
                    <input type="hidden" name="lang" value="<?= htmlspecialchars($lang) ?>">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" 
                               placeholder="<?= htmlspecialchars($texts['searchArticles']) ?>"
                               value="<?= htmlspecialchars($search) ?>">
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
            </div>
            <div class="col-md-6 mb-3">
                <select class="form-select" onchange="filterByCategory(this.value)">
                    <option value=""><?= htmlspecialchars($texts['allCategories']) ?></option>
                    <option value="methodology"<?= $category === 'methodology' ? ' selected' : '' ?>><?= htmlspecialchars($texts['methodology']) ?></option>
                    <option value="caseStudies"<?= $category === 'caseStudies' ? ' selected' : '' ?>><?= htmlspecialchars($texts['caseStudies']) ?></option>
                    <option value="theory"<?= $category === 'theory' ? ' selected' : '' ?>><?= htmlspecialchars($texts['theory']) ?></option>
                    <option value="software"<?= $category === 'software' ? ' selected' : '' ?>><?= htmlspecialchars($texts['software']) ?></option>
                    <option value="interviews"<?= $category === 'interviews' ? ' selected' : '' ?>><?= htmlspecialchars($texts['interviews']) ?></option>
                    <option value="dataAnalysis"<?= $category === 'dataAnalysis' ? ' selected' : '' ?>><?= htmlspecialchars($texts['dataAnalysis']) ?></option>
                </select>
            </div>
        </div>
    </div>
</div>

<!-- Featured Articles -->
<?php if (!empty($featuredArticles) && !$search && !$category): ?>
<div class="py-5">
    <div class="container">
        <h2 class="fw-bold mb-4"><?= htmlspecialchars($texts['featured']) ?></h2>
        <div class="row">
            <?php foreach (array_slice($featuredArticles, 0, 2) as $article): ?>
                <div class="col-lg-6 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <img src="<?= htmlspecialchars($article['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($article['title']) ?>" style="height: 250px; object-fit: cover;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge bg-primary"><?= htmlspecialchars($texts[$article['category']] ?? $article['category']) ?></span>
                                <small class="text-muted">
                                    <i class="bi bi-clock"></i> <?= htmlspecialchars($article['read_time']) ?> <?= htmlspecialchars($texts['readTime']) ?>
                                </small>
                            </div>
                            <h4 class="card-title"><?= htmlspecialchars($article['title']) ?></h4>
                            <p class="card-text"><?= htmlspecialchars($article['excerpt']) ?></p>
                            <div class="d-flex align-items-center mb-3">
                                <img src="<?= htmlspecialchars($article['author_avatar']) ?>" alt="<?= htmlspecialchars($article['author']) ?>" class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover;">
                                <div>
                                    <small class="text-muted"><?= htmlspecialchars($texts['author']) ?></small><br>
                                    <small class="fw-bold"><?= htmlspecialchars($article['author']) ?></small>
                                </div>
                            </div>
                            <a href="article.php?id=<?= $article['id'] ?>&lang=<?= urlencode($lang) ?>" class="btn btn-outline-primary">
                                <?= htmlspecialchars($texts['readMore']) ?>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Articles List -->
<div class="py-5">
    <div class="container">
        <h2 class="fw-bold mb-4"><?= htmlspecialchars($texts['latest']) ?></h2>
        
        <?php if (empty($paginatedArticles)): ?>
            <div class="text-center py-5">
                <i class="bi bi-journal-x fs-1 text-muted mb-3"></i>
                <h4 class="text-muted"><?= htmlspecialchars($texts['noArticles']) ?></h4>
                <p class="text-muted">Try adjusting your search or filter criteria.</p>
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($paginatedArticles as $article): ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <img src="<?= htmlspecialchars($article['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($article['title']) ?>" style="height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span class="badge bg-secondary"><?= htmlspecialchars($texts[$article['category']] ?? $article['category']) ?></span>
                                    <small class="text-muted">
                                        <i class="bi bi-clock"></i> <?= htmlspecialchars($article['read_time']) ?> <?= htmlspecialchars($texts['readTime']) ?>
                                    </small>
                                </div>
                                <h5 class="card-title"><?= htmlspecialchars($article['title']) ?></h5>
                                <p class="card-text"><?= htmlspecialchars($article['excerpt']) ?></p>
                                <div class="d-flex align-items-center mb-3">
                                    <img src="<?= htmlspecialchars($article['author_avatar']) ?>" alt="<?= htmlspecialchars($article['author']) ?>" class="rounded-circle me-2" style="width: 24px; height: 24px; object-fit: cover;">
                                    <small class="text-muted"><?= htmlspecialchars($article['author']) ?></small>
                                </div>
                                <div class="mb-3">
                                    <?php foreach (array_slice($article['tags'], 0, 3) as $tag): ?>
                                        <span class="badge bg-light text-dark me-1"><?= htmlspecialchars($tag) ?></span>
                                    <?php endforeach; ?>
                                </div>
                                <a href="article.php?id=<?= $article['id'] ?>&lang=<?= urlencode($lang) ?>" class="btn btn-outline-primary btn-sm">
                                    <?= htmlspecialchars($texts['readMore']) ?>
                                </a>
                            </div>
                            <div class="card-footer bg-transparent border-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="bi bi-calendar"></i> <?= date('M j, Y', strtotime($article['published_date'])) ?>
                                    </small>
                                    <small class="text-muted">
                                        <i class="bi bi-eye"></i> <?= htmlspecialchars($article['views']) ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <nav aria-label="Blog pagination" class="mt-5">
                    <ul class="pagination justify-content-center">
                        <?php if ($page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $page - 1 ?>&category=<?= urlencode($category) ?>&search=<?= urlencode($search) ?>&lang=<?= urlencode($lang) ?>">
                                    <i class="bi bi-chevron-left"></i>
                                </a>
                            </li>
                        <?php endif; ?>
                        
                        <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                            <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>&category=<?= urlencode($category) ?>&search=<?= urlencode($search) ?>&lang=<?= urlencode($lang) ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                        
                        <?php if ($page < $totalPages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $page + 1 ?>&category=<?= urlencode($category) ?>&search=<?= urlencode($search) ?>&lang=<?= urlencode($lang) ?>">
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

<!-- Newsletter Subscription -->
<div class="bg-light py-5">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-6">
                <h3 class="fw-bold mb-3"><?= htmlspecialchars($texts['subscribe']) ?></h3>
                <p class="text-muted mb-4"><?= htmlspecialchars($texts['subscribeText']) ?></p>
                <form class="d-flex gap-2">
                    <input type="email" class="form-control" placeholder="<?= htmlspecialchars($texts['emailPlaceholder']) ?>" required>
                    <button type="submit" class="btn btn-primary">
                        <?= htmlspecialchars($texts['subscribeBtn']) ?>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function filterByCategory(category) {
    const currentUrl = new URL(window.location);
    if (category) {
        currentUrl.searchParams.set('category', category);
    } else {
        currentUrl.searchParams.delete('category');
    }
    currentUrl.searchParams.set('lang', '<?= $lang ?>');
    currentUrl.searchParams.delete('page'); // Reset to first page
    window.location.href = currentUrl.toString();
}

// Search form enhancement
document.querySelector('form[action="blog.php"]').addEventListener('submit', function(e) {
    const searchInput = this.querySelector('input[name="search"]');
    if (searchInput.value.trim() === '') {
        e.preventDefault();
        return false;
    }
});

// Newsletter subscription
document.querySelector('form[action*="newsletter"]').addEventListener('submit', function(e) {
    e.preventDefault();
    const email = this.querySelector('input[type="email"]').value;
    if (email) {
        // In a real app, this would make an API call
        showToast('Success', 'You have been subscribed to our blog newsletter!');
        this.reset();
    }
});
</script>

<?php include_once __DIR__ . '/includes/footer.php'; ?> 