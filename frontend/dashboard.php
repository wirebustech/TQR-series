<?php
require_once 'includes/translation.php';
$lang = $_GET['lang'] ?? 'en';
$translations = getTranslations($lang);

// Check if user is logged in
session_start();
$user = null;
$userToken = null;

if (isset($_SESSION['user_id'])) {
    $user = [
        'id' => $_SESSION['user_id'],
        'name' => $_SESSION['user_name'] ?? 'User',
        'email' => $_SESSION['user_email'] ?? '',
        'role' => $_SESSION['user_role'] ?? 'user'
    ];
    
    // Get user token from session or generate new one
    $userToken = $_SESSION['user_token'] ?? null;
}
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
    
    <style>
        .loading-skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }
        
        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
        
        .dashboard-container {
            min-height: 100vh;
        }
        
        .stats-card {
            transition: transform 0.2s;
        }
        
        .stats-card:hover {
            transform: translateY(-2px);
        }
        
        .activity-item {
            border-left: 3px solid transparent;
            transition: all 0.2s;
        }
        
        .activity-item:hover {
            border-left-color: #667eea;
            background-color: #f8f9fa;
        }
        
        .recommendation-card {
            border: 1px solid #e9ecef;
            transition: all 0.2s;
        }
        
        .recommendation-card:hover {
            border-color: #667eea;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.1);
        }
    </style>
