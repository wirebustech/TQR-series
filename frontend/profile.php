<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?lang=' . urlencode($_GET['lang'] ?? 'en'));
    exit;
}

$pageTitle = 'Profile - TQRS';
$pageDescription = 'Manage your TQRS account, preferences, and view your activity history.';

include_once __DIR__ . '/includes/header.php';

$texts = [
    'profileTitle' => 'My Profile',
    'accountSettings' => 'Account Settings',
    'personalInfo' => 'Personal Information',
    'preferences' => 'Preferences',
    'activity' => 'Activity History',
    'security' => 'Security',
    'notifications' => 'Notifications',
    'subscriptions' => 'Subscriptions',
    'firstName' => 'First Name',
    'lastName' => 'Last Name',
    'email' => 'Email Address',
    'phone' => 'Phone Number',
    'institution' => 'Institution/Organization',
    'researchArea' => 'Research Area',
    'role' => 'Role',
    'bio' => 'Biography',
    'location' => 'Location',
    'website' => 'Website',
    'socialLinks' => 'Social Links',
    'linkedin' => 'LinkedIn',
    'twitter' => 'Twitter',
    'researchGate' => 'ResearchGate',
    'saveChanges' => 'Save Changes',
    'cancel' => 'Cancel',
    'changePassword' => 'Change Password',
    'currentPassword' => 'Current Password',
    'newPassword' => 'New Password',
    'confirmNewPassword' => 'Confirm New Password',
    'updatePassword' => 'Update Password',
    'notificationPreferences' => 'Notification Preferences',
    'emailNotifications' => 'Email Notifications',
    'webinarReminders' => 'Webinar Reminders',
    'newArticles' => 'New Articles',
    'communityUpdates' => 'Community Updates',
    'marketingEmails' => 'Marketing Emails',
    'pushNotifications' => 'Push Notifications',
    'language' => 'Language',
    'timezone' => 'Timezone',
    'dateFormat' => 'Date Format',
    'timeFormat' => 'Time Format',
    'recentActivity' => 'Recent Activity',
    'webinarsAttended' => 'Webinars Attended',
    'articlesRead' => 'Articles Read',
    'commentsPosted' => 'Comments Posted',
    'resourcesDownloaded' => 'Resources Downloaded',
    'viewAll' => 'View All',
    'noActivity' => 'No recent activity',
    'profileUpdated' => 'Profile updated successfully!',
    'passwordUpdated' => 'Password updated successfully!',
    'preferencesUpdated' => 'Preferences updated successfully!',
    'errorUpdating' => 'Error updating profile. Please try again.',
    'deleteAccount' => 'Delete Account',
    'deleteAccountWarning' => 'This action cannot be undone. All your data will be permanently deleted.',
    'confirmDelete' => 'I understand and want to delete my account',
    'deleteAccountBtn' => 'Delete My Account',
    'exportData' => 'Export My Data',
    'exportDataText' => 'Download a copy of all your data',
    'exportDataBtn' => 'Export Data'
];
if ($lang !== 'en') {
    foreach ($texts as $k => $v) {
        $texts[$k] = translateText($v, $lang, 'en');
    }
}

// Mock user data - in real app, fetch from database
$user = [
    'id' => $_SESSION['user_id'],
    'first_name' => $_SESSION['user_name'] ?? 'Demo',
    'last_name' => 'User',
    'email' => $_SESSION['user_email'] ?? 'demo@tqrs.org',
    'phone' => '+1 (555) 123-4567',
    'institution' => 'University of Research',
    'research_area' => 'Educational Psychology',
    'role' => 'researcher',
    'bio' => 'Passionate researcher focused on qualitative methodologies in educational settings.',
    'location' => 'New York, NY',
    'website' => 'https://example.com',
    'linkedin' => 'linkedin.com/in/demouser',
    'twitter' => '@demouser',
    'research_gate' => 'researchgate.net/profile/demouser',
    'language' => $lang,
    'timezone' => 'America/New_York',
    'date_format' => 'MM/DD/YYYY',
    'time_format' => '12-hour',
    'notifications' => [
        'email' => true,
        'webinar_reminders' => true,
        'new_articles' => true,
        'community_updates' => false,
        'marketing_emails' => false,
        'push_notifications' => true
    ]
];

