<?php
require_once 'includes/translation.php';
$lang = $_GET['lang'] ?? 'en';
$translations = getTranslations($lang);
?>

<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $translations['dashboard_title'] ?? 'Dashboard - TQRS' ?></title>
    <meta name="description" content="<?= $translations['dashboard_description'] ?? 'Your personal dashboard for managing research activities and preferences' ?>">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="assets/css/style.css" rel="stylesheet">
    
    <!-- PWA Manifest -->
    <link rel="manifest" href="manifest.json">
    <meta name="theme-color" content="#667eea">
</head>
<body class="bg-light">
    <?php include 'includes/header.php'; ?>

    <main class="container-fluid py-4">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <div class="position-relative d-inline-block">
                                <img src="assets/images/avatar-placeholder.jpg" alt="Profile" class="rounded-circle" width="80" height="80">
                                <div class="position-absolute bottom-0 end-0 bg-success rounded-circle border border-white" style="width: 20px; height: 20px;"></div>
                            </div>
                            <h5 class="mt-3 mb-1"><?= $translations['welcome_user'] ?? 'Welcome, User' ?></h5>
                            <p class="text-muted small"><?= $translations['member_since'] ?? 'Member since' ?> 2024</p>
                        </div>
                        
                        <!-- Quick Stats -->
                        <div class="row text-center mb-4">
                            <div class="col-4">
                                <div class="bg-primary bg-opacity-10 rounded p-2">
                                    <h6 class="text-primary mb-0">12</h6>
                                    <small class="text-muted"><?= $translations['webinars'] ?? 'Webinars' ?></small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="bg-success bg-opacity-10 rounded p-2">
                                    <h6 class="text-success mb-0">8</h6>
                                    <small class="text-muted"><?= $translations['articles'] ?? 'Articles' ?></small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="bg-warning bg-opacity-10 rounded p-2">
                                    <h6 class="text-warning mb-0">5</h6>
                                    <small class="text-muted"><?= $translations['projects'] ?? 'Projects' ?></small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Navigation -->
                        <nav class="nav flex-column">
                            <a class="nav-link active" href="#overview">
                                <i class="bi bi-house me-2"></i><?= $translations['overview'] ?? 'Overview' ?>
                            </a>
                            <a class="nav-link" href="#webinars">
                                <i class="bi bi-camera-video me-2"></i><?= $translations['my_webinars'] ?? 'My Webinars' ?>
                            </a>
                            <a class="nav-link" href="#articles">
                                <i class="bi bi-file-text me-2"></i><?= $translations['my_articles'] ?? 'My Articles' ?>
                            </a>
                            <a class="nav-link" href="#projects">
                                <i class="bi bi-folder me-2"></i><?= $translations['my_projects'] ?? 'My Projects' ?>
                            </a>
                            <a class="nav-link" href="#settings">
                                <i class="bi bi-gear me-2"></i><?= $translations['settings'] ?? 'Settings' ?>
                            </a>
                        </nav>
                    </div>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-lg-9">
                <!-- Welcome Section -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h4 class="mb-2"><?= $translations['good_morning'] ?? 'Good Morning' ?>! 👋</h4>
                                <p class="text-muted mb-0"><?= $translations['dashboard_subtitle'] ?? 'Here\'s what\'s happening with your research activities today.' ?></p>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <button class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-2"></i><?= $translations['new_project'] ?? 'New Project' ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 rounded p-3 me-3">
                                        <i class="bi bi-graph-up text-primary fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1"><?= $translations['total_views'] ?? 'Total Views' ?></h6>
                                        <h4 class="mb-0 text-primary">2,847</h4>
                                        <small class="text-success">
                                            <i class="bi bi-arrow-up"></i> 12% <?= $translations['this_month'] ?? 'this month' ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="bg-success bg-opacity-10 rounded p-3 me-3">
                                        <i class="bi bi-people text-success fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1"><?= $translations['followers'] ?? 'Followers' ?></h6>
                                        <h4 class="mb-0 text-success">1,234</h4>
                                        <small class="text-success">
                                            <i class="bi bi-arrow-up"></i> 8% <?= $translations['this_month'] ?? 'this month' ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="bg-warning bg-opacity-10 rounded p-3 me-3">
                                        <i class="bi bi-star text-warning fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1"><?= $translations['rating'] ?? 'Rating' ?></h6>
                                        <h4 class="mb-0 text-warning">4.8</h4>
                                        <small class="text-muted"><?= $translations['out_of_5'] ?? 'out of 5' ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="bg-info bg-opacity-10 rounded p-3 me-3">
                                        <i class="bi bi-calendar-check text-info fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1"><?= $translations['upcoming'] ?? 'Upcoming' ?></h6>
                                        <h4 class="mb-0 text-info">3</h4>
                                        <small class="text-muted"><?= $translations['webinars'] ?? 'webinars' ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Activity & Quick Actions -->
                <div class="row">
                    <!-- Recent Activity -->
                    <div class="col-lg-8 mb-4">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-transparent border-0">
                                <h5 class="mb-0">
                                    <i class="bi bi-activity me-2"></i><?= $translations['recent_activity'] ?? 'Recent Activity' ?>
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="timeline">
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-primary"></div>
                                        <div class="timeline-content">
                                            <h6 class="mb-1"><?= $translations['webinar_registered'] ?? 'Registered for Webinar' ?></h6>
                                            <p class="text-muted mb-1"><?= $translations['advanced_qualitative_methods'] ?? 'Advanced Qualitative Methods' ?></p>
                                            <small class="text-muted">2 <?= $translations['hours_ago'] ?? 'hours ago' ?></small>
                                        </div>
                                    </div>
                                    
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-success"></div>
                                        <div class="timeline-content">
                                            <h6 class="mb-1"><?= $translations['article_published'] ?? 'Article Published' ?></h6>
                                            <p class="text-muted mb-1"><?= $translations['research_findings'] ?? 'Research Findings on User Experience' ?></p>
                                            <small class="text-muted">1 <?= $translations['day_ago'] ?? 'day ago' ?></small>
                                        </div>
                                    </div>
                                    
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-warning"></div>
                                        <div class="timeline-content">
                                            <h6 class="mb-1"><?= $translations['project_updated'] ?? 'Project Updated' ?></h6>
                                            <p class="text-muted mb-1"><?= $translations['market_research'] ?? 'Market Research Analysis' ?></p>
                                            <small class="text-muted">3 <?= $translations['days_ago'] ?? 'days ago' ?></small>
                                        </div>
                                    </div>
                                    
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-info"></div>
                                        <div class="timeline-content">
                                            <h6 class="mb-1"><?= $translations['comment_received'] ?? 'Comment Received' ?></h6>
                                            <p class="text-muted mb-1"><?= $translations['on_research_methodology'] ?? 'On Research Methodology Article' ?></p>
                                            <small class="text-muted">1 <?= $translations['week_ago'] ?? 'week ago' ?></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quick Actions -->
                    <div class="col-lg-4 mb-4">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-transparent border-0">
                                <h5 class="mb-0">
                                    <i class="bi bi-lightning me-2"></i><?= $translations['quick_actions'] ?? 'Quick Actions' ?>
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <button class="btn btn-outline-primary">
                                        <i class="bi bi-camera-video me-2"></i><?= $translations['join_webinar'] ?? 'Join Webinar' ?>
                                    </button>
                                    <button class="btn btn-outline-success">
                                        <i class="bi bi-file-earmark-plus me-2"></i><?= $translations['create_article'] ?? 'Create Article' ?>
                                    </button>
                                    <button class="btn btn-outline-warning">
                                        <i class="bi bi-folder-plus me-2"></i><?= $translations['new_project'] ?? 'New Project' ?>
                                    </button>
                                    <button class="btn btn-outline-info">
                                        <i class="bi bi-search me-2"></i><?= $translations['search_resources'] ?? 'Search Resources' ?>
                                    </button>
                                    <button class="btn btn-outline-secondary">
                                        <i class="bi bi-gear me-2"></i><?= $translations['settings'] ?? 'Settings' ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Upcoming Events -->
                        <div class="card shadow-sm border-0 mt-4">
                            <div class="card-header bg-transparent border-0">
                                <h5 class="mb-0">
                                    <i class="bi bi-calendar-event me-2"></i><?= $translations['upcoming_events'] ?? 'Upcoming Events' ?>
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="event-item mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary rounded p-2 me-3">
                                            <i class="bi bi-camera-video text-white"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1"><?= $translations['qualitative_analysis'] ?? 'Qualitative Analysis Workshop' ?></h6>
                                            <small class="text-muted"><?= $translations['tomorrow'] ?? 'Tomorrow' ?> • 2:00 PM</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="event-item mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-success rounded p-2 me-3">
                                            <i class="bi bi-people text-white"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1"><?= $translations['research_meetup'] ?? 'Research Meetup' ?></h6>
                                            <small class="text-muted"><?= $translations['friday'] ?? 'Friday' ?> • 6:00 PM</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="event-item">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-warning rounded p-2 me-3">
                                            <i class="bi bi-presentation text-white"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1"><?= $translations['methodology_webinar'] ?? 'Methodology Webinar' ?></h6>
                                            <small class="text-muted"><?= $translations['next_week'] ?? 'Next Week' ?> • 3:00 PM</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

    <!-- Custom CSS for Dashboard -->
    <style>
        .timeline {
            position: relative;
            padding-left: 30px;
        }
        
        .timeline::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e9ecef;
        }
        
        .timeline-item {
            position: relative;
            margin-bottom: 30px;
        }
        
        .timeline-marker {
            position: absolute;
            left: -22px;
            top: 0;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 2px solid #fff;
            box-shadow: 0 0 0 2px #e9ecef;
        }
        
        .nav-link {
            color: #6c757d;
            border-radius: 8px;
            margin-bottom: 5px;
            transition: all 0.3s ease;
        }
        
        .nav-link:hover,
        .nav-link.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
        }
        
        .btn {
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            transform: translateY(-2px);
        }
        
        .event-item {
            transition: all 0.3s ease;
        }
        
        .event-item:hover {
            transform: translateX(5px);
        }
    </style>
</body>
</html> 