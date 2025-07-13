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
    <title><?= $translations['notifications_title'] ?? 'Notifications - TQRS' ?></title>
    <meta name="description" content="<?= $translations['notifications_description'] ?? 'Stay updated with your latest notifications and activities' ?>">
    
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

    <main class="container py-4">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="mb-1">
                            <i class="bi bi-bell text-primary me-2"></i>
                            <?= $translations['notifications'] ?? 'Notifications' ?>
                        </h2>
                        <p class="text-muted mb-0"><?= $translations['notifications_subtitle'] ?? 'Stay updated with your latest activities' ?></p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary btn-sm" onclick="markAllAsRead()">
                            <i class="bi bi-check-all me-1"></i><?= $translations['mark_all_read'] ?? 'Mark All Read' ?>
                        </button>
                        <button class="btn btn-outline-secondary btn-sm" onclick="clearAll()">
                            <i class="bi bi-trash me-1"></i><?= $translations['clear_all'] ?? 'Clear All' ?>
                        </button>
                    </div>
                </div>

                <!-- Filter Tabs -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">
                        <ul class="nav nav-pills nav-fill" id="notificationTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="all-tab" data-bs-toggle="pill" data-bs-target="#all" type="button" role="tab">
                                    <i class="bi bi-bell me-1"></i><?= $translations['all'] ?? 'All' ?>
                                    <span class="badge bg-primary ms-1">12</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="unread-tab" data-bs-toggle="pill" data-bs-target="#unread" type="button" role="tab">
                                    <i class="bi bi-circle me-1"></i><?= $translations['unread'] ?? 'Unread' ?>
                                    <span class="badge bg-danger ms-1">5</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="webinars-tab" data-bs-toggle="pill" data-bs-target="#webinars" type="button" role="tab">
                                    <i class="bi bi-camera-video me-1"></i><?= $translations['webinars'] ?? 'Webinars' ?>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="articles-tab" data-bs-toggle="pill" data-bs-target="#articles" type="button" role="tab">
                                    <i class="bi bi-file-text me-1"></i><?= $translations['articles'] ?? 'Articles' ?>
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Notifications Content -->
                <div class="tab-content" id="notificationTabContent">
                    <!-- All Notifications -->
                    <div class="tab-pane fade show active" id="all" role="tabpanel">
                        <div class="notifications-list">
                            <!-- Webinar Notification -->
                            <div class="notification-item unread">
                                <div class="notification-icon bg-primary">
                                    <i class="bi bi-camera-video text-white"></i>
                                </div>
                                <div class="notification-content">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1"><?= $translations['webinar_reminder'] ?? 'Webinar Reminder' ?></h6>
                                            <p class="text-muted mb-1"><?= $translations['advanced_qualitative_methods'] ?? 'Advanced Qualitative Methods' ?> <?= $translations['starts_in'] ?? 'starts in' ?> 30 <?= $translations['minutes'] ?? 'minutes' ?></p>
                                            <small class="text-muted"><?= $translations['just_now'] ?? 'Just now' ?></small>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-link text-muted" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#" onclick="markAsRead(this)"><?= $translations['mark_read'] ?? 'Mark as Read' ?></a></li>
                                                <li><a class="dropdown-item" href="#" onclick="deleteNotification(this)"><?= $translations['delete'] ?? 'Delete' ?></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Comment Notification -->
                            <div class="notification-item unread">
                                <div class="notification-icon bg-success">
                                    <i class="bi bi-chat-dots text-white"></i>
                                </div>
                                <div class="notification-content">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1"><?= $translations['new_comment'] ?? 'New Comment' ?></h6>
                                            <p class="text-muted mb-1"><?= $translations['john_doe_commented'] ?? 'John Doe commented on your article' ?> "<?= $translations['research_methodology'] ?? 'Research Methodology' ?>"</p>
                                            <small class="text-muted">5 <?= $translations['minutes_ago'] ?? 'minutes ago' ?></small>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-link text-muted" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#" onclick="markAsRead(this)"><?= $translations['mark_read'] ?? 'Mark as Read' ?></a></li>
                                                <li><a class="dropdown-item" href="#" onclick="deleteNotification(this)"><?= $translations['delete'] ?? 'Delete' ?></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Like Notification -->
                            <div class="notification-item unread">
                                <div class="notification-icon bg-warning">
                                    <i class="bi bi-heart text-white"></i>
                                </div>
                                <div class="notification-content">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1"><?= $translations['new_like'] ?? 'New Like' ?></h6>
                                            <p class="text-muted mb-1"><?= $translations['sarah_smith_liked'] ?? 'Sarah Smith liked your article' ?> "<?= $translations['qualitative_analysis'] ?? 'Qualitative Analysis' ?>"</p>
                                            <small class="text-muted">15 <?= $translations['minutes_ago'] ?? 'minutes ago' ?></small>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-link text-muted" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#" onclick="markAsRead(this)"><?= $translations['mark_read'] ?? 'Mark as Read' ?></a></li>
                                                <li><a class="dropdown-item" href="#" onclick="deleteNotification(this)"><?= $translations['delete'] ?? 'Delete' ?></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Follow Notification -->
                            <div class="notification-item">
                                <div class="notification-icon bg-info">
                                    <i class="bi bi-person-plus text-white"></i>
                                </div>
                                <div class="notification-content">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1"><?= $translations['new_follower'] ?? 'New Follower' ?></h6>
                                            <p class="text-muted mb-1"><?= $translations['mike_johnson_followed'] ?? 'Mike Johnson started following you' ?></p>
                                            <small class="text-muted">1 <?= $translations['hour_ago'] ?? 'hour ago' ?></small>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-link text-muted" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#" onclick="markAsRead(this)"><?= $translations['mark_read'] ?? 'Mark as Read' ?></a></li>
                                                <li><a class="dropdown-item" href="#" onclick="deleteNotification(this)"><?= $translations['delete'] ?? 'Delete' ?></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- System Notification -->
                            <div class="notification-item">
                                <div class="notification-icon bg-secondary">
                                    <i class="bi bi-gear text-white"></i>
                                </div>
                                <div class="notification-content">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1"><?= $translations['system_update'] ?? 'System Update' ?></h6>
                                            <p class="text-muted mb-1"><?= $translations['new_features_available'] ?? 'New features are now available on the platform' ?></p>
                                            <small class="text-muted">2 <?= $translations['hours_ago'] ?? 'hours ago' ?></small>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-link text-muted" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#" onclick="markAsRead(this)"><?= $translations['mark_read'] ?? 'Mark as Read' ?></a></li>
                                                <li><a class="dropdown-item" href="#" onclick="deleteNotification(this)"><?= $translations['delete'] ?? 'Delete' ?></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Article Published -->
                            <div class="notification-item">
                                <div class="notification-icon bg-success">
                                    <i class="bi bi-check-circle text-white"></i>
                                </div>
                                <div class="notification-content">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1"><?= $translations['article_published'] ?? 'Article Published' ?></h6>
                                            <p class="text-muted mb-1"><?= $translations['your_article_published'] ?? 'Your article "Research Findings" has been published successfully' ?></p>
                                            <small class="text-muted">1 <?= $translations['day_ago'] ?? 'day ago' ?></small>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-link text-muted" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#" onclick="markAsRead(this)"><?= $translations['mark_read'] ?? 'Mark as Read' ?></a></li>
                                                <li><a class="dropdown-item" href="#" onclick="deleteNotification(this)"><?= $translations['delete'] ?? 'Delete' ?></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Unread Notifications -->
                    <div class="tab-pane fade" id="unread" role="tabpanel">
                        <div class="notifications-list">
                            <!-- Only unread notifications will be shown here -->
                        </div>
                    </div>

                    <!-- Webinar Notifications -->
                    <div class="tab-pane fade" id="webinars" role="tabpanel">
                        <div class="notifications-list">
                            <!-- Only webinar notifications will be shown here -->
                        </div>
                    </div>

                    <!-- Article Notifications -->
                    <div class="tab-pane fade" id="articles" role="tabpanel">
                        <div class="notifications-list">
                            <!-- Only article notifications will be shown here -->
                        </div>
                    </div>
                </div>

                <!-- Load More Button -->
                <div class="text-center mt-4">
                    <button class="btn btn-outline-primary" onclick="loadMoreNotifications()">
                        <i class="bi bi-arrow-down me-2"></i><?= $translations['load_more'] ?? 'Load More' ?>
                    </button>
                </div>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

    <!-- Custom CSS for Notifications -->
    <style>
        .notification-item {
            display: flex;
            align-items: flex-start;
            padding: 1rem;
            border-bottom: 1px solid #e9ecef;
            transition: all 0.3s ease;
            background: white;
            margin-bottom: 0.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .notification-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .notification-item.unread {
            background: linear-gradient(135deg, #f8f9ff 0%, #e8f2ff 100%);
            border-left: 4px solid #667eea;
        }
        
        .notification-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            flex-shrink: 0;
        }
        
        .notification-content {
            flex-grow: 1;
        }
        
        .nav-pills .nav-link {
            border-radius: 25px;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
        }
        
        .nav-pills .nav-link.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .nav-pills .nav-link:hover {
            transform: translateY(-1px);
        }
        
        .badge {
            border-radius: 10px;
        }
        
        .dropdown-menu {
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        
        .dropdown-item {
            border-radius: 4px;
            margin: 2px;
        }
        
        .dropdown-item:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .btn {
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            transform: translateY(-2px);
        }
    </style>

    <!-- Notifications JavaScript -->
    <script>
        function markAsRead(button) {
            const notificationItem = button.closest('.notification-item');
            notificationItem.classList.remove('unread');
            updateUnreadCount();
        }
        
        function deleteNotification(button) {
            const notificationItem = button.closest('.notification-item');
            notificationItem.style.animation = 'slideOut 0.3s ease forwards';
            setTimeout(() => {
                notificationItem.remove();
                updateUnreadCount();
            }, 300);
        }
        
        function markAllAsRead() {
            const unreadNotifications = document.querySelectorAll('.notification-item.unread');
            unreadNotifications.forEach(item => {
                item.classList.remove('unread');
            });
            updateUnreadCount();
        }
        
        function clearAll() {
            if (confirm('<?= $translations['clear_all_confirm'] ?? 'Are you sure you want to clear all notifications?' ?>')) {
                const notifications = document.querySelectorAll('.notification-item');
                notifications.forEach((item, index) => {
                    setTimeout(() => {
                        item.style.animation = 'slideOut 0.3s ease forwards';
                        setTimeout(() => item.remove(), 300);
                    }, index * 100);
                });
            }
        }
        
        function updateUnreadCount() {
            const unreadCount = document.querySelectorAll('.notification-item.unread').length;
            const unreadBadge = document.querySelector('#unread-tab .badge');
            if (unreadBadge) {
                unreadBadge.textContent = unreadCount;
                if (unreadCount === 0) {
                    unreadBadge.style.display = 'none';
                }
            }
        }
        
        function loadMoreNotifications() {
            // Simulate loading more notifications
            const button = event.target;
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="bi bi-hourglass-split me-2"></i><?= $translations['loading'] ?? 'Loading' ?>...';
            button.disabled = true;
            
            setTimeout(() => {
                button.innerHTML = originalText;
                button.disabled = false;
                // Add more notifications here
            }, 2000);
        }
        
        // Add slideOut animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideOut {
                from {
                    opacity: 1;
                    transform: translateX(0);
                }
                to {
                    opacity: 0;
                    transform: translateX(-100%);
                }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html> 