// Mock activity data
$activities = [
    [
        'type' => 'webinar',
        'title' => 'Advanced Grounded Theory Methodology',
        'date' => '2024-02-10',
        'icon' => 'bi-camera-video'
    ],
    [
        'type' => 'article',
        'title' => 'Understanding Grounded Theory: A Comprehensive Guide',
        'date' => '2024-02-08',
        'icon' => 'bi-journal-text'
    ],
    [
        'type' => 'comment',
        'title' => 'Commented on "NVivo Software Masterclass"',
        'date' => '2024-02-05',
        'icon' => 'bi-chat-dots'
    ],
    [
        'type' => 'download',
        'title' => 'Downloaded "Qualitative Research Methods Handbook"',
        'date' => '2024-02-03',
        'icon' => 'bi-download'
    ]
];

// Handle form submissions
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'update_profile':
                // Update profile information
                $user['first_name'] = $_POST['first_name'] ?? $user['first_name'];
                $user['last_name'] = $_POST['last_name'] ?? $user['last_name'];
                $user['phone'] = $_POST['phone'] ?? $user['phone'];
                $user['institution'] = $_POST['institution'] ?? $user['institution'];
                $user['research_area'] = $_POST['research_area'] ?? $user['research_area'];
                $user['bio'] = $_POST['bio'] ?? $user['bio'];
                $user['location'] = $_POST['location'] ?? $user['location'];
                $user['website'] = $_POST['website'] ?? $user['website'];
                $user['linkedin'] = $_POST['linkedin'] ?? $user['linkedin'];
                $user['twitter'] = $_POST['twitter'] ?? $user['twitter'];
                $user['research_gate'] = $_POST['research_gate'] ?? $user['research_gate'];
                
                $message = $texts['profileUpdated'];
                $messageType = 'success';
                break;
                
            case 'update_password':
                $currentPassword = $_POST['current_password'] ?? '';
                $newPassword = $_POST['new_password'] ?? '';
                $confirmPassword = $_POST['confirm_password'] ?? '';
                
                if ($newPassword === $confirmPassword && strlen($newPassword) >= 8) {
                    // In real app, verify current password and update
                    $message = $texts['passwordUpdated'];
                    $messageType = 'success';
                } else {
                    $message = 'Password update failed. Please check your input.';
                    $messageType = 'error';
                }
                break;
                
            case 'update_preferences':
                $user['notifications']['email'] = isset($_POST['email_notifications']);
                $user['notifications']['webinar_reminders'] = isset($_POST['webinar_reminders']);
                $user['notifications']['new_articles'] = isset($_POST['new_articles']);
                $user['notifications']['community_updates'] = isset($_POST['community_updates']);
                $user['notifications']['marketing_emails'] = isset($_POST['marketing_emails']);
                $user['notifications']['push_notifications'] = isset($_POST['push_notifications']);
                
                $message = $texts['preferencesUpdated'];
                $messageType = 'success';
                break;
        }
    }
}
?>

<!-- Profile Header -->
<div class="bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="display-6 fw-bold mb-2"><?= htmlspecialchars($texts['profileTitle']) ?></h1>
                <p class="lead mb-0"><?= htmlspecialchars($user['first_name']) ?> <?= htmlspecialchars($user['last_name']) ?></p>
            </div>
            <div class="col-md-4 text-md-end">
                <div class="d-flex align-items-center justify-content-md-end">
                    <img src="assets/images/avatar.jpg" alt="Profile" class="rounded-circle me-3" style="width: 60px; height: 60px; object-fit: cover;">
                    <div>
                        <small class="text-light"><?= htmlspecialchars($user['role']) ?></small><br>
                        <small class="text-light"><?= htmlspecialchars($user['institution']) ?></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Profile Content -->
