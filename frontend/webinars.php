<?php
session_start();

$pageTitle = 'Webinars - TQRS';
$pageDescription = 'Explore upcoming and past webinars on qualitative research methodologies. Register for live sessions and access recordings.';

include_once __DIR__ . '/includes/header.php';

$texts = [
    'webinarsTitle' => 'Webinars',
    'webinarsSubtitle' => 'Learn from experts in qualitative research',
    'upcomingWebinars' => 'Upcoming Webinars',
    'pastWebinars' => 'Past Webinars',
    'liveNow' => 'Live Now',
    'register' => 'Register',
    'watchRecording' => 'Watch Recording',
    'free' => 'Free',
    'premium' => 'Premium',
    'filterBy' => 'Filter by',
    'category' => 'Category',
    'date' => 'Date',
    'price' => 'Price',
    'searchWebinars' => 'Search webinars...',
    'allCategories' => 'All Categories',
    'methodology' => 'Methodology',
    'dataAnalysis' => 'Data Analysis',
    'software' => 'Software',
    'caseStudies' => 'Case Studies',
    'theory' => 'Theory',
    'allDates' => 'All Dates',
    'thisWeek' => 'This Week',
    'thisMonth' => 'This Month',
    'nextMonth' => 'Next Month',
    'allPrices' => 'All Prices',
    'freeOnly' => 'Free Only',
    'premiumOnly' => 'Premium Only',
    'noWebinars' => 'No webinars found',
    'noUpcoming' => 'No upcoming webinars at the moment',
    'noPast' => 'No past webinars available',
    'duration' => 'Duration',
    'presenter' => 'Presenter',
    'attendees' => 'Attendees',
    'description' => 'Description',
    'learnMore' => 'Learn More',
    'calendarView' => 'Calendar View',
    'listView' => 'List View'
];
if ($lang !== 'en') {
    foreach ($texts as $k => $v) {
        $texts[$k] = translateText($v, $lang, 'en');
    }
}

// Mock data for webinars
$upcomingWebinars = [
    [
        'id' => 1,
        'title' => 'Advanced Grounded Theory Methodology',
        'presenter' => 'Dr. Sarah Johnson',
        'date' => '2024-02-15',
        'time' => '14:00',
        'duration' => '90',
        'category' => 'methodology',
        'price' => 'free',
        'attendees' => 45,
        'description' => 'Explore advanced techniques in grounded theory methodology for qualitative research.',
        'image' => 'assets/images/webinar-1.jpg'
    ],
    [
        'id' => 2,
        'title' => 'NVivo Software Masterclass',
        'presenter' => 'Dr. Michael Chen',
        'date' => '2024-02-20',
        'time' => '10:00',
        'duration' => '120',
        'category' => 'software',
        'price' => 'premium',
        'attendees' => 32,
        'description' => 'Master NVivo software for qualitative data analysis with hands-on examples.',
        'image' => 'assets/images/webinar-2.jpg'
    ],
    [
        'id' => 3,
        'title' => 'Qualitative Data Analysis Techniques',
        'presenter' => 'Dr. Emily Rodriguez',
        'date' => '2024-02-25',
        'time' => '16:00',
        'duration' => '75',
        'category' => 'dataAnalysis',
        'price' => 'free',
        'attendees' => 28,
        'description' => 'Learn essential techniques for analyzing qualitative data effectively.',
        'image' => 'assets/images/webinar-3.jpg'
    ]
];

$pastWebinars = [
    [
        'id' => 4,
        'title' => 'Introduction to Phenomenology',
        'presenter' => 'Dr. David Kim',
        'date' => '2024-01-30',
        'duration' => '90',
        'category' => 'theory',
        'price' => 'free',
        'attendees' => 67,
        'description' => 'Introduction to phenomenological research methods and applications.',
        'image' => 'assets/images/webinar-4.jpg'
    ],
    [
        'id' => 5,
        'title' => 'Case Study Research Design',
        'presenter' => 'Dr. Lisa Wang',
        'date' => '2024-01-25',
        'duration' => '105',
        'category' => 'caseStudies',
        'price' => 'premium',
        'attendees' => 41,
        'description' => 'Design and conduct effective case study research projects.',
        'image' => 'assets/images/webinar-5.jpg'
    ]
];
?>

