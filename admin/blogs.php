<?php
include_once __DIR__ . '/../frontend/includes/translate.php';

$lang = $_GET['lang'] ?? 'en';
$texts = [
    'title' => 'Blog Management - TQRS Admin',
    'dashboard' => 'Dashboard',
    'pages' => 'Pages',
    'blogs' => 'Blogs',
    'webinars' => 'Webinars',
    'users' => 'Users',
    'contributions' => 'Contributions',
    'analytics' => 'Analytics',
    'blogManagement' => 'Blog Management',
    'addBlog' => 'Add Blog',
    'searchBlogs' => 'Search blogs...',
    'title' => 'Title',
    'author' => 'Author',
    'category' => 'Category',
    'status' => 'Status',
    'date' => 'Date',
    'actions' => 'Actions',
    'edit' => 'Edit',
    'delete' => 'Delete',
    'publish' => 'Publish',
    'draft' => 'Draft',
    'published' => 'Published',
    'archived' => 'Archived'
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
                <li class="active">
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
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h1><?= htmlspecialchars($texts['blogManagement']) ?></h1>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#blogModal">
                                <i class="bi bi-plus"></i> <?= htmlspecialchars($texts['addBlog']) ?>
                            </button>
                        </div>
                        
                        <!-- Search and Filters -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" id="searchBlogs" placeholder="<?= htmlspecialchars($texts['searchBlogs']) ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <select class="form-select" id="statusFilter">
                                            <option value=""><?= htmlspecialchars($texts['status']) ?></option>
                                            <option value="draft"><?= htmlspecialchars($texts['draft']) ?></option>
                                            <option value="published"><?= htmlspecialchars($texts['published']) ?></option>
                                            <option value="archived"><?= htmlspecialchars($texts['archived']) ?></option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <button class="btn btn-outline-secondary w-100" onclick="refreshBlogs()">
                                            <i class="bi bi-arrow-clockwise"></i> <?= htmlspecialchars($texts['refresh']) ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Blogs Table -->
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped" id="blogsTable">
                                        <thead>
                                            <tr>
                                                <th><?= htmlspecialchars($texts['title']) ?></th>
                                                <th><?= htmlspecialchars($texts['author']) ?></th>
                                                <th><?= htmlspecialchars($texts['category']) ?></th>
                                                <th><?= htmlspecialchars($texts['status']) ?></th>
                                                <th><?= htmlspecialchars($texts['date']) ?></th>
                                                <th><?= htmlspecialchars($texts['actions']) ?></th>
                                            </tr>
                                        </thead>
                                        <tbody id="blogsTableBody">
                                            <!-- Blogs will be loaded here -->
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

    <!-- Blog Modal -->
    <div class="modal fade" id="blogModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="blogModalTitle"><?= htmlspecialchars($texts['addBlog']) ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="blogForm">
                        <div class="mb-3">
                            <label for="blogTitle" class="form-label"><?= htmlspecialchars($texts['title']) ?></label>
                            <input type="text" class="form-control" id="blogTitle" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="blogAuthor" class="form-label"><?= htmlspecialchars($texts['author']) ?></label>
                                    <input type="text" class="form-control" id="blogAuthor" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="blogCategory" class="form-label"><?= htmlspecialchars($texts['category']) ?></label>
                                    <select class="form-select" id="blogCategory" required>
                                        <option value="research"><?= htmlspecialchars($texts['research']) ?></option>
                                        <option value="methodology"><?= htmlspecialchars($texts['methodology']) ?></option>
                                        <option value="case-studies"><?= htmlspecialchars($texts['caseStudies']) ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="blogContent" class="form-label"><?= htmlspecialchars($texts['content']) ?></label>
                            <textarea class="form-control" id="blogContent" rows="10"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= htmlspecialchars($texts['cancel']) ?></button>
                    <button type="button" class="btn btn-primary" onclick="saveBlog()"><?= htmlspecialchars($texts['save']) ?></button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/blogs.js"></script>
</body>
</html> 