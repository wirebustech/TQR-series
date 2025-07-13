<?php
require_once __DIR__ . '/translation.php';

$lang = getCurrentLanguage();
$texts = getTranslations($lang);
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
        
        .search-form {
            min-width: 250px;
        }
        
        .lang-switcher .form-select {
            min-width: 130px;
            border: none;
            background: transparent;
            color: inherit;
        }
        
        .lang-switcher .form-select:focus {
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }
        
        .navbar-dark .lang-switcher .form-select {
            color: rgba(255, 255, 255, 0.9);
        }
        
        .navbar-dark .lang-switcher .form-select option {
            color: #000;
        }
        
        @media (max-width: 768px) {
            .search-form {
                min-width: 200px;
                margin-bottom: 1rem;
            }
            
            .lang-switcher {
                margin-bottom: 1rem;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php?lang=<?= urlencode($lang) ?>">
                <i class="bi bi-journal-bookmark"></i> TQRS
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
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
                            <i class="bi bi-robot"></i> <?= htmlspecialchars($texts['research_ai']) ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pages.php?lang=<?= urlencode($lang) ?>">
                            <i class="bi bi-file-earmark-text"></i> <?= htmlspecialchars($texts['pages'] ?? 'Pages') ?>
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
                        <button class="btn btn-outline-light" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>

                <!-- Language Switcher -->
                <div class="lang-switcher">
                    <?php include __DIR__ . '/../components/language-switcher.php'; ?>
                </div>

                <!-- User Menu -->
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> <?= htmlspecialchars($texts['profile']) ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="admin/index.php?lang=<?= urlencode($lang) ?>">
                                <i class="bi bi-gear"></i> <?= htmlspecialchars($texts['admin']) ?>
                            </a></li>
                            <li><a class="dropdown-item" href="login.php?lang=<?= urlencode($lang) ?>">
                                <i class="bi bi-box-arrow-in-right"></i> <?= htmlspecialchars($texts['login']) ?>
                            </a></li>
                            <li><a class="dropdown-item" href="register.php?lang=<?= urlencode($lang) ?>">
                                <i class="bi bi-person-plus"></i> <?= htmlspecialchars($texts['register']) ?>
                            </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content Container -->
    <main class="container-fluid py-4"> 