</head>
<body class="bg-light">
    <?php include 'includes/header.php'; ?>

    <main class="dashboard-container">
        <div class="container-fluid py-4">
            <div class="row">
                <!-- Sidebar -->
                <div class="col-lg-3 mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <div class="text-center mb-4">
                                <div class="position-relative d-inline-block">
                                    <img src="assets/images/avatar-placeholder.jpg" alt="Profile" class="rounded-circle" width="80" height="80" id="userAvatar">
                                    <div class="position-absolute bottom-0 end-0 bg-success rounded-circle border border-white" style="width: 20px; height: 20px;"></div>
                                </div>
                                <h5 class="mt-3 mb-1" id="userName"><?= $translations['welcome_user'] ?? 'Welcome, User' ?></h5>
                                <p class="text-muted small" id="memberSince"><?= $translations['member_since'] ?? 'Member since' ?> 2024</p>
                            </div>
                            
                            <!-- Quick Stats -->
                            <div class="row text-center mb-4" id="quickStats">
                                <div class="col-4">
                                    <div class="bg-primary bg-opacity-10 rounded p-2">
                                        <div class="loading-skeleton" style="height: 20px; width: 100%;"></div>
                                        <small class="text-muted"><?= $translations['webinars'] ?? 'Webinars' ?></small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="bg-success bg-opacity-10 rounded p-2">
                                        <div class="loading-skeleton" style="height: 20px; width: 100%;"></div>
                                        <small class="text-muted"><?= $translations['articles'] ?? 'Articles' ?></small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="bg-warning bg-opacity-10 rounded p-2">
                                        <div class="loading-skeleton" style="height: 20px; width: 100%;"></div>
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
                                    <button class="btn btn-primary" onclick="createNewProject()">
                                        <i class="bi bi-plus-circle me-2"></i><?= $translations['new_project'] ?? 'New Project' ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Statistics Cards -->
                    <div class="row mb-4" id="statisticsCards">
                        <div class="col-md-3 mb-3">
                            <div class="card shadow-sm border-0 h-100 stats-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 rounded p-3 me-3">
                                            <i class="bi bi-graph-up text-primary fs-4"></i>
                                        </div>
                                        <div>
                                            <div class="loading-skeleton" style="height: 16px; width: 80px; margin-bottom: 8px;"></div>
                                            <div class="loading-skeleton" style="height: 32px; width: 60px; margin-bottom: 4px;"></div>
                                            <div class="loading-skeleton" style="height: 12px; width: 100px;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <div class="card shadow-sm border-0 h-100 stats-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-success bg-opacity-10 rounded p-3 me-3">
                                            <i class="bi bi-people text-success fs-4"></i>
                                        </div>
                                        <div>
                                            <div class="loading-skeleton" style="height: 16px; width: 80px; margin-bottom: 8px;"></div>
                                            <div class="loading-skeleton" style="height: 32px; width: 60px; margin-bottom: 4px;"></div>
                                            <div class="loading-skeleton" style="height: 12px; width: 100px;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <div class="card shadow-sm border-0 h-100 stats-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-warning bg-opacity-10 rounded p-3 me-3">
                                            <i class="bi bi-star text-warning fs-4"></i>
                                        </div>
                                        <div>
                                            <div class="loading-skeleton" style="height: 16px; width: 80px; margin-bottom: 8px;"></div>
                                            <div class="loading-skeleton" style="height: 32px; width: 60px; margin-bottom: 4px;"></div>
                                            <div class="loading-skeleton" style="height: 12px; width: 100px;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <div class="card shadow-sm border-0 h-100 stats-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-info bg-opacity-10 rounded p-3 me-3">
                                            <i class="bi bi-calendar-check text-info fs-4"></i>
                                        </div>
                                        <div>
                                            <div class="loading-skeleton" style="height: 16px; width: 80px; margin-bottom: 8px;"></div>
                                            <div class="loading-skeleton" style="height: 32px; width: 60px; margin-bottom: 4px;"></div>
                                            <div class="loading-skeleton" style="height: 12px; width: 100px;"></div>
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
                                    <div id="recentActivity">
                                        <!-- Loading skeleton for recent activity -->
                                        <?php for ($i = 0; $i < 5; $i++): ?>
                                        <div class="activity-item p-3 mb-3">
                                            <div class="loading-skeleton" style="height: 16px; width: 60%; margin-bottom: 8px;"></div>
                                            <div class="loading-skeleton" style="height: 14px; width: 80%; margin-bottom: 4px;"></div>
                                            <div class="loading-skeleton" style="height: 12px; width: 40%;"></div>
                                        </div>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Quick Actions & Notifications -->
                        <div class="col-lg-4 mb-4">
                            <!-- Quick Actions -->
                            <div class="card shadow-sm border-0 mb-4">
                                <div class="card-header bg-transparent border-0">
                                    <h6 class="mb-0">
                                        <i class="bi bi-lightning me-2"></i><?= $translations['quick_actions'] ?? 'Quick Actions' ?>
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div id="quickActions">
                                        <!-- Loading skeleton for quick actions -->
                                        <?php for ($i = 0; $i < 4; $i++): ?>
                                        <div class="mb-3">
                                            <div class="loading-skeleton" style="height: 40px; width: 100%;"></div>
                                        </div>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Notifications -->
                            <div class="card shadow-sm border-0">
                                <div class="card-header bg-transparent border-0">
                                    <h6 class="mb-0">
                                        <i class="bi bi-bell me-2"></i><?= $translations['notifications'] ?? 'Notifications' ?>
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div id="notifications">
                                        <!-- Loading skeleton for notifications -->
                                        <?php for ($i = 0; $i < 3; $i++): ?>
                                        <div class="mb-3">
                                            <div class="loading-skeleton" style="height: 16px; width: 80%; margin-bottom: 4px;"></div>
                                            <div class="loading-skeleton" style="height: 12px; width: 60%;"></div>
                                        </div>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Upcoming Webinars & Recommendations -->
                    <div class="row">
                        <!-- Upcoming Webinars -->
                        <div class="col-lg-6 mb-4">
                            <div class="card shadow-sm border-0">
                                <div class="card-header bg-transparent border-0">
                                    <h5 class="mb-0">
                                        <i class="bi bi-camera-video me-2"></i><?= $translations['upcoming_webinars'] ?? 'Upcoming Webinars' ?>
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div id="upcomingWebinars">
                                        <!-- Loading skeleton for upcoming webinars -->
                                        <?php for ($i = 0; $i < 3; $i++): ?>
                                        <div class="card mb-3">
                                            <div class="card-body">
                                                <div class="loading-skeleton" style="height: 18px; width: 70%; margin-bottom: 8px;"></div>
                                                <div class="loading-skeleton" style="height: 14px; width: 90%; margin-bottom: 12px;"></div>
                                                <div class="d-flex justify-content-between">
                                                    <div class="loading-skeleton" style="height: 12px; width: 40%;"></div>
                                                    <div class="loading-skeleton" style="height: 32px; width: 80px;"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Recommendations -->
                        <div class="col-lg-6 mb-4">
                            <div class="card shadow-sm border-0">
                                <div class="card-header bg-transparent border-0">
                                    <h5 class="mb-0">
                                        <i class="bi bi-lightbulb me-2"></i><?= $translations['recommendations'] ?? 'Recommendations' ?>
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div id="recommendations">
                                        <!-- Loading skeleton for recommendations -->
                                        <?php for ($i = 0; $i < 3; $i++): ?>
                                        <div class="recommendation-card card mb-3">
                                            <div class="card-body">
                                                <div class="loading-skeleton" style="height: 16px; width: 80%; margin-bottom: 8px;"></div>
                                                <div class="loading-skeleton" style="height: 14px; width: 90%; margin-bottom: 8px;"></div>
                                                <div class="loading-skeleton" style="height: 12px; width: 60%; margin-bottom: 12px;"></div>
                                                <div class="loading-skeleton" style="height: 32px; width: 80px;"></div>
                                            </div>
                                        </div>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="position-fixed top-0 start-0 w-100 h-100 bg-white bg-opacity-75 d-flex align-items-center justify-content-center" style="z-index: 9999; display: none;">
        <div class="text-center">
            <div class="spinner-border text-primary mb-3" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="text-muted">Loading dashboard data...</p>
        </div>
    </div>

    <!-- Error Modal -->
    <div class="modal fade" id="errorModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Error</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p id="errorMessage">An error occurred while loading the dashboard.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="retryLoading()">Retry</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- API Client -->
    <script src="assets/js/api.js"></script>
    <!-- Main JS -->
    <script src="assets/js/main.js"></script>
    
    <script>
        // Dashboard specific JavaScript
        let dashboardData = null;
        let isLoading = false;

        // Initialize dashboard when page loads
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof api !== 'undefined') {
                loadDashboardData();
            } else {
                console.error('API client not loaded');
                showError('API client not available');
            }
        });

        async function loadDashboardData() {
            if (isLoading) return;
            
            isLoading = true;
            showLoading(true);

            try {
                const response = await api.getDashboard();
                if (response.success) {
                    dashboardData = response.data;
                    updateDashboardUI(dashboardData);
                } else {
                    throw new Error(response.message || 'Failed to load dashboard data');
                }
            } catch (error) {
                console.error('Dashboard loading error:', error);
                showError(error.message);
            } finally {
                isLoading = false;
                showLoading(false);
            }
        }

        function updateDashboardUI(data) {
            // Update user information
            if (data.user) {
                updateUserInfo(data.user);
            }

            // Update statistics
            if (data.stats) {
                updateStatistics(data.stats);
            }

            // Update recent activity
            if (data.recent_activity) {
                updateRecentActivity(data.recent_activity);
            }

            // Update quick actions
            if (data.quick_actions) {
                updateQuickActions(data.quick_actions);
            }

            // Update notifications
            if (data.notifications) {
                updateNotifications(data.notifications);
            }

            // Update upcoming webinars
            if (data.upcoming_webinars) {
                updateUpcomingWebinars(data.upcoming_webinars);
            }

            // Update recommendations
            if (data.recommendations) {
                updateRecommendations(data.recommendations);
            }
        }

        function updateUserInfo(user) {
            const userName = document.getElementById('userName');
            const memberSince = document.getElementById('memberSince');
            
            if (userName) {
                userName.textContent = `Welcome, ${user.name || 'User'}`;
            }
            
            if (memberSince && user.created_at) {
                const date = new Date(user.created_at);
                memberSince.textContent = `Member since ${date.toLocaleDateString()}`;
            }
        }

        function updateStatistics(stats) {
            const statsContainer = document.getElementById('statisticsCards');
            if (!statsContainer) return;

            statsContainer.innerHTML = `
                <div class="col-md-3 mb-3">
                    <div class="card shadow-sm border-0 h-100 stats-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 rounded p-3 me-3">
                                    <i class="bi bi-graph-up text-primary fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Total Views</h6>
                                    <h4 class="mb-0 text-primary">${stats.total_views || 0}</h4>
                                    <small class="text-success">
                                        <i class="bi bi-arrow-up"></i> 12% this month
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-3">
                    <div class="card shadow-sm border-0 h-100 stats-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="bg-success bg-opacity-10 rounded p-3 me-3">
                                    <i class="bi bi-people text-success fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Followers</h6>
                                    <h4 class="mb-0 text-success">${stats.followers || 0}</h4>
                                    <small class="text-success">
                                        <i class="bi bi-arrow-up"></i> 8% this month
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-3">
                    <div class="card shadow-sm border-0 h-100 stats-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="bg-warning bg-opacity-10 rounded p-3 me-3">
                                    <i class="bi bi-star text-warning fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Rating</h6>
                                    <h4 class="mb-0 text-warning">${stats.rating || '4.8'}</h4>
                                    <small class="text-muted">out of 5</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-3">
                    <div class="card shadow-sm border-0 h-100 stats-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="bg-info bg-opacity-10 rounded p-3 me-3">
                                    <i class="bi bi-calendar-check text-info fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Upcoming</h6>
                                    <h4 class="mb-0 text-info">${stats.upcoming_webinars || 0}</h4>
                                    <small class="text-muted">webinars</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        function updateRecentActivity(activities) {
            const container = document.getElementById('recentActivity');
            if (!container) return;

            if (activities.length === 0) {
                container.innerHTML = '<p class="text-muted text-center py-3">No recent activity</p>';
                return;
            }

            const activityHTML = activities.map(activity => `
                <div class="activity-item p-3 mb-3">
                    <h6 class="mb-1">${activity.title}</h6>
                    <p class="text-muted mb-1">${activity.description || ''}</p>
                    <small class="text-muted">${formatDate(activity.date)}</small>
                </div>
            `).join('');

            container.innerHTML = activityHTML;
        }

        function updateQuickActions(actions) {
            const container = document.getElementById('quickActions');
            if (!container) return;

            const actionsHTML = actions.map(action => `
                <button class="btn btn-outline-primary w-100 mb-2" onclick="performQuickAction('${action.url}')">
                    <i class="bi bi-${action.icon} me-2"></i>${action.title}
                </button>
            `).join('');

            container.innerHTML = actionsHTML;
        }

        function updateNotifications(notifications) {
            const container = document.getElementById('notifications');
            if (!container) return;

            if (notifications.length === 0) {
                container.innerHTML = '<p class="text-muted text-center py-3">No notifications</p>';
                return;
            }

            const notificationsHTML = notifications.map(notification => `
                <div class="alert alert-info alert-dismissible fade show mb-2">
                    <strong>${notification.title}</strong>
                    <p class="mb-0 small">${notification.message}</p>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `).join('');

            container.innerHTML = notificationsHTML;
        }

        function updateUpcomingWebinars(webinars) {
            const container = document.getElementById('upcomingWebinars');
            if (!container) return;

            if (webinars.length === 0) {
                container.innerHTML = '<p class="text-muted text-center py-3">No upcoming webinars</p>';
                return;
            }

            const webinarsHTML = webinars.map(webinar => `
                <div class="card mb-3">
                    <div class="card-body">
                        <h6 class="card-title">${webinar.title}</h6>
                        <p class="card-text small text-muted">${webinar.description}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">${formatDate(webinar.scheduled_at)}</small>
                            <a href="${webinar.registration_url}" class="btn btn-sm btn-primary">
                                ${webinar.is_registered ? 'View' : 'Register'}
                            </a>
                        </div>
                    </div>
                </div>
            `).join('');

            container.innerHTML = webinarsHTML;
        }

        function updateRecommendations(recommendations) {
            const container = document.getElementById('recommendations');
            if (!container) return;

            if (recommendations.length === 0) {
                container.innerHTML = '<p class="text-muted text-center py-3">No recommendations</p>';
                return;
            }

            const recommendationsHTML = recommendations.map(rec => `
                <div class="recommendation-card card mb-3">
                    <div class="card-body">
                        <h6 class="card-title">${rec.title}</h6>
                        <p class="card-text small">${rec.description}</p>
                        <small class="text-muted">${rec.reason}</small>
                        <a href="${rec.url}" class="btn btn-sm btn-outline-primary mt-2">View</a>
                    </div>
                </div>
            `).join('');

            container.innerHTML = recommendationsHTML;
        }

        function showLoading(show) {
            const overlay = document.getElementById('loadingOverlay');
            if (overlay) {
                overlay.style.display = show ? 'flex' : 'none';
            }
        }

        function showError(message) {
            const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
            document.getElementById('errorMessage').textContent = message;
            errorModal.show();
        }

        function retryLoading() {
            const errorModal = bootstrap.Modal.getInstance(document.getElementById('errorModal'));
            errorModal.hide();
            loadDashboardData();
        }

        function performQuickAction(url) {
            if (url) {
                window.location.href = url;
            }
        }

        function createNewProject() {
            // Implement new project creation
            alert('New project creation feature coming soon!');
        }

        function formatDate(dateString) {
            if (!dateString) return '';
            
            const date = new Date(dateString);
            const now = new Date();
            const diffTime = Math.abs(now - date);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            
            if (diffDays === 1) {
                return 'Today';
            } else if (diffDays === 2) {
                return 'Yesterday';
            } else if (diffDays <= 7) {
                return `${diffDays - 1} days ago`;
            } else {
                return date.toLocaleDateString();
            }
        }

        // Update last seen when user is active
        let lastSeenTimeout;
        function updateLastSeen() {
            clearTimeout(lastSeenTimeout);
            lastSeenTimeout = setTimeout(async () => {
                try {
                    await api.updateLastSeen();
                } catch (error) {
                    console.error('Failed to update last seen:', error);
                }
            }, 30000); // Update every 30 seconds
        }

        // Track user activity
        document.addEventListener('mousemove', updateLastSeen);
        document.addEventListener('keypress', updateLastSeen);
        document.addEventListener('click', updateLastSeen);
    </script>

    <?php include 'includes/footer.php'; ?>
</body>
</html> 