<?php
session_start();

// Check if user is admin (simple check for demo)
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? 'user') !== 'admin') {
    header('Location: ../login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
    exit;
}

$pageTitle = 'Admin Dashboard - TQRS';
$pageDescription = 'Administrative dashboard for The Qualitative Research Series platform.';

$lang = $_GET['lang'] ?? 'en';

// Load translations
require_once __DIR__ . '/../includes/translation.php';
$translations = getTranslations($lang);

$texts = [
    'dashboard_title' => 'Admin Dashboard',
    'dashboard_subtitle' => 'Manage your TQRS platform content and settings',
    'overview' => 'Overview',
    'quick_actions' => 'Quick Actions',
    'recent_activity' => 'Recent Activity',
    'statistics' => 'Statistics',
    'pages' => 'Pages',
    'opportunities' => 'Opportunities',
    'users' => 'Users',
    'settings' => 'Settings',
    'total_pages' => 'Total Pages',
    'published_pages' => 'Published Pages',
    'draft_pages' => 'Draft Pages',
    'total_opportunities' => 'Total Opportunities',
    'active_opportunities' => 'Active Opportunities',
    'view_all' => 'View All',
    'add_new' => 'Add New',
    'manage' => 'Manage',
    'no_recent_activity' => 'No recent activity',
    'welcome_message' => 'Welcome to the TQRS Admin Dashboard',
    'platform_status' => 'Platform Status',
    'all_systems_operational' => 'All systems operational',
    'last_updated' => 'Last updated',
    'quick_stats' => 'Quick Stats'
];

// Mock statistics (in real implementation, these would come from API)
$stats = [
    'total_pages' => 12,
    'published_pages' => 8,
    'draft_pages' => 4,
    'total_opportunities' => 15,
    'active_opportunities' => 12,
    'total_users' => 245,
    'recent_signups' => 23
];

include '../includes/header.php';
?>

<div class="container-fluid py-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2">
            <div class="admin-sidebar">
                <h5 class="sidebar-title">
                    <i class="bi bi-gear"></i> Admin Panel
                </h5>
                <nav class="nav flex-column">
                    <a class="nav-link active" href="index.php?lang=<?= urlencode($lang) ?>">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                    <a class="nav-link" href="pages.php?lang=<?= urlencode($lang) ?>">
                        <i class="bi bi-file-earmark-text"></i> Pages
                    </a>
                    <a class="nav-link" href="opportunities.php?lang=<?= urlencode($lang) ?>">
                        <i class="bi bi-bullhorn"></i> Opportunities
                    </a>
                    <a class="nav-link" href="../index.php?lang=<?= urlencode($lang) ?>">
                        <i class="bi bi-arrow-left"></i> Back to Site
                    </a>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-10">
            <div class="admin-content">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h3 mb-1"><?= htmlspecialchars($texts['dashboard_title']) ?></h1>
                        <p class="text-muted mb-0"><?= htmlspecialchars($texts['dashboard_subtitle']) ?></p>
                    </div>
                    <div class="badge bg-success">
                        <i class="bi bi-check-circle"></i> <?= htmlspecialchars($texts['all_systems_operational']) ?>
                    </div>
                </div>

                <!-- Welcome Message -->
                <div class="alert alert-info mb-4">
                    <h5 class="alert-heading">
                        <i class="bi bi-hand-thumbs-up"></i> <?= htmlspecialchars($texts['welcome_message']) ?>
                    </h5>
                    <p class="mb-0">Use this dashboard to manage your content, view analytics, and configure platform settings.</p>
                </div>

                <!-- Quick Stats -->
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <div class="card stat-card">
                            <div class="card-body text-center">
                                <div class="stat-icon bg-primary">
                                    <i class="bi bi-file-earmark-text"></i>
                                </div>
                                <h3 class="stat-number"><?= $stats['total_pages'] ?></h3>
                                <p class="stat-label"><?= htmlspecialchars($texts['total_pages']) ?></p>
                                <small class="text-muted">
                                    <?= $stats['published_pages'] ?> published, <?= $stats['draft_pages'] ?> drafts
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card stat-card">
                            <div class="card-body text-center">
                                <div class="stat-icon bg-success">
                                    <i class="bi bi-bullhorn"></i>
                                </div>
                                <h3 class="stat-number"><?= $stats['total_opportunities'] ?></h3>
                                <p class="stat-label"><?= htmlspecialchars($texts['total_opportunities']) ?></p>
                                <small class="text-muted">
                                    <?= $stats['active_opportunities'] ?> active
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card stat-card">
                            <div class="card-body text-center">
                                <div class="stat-icon bg-info">
                                    <i class="bi bi-people"></i>
                                </div>
                                <h3 class="stat-number"><?= $stats['total_users'] ?></h3>
                                <p class="stat-label"><?= htmlspecialchars($texts['users']) ?></p>
                                <small class="text-muted">
                                    +<?= $stats['recent_signups'] ?> this month
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card stat-card">
                            <div class="card-body text-center">
                                <div class="stat-icon bg-warning">
                                    <i class="bi bi-graph-up"></i>
                                </div>
                                <h3 class="stat-number">98%</h3>
                                <p class="stat-label">Uptime</p>
                                <small class="text-muted">
                                    Last 30 days
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="bi bi-lightning"></i> <?= htmlspecialchars($texts['quick_actions']) ?>
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <a href="pages.php?lang=<?= urlencode($lang) ?>" class="btn btn-outline-primary">
                                        <i class="bi bi-plus-circle"></i> Add New Page
                                    </a>
                                    <a href="opportunities.php?lang=<?= urlencode($lang) ?>" class="btn btn-outline-success">
                                        <i class="bi bi-plus-circle"></i> Add New Opportunity
                                    </a>
                                    <a href="../research-ai.php?lang=<?= urlencode($lang) ?>" class="btn btn-outline-info" target="_blank">
                                        <i class="bi bi-eye"></i> Preview Research AI Page
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="bi bi-activity"></i> <?= htmlspecialchars($texts['recent_activity']) ?>
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="activity-list">
                                    <div class="activity-item">
                                        <div class="activity-icon bg-success">
                                            <i class="bi bi-file-plus"></i>
                                        </div>
                                        <div class="activity-content">
                                            <strong>Research AI page created</strong>
                                            <br>
                                            <small class="text-muted">2 hours ago</small>
                                        </div>
                                    </div>
                                    <div class="activity-item">
                                        <div class="activity-icon bg-info">
                                            <i class="bi bi-pencil"></i>
                                        </div>
                                        <div class="activity-content">
                                            <strong>Opportunities updated</strong>
                                            <br>
                                            <small class="text-muted">5 hours ago</small>
                                        </div>
                                    </div>
                                    <div class="activity-item">
                                        <div class="activity-icon bg-warning">
                                            <i class="bi bi-globe"></i>
                                        </div>
                                        <div class="activity-content">
                                            <strong>Multi-language support enabled</strong>
                                            <br>
                                            <small class="text-muted">1 day ago</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Management Links -->
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="card management-card">
                            <div class="card-body text-center">
                                <div class="management-icon">
                                    <i class="bi bi-file-earmark-text"></i>
                                </div>
                                <h5><?= htmlspecialchars($texts['pages']) ?></h5>
                                <p class="text-muted">Create and manage website pages with multi-language support</p>
                                <div class="d-grid gap-2">
                                    <a href="pages.php?lang=<?= urlencode($lang) ?>" class="btn btn-primary">
                                        <?= htmlspecialchars($texts['manage']) ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card management-card">
                            <div class="card-body text-center">
                                <div class="management-icon">
                                    <i class="bi bi-bullhorn"></i>
                                </div>
                                <h5><?= htmlspecialchars($texts['opportunities']) ?></h5>
                                <p class="text-muted">Manage research opportunities and collaboration announcements</p>
                                <div class="d-grid gap-2">
                                    <a href="opportunities.php?lang=<?= urlencode($lang) ?>" class="btn btn-success">
                                        <?= htmlspecialchars($texts['manage']) ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card management-card">
                            <div class="card-body text-center">
                                <div class="management-icon">
                                    <i class="bi bi-gear"></i>
                                </div>
                                <h5><?= htmlspecialchars($texts['settings']) ?></h5>
                                <p class="text-muted">Configure platform settings and preferences</p>
                                <div class="d-grid gap-2">
                                    <button class="btn btn-outline-secondary" disabled>
                                        Coming Soon
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.admin-sidebar {
    background: #f8f9fa;
    border-radius: 0.5rem;
    padding: 1.5rem;
    height: fit-content;
}

.admin-sidebar .sidebar-title {
    color: #495057;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid #dee2e6;
}

.admin-sidebar .nav-link {
    color: #6c757d;
    padding: 0.75rem 1rem;
    border-radius: 0.375rem;
    margin-bottom: 0.25rem;
    transition: all 0.2s;
}

.admin-sidebar .nav-link:hover,
.admin-sidebar .nav-link.active {
    background: #0d6efd;
    color: white;
}

.admin-content {
    background: white;
    border-radius: 0.5rem;
    padding: 2rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.stat-card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    transition: transform 0.2s;
}

.stat-card:hover {
    transform: translateY(-2px);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 1.5rem;
    color: white;
}

.stat-number {
    font-size: 2rem;
    font-weight: bold;
    margin-bottom: 0.5rem;
    color: #495057;
}

.stat-label {
    font-weight: 600;
    color: #6c757d;
    margin-bottom: 0.5rem;
}

.activity-list {
    max-height: 300px;
    overflow-y: auto;
}

.activity-item {
    display: flex;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f8f9fa;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    font-size: 1rem;
    color: white;
}

.activity-content {
    flex: 1;
}

.management-card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    transition: transform 0.2s;
    height: 100%;
}

.management-card:hover {
    transform: translateY(-2px);
}

.management-icon {
    font-size: 3rem;
    color: #0d6efd;
    margin-bottom: 1rem;
}

@media (max-width: 768px) {
    .admin-content {
        padding: 1rem;
    }
    
    .stat-card {
        margin-bottom: 1rem;
    }
}
</style>

<?php include '../includes/footer.php'; ?> 