<!-- Hero Section -->
<div class="bg-primary text-white py-5">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3"><?= htmlspecialchars($texts['webinarsTitle']) ?></h1>
                <p class="lead"><?= htmlspecialchars($texts['webinarsSubtitle']) ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="py-4 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-md-3 mb-3">
                <label for="categoryFilter" class="form-label"><?= htmlspecialchars($texts['category']) ?></label>
                <select class="form-select" id="categoryFilter">
                    <option value=""><?= htmlspecialchars($texts['allCategories']) ?></option>
                    <option value="methodology"><?= htmlspecialchars($texts['methodology']) ?></option>
                    <option value="dataAnalysis"><?= htmlspecialchars($texts['dataAnalysis']) ?></option>
                    <option value="software"><?= htmlspecialchars($texts['software']) ?></option>
                    <option value="caseStudies"><?= htmlspecialchars($texts['caseStudies']) ?></option>
                    <option value="theory"><?= htmlspecialchars($texts['theory']) ?></option>
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label for="dateFilter" class="form-label"><?= htmlspecialchars($texts['date']) ?></label>
                <select class="form-select" id="dateFilter">
                    <option value=""><?= htmlspecialchars($texts['allDates']) ?></option>
                    <option value="thisWeek"><?= htmlspecialchars($texts['thisWeek']) ?></option>
                    <option value="thisMonth"><?= htmlspecialchars($texts['thisMonth']) ?></option>
                    <option value="nextMonth"><?= htmlspecialchars($texts['nextMonth']) ?></option>
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label for="priceFilter" class="form-label"><?= htmlspecialchars($texts['price']) ?></label>
                <select class="form-select" id="priceFilter">
                    <option value=""><?= htmlspecialchars($texts['allPrices']) ?></option>
                    <option value="free"><?= htmlspecialchars($texts['freeOnly']) ?></option>
                    <option value="premium"><?= htmlspecialchars($texts['premiumOnly']) ?></option>
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label for="searchWebinars" class="form-label"><?= htmlspecialchars($texts['searchWebinars']) ?></label>
                <input type="text" class="form-control" id="searchWebinars" placeholder="<?= htmlspecialchars($texts['searchWebinars']) ?>">
            </div>
        </div>
    </div>
</div>

