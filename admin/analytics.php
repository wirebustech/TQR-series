<?php
include_once __DIR__ . '/../frontend/includes/translate.php';

$lang = $_GET['lang'] ?? 'en';
$texts = [
    'title' => 'Analytics Dashboard - TQRS Admin',
    'dashboard' => 'Dashboard',
    'pages' => 'Pages',
    'blogs' => 'Blogs',
    'webinars' => 'Webinars',
    'users' => 'Users',
    'contributions' => 'Contributions',
    'analytics' => 'Analytics',
    'analyticsTitle' => 'Analytics Dashboard',
    'dateRange' => 'Date Range',
    'last7Days' => 'Last 7 Days',
    'last30Days' => 'Last 30 Days',
    'last3Months' => 'Last 3 Months',
    'lastYear' => 'Last Year',
    'customRange' => 'Custom Range',
    'fromDate' => 'From Date',
    'toDate' => 'To Date',
    'applyFilter' => 'Apply Filter',
    'totalUsers' => 'Total Users',
    'totalWebinars' => 'Total Webinars',
    'totalContributions' => 'Total Contributions',
    'totalBlogs' => 'Total Blogs',
    'fromLastPeriod' => 'from last period',
    'userGrowth' => 'User Growth',
    'webinarGrowth' => 'Webinar Growth',
    'contributionGrowth' => 'Contribution Growth',
    'blogGrowth' => 'Blog Growth',
    'detailedStats' => 'Detailed Statistics',
    'newUsersMonth' => 'New Users This Month',
    'activeUsers' => 'Active Users',
    'verifiedUsers' => 'Verified Users',
    'adminUsers' => 'Admin Users',
    'publishedBlogs' => 'Published Blogs',
    'upcomingWebinars' => 'Upcoming Webinars',
    'approvedContributions' => 'Approved Contributions',
    'userGrowthChart' => 'User Growth',
    'userDistributionChart' => 'User Distribution',
    'webinarPerformanceChart' => 'Webinar Performance',
    'contributionStatusChart' => 'Contribution Status',
    'recentActivity' => 'Recent Activity',
    'topPerformingContent' => 'Top Performing Content',
    'exportReport' => 'Export Report',
    'refreshData' => 'Refresh Data'
];
if ($lang !== 'en') {
    foreach ($texts as $k => $v) {
        $texts[$k] = translateText($v, $lang, 'en');
    }
}
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($texts['title']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/css/admin.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .lang-switcher {
            position: absolute;
            top: 1rem;
            right: 1rem;
            z-index: 1000;
        }
    </style>
</head>
<body>
    <div class="lang-switcher">
        <form method="get">
            <select name="lang" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="en"<?= $lang=='en'?' selected':'' ?>>English</option>
                <option value="fr"<?= $lang=='fr'?' selected':'' ?>>Français</option>
                <option value="es"<?= $lang=='es'?' selected':'' ?>>Español</option>
            </select>
        </form>
    </div>
    
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar" class="sidebar">
            <div class="sidebar-header">
                <h3>TQRS Admin</h3>
            </div>
            <ul class="list-unstyled components">
                <li>
                    <a href="index.php?lang=<?= urlencode($lang) ?>">
                        <i class="bi bi-speedometer2"></i> <?= htmlspecialchars($texts['dashboard']) ?>
                    </a>
                </li>
                <li>
                    <a href="pages.php?lang=<?= urlencode($lang) ?>">
                        <i class="bi bi-file-text"></i> <?= htmlspecialchars($texts['pages']) ?>
                    </a>
                </li>
                <li>
                    <a href="blogs.php?lang=<?= urlencode($lang) ?>">
                        <i class="bi bi-journal-text"></i> <?= htmlspecialchars($texts['blogs']) ?>
                    </a>
                </li>
                <li>
                    <a href="webinars.php?lang=<?= urlencode($lang) ?>">
                        <i class="bi bi-camera-video"></i> <?= htmlspecialchars($texts['webinars']) ?>
                    </a>
                </li>
                <li>
                    <a href="users.php?lang=<?= urlencode($lang) ?>">
                        <i class="bi bi-people"></i> <?= htmlspecialchars($texts['users']) ?>
                    </a>
                </li>
                <li>
                    <a href="contributions.php?lang=<?= urlencode($lang) ?>">
                        <i class="bi bi-file-earmark-text"></i> <?= htmlspecialchars($texts['contributions']) ?>
                    </a>
                </li>
                <li class="active">
                    <a href="analytics.php?lang=<?= urlencode($lang) ?>">
                        <i class="bi bi-graph-up"></i> <?= htmlspecialchars($texts['analytics']) ?>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Page Content -->
        <div id="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <h1 class="mb-4"><?= htmlspecialchars($texts['analyticsTitle']) ?></h1>
                        
                        <!-- Date Range Filter -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="dateRange" class="form-label"><?= htmlspecialchars($texts['dateRange']) ?></label>
                                        <select class="form-select" id="dateRange">
                                            <option value="7"><?= htmlspecialchars($texts['last7Days']) ?></option>
                                            <option value="30" selected><?= htmlspecialchars($texts['last30Days']) ?></option>
                                            <option value="90"><?= htmlspecialchars($texts['last3Months']) ?></option>
                                            <option value="365"><?= htmlspecialchars($texts['lastYear']) ?></option>
                                            <option value="custom"><?= htmlspecialchars($texts['customRange']) ?></option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="customDateFrom" class="form-label"><?= htmlspecialchars($texts['fromDate']) ?></label>
                                        <input type="date" class="form-control" id="customDateFrom" disabled>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="customDateTo" class="form-label"><?= htmlspecialchars($texts['toDate']) ?></label>
                                        <input type="date" class="form-control" id="customDateTo" disabled>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">&nbsp;</label>
                                        <button class="btn btn-primary d-block" id="applyDateRangeBtn">
                                            <i class="bi bi-funnel"></i> <?= htmlspecialchars($texts['applyFilter']) ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Key Metrics Cards -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h4 class="card-title" id="totalUsers">0</h4>
                                                <p class="card-text"><?= htmlspecialchars($texts['totalUsers']) ?></p>
                                                <small id="userGrowth">+0% <?= htmlspecialchars($texts['fromLastPeriod']) ?></small>
                                            </div>
                                            <div class="align-self-center">
                                                <i class="bi bi-people fs-1"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h4 class="card-title" id="totalWebinars">0</h4>
                                                <p class="card-text"><?= htmlspecialchars($texts['totalWebinars']) ?></p>
                                                <small id="webinarGrowth">+0% <?= htmlspecialchars($texts['fromLastPeriod']) ?></small>
                                            </div>
                                            <div class="align-self-center">
                                                <i class="bi bi-camera-video fs-1"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h4 class="card-title" id="totalBlogs">0</h4>
                                                <p class="card-text"><?= htmlspecialchars($texts['totalBlogs']) ?></p>
                                                <small id="blogGrowth">+0% <?= htmlspecialchars($texts['fromLastPeriod']) ?></small>
                                            </div>
                                            <div class="align-self-center">
                                                <i class="bi bi-journal-text fs-1"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-warning text-white">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h4 class="card-title" id="totalContributions">0</h4>
                                                <p class="card-text"><?= htmlspecialchars($texts['totalContributions']) ?></p>
                                                <small id="contributionGrowth">+0% <?= htmlspecialchars($texts['fromLastPeriod']) ?></small>
                                            </div>
                                            <div class="align-self-center">
                                                <i class="bi bi-file-earmark-text fs-1"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Charts -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0"><?= htmlspecialchars($texts['userGrowthChart']) ?></h5>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="userGrowthChart" height="100"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0"><?= htmlspecialchars($texts['userDistributionChart']) ?></h5>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="userDistributionChart" height="100"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0"><?= htmlspecialchars($texts['webinarPerformanceChart']) ?></h5>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="webinarPerformanceChart" height="100"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0"><?= htmlspecialchars($texts['contributionStatusChart']) ?></h5>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="contributionStatusChart" height="100"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Activity and Engagement -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0"><?= htmlspecialchars($texts['recentActivity']) ?></h5>
                                    </div>
                                    <div class="card-body">
                                        <div id="recentActivity" class="list-group list-group-flush">
                                            <!-- Activity items will be loaded here -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0"><?= htmlspecialchars($texts['topPerformingContent']) ?></h5>
                                    </div>
                                    <div class="card-body">
                                        <div id="topContent" class="list-group list-group-flush">
                                            <!-- Top content items will be loaded here -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/analytics.js"></script>
</body>
</html> 