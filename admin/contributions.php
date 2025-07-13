<?php
include_once __DIR__ . '/../frontend/includes/translate.php';

$lang = $_GET['lang'] ?? 'en';
$texts = [
    'title' => 'Contribution Management - TQRS Admin',
    'dashboard' => 'Dashboard',
    'pages' => 'Pages',
    'blogs' => 'Blogs',
    'webinars' => 'Webinars',
    'users' => 'Users',
    'contributions' => 'Contributions',
    'analytics' => 'Analytics',
    'contributionManagement' => 'Contribution Management',
    'searchContributions' => 'Search contributions...',
    'title' => 'Title',
    'author' => 'Author',
    'type' => 'Type',
    'status' => 'Status',
    'date' => 'Date',
    'actions' => 'Actions',
    'edit' => 'Edit',
    'delete' => 'Delete',
    'approve' => 'Approve',
    'reject' => 'Reject',
    'pending' => 'Pending',
    'approved' => 'Approved',
    'rejected' => 'Rejected',
    'blog' => 'Blog',
    'article' => 'Article',
    'research' => 'Research'
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
                <li class="active">
                    <a href="contributions.php?lang=<?= urlencode($lang) ?>">
                        <i class="bi bi-file-earmark-text"></i> <?= htmlspecialchars($texts['contributions']) ?>
                    </a>
                </li>
                <li>
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
                        <h1 class="mb-4"><?= htmlspecialchars($texts['contributionManagement']) ?></h1>
                        
                        <!-- Search and Filters -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" id="searchContributions" placeholder="<?= htmlspecialchars($texts['searchContributions']) ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <select class="form-select" id="typeFilter">
                                            <option value=""><?= htmlspecialchars($texts['type']) ?></option>
                                            <option value="blog"><?= htmlspecialchars($texts['blog']) ?></option>
                                            <option value="article"><?= htmlspecialchars($texts['article']) ?></option>
                                            <option value="research"><?= htmlspecialchars($texts['research']) ?></option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select class="form-select" id="statusFilter">
                                            <option value=""><?= htmlspecialchars($texts['status']) ?></option>
                                            <option value="pending"><?= htmlspecialchars($texts['pending']) ?></option>
                                            <option value="approved"><?= htmlspecialchars($texts['approved']) ?></option>
                                            <option value="rejected"><?= htmlspecialchars($texts['rejected']) ?></option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-outline-secondary w-100" onclick="refreshContributions()">
                                            <i class="bi bi-arrow-clockwise"></i> <?= htmlspecialchars($texts['refresh']) ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contributions Table -->
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped" id="contributionsTable">
                                        <thead>
                                            <tr>
                                                <th><?= htmlspecialchars($texts['title']) ?></th>
                                                <th><?= htmlspecialchars($texts['author']) ?></th>
                                                <th><?= htmlspecialchars($texts['type']) ?></th>
                                                <th><?= htmlspecialchars($texts['status']) ?></th>
                                                <th><?= htmlspecialchars($texts['date']) ?></th>
                                                <th><?= htmlspecialchars($texts['actions']) ?></th>
                                            </tr>
                                        </thead>
                                        <tbody id="contributionsTableBody">
                                            <!-- Contributions will be loaded here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/contributions.js"></script>
</body>
</html> 