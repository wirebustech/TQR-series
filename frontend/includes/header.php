<?php
include_once __DIR__ . '/translate.php';

$lang = $_GET['lang'] ?? 'en';
$texts = [
    'home' => 'Home',
    'about' => 'About',
    'webinars' => 'Webinars',
    'blog' => 'Blog',
    'research' => 'Research',
    'ai_app' => 'AI App',
    'contact' => 'Contact',
    'login' => 'Login',
    'register' => 'Register',
    'search' => 'Search...',
    'admin' => 'Admin',
    'profile' => 'Profile',
    'logout' => 'Logout'
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
    <title><?= htmlspecialchars($pageTitle ?? 'TQRS - The Qualitative Research Series') ?></title>
    <meta name="description" content="<?= htmlspecialchars($pageDescription ?? 'Explore qualitative research methodologies, webinars, and insights') ?>">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="assets/css/style.css" rel="stylesheet">
    
    <!-- PWA Manifest -->
    <link rel="manifest" href="manifest.json">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.ico">
    
    <style>
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        .lang-switcher {
            margin-left: 1rem;
        }
        .navbar-nav .nav-link {
            font-weight: 500;
            transition: color 0.3s ease;
        }
        .navbar-nav .nav-link:hover {
            color: #0d6efd !important;
        }
        .search-form {
            position: relative;
        }
        .search-form .form-control {
            padding-right: 40px;
        }
        .search-form .btn {
            position: absolute;
            right: 0;
            top: 0;
            border: none;
            background: none;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <!-- Brand -->
            <a class="navbar-brand" href="index.php?lang=<?= urlencode($lang) ?>">
                <i class="bi bi-book"></i> TQRS
            </a>

            <!-- Mobile Toggle -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navigation Items -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?lang=<?= urlencode($lang) ?>">
                            <i class="bi bi-house"></i> <?= htmlspecialchars($texts['home']) ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php?lang=<?= urlencode($lang) ?>">
                            <i class="bi bi-info-circle"></i> <?= htmlspecialchars($texts['about']) ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="webinars.php?lang=<?= urlencode($lang) ?>">
                            <i class="bi bi-camera-video"></i> <?= htmlspecialchars($texts['webinars']) ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="blog.php?lang=<?= urlencode($lang) ?>">
                            <i class="bi bi-journal-text"></i> <?= htmlspecialchars($texts['blog']) ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="research.php?lang=<?= urlencode($lang) ?>">
                            <i class="bi bi-search"></i> <?= htmlspecialchars($texts['research']) ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="research-ai.php?lang=<?= urlencode($lang) ?>">
                            <i class="bi bi-robot"></i> <?= htmlspecialchars($texts['ai_app']) ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php?lang=<?= urlencode($lang) ?>">
                            <i class="bi bi-envelope"></i> <?= htmlspecialchars($texts['contact']) ?>
                        </a>
                    </li>
                </ul>

                <!-- Search Form -->
                <form class="search-form me-3">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="<?= htmlspecialchars($texts['search']) ?>" id="searchInput">
                        <button class="btn" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>

                <!-- Language Switcher -->
                <div class="lang-switcher">
                    <form method="get" class="d-flex">
                        <select name="lang" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="en"<?= $lang=='en'?' selected':'' ?>>🇺🇸 English</option>
                            <option value="fr"<?= $lang=='fr'?' selected':'' ?>>🇫🇷 Français</option>
                            <option value="es"<?= $lang=='es'?' selected':'' ?>>🇪🇸 Español</option>
                        </select>
                    </form>
                </div>

                <!-- User Actions -->
                <ul class="navbar-nav ms-3">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> <?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="profile.php?lang=<?= urlencode($lang) ?>">
                                    <i class="bi bi-person"></i> <?= htmlspecialchars($texts['profile']) ?>
                                </a></li>
                                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                                    <li><a class="dropdown-item" href="../admin/index.php?lang=<?= urlencode($lang) ?>">
                                        <i class="bi bi-gear"></i> <?= htmlspecialchars($texts['admin']) ?>
                                    </a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="logout.php">
                                    <i class="bi bi-box-arrow-right"></i> <?= htmlspecialchars($texts['logout']) ?>
                                </a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php?lang=<?= urlencode($lang) ?>">
                                <i class="bi bi-box-arrow-in-right"></i> <?= htmlspecialchars($texts['login']) ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary btn-sm" href="register.php?lang=<?= urlencode($lang) ?>">
                                <?= htmlspecialchars($texts['register']) ?>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content Container -->
    <main class="container-fluid py-4"> 