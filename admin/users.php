<?php
include_once __DIR__ . '/../frontend/includes/translate.php';

$lang = $_GET['lang'] ?? 'en';
$texts = [
    'title' => 'User Management - TQRS Admin',
    'dashboard' => 'Dashboard',
    'pages' => 'Pages',
    'blogs' => 'Blogs',
    'webinars' => 'Webinars',
    'users' => 'Users',
    'contributions' => 'Contributions',
    'analytics' => 'Analytics',
    'userManagement' => 'User Management',
    'addUser' => 'Add User',
    'searchUsers' => 'Search users...',
    'name' => 'Name',
    'email' => 'Email',
    'role' => 'Role',
    'status' => 'Status',
    'actions' => 'Actions',
    'edit' => 'Edit',
    'delete' => 'Delete',
    'active' => 'Active',
    'inactive' => 'Inactive',
    'admin' => 'Admin',
    'user' => 'User',
    'moderator' => 'Moderator'
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
                <li class="active">
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
                            <h1><?= htmlspecialchars($texts['userManagement']) ?></h1>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#userModal">
                                <i class="bi bi-plus"></i> <?= htmlspecialchars($texts['addUser']) ?>
                            </button>
                        </div>
                        
                        <!-- Search and Filters -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" id="searchUsers" placeholder="<?= htmlspecialchars($texts['searchUsers']) ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <select class="form-select" id="roleFilter">
                                            <option value=""><?= htmlspecialchars($texts['role']) ?></option>
                                            <option value="admin"><?= htmlspecialchars($texts['admin']) ?></option>
                                            <option value="user"><?= htmlspecialchars($texts['user']) ?></option>
                                            <option value="moderator"><?= htmlspecialchars($texts['moderator']) ?></option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select class="form-select" id="statusFilter">
                                            <option value=""><?= htmlspecialchars($texts['status']) ?></option>
                                            <option value="active"><?= htmlspecialchars($texts['active']) ?></option>
                                            <option value="inactive"><?= htmlspecialchars($texts['inactive']) ?></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Users Table -->
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped" id="usersTable">
                                        <thead>
                                            <tr>
                                                <th><?= htmlspecialchars($texts['name']) ?></th>
                                                <th><?= htmlspecialchars($texts['email']) ?></th>
                                                <th><?= htmlspecialchars($texts['role']) ?></th>
                                                <th><?= htmlspecialchars($texts['status']) ?></th>
                                                <th><?= htmlspecialchars($texts['actions']) ?></th>
                                            </tr>
                                        </thead>
                                        <tbody id="usersTableBody">
                                            <!-- Users will be loaded here -->
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

    <!-- User Modal -->
    <div class="modal fade" id="userModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalTitle"><?= htmlspecialchars($texts['addUser']) ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="userForm">
                        <div class="mb-3">
                            <label for="userName" class="form-label"><?= htmlspecialchars($texts['name']) ?></label>
                            <input type="text" class="form-control" id="userName" required>
                        </div>
                        <div class="mb-3">
                            <label for="userEmail" class="form-label"><?= htmlspecialchars($texts['email']) ?></label>
                            <input type="email" class="form-control" id="userEmail" required>
                        </div>
                        <div class="mb-3">
                            <label for="userRole" class="form-label"><?= htmlspecialchars($texts['role']) ?></label>
                            <select class="form-select" id="userRole" required>
                                <option value="user"><?= htmlspecialchars($texts['user']) ?></option>
                                <option value="moderator"><?= htmlspecialchars($texts['moderator']) ?></option>
                                <option value="admin"><?= htmlspecialchars($texts['admin']) ?></option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= htmlspecialchars($texts['cancel']) ?></button>
                    <button type="button" class="btn btn-primary" onclick="saveUser()"><?= htmlspecialchars($texts['save']) ?></button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/users.js"></script>
</body>
</html> 