<div class="py-5">
    <div class="container">
        <!-- Message Display -->
        <?php if ($message): ?>
            <div class="alert alert-<?= $messageType === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show mb-4">
                <i class="bi bi-<?= $messageType === 'success' ? 'check-circle' : 'exclamation-triangle' ?>"></i>
                <?= htmlspecialchars($message) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <!-- Navigation Sidebar -->
            <div class="col-lg-3 mb-4">
                <div class="list-group">
                    <a href="#profile" class="list-group-item list-group-item-action active" data-bs-toggle="list">
                        <i class="bi bi-person me-2"></i> <?= htmlspecialchars($texts['personalInfo']) ?>
                    </a>
                    <a href="#preferences" class="list-group-item list-group-item-action" data-bs-toggle="list">
                        <i class="bi bi-gear me-2"></i> <?= htmlspecialchars($texts['preferences']) ?>
                    </a>
                    <a href="#security" class="list-group-item list-group-item-action" data-bs-toggle="list">
                        <i class="bi bi-shield-lock me-2"></i> <?= htmlspecialchars($texts['security']) ?>
                    </a>
                    <a href="#activity" class="list-group-item list-group-item-action" data-bs-toggle="list">
                        <i class="bi bi-activity me-2"></i> <?= htmlspecialchars($texts['activity']) ?>
                    </a>
                    <a href="#notifications" class="list-group-item list-group-item-action" data-bs-toggle="list">
                        <i class="bi bi-bell me-2"></i> <?= htmlspecialchars($texts['notifications']) ?>
                    </a>
                </div>
            </div>

            <!-- Content Area -->
            <div class="col-lg-9">
                <div class="tab-content">
                    <!-- Personal Information -->
                    <div class="tab-pane fade show active" id="profile">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white">
                                <h5 class="card-title mb-0"><?= htmlspecialchars($texts['personalInfo']) ?></h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="profile.php?lang=<?= urlencode($lang) ?>">
                                    <input type="hidden" name="action" value="update_profile">
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="first_name" class="form-label"><?= htmlspecialchars($texts['firstName']) ?></label>
                                            <input type="text" class="form-control" id="first_name" name="first_name" 
                                                   value="<?= htmlspecialchars($user['first_name']) ?>" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="last_name" class="form-label"><?= htmlspecialchars($texts['lastName']) ?></label>
                                            <input type="text" class="form-control" id="last_name" name="last_name" 
                                                   value="<?= htmlspecialchars($user['last_name']) ?>" required>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="email" class="form-label"><?= htmlspecialchars($texts['email']) ?></label>
                                            <input type="email" class="form-control" id="email" value="<?= htmlspecialchars($user['email']) ?>" readonly>
                                            <div class="form-text">Email cannot be changed</div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="phone" class="form-label"><?= htmlspecialchars($texts['phone']) ?></label>
                                            <input type="tel" class="form-control" id="phone" name="phone" 
                                                   value="<?= htmlspecialchars($user['phone']) ?>">
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="institution" class="form-label"><?= htmlspecialchars($texts['institution']) ?></label>
                                            <input type="text" class="form-control" id="institution" name="institution" 
                                                   value="<?= htmlspecialchars($user['institution']) ?>">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="research_area" class="form-label"><?= htmlspecialchars($texts['researchArea']) ?></label>
                                            <input type="text" class="form-control" id="research_area" name="research_area" 
                                                   value="<?= htmlspecialchars($user['research_area']) ?>">
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="bio" class="form-label"><?= htmlspecialchars($texts['bio']) ?></label>
                                        <textarea class="form-control" id="bio" name="bio" rows="4"><?= htmlspecialchars($user['bio']) ?></textarea>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="location" class="form-label"><?= htmlspecialchars($texts['location']) ?></label>
                                            <input type="text" class="form-control" id="location" name="location" 
                                                   value="<?= htmlspecialchars($user['location']) ?>">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="website" class="form-label"><?= htmlspecialchars($texts['website']) ?></label>
                                            <input type="url" class="form-control" id="website" name="website" 
                                                   value="<?= htmlspecialchars($user['website']) ?>">
                                        </div>
                                    </div>
                                    
                                    <h6 class="mt-4 mb-3"><?= htmlspecialchars($texts['socialLinks']) ?></h6>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="linkedin" class="form-label"><?= htmlspecialchars($texts['linkedin']) ?></label>
                                            <input type="url" class="form-control" id="linkedin" name="linkedin" 
                                                   value="<?= htmlspecialchars($user['linkedin']) ?>">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="twitter" class="form-label"><?= htmlspecialchars($texts['twitter']) ?></label>
                                            <input type="text" class="form-control" id="twitter" name="twitter" 
                                                   value="<?= htmlspecialchars($user['twitter']) ?>">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="research_gate" class="form-label"><?= htmlspecialchars($texts['researchGate']) ?></label>
                                            <input type="url" class="form-control" id="research_gate" name="research_gate" 
                                                   value="<?= htmlspecialchars($user['research_gate']) ?>">
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-check-circle"></i> <?= htmlspecialchars($texts['saveChanges']) ?>
                                        </button>
                                        <button type="reset" class="btn btn-outline-secondary">
                                            <?= htmlspecialchars($texts['cancel']) ?>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Preferences -->
                    <div class="tab-pane fade" id="preferences">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white">
                                <h5 class="card-title mb-0"><?= htmlspecialchars($texts['preferences']) ?></h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="profile.php?lang=<?= urlencode($lang) ?>">
                                    <input type="hidden" name="action" value="update_preferences">
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="language" class="form-label"><?= htmlspecialchars($texts['language']) ?></label>
                                            <select class="form-select" id="language" name="language">
                                                <option value="en"<?= $user['language'] === 'en' ? ' selected' : '' ?>>English</option>
                                                <option value="es"<?= $user['language'] === 'es' ? ' selected' : '' ?>>Español</option>
                                                <option value="fr"<?= $user['language'] === 'fr' ? ' selected' : '' ?>>Français</option>
                                                <option value="de"<?= $user['language'] === 'de' ? ' selected' : '' ?>>Deutsch</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="timezone" class="form-label"><?= htmlspecialchars($texts['timezone']) ?></label>
                                            <select class="form-select" id="timezone" name="timezone">
                                                <option value="America/New_York"<?= $user['timezone'] === 'America/New_York' ? ' selected' : '' ?>>Eastern Time</option>
                                                <option value="America/Chicago"<?= $user['timezone'] === 'America/Chicago' ? ' selected' : '' ?>>Central Time</option>
                                                <option value="America/Denver"<?= $user['timezone'] === 'America/Denver' ? ' selected' : '' ?>>Mountain Time</option>
                                                <option value="America/Los_Angeles"<?= $user['timezone'] === 'America/Los_Angeles' ? ' selected' : '' ?>>Pacific Time</option>
                                                <option value="Europe/London"<?= $user['timezone'] === 'Europe/London' ? ' selected' : '' ?>>London</option>
                                                <option value="Europe/Paris"<?= $user['timezone'] === 'Europe/Paris' ? ' selected' : '' ?>>Paris</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="date_format" class="form-label"><?= htmlspecialchars($texts['dateFormat']) ?></label>
                                            <select class="form-select" id="date_format" name="date_format">
                                                <option value="MM/DD/YYYY"<?= $user['date_format'] === 'MM/DD/YYYY' ? ' selected' : '' ?>>MM/DD/YYYY</option>
                                                <option value="DD/MM/YYYY"<?= $user['date_format'] === 'DD/MM/YYYY' ? ' selected' : '' ?>>DD/MM/YYYY</option>
                                                <option value="YYYY-MM-DD"<?= $user['date_format'] === 'YYYY-MM-DD' ? ' selected' : '' ?>>YYYY-MM-DD</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="time_format" class="form-label"><?= htmlspecialchars($texts['timeFormat']) ?></label>
                                            <select class="form-select" id="time_format" name="time_format">
                                                <option value="12-hour"<?= $user['time_format'] === '12-hour' ? ' selected' : '' ?>>12-hour</option>
                                                <option value="24-hour"<?= $user['time_format'] === '24-hour' ? ' selected' : '' ?>>24-hour</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle"></i> <?= htmlspecialchars($texts['saveChanges']) ?>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Security -->
                    <div class="tab-pane fade" id="security">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white">
                                <h5 class="card-title mb-0"><?= htmlspecialchars($texts['security']) ?></h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="profile.php?lang=<?= urlencode($lang) ?>">
                                    <input type="hidden" name="action" value="update_password">
                                    
                                    <div class="mb-3">
                                        <label for="current_password" class="form-label"><?= htmlspecialchars($texts['currentPassword']) ?></label>
                                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="new_password" class="form-label"><?= htmlspecialchars($texts['newPassword']) ?></label>
                                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                                            <div class="form-text">Minimum 8 characters</div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="confirm_password" class="form-label"><?= htmlspecialchars($texts['confirmNewPassword']) ?></label>
                                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                        </div>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-shield-check"></i> <?= htmlspecialchars($texts['updatePassword']) ?>
                                    </button>
                                </form>
                                
                                <hr class="my-4">
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6><?= htmlspecialchars($texts['exportData']) ?></h6>
                                        <p class="text-muted small"><?= htmlspecialchars($texts['exportDataText']) ?></p>
                                        <button class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-download"></i> <?= htmlspecialchars($texts['exportDataBtn']) ?>
                                        </button>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="text-danger"><?= htmlspecialchars($texts['deleteAccount']) ?></h6>
                                        <p class="text-muted small"><?= htmlspecialchars($texts['deleteAccountWarning']) ?></p>
                                        <button class="btn btn-outline-danger btn-sm" onclick="showDeleteAccountModal()">
                                            <i class="bi bi-trash"></i> <?= htmlspecialchars($texts['deleteAccountBtn']) ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Activity -->
                    <div class="tab-pane fade" id="activity">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white">
                                <h5 class="card-title mb-0"><?= htmlspecialchars($texts['recentActivity']) ?></h5>
                            </div>
                            <div class="card-body">
                                <?php if (empty($activities)): ?>
                                    <div class="text-center py-4">
                                        <i class="bi bi-activity fs-1 text-muted mb-3"></i>
                                        <p class="text-muted"><?= htmlspecialchars($texts['noActivity']) ?></p>
                                    </div>
                                <?php else: ?>
                                    <div class="list-group list-group-flush">
                                        <?php foreach ($activities as $activity): ?>
                                            <div class="list-group-item border-0 px-0">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <i class="bi <?= htmlspecialchars($activity['icon']) ?> fs-4 text-primary"></i>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h6 class="mb-1"><?= htmlspecialchars($activity['title']) ?></h6>
                                                        <small class="text-muted"><?= date('M j, Y', strtotime($activity['date'])) ?></small>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="text-center mt-3">
                                        <a href="#" class="btn btn-outline-primary btn-sm">
                                            <?= htmlspecialchars($texts['viewAll']) ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Notifications -->
                    <div class="tab-pane fade" id="notifications">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white">
                                <h5 class="card-title mb-0"><?= htmlspecialchars($texts['notificationPreferences']) ?></h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="profile.php?lang=<?= urlencode($lang) ?>">
                                    <input type="hidden" name="action" value="update_preferences">
                                    
                                    <h6><?= htmlspecialchars($texts['emailNotifications']) ?></h6>
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="email_notifications" name="email_notifications" 
                                                   <?= $user['notifications']['email'] ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="email_notifications">
                                                <?= htmlspecialchars($texts['emailNotifications']) ?>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="webinar_reminders" name="webinar_reminders" 
                                                   <?= $user['notifications']['webinar_reminders'] ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="webinar_reminders">
                                                <?= htmlspecialchars($texts['webinarReminders']) ?>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="new_articles" name="new_articles" 
                                                   <?= $user['notifications']['new_articles'] ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="new_articles">
                                                <?= htmlspecialchars($texts['newArticles']) ?>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="community_updates" name="community_updates" 
                                                   <?= $user['notifications']['community_updates'] ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="community_updates">
                                                <?= htmlspecialchars($texts['communityUpdates']) ?>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="marketing_emails" name="marketing_emails" 
                                                   <?= $user['notifications']['marketing_emails'] ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="marketing_emails">
                                                <?= htmlspecialchars($texts['marketingEmails']) ?>
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <h6><?= htmlspecialchars($texts['pushNotifications']) ?></h6>
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="push_notifications" name="push_notifications" 
                                                   <?= $user['notifications']['push_notifications'] ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="push_notifications">
                                                <?= htmlspecialchars($texts['pushNotifications']) ?>
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle"></i> <?= htmlspecialchars($texts['saveChanges']) ?>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger"><?= htmlspecialchars($texts['deleteAccount']) ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-danger"><?= htmlspecialchars($texts['deleteAccountWarning']) ?></p>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="confirmDelete">
                    <label class="form-check-label" for="confirmDelete">
                        <?= htmlspecialchars($texts['confirmDelete']) ?>
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= htmlspecialchars($texts['cancel']) ?></button>
                <button type="button" class="btn btn-danger" id="deleteAccountBtn" disabled>
                    <?= htmlspecialchars($texts['deleteAccountBtn']) ?>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Tab navigation
