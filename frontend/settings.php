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
    <title><?= $translations['settings_title'] ?? 'Settings - TQRS' ?></title>
    <meta name="description" content="<?= $translations['settings_description'] ?? 'Manage your account settings and preferences' ?>">
    
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
            <div class="col-lg-10 mx-auto">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="mb-1">
                            <i class="bi bi-gear text-primary me-2"></i>
                            <?= $translations['settings'] ?? 'Settings' ?>
                        </h2>
                        <p class="text-muted mb-0"><?= $translations['settings_subtitle'] ?? 'Manage your account and preferences' ?></p>
                    </div>
                    <button class="btn btn-primary" onclick="saveAllSettings()">
                        <i class="bi bi-check-circle me-2"></i><?= $translations['save_changes'] ?? 'Save Changes' ?>
                    </button>
                </div>

                <!-- Settings Navigation -->
                <div class="row">
                    <div class="col-lg-3 mb-4">
                        <div class="card shadow-sm border-0">
                            <div class="card-body p-0">
                                <nav class="nav flex-column">
                                    <a class="nav-link active" href="#profile" data-bs-toggle="pill">
                                        <i class="bi bi-person me-2"></i><?= $translations['profile'] ?? 'Profile' ?>
                                    </a>
                                    <a class="nav-link" href="#account" data-bs-toggle="pill">
                                        <i class="bi bi-shield-lock me-2"></i><?= $translations['account'] ?? 'Account' ?>
                                    </a>
                                    <a class="nav-link" href="#notifications" data-bs-toggle="pill">
                                        <i class="bi bi-bell me-2"></i><?= $translations['notifications'] ?? 'Notifications' ?>
                                    </a>
                                    <a class="nav-link" href="#privacy" data-bs-toggle="pill">
                                        <i class="bi bi-eye me-2"></i><?= $translations['privacy'] ?? 'Privacy' ?>
                                    </a>
                                    <a class="nav-link" href="#appearance" data-bs-toggle="pill">
                                        <i class="bi bi-palette me-2"></i><?= $translations['appearance'] ?? 'Appearance' ?>
                                    </a>
                                    <a class="nav-link" href="#language" data-bs-toggle="pill">
                                        <i class="bi bi-translate me-2"></i><?= $translations['language'] ?? 'Language' ?>
                                    </a>
                                    <a class="nav-link" href="#security" data-bs-toggle="pill">
                                        <i class="bi bi-key me-2"></i><?= $translations['security'] ?? 'Security' ?>
                                    </a>
                                </nav>
                            </div>
                        </div>
                    </div>

                    <!-- Settings Content -->
                    <div class="col-lg-9">
                        <div class="tab-content">
                            <!-- Profile Settings -->
                            <div class="tab-pane fade show active" id="profile">
                                <div class="card shadow-sm border-0">
                                    <div class="card-header bg-transparent border-0">
                                        <h5 class="mb-0">
                                            <i class="bi bi-person me-2"></i><?= $translations['profile_settings'] ?? 'Profile Settings' ?>
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3 text-center mb-4">
                                                <div class="position-relative d-inline-block">
                                                    <img src="assets/images/avatar-placeholder.jpg" alt="Profile" class="rounded-circle" width="120" height="120">
                                                    <button class="btn btn-sm btn-primary position-absolute bottom-0 end-0 rounded-circle">
                                                        <i class="bi bi-camera"></i>
                                                    </button>
                                                </div>
                                                <p class="text-muted small mt-2"><?= $translations['click_to_change'] ?? 'Click to change photo' ?></p>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label"><?= $translations['first_name'] ?? 'First Name' ?></label>
                                                        <input type="text" class="form-control" value="John">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label"><?= $translations['last_name'] ?? 'Last Name' ?></label>
                                                        <input type="text" class="form-control" value="Doe">
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label"><?= $translations['email'] ?? 'Email' ?></label>
                                                    <input type="email" class="form-control" value="john.doe@example.com">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label"><?= $translations['bio'] ?? 'Bio' ?></label>
                                                    <textarea class="form-control" rows="3" placeholder="<?= $translations['tell_us_about_yourself'] ?? 'Tell us about yourself' ?>"><?= $translations['bio_placeholder'] ?? 'Research enthusiast with 5+ years of experience in qualitative research methodologies.' ?></textarea>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label"><?= $translations['location'] ?? 'Location' ?></label>
                                                        <input type="text" class="form-control" value="New York, USA">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label"><?= $translations['website'] ?? 'Website' ?></label>
                                                        <input type="url" class="form-control" value="https://johndoe.com">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Account Settings -->
                            <div class="tab-pane fade" id="account">
                                <div class="card shadow-sm border-0">
                                    <div class="card-header bg-transparent border-0">
                                        <h5 class="mb-0">
                                            <i class="bi bi-shield-lock me-2"></i><?= $translations['account_settings'] ?? 'Account Settings' ?>
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-4">
                                            <h6><?= $translations['account_status'] ?? 'Account Status' ?></h6>
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-success me-2"><?= $translations['active'] ?? 'Active' ?></span>
                                                <span class="text-muted"><?= $translations['member_since'] ?? 'Member since' ?> January 2024</span>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-4">
                                            <h6><?= $translations['subscription'] ?? 'Subscription' ?></h6>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div>
                                                    <h6 class="mb-1"><?= $translations['premium_plan'] ?? 'Premium Plan' ?></h6>
                                                    <p class="text-muted mb-0"><?= $translations['renews_on'] ?? 'Renews on' ?> March 15, 2024</p>
                                                </div>
                                                <button class="btn btn-outline-primary btn-sm"><?= $translations['manage'] ?? 'Manage' ?></button>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-4">
                                            <h6><?= $translations['data_export'] ?? 'Data Export' ?></h6>
                                            <p class="text-muted"><?= $translations['export_data_description'] ?? 'Download all your data including articles, webinars, and preferences.' ?></p>
                                            <button class="btn btn-outline-secondary">
                                                <i class="bi bi-download me-2"></i><?= $translations['export_data'] ?? 'Export Data' ?>
                                            </button>
                                        </div>
                                        
                                        <div class="mb-4">
                                            <h6 class="text-danger"><?= $translations['danger_zone'] ?? 'Danger Zone' ?></h6>
                                            <p class="text-muted"><?= $translations['delete_account_warning'] ?? 'Once you delete your account, there is no going back. Please be certain.' ?></p>
                                            <button class="btn btn-outline-danger">
                                                <i class="bi bi-trash me-2"></i><?= $translations['delete_account'] ?? 'Delete Account' ?>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Notification Settings -->
                            <div class="tab-pane fade" id="notifications">
                                <div class="card shadow-sm border-0">
                                    <div class="card-header bg-transparent border-0">
                                        <h5 class="mb-0">
                                            <i class="bi bi-bell me-2"></i><?= $translations['notification_settings'] ?? 'Notification Settings' ?>
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-4">
                                            <h6><?= $translations['email_notifications'] ?? 'Email Notifications' ?></h6>
                                            <div class="form-check form-switch mb-2">
                                                <input class="form-check-input" type="checkbox" id="emailWebinars" checked>
                                                <label class="form-check-label" for="emailWebinars">
                                                    <?= $translations['webinar_reminders'] ?? 'Webinar reminders and updates' ?>
                                                </label>
                                            </div>
                                            <div class="form-check form-switch mb-2">
                                                <input class="form-check-input" type="checkbox" id="emailComments" checked>
                                                <label class="form-check-label" for="emailComments">
                                                    <?= $translations['comment_notifications'] ?? 'Comments on your articles' ?>
                                                </label>
                                            </div>
                                            <div class="form-check form-switch mb-2">
                                                <input class="form-check-input" type="checkbox" id="emailLikes">
                                                <label class="form-check-label" for="emailLikes">
                                                    <?= $translations['like_notifications'] ?? 'Likes on your content' ?>
                                                </label>
                                            </div>
                                            <div class="form-check form-switch mb-2">
                                                <input class="form-check-input" type="checkbox" id="emailNewsletter" checked>
                                                <label class="form-check-label" for="emailNewsletter">
                                                    <?= $translations['newsletter'] ?? 'Weekly newsletter' ?>
                                                </label>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-4">
                                            <h6><?= $translations['push_notifications'] ?? 'Push Notifications' ?></h6>
                                            <div class="form-check form-switch mb-2">
                                                <input class="form-check-input" type="checkbox" id="pushWebinars" checked>
                                                <label class="form-check-label" for="pushWebinars">
                                                    <?= $translations['webinar_alerts'] ?? 'Webinar alerts' ?>
                                                </label>
                                            </div>
                                            <div class="form-check form-switch mb-2">
                                                <input class="form-check-input" type="checkbox" id="pushComments">
                                                <label class="form-check-label" for="pushComments">
                                                    <?= $translations['comment_alerts'] ?? 'Comment alerts' ?>
                                                </label>
                                            </div>
                                            <div class="form-check form-switch mb-2">
                                                <input class="form-check-input" type="checkbox" id="pushSystem">
                                                <label class="form-check-label" for="pushSystem">
                                                    <?= $translations['system_updates'] ?? 'System updates' ?>
                                                </label>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-4">
                                            <h6><?= $translations['notification_frequency'] ?? 'Notification Frequency' ?></h6>
                                            <select class="form-select">
                                                <option><?= $translations['immediately'] ?? 'Immediately' ?></option>
                                                <option><?= $translations['hourly'] ?? 'Hourly' ?></option>
                                                <option><?= $translations['daily'] ?? 'Daily' ?></option>
                                                <option><?= $translations['weekly'] ?? 'Weekly' ?></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Privacy Settings -->
                            <div class="tab-pane fade" id="privacy">
                                <div class="card shadow-sm border-0">
                                    <div class="card-header bg-transparent border-0">
                                        <h5 class="mb-0">
                                            <i class="bi bi-eye me-2"></i><?= $translations['privacy_settings'] ?? 'Privacy Settings' ?>
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-4">
                                            <h6><?= $translations['profile_visibility'] ?? 'Profile Visibility' ?></h6>
                                            <div class="form-check form-switch mb-2">
                                                <input class="form-check-input" type="checkbox" id="publicProfile" checked>
                                                <label class="form-check-label" for="publicProfile">
                                                    <?= $translations['public_profile'] ?? 'Make my profile public' ?>
                                                </label>
                                            </div>
                                            <div class="form-check form-switch mb-2">
                                                <input class="form-check-input" type="checkbox" id="showEmail">
                                                <label class="form-check-label" for="showEmail">
                                                    <?= $translations['show_email'] ?? 'Show email address on profile' ?>
                                                </label>
                                            </div>
                                            <div class="form-check form-switch mb-2">
                                                <input class="form-check-input" type="checkbox" id="showLocation" checked>
                                                <label class="form-check-label" for="showLocation">
                                                    <?= $translations['show_location'] ?? 'Show location on profile' ?>
                                                </label>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-4">
                                            <h6><?= $translations['content_privacy'] ?? 'Content Privacy' ?></h6>
                                            <div class="form-check form-switch mb-2">
                                                <input class="form-check-input" type="checkbox" id="allowComments" checked>
                                                <label class="form-check-label" for="allowComments">
                                                    <?= $translations['allow_comments'] ?? 'Allow comments on my articles' ?>
                                                </label>
                                            </div>
                                            <div class="form-check form-switch mb-2">
                                                <input class="form-check-input" type="checkbox" id="allowLikes" checked>
                                                <label class="form-check-label" for="allowLikes">
                                                    <?= $translations['allow_likes'] ?? 'Allow likes on my content' ?>
                                                </label>
                                            </div>
                                            <div class="form-check form-switch mb-2">
                                                <input class="form-check-input" type="checkbox" id="allowSharing" checked>
                                                <label class="form-check-label" for="allowSharing">
                                                    <?= $translations['allow_sharing'] ?? 'Allow sharing of my content' ?>
                                                </label>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-4">
                                            <h6><?= $translations['data_collection'] ?? 'Data Collection' ?></h6>
                                            <div class="form-check form-switch mb-2">
                                                <input class="form-check-input" type="checkbox" id="analytics" checked>
                                                <label class="form-check-label" for="analytics">
                                                    <?= $translations['allow_analytics'] ?? 'Allow analytics and usage data collection' ?>
                                                </label>
                                            </div>
                                            <div class="form-check form-switch mb-2">
                                                <input class="form-check-input" type="checkbox" id="personalization" checked>
                                                <label class="form-check-label" for="personalization">
                                                    <?= $translations['allow_personalization'] ?? 'Allow personalized content recommendations' ?>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Appearance Settings -->
                            <div class="tab-pane fade" id="appearance">
                                <div class="card shadow-sm border-0">
                                    <div class="card-header bg-transparent border-0">
                                        <h5 class="mb-0">
                                            <i class="bi bi-palette me-2"></i><?= $translations['appearance_settings'] ?? 'Appearance Settings' ?>
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-4">
                                            <h6><?= $translations['theme'] ?? 'Theme' ?></h6>
                                            <div class="row">
                                                <div class="col-md-4 mb-3">
                                                    <div class="theme-option active" data-theme="light">
                                                        <div class="theme-preview bg-light border rounded p-3 text-center">
                                                            <i class="bi bi-sun text-warning fs-4"></i>
                                                            <p class="mb-0 mt-2"><?= $translations['light'] ?? 'Light' ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <div class="theme-option" data-theme="dark">
                                                        <div class="theme-preview bg-dark border rounded p-3 text-center text-white">
                                                            <i class="bi bi-moon text-info fs-4"></i>
                                                            <p class="mb-0 mt-2"><?= $translations['dark'] ?? 'Dark' ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <div class="theme-option" data-theme="auto">
                                                        <div class="theme-preview bg-gradient border rounded p-3 text-center text-white">
                                                            <i class="bi bi-circle-half text-white fs-4"></i>
                                                            <p class="mb-0 mt-2"><?= $translations['auto'] ?? 'Auto' ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-4">
                                            <h6><?= $translations['font_size'] ?? 'Font Size' ?></h6>
                                            <div class="d-flex align-items-center">
                                                <span class="me-3"><?= $translations['small'] ?? 'Small' ?></span>
                                                <input type="range" class="form-range flex-grow-1" min="12" max="20" value="16" id="fontSize">
                                                <span class="ms-3"><?= $translations['large'] ?? 'Large' ?></span>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-4">
                                            <h6><?= $translations['compact_mode'] ?? 'Compact Mode' ?></h6>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="compactMode">
                                                <label class="form-check-label" for="compactMode">
                                                    <?= $translations['enable_compact_mode'] ?? 'Enable compact mode for more content on screen' ?>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Language Settings -->
                            <div class="tab-pane fade" id="language">
                                <div class="card shadow-sm border-0">
                                    <div class="card-header bg-transparent border-0">
                                        <h5 class="mb-0">
                                            <i class="bi bi-translate me-2"></i><?= $translations['language_settings'] ?? 'Language Settings' ?>
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-4">
                                            <h6><?= $translations['interface_language'] ?? 'Interface Language' ?></h6>
                                            <select class="form-select" id="interfaceLanguage">
                                                <option value="en" selected>English</option>
                                                <option value="es">Español</option>
                                                <option value="fr">Français</option>
                                                <option value="de">Deutsch</option>
                                                <option value="it">Italiano</option>
                                                <option value="pt">Português</option>
                                                <option value="ru">Русский</option>
                                                <option value="zh">中文</option>
                                                <option value="ja">日本語</option>
                                                <option value="ko">한국어</option>
                                            </select>
                                        </div>
                                        
                                        <div class="mb-4">
                                            <h6><?= $translations['content_language'] ?? 'Content Language' ?></h6>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" id="contentEnglish" checked>
                                                <label class="form-check-label" for="contentEnglish">English</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" id="contentSpanish">
                                                <label class="form-check-label" for="contentSpanish">Spanish</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" id="contentFrench">
                                                <label class="form-check-label" for="contentFrench">French</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" id="contentGerman">
                                                <label class="form-check-label" for="contentGerman">German</label>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-4">
                                            <h6><?= $translations['auto_translate'] ?? 'Auto Translation' ?></h6>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="autoTranslate">
                                                <label class="form-check-label" for="autoTranslate">
                                                    <?= $translations['auto_translate_description'] ?? 'Automatically translate content to your preferred language' ?>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Security Settings -->
                            <div class="tab-pane fade" id="security">
                                <div class="card shadow-sm border-0">
                                    <div class="card-header bg-transparent border-0">
                                        <h5 class="mb-0">
                                            <i class="bi bi-key me-2"></i><?= $translations['security_settings'] ?? 'Security Settings' ?>
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-4">
                                            <h6><?= $translations['change_password'] ?? 'Change Password' ?></h6>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label"><?= $translations['current_password'] ?? 'Current Password' ?></label>
                                                    <input type="password" class="form-control">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label"><?= $translations['new_password'] ?? 'New Password' ?></label>
                                                    <input type="password" class="form-control">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label"><?= $translations['confirm_password'] ?? 'Confirm New Password' ?></label>
                                                <input type="password" class="form-control">
                                            </div>
                                            <button class="btn btn-primary"><?= $translations['update_password'] ?? 'Update Password' ?></button>
                                        </div>
                                        
                                        <div class="mb-4">
                                            <h6><?= $translations['two_factor_auth'] ?? 'Two-Factor Authentication' ?></h6>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div>
                                                    <h6 class="mb-1"><?= $translations['2fa_status'] ?? '2FA Status' ?></h6>
                                                    <p class="text-muted mb-0"><?= $translations['2fa_disabled'] ?? 'Two-factor authentication is currently disabled' ?></p>
                                                </div>
                                                <button class="btn btn-outline-primary"><?= $translations['enable_2fa'] ?? 'Enable 2FA' ?></button>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-4">
                                            <h6><?= $translations['login_history'] ?? 'Login History' ?></h6>
                                            <div class="table-responsive">
                                                <table class="table table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th><?= $translations['device'] ?? 'Device' ?></th>
                                                            <th><?= $translations['location'] ?? 'Location' ?></th>
                                                            <th><?= $translations['last_login'] ?? 'Last Login' ?></th>
                                                            <th><?= $translations['status'] ?? 'Status' ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>Chrome on Windows</td>
                                                            <td>New York, USA</td>
                                                            <td><?= $translations['just_now'] ?? 'Just now' ?></td>
                                                            <td><span class="badge bg-success"><?= $translations['current'] ?? 'Current' ?></span></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Safari on iPhone</td>
                                                            <td>New York, USA</td>
                                                            <td>2 <?= $translations['hours_ago'] ?? 'hours ago' ?></td>
                                                            <td><span class="badge bg-secondary"><?= $translations['active'] ?? 'Active' ?></span></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-4">
                                            <h6><?= $translations['sessions'] ?? 'Active Sessions' ?></h6>
                                            <button class="btn btn-outline-danger">
                                                <i class="bi bi-power me-2"></i><?= $translations['logout_all_devices'] ?? 'Logout All Devices' ?>
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
    </main>

    <?php include 'includes/footer.php'; ?>

    <!-- Custom CSS for Settings -->
    <style>
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
        
        .theme-option {
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .theme-option:hover {
            transform: translateY(-2px);
        }
        
        .theme-option.active .theme-preview {
            border-color: #667eea !important;
            box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.2);
        }
        
        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }
        
        .form-range::-webkit-slider-thumb {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .btn {
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            transform: translateY(-2px);
        }
        
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
        }
    </style>

    <!-- Settings JavaScript -->
    <script>
        // Theme selection
        document.querySelectorAll('.theme-option').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelectorAll('.theme-option').forEach(opt => opt.classList.remove('active'));
                this.classList.add('active');
                
                const theme = this.dataset.theme;
                // Apply theme logic here
                console.log('Theme changed to:', theme);
            });
        });
        
        // Font size adjustment
        document.getElementById('fontSize').addEventListener('input', function() {
            const size = this.value;
            document.documentElement.style.fontSize = size + 'px';
        });
        
        // Save all settings
        function saveAllSettings() {
            const button = event.target;
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="bi bi-hourglass-split me-2"></i><?= $translations['saving'] ?? 'Saving' ?>...';
            button.disabled = true;
            
            setTimeout(() => {
                button.innerHTML = '<i class="bi bi-check-circle me-2"></i><?= $translations['saved'] ?? 'Saved' ?>!';
                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.disabled = false;
                }, 2000);
            }, 1500);
        }
        
        // Language change
        document.getElementById('interfaceLanguage').addEventListener('change', function() {
            const lang = this.value;
            window.location.href = `settings.php?lang=${lang}`;
        });
    </script>
</body>
</html> 