<!-- Upcoming Webinars -->
<div class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold"><?= htmlspecialchars($texts['upcomingWebinars']) ?></h2>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-primary active" id="listViewBtn">
                    <i class="bi bi-list"></i> <?= htmlspecialchars($texts['listView']) ?>
                </button>
                <button type="button" class="btn btn-outline-primary" id="calendarViewBtn">
                    <i class="bi bi-calendar"></i> <?= htmlspecialchars($texts['calendarView']) ?>
                </button>
            </div>
        </div>
        
        <div id="upcomingWebinarsList">
            <?php if (empty($upcomingWebinars)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-calendar-x fs-1 text-muted mb-3"></i>
                    <h4 class="text-muted"><?= htmlspecialchars($texts['noUpcoming']) ?></h4>
                    <p class="text-muted">Check back soon for new webinars!</p>
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($upcomingWebinars as $webinar): ?>
                        <div class="col-lg-4 col-md-6 mb-4 webinar-card" 
                             data-category="<?= htmlspecialchars($webinar['category']) ?>"
                             data-price="<?= htmlspecialchars($webinar['price']) ?>"
                             data-date="<?= htmlspecialchars($webinar['date']) ?>">
                            <div class="card h-100 border-0 shadow-sm">
                                <img src="<?= htmlspecialchars($webinar['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($webinar['title']) ?>" style="height: 200px; object-fit: cover;">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <span class="badge bg-<?= $webinar['price'] === 'free' ? 'success' : 'warning' ?>">
                                            <?= htmlspecialchars($texts[$webinar['price']]) ?>
                                        </span>
                                        <span class="badge bg-primary">
                                            <?= htmlspecialchars($webinar['category']) ?>
                                        </span>
                                    </div>
                                    <h5 class="card-title"><?= htmlspecialchars($webinar['title']) ?></h5>
                                    <p class="card-text text-muted small">
                                        <i class="bi bi-person"></i> <?= htmlspecialchars($webinar['presenter']) ?>
                                    </p>
                                    <p class="card-text text-muted small">
                                        <i class="bi bi-calendar"></i> <?= date('M j, Y', strtotime($webinar['date'])) ?> at <?= htmlspecialchars($webinar['time']) ?>
                                    </p>
                                    <p class="card-text text-muted small">
                                        <i class="bi bi-clock"></i> <?= htmlspecialchars($webinar['duration']) ?> minutes
                                    </p>
                                    <p class="card-text"><?= htmlspecialchars($webinar['description']) ?></p>
                                </div>
                                <div class="card-footer bg-transparent border-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            <i class="bi bi-people"></i> <?= htmlspecialchars($webinar['attendees']) ?> <?= htmlspecialchars($texts['attendees']) ?>
                                        </small>
                                        <a href="webinar-register.php?id=<?= $webinar['id'] ?>&lang=<?= urlencode($lang) ?>" class="btn btn-primary btn-sm">
                                            <?= htmlspecialchars($texts['register']) ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Past Webinars -->
<div class="bg-light py-5">
    <div class="container">
        <h2 class="fw-bold mb-4"><?= htmlspecialchars($texts['pastWebinars']) ?></h2>
        
        <?php if (empty($pastWebinars)): ?>
            <div class="text-center py-5">
                <i class="bi bi-archive fs-1 text-muted mb-3"></i>
                <h4 class="text-muted"><?= htmlspecialchars($texts['noPast']) ?></h4>
                <p class="text-muted">No past webinars available at the moment.</p>
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($pastWebinars as $webinar): ?>
                    <div class="col-lg-4 col-md-6 mb-4 webinar-card" 
                         data-category="<?= htmlspecialchars($webinar['category']) ?>"
                         data-price="<?= htmlspecialchars($webinar['price']) ?>"
                         data-date="<?= htmlspecialchars($webinar['date']) ?>">
                        <div class="card h-100 border-0 shadow-sm">
                            <img src="<?= htmlspecialchars($webinar['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($webinar['title']) ?>" style="height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span class="badge bg-<?= $webinar['price'] === 'free' ? 'success' : 'warning' ?>">
                                        <?= htmlspecialchars($texts[$webinar['price']]) ?>
                                    </span>
                                    <span class="badge bg-secondary">
                                        <?= htmlspecialchars($texts['pastWebinars']) ?>
                                    </span>
                                </div>
                                <h5 class="card-title"><?= htmlspecialchars($webinar['title']) ?></h5>
                                <p class="card-text text-muted small">
                                    <i class="bi bi-person"></i> <?= htmlspecialchars($webinar['presenter']) ?>
                                </p>
                                <p class="card-text text-muted small">
                                    <i class="bi bi-calendar"></i> <?= date('M j, Y', strtotime($webinar['date'])) ?>
                                </p>
                                <p class="card-text text-muted small">
                                    <i class="bi bi-clock"></i> <?= htmlspecialchars($webinar['duration']) ?> minutes
                                </p>
                                <p class="card-text"><?= htmlspecialchars($webinar['description']) ?></p>
                            </div>
                            <div class="card-footer bg-transparent border-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="bi bi-people"></i> <?= htmlspecialchars($webinar['attendees']) ?> <?= htmlspecialchars($texts['attendees']) ?>
                                    </small>
                                    <a href="webinar-recording.php?id=<?= $webinar['id'] ?>&lang=<?= urlencode($lang) ?>" class="btn btn-outline-primary btn-sm">
                                        <?= htmlspecialchars($texts['watchRecording']) ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Call to Action -->
<div class="py-5">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h2 class="fw-bold mb-4">Stay Updated</h2>
                <p class="lead mb-4">Get notified about upcoming webinars and research opportunities.</p>
                <div class="d-flex gap-3 justify-content-center">
                    <a href="newsletter.php?lang=<?= urlencode($lang) ?>" class="btn btn-primary btn-lg">
                        <i class="bi bi-envelope"></i> Subscribe to Newsletter
                    </a>
                    <a href="calendar.php?lang=<?= urlencode($lang) ?>" class="btn btn-outline-primary btn-lg">
                        <i class="bi bi-calendar"></i> View Calendar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Filter functionality
document.addEventListener('DOMContentLoaded', function() {
    const categoryFilter = document.getElementById('categoryFilter');
    const dateFilter = document.getElementById('dateFilter');
    const priceFilter = document.getElementById('priceFilter');
    const searchInput = document.getElementById('searchWebinars');
    const webinarCards = document.querySelectorAll('.webinar-card');

    function filterWebinars() {
        const category = categoryFilter.value;
        const date = dateFilter.value;
        const price = priceFilter.value;
        const search = searchInput.value.toLowerCase();

        webinarCards.forEach(card => {
            let show = true;

            // Category filter
            if (category && card.dataset.category !== category) {
                show = false;
            }

            // Price filter
            if (price && card.dataset.price !== price) {
                show = false;
            }

            // Search filter
            if (search) {
                const title = card.querySelector('.card-title').textContent.toLowerCase();
                const description = card.querySelector('.card-text').textContent.toLowerCase();
                if (!title.includes(search) && !description.includes(search)) {
                    show = false;
                }
            }

            // Date filter (simplified)
            if (date) {
                const cardDate = new Date(card.dataset.date);
                const now = new Date();
                
                if (date === 'thisWeek') {
                    const weekFromNow = new Date(now.getTime() + 7 * 24 * 60 * 60 * 1000);
                    if (cardDate > weekFromNow || cardDate < now) {
                        show = false;
                    }
                } else if (date === 'thisMonth') {
                    const monthFromNow = new Date(now.getFullYear(), now.getMonth() + 1, now.getDate());
                    if (cardDate > monthFromNow || cardDate < now) {
                        show = false;
                    }
                } else if (date === 'nextMonth') {
                    const monthFromNow = new Date(now.getFullYear(), now.getMonth() + 1, now.getDate());
                    const twoMonthsFromNow = new Date(now.getFullYear(), now.getMonth() + 2, now.getDate());
                    if (cardDate < monthFromNow || cardDate > twoMonthsFromNow) {
                        show = false;
                    }
                }
            }

            card.style.display = show ? 'block' : 'none';
        });
    }

    categoryFilter.addEventListener('change', filterWebinars);
    dateFilter.addEventListener('change', filterWebinars);
    priceFilter.addEventListener('change', filterWebinars);
    searchInput.addEventListener('input', filterWebinars);
});
</script>

<?php include_once __DIR__ . '/includes/footer.php'; ?> 