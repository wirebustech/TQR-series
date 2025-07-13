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
    <title><?= $translations['help_center_title'] ?? 'Help Center - TQRS' ?></title>
    <meta name="description" content="<?= $translations['help_center_description'] ?? 'Get help and support for TQRS platform' ?>">
    
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
        <!-- Header -->
        <div class="text-center mb-5">
            <h1 class="display-5 fw-bold mb-3">
                <i class="bi bi-question-circle text-primary me-3"></i>
                <?= $translations['help_center'] ?? 'Help Center' ?>
            </h1>
            <p class="lead text-muted mb-4">
                <?= $translations['help_center_subtitle'] ?? 'Find answers, tutorials, and support resources' ?>
            </p>
            
            <!-- Search Bar -->
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control border-start-0" id="helpSearch" 
                               placeholder="<?= $translations['search_help'] ?? 'Search for help articles...' ?>">
                        <button class="btn btn-primary" type="button">
                            <?= $translations['search'] ?? 'Search' ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Help Categories -->
        <div class="row mb-5">
            <div class="col-12">
                <h3 class="text-center mb-4">
                    <i class="bi bi-lightning text-warning me-2"></i>
                    <?= $translations['quick_help'] ?? 'Quick Help' ?>
                </h3>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 help-category-card">
                    <div class="card-body text-center p-4">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <i class="bi bi-person-plus text-primary fs-4"></i>
                        </div>
                        <h5 class="card-title"><?= $translations['getting_started'] ?? 'Getting Started' ?></h5>
                        <p class="text-muted"><?= $translations['getting_started_desc'] ?? 'New to TQRS? Learn the basics' ?></p>
                        <a href="#getting-started" class="btn btn-outline-primary btn-sm"><?= $translations['learn_more'] ?? 'Learn More' ?></a>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 help-category-card">
                    <div class="card-body text-center p-4">
                        <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <i class="bi bi-camera-video text-success fs-4"></i>
                        </div>
                        <h5 class="card-title"><?= $translations['webinars'] ?? 'Webinars' ?></h5>
                        <p class="text-muted"><?= $translations['webinars_desc'] ?? 'Join and manage webinars' ?></p>
                        <a href="#webinars" class="btn btn-outline-success btn-sm"><?= $translations['learn_more'] ?? 'Learn More' ?></a>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 help-category-card">
                    <div class="card-body text-center p-4">
                        <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <i class="bi bi-robot text-warning fs-4"></i>
                        </div>
                        <h5 class="card-title"><?= $translations['ai_assistant'] ?? 'AI Assistant' ?></h5>
                        <p class="text-muted"><?= $translations['ai_assistant_desc'] ?? 'Using the AI research assistant' ?></p>
                        <a href="#ai-assistant" class="btn btn-outline-warning btn-sm"><?= $translations['learn_more'] ?? 'Learn More' ?></a>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 help-category-card">
                    <div class="card-body text-center p-4">
                        <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <i class="bi bi-gear text-info fs-4"></i>
                        </div>
                        <h5 class="card-title"><?= $translations['account_settings'] ?? 'Account & Settings' ?></h5>
                        <p class="text-muted"><?= $translations['account_settings_desc'] ?? 'Manage your account' ?></p>
                        <a href="#account-settings" class="btn btn-outline-info btn-sm"><?= $translations['learn_more'] ?? 'Learn More' ?></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Help Articles -->
        <div class="row">
            <div class="col-lg-8">
                <!-- Getting Started Section -->
                <div class="card border-0 shadow-sm mb-4" id="getting-started">
                    <div class="card-header bg-transparent border-0">
                        <h4 class="mb-0">
                            <i class="bi bi-person-plus text-primary me-2"></i>
                            <?= $translations['getting_started'] ?? 'Getting Started' ?>
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="accordion" id="gettingStartedAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#gs1">
                                        <?= $translations['create_account'] ?? 'How to Create an Account' ?>
                                    </button>
                                </h2>
                                <div id="gs1" class="accordion-collapse collapse show" data-bs-parent="#gettingStartedAccordion">
                                    <div class="accordion-body">
                                        <ol>
                                            <li><?= $translations['gs_step1'] ?? 'Click the "Register" button in the top navigation' ?></li>
                                            <li><?= $translations['gs_step2'] ?? 'Fill out the registration form with your details' ?></li>
                                            <li><?= $translations['gs_step3'] ?? 'Verify your email address' ?></li>
                                            <li><?= $translations['gs_step4'] ?? 'Complete your profile (optional but recommended)' ?></li>
                                        </ol>
                                        <div class="alert alert-info">
                                            <i class="bi bi-info-circle me-2"></i>
                                            <?= $translations['gs_tip'] ?? 'Creating an account is free and gives you access to basic features.' ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#gs2">
                                        <?= $translations['first_webinar'] ?? 'Joining Your First Webinar' ?>
                                    </button>
                                </h2>
                                <div id="gs2" class="accordion-collapse collapse" data-bs-parent="#gettingStartedAccordion">
                                    <div class="accordion-body">
                                        <ol>
                                            <li><?= $translations['webinar_step1'] ?? 'Browse available webinars on the webinars page' ?></li>
                                            <li><?= $translations['webinar_step2'] ?? 'Click "Register" on a webinar you want to attend' ?></li>
                                            <li><?= $translations['webinar_step3'] ?? 'You\'ll receive a confirmation email' ?></li>
                                            <li><?= $translations['webinar_step4'] ?? 'Join the live session at the scheduled time' ?></li>
                                        </ol>
                                        <div class="alert alert-success">
                                            <i class="bi bi-check-circle me-2"></i>
                                            <?= $translations['webinar_tip'] ?? 'Most webinars are free and recorded for later viewing.' ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Webinars Section -->
                <div class="card border-0 shadow-sm mb-4" id="webinars">
                    <div class="card-header bg-transparent border-0">
                        <h4 class="mb-0">
                            <i class="bi bi-camera-video text-success me-2"></i>
                            <?= $translations['webinars'] ?? 'Webinars' ?>
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="accordion" id="webinarsAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#web1">
                                        <?= $translations['webinar_requirements'] ?? 'Webinar Requirements' ?>
                                    </button>
                                </h2>
                                <div id="web1" class="accordion-collapse collapse show" data-bs-parent="#webinarsAccordion">
                                    <div class="accordion-body">
                                        <h6><?= $translations['system_requirements'] ?? 'System Requirements:' ?></h6>
                                        <ul>
                                            <li><?= $translations['req_browser'] ?? 'Modern web browser (Chrome, Firefox, Safari, Edge)' ?></li>
                                            <li><?= $translations['req_internet'] ?? 'Stable internet connection' ?></li>
                                            <li><?= $translations['req_audio'] ?? 'Audio speakers or headphones' ?></li>
                                            <li><?= $translations['req_microphone'] ?? 'Microphone (for interactive sessions)' ?></li>
                                        </ul>
                                        <h6><?= $translations['recommended'] ?? 'Recommended:' ?></h6>
                                        <ul>
                                            <li><?= $translations['rec_webcam'] ?? 'Webcam for video participation' ?></li>
                                            <li><?= $translations['rec_bandwidth'] ?? 'High-speed internet connection' ?></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#web2">
                                        <?= $translations['webinar_features'] ?? 'Webinar Features' ?>
                                    </button>
                                </h2>
                                <div id="web2" class="accordion-collapse collapse" data-bs-parent="#webinarsAccordion">
                                    <div class="accordion-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6><?= $translations['live_features'] ?? 'Live Features:' ?></h6>
                                                <ul>
                                                    <li><?= $translations['live_chat'] ?? 'Live chat with participants' ?></li>
                                                    <li><?= $translations['live_qna'] ?? 'Q&A sessions' ?></li>
                                                    <li><?= $translations['live_polls'] ?? 'Interactive polls' ?></li>
                                                    <li><?= $translations['live_screen'] ?? 'Screen sharing' ?></li>
                                                </ul>
                                            </div>
                                            <div class="col-md-6">
                                                <h6><?= $translations['recording_features'] ?? 'Recording Features:' ?></h6>
                                                <ul>
                                                    <li><?= $translations['recording_access'] ?? 'Access to recordings' ?></li>
                                                    <li><?= $translations['recording_download'] ?? 'Download capabilities' ?></li>
                                                    <li><?= $translations['recording_notes'] ?? 'Session notes' ?></li>
                                                    <li><?= $translations['recording_certificate'] ?? 'Attendance certificates' ?></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- AI Assistant Section -->
                <div class="card border-0 shadow-sm mb-4" id="ai-assistant">
                    <div class="card-header bg-transparent border-0">
                        <h4 class="mb-0">
                            <i class="bi bi-robot text-warning me-2"></i>
                            <?= $translations['ai_assistant'] ?? 'AI Assistant' ?>
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="accordion" id="aiAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#ai1">
                                        <?= $translations['ai_introduction'] ?? 'Introduction to AI Assistant' ?>
                                    </button>
                                </h2>
                                <div id="ai1" class="accordion-collapse collapse show" data-bs-parent="#aiAccordion">
                                    <div class="accordion-body">
                                        <p><?= $translations['ai_intro_text'] ?? 'The TQRS AI Assistant is a powerful tool designed to help researchers with qualitative data analysis, methodology guidance, and research insights.' ?></p>
                                        
                                        <h6><?= $translations['ai_capabilities'] ?? 'Key Capabilities:' ?></h6>
                                        <ul>
                                            <li><?= $translations['ai_data_analysis'] ?? 'Qualitative data analysis and coding' ?></li>
                                            <li><?= $translations['ai_methodology'] ?? 'Research methodology guidance' ?></li>
                                            <li><?= $translations['ai_literature'] ?? 'Literature review assistance' ?></li>
                                            <li><?= $translations['ai_writing'] ?? 'Research writing support' ?></li>
                                            <li><?= $translations['ai_insights'] ?? 'Pattern recognition and insights' ?></li>
                                        </ul>
                                        
                                        <div class="alert alert-warning">
                                            <i class="bi bi-exclamation-triangle me-2"></i>
                                            <?= $translations['ai_disclaimer'] ?? 'The AI Assistant is a tool to support your research, not replace human analysis and critical thinking.' ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#ai2">
                                        <?= $translations['ai_usage'] ?? 'How to Use AI Assistant' ?>
                                    </button>
                                </h2>
                                <div id="ai2" class="accordion-collapse collapse" data-bs-parent="#aiAccordion">
                                    <div class="accordion-body">
                                        <ol>
                                            <li><?= $translations['ai_usage_step1'] ?? 'Navigate to the AI Assistant section' ?></li>
                                            <li><?= $translations['ai_usage_step2'] ?? 'Choose your research task or upload data' ?></li>
                                            <li><?= $translations['ai_usage_step3'] ?? 'Provide context and specific questions' ?></li>
                                            <li><?= $translations['ai_usage_step4'] ?? 'Review and refine AI suggestions' ?></li>
                                            <li><?= $translations['ai_usage_step5'] ?? 'Export results and integrate into your research' ?></li>
                                        </ol>
                                        
                                        <h6><?= $translations['ai_best_practices'] ?? 'Best Practices:' ?></h6>
                                        <ul>
                                            <li><?= $translations['ai_practice1'] ?? 'Always review AI-generated content critically' ?></li>
                                            <li><?= $translations['ai_practice2'] ?? 'Provide clear, specific instructions' ?></li>
                                            <li><?= $translations['ai_practice3'] ?? 'Use AI as a starting point, not final output' ?></li>
                                            <li><?= $translations['ai_practice4'] ?? 'Maintain research ethics and transparency' ?></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Account & Settings Section -->
                <div class="card border-0 shadow-sm mb-4" id="account-settings">
                    <div class="card-header bg-transparent border-0">
                        <h4 class="mb-0">
                            <i class="bi bi-gear text-info me-2"></i>
                            <?= $translations['account_settings'] ?? 'Account & Settings' ?>
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="accordion" id="accountAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#acc1">
                                        <?= $translations['profile_management'] ?? 'Profile Management' ?>
                                    </button>
                                </h2>
                                <div id="acc1" class="accordion-collapse collapse show" data-bs-parent="#accountAccordion">
                                    <div class="accordion-body">
                                        <h6><?= $translations['update_profile'] ?? 'Updating Your Profile:' ?></h6>
                                        <ol>
                                            <li><?= $translations['profile_step1'] ?? 'Go to Settings > Profile' ?></li>
                                            <li><?= $translations['profile_step2'] ?? 'Update your personal information' ?></li>
                                            <li><?= $translations['profile_step3'] ?? 'Upload a profile picture' ?></li>
                                            <li><?= $translations['profile_step4'] ?? 'Add your bio and expertise areas' ?></li>
                                            <li><?= $translations['profile_step5'] ?? 'Save your changes' ?></li>
                                        </ol>
                                        
                                        <h6><?= $translations['privacy_settings'] ?? 'Privacy Settings:' ?></h6>
                                        <ul>
                                            <li><?= $translations['privacy_profile'] ?? 'Control profile visibility' ?></li>
                                            <li><?= $translations['privacy_content'] ?? 'Manage content sharing permissions' ?></li>
                                            <li><?= $translations['privacy_notifications'] ?? 'Customize notification preferences' ?></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#acc2">
                                        <?= $translations['subscription_management'] ?? 'Subscription Management' ?>
                                    </button>
                                </h2>
                                <div id="acc2" class="accordion-collapse collapse" data-bs-parent="#accountAccordion">
                                    <div class="accordion-body">
                                        <h6><?= $translations['current_plan'] ?? 'Current Plan:' ?></h6>
                                        <p><?= $translations['plan_info'] ?? 'View your current subscription plan and billing information in Settings > Account.' ?></p>
                                        
                                        <h6><?= $translations['upgrade_downgrade'] ?? 'Upgrading/Downgrading:' ?></h6>
                                        <ol>
                                            <li><?= $translations['plan_step1'] ?? 'Go to Settings > Account' ?></li>
                                            <li><?= $translations['plan_step2'] ?? 'Click "Manage Subscription"' ?></li>
                                            <li><?= $translations['plan_step3'] ?? 'Choose your new plan' ?></li>
                                            <li><?= $translations['plan_step4'] ?? 'Complete the payment process' ?></li>
                                        </ol>
                                        
                                        <div class="alert alert-info">
                                            <i class="bi bi-info-circle me-2"></i>
                                            <?= $translations['plan_note'] ?? 'Plan changes are prorated and take effect immediately.' ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Contact Support -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body text-center p-4">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <i class="bi bi-headset text-primary fs-4"></i>
                        </div>
                        <h5 class="card-title"><?= $translations['need_help'] ?? 'Need More Help?' ?></h5>
                        <p class="text-muted"><?= $translations['contact_support_desc'] ?? 'Can\'t find what you\'re looking for? Our support team is here to help.' ?></p>
                        <div class="d-grid gap-2">
                            <a href="contact.php?lang=<?= urlencode($lang) ?>" class="btn btn-primary">
                                <i class="bi bi-envelope me-2"></i><?= $translations['contact_support'] ?? 'Contact Support' ?>
                            </a>
                            <a href="faq.php?lang=<?= urlencode($lang) ?>" class="btn btn-outline-primary">
                                <i class="bi bi-question-circle me-2"></i><?= $translations['view_faq'] ?? 'View FAQ' ?>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Popular Articles -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-transparent border-0">
                        <h5 class="mb-0">
                            <i class="bi bi-fire text-warning me-2"></i>
                            <?= $translations['popular_articles'] ?? 'Popular Articles' ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-3">
                                <a href="#" class="text-decoration-none d-flex align-items-center">
                                    <i class="bi bi-file-text text-primary me-2"></i>
                                    <span><?= $translations['popular_article1'] ?? 'How to join your first webinar' ?></span>
                                </a>
                            </li>
                            <li class="mb-3">
                                <a href="#" class="text-decoration-none d-flex align-items-center">
                                    <i class="bi bi-file-text text-primary me-2"></i>
                                    <span><?= $translations['popular_article2'] ?? 'Using the AI research assistant' ?></span>
                                </a>
                            </li>
                            <li class="mb-3">
                                <a href="#" class="text-decoration-none d-flex align-items-center">
                                    <i class="bi bi-file-text text-primary me-2"></i>
                                    <span><?= $translations['popular_article3'] ?? 'Managing your subscription' ?></span>
                                </a>
                            </li>
                            <li class="mb-3">
                                <a href="#" class="text-decoration-none d-flex align-items-center">
                                    <i class="bi bi-file-text text-primary me-2"></i>
                                    <span><?= $translations['popular_article4'] ?? 'Privacy and security settings' ?></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Video Tutorials -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent border-0">
                        <h5 class="mb-0">
                            <i class="bi bi-play-circle text-success me-2"></i>
                            <?= $translations['video_tutorials'] ?? 'Video Tutorials' ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="video-thumbnail">
                                    <img src="assets/images/tutorial-1.jpg" alt="Getting Started" class="img-fluid rounded">
                                    <div class="play-button">
                                        <i class="bi bi-play-fill"></i>
                                    </div>
                                    <p class="mt-2 mb-0 small"><?= $translations['tutorial1'] ?? 'Getting Started with TQRS' ?></p>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="video-thumbnail">
                                    <img src="assets/images/tutorial-2.jpg" alt="AI Assistant" class="img-fluid rounded">
                                    <div class="play-button">
                                        <i class="bi bi-play-fill"></i>
                                    </div>
                                    <p class="mt-2 mb-0 small"><?= $translations['tutorial2'] ?? 'Using the AI Research Assistant' ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

    <!-- Custom CSS for Help Center -->
    <style>
        .help-category-card {
            transition: all 0.3s ease;
            border-radius: 16px;
        }
        
        .help-category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
        }
        
        .accordion-button:not(.collapsed) {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .accordion-button:focus {
            box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
        }
        
        .accordion-item {
            border: none;
            margin-bottom: 1rem;
            border-radius: 12px !important;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        
        .accordion-button {
            border-radius: 12px !important;
            font-weight: 600;
            padding: 1.25rem;
        }
        
        .accordion-body {
            padding: 1.5rem;
            background: #f8f9fa;
        }
        
        .input-group {
            border-radius: 25px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .input-group-text {
            border: none;
            background: white;
        }
        
        .form-control {
            border: none;
            padding: 0.75rem 1rem;
        }
        
        .form-control:focus {
            box-shadow: none;
        }
        
        .video-thumbnail {
            position: relative;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .video-thumbnail:hover {
            transform: scale(1.02);
        }
        
        .play-button {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(0,0,0,0.7);
            color: white;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
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

    <!-- Help Center JavaScript -->
    <script>
        // Search functionality
        document.getElementById('helpSearch').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const accordionItems = document.querySelectorAll('.accordion-item');
            
            accordionItems.forEach(item => {
                const content = item.textContent.toLowerCase();
                if (content.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
        
        // Smooth scroll to sections
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
        
        // Video thumbnail click handlers
        document.querySelectorAll('.video-thumbnail').forEach(thumbnail => {
            thumbnail.addEventListener('click', function() {
                // Simulate video player opening
                alert('<?= $translations['video_player'] ?? 'Video player would open here' ?>');
            });
        });
    </script>
</body>
</html> 