document.addEventListener('DOMContentLoaded', function() {
    const hash = window.location.hash;
    if (hash) {
        const tab = document.querySelector(`a[href="${hash}"]`);
        if (tab) {
            tab.click();
        }
    }
});

// Delete account confirmation
function showDeleteAccountModal() {
    const modal = new bootstrap.Modal(document.getElementById('deleteAccountModal'));
    modal.show();
}

document.getElementById('confirmDelete').addEventListener('change', function() {
    document.getElementById('deleteAccountBtn').disabled = !this.checked;
});

document.getElementById('deleteAccountBtn').addEventListener('click', function() {
    if (confirm('Are you absolutely sure you want to delete your account? This action cannot be undone.')) {
        // In a real app, this would make an API call to delete the account
        window.location.href = 'logout.php?lang=<?= urlencode($lang) ?>';
    }
});

// Password strength indicator
document.getElementById('new_password').addEventListener('input', function() {
    const password = this.value;
    const strength = calculatePasswordStrength(password);
    updatePasswordStrengthIndicator(strength);
});

function calculatePasswordStrength(password) {
    let score = 0;
    
    if (password.length >= 8) score++;
    if (/[a-z]/.test(password)) score++;
    if (/[A-Z]/.test(password)) score++;
    if (/[0-9]/.test(password)) score++;
    if (/[^A-Za-z0-9]/.test(password)) score++;
    
    return score;
}

function updatePasswordStrengthIndicator(strength) {
    const strengthText = document.getElementById('password-strength');
    if (!strengthText) {
        const strengthDiv = document.createElement('div');
        strengthDiv.id = 'password-strength';
        strengthDiv.className = 'form-text mt-1';
        document.getElementById('new_password').parentNode.appendChild(strengthDiv);
    }
    
    const strengthDiv = document.getElementById('password-strength');
    const colors = ['text-danger', 'text-warning', 'text-info', 'text-primary', 'text-success'];
    const messages = ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong'];
    
    if (strength > 0) {
        strengthDiv.className = `form-text mt-1 ${colors[strength - 1]}`;
        strengthDiv.textContent = `Password strength: ${messages[strength - 1]}`;
    } else {
        strengthDiv.textContent = '';
    }
}

// Password confirmation validation
document.getElementById('confirm_password').addEventListener('input', function() {
    const password = document.getElementById('new_password').value;
    const confirmPassword = this.value;
    
    if (confirmPassword && password !== confirmPassword) {
        this.setCustomValidity('Passwords do not match');
    } else {
        this.setCustomValidity('');
    }
});
</script>

<?php include_once __DIR__ . '/includes/footer.php'; ?> 