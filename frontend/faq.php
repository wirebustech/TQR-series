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
    <title><?= $translations['faq_title'] ?? 'FAQ - TQRS' ?></title>
    <meta name="description" content="<?= $translations['faq_description'] ?? 'Frequently Asked Questions about TQRS platform and services' ?>">
    
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
                <?= $translations['frequently_asked_questions'] ?? 'Frequently Asked Questions' ?>
            </h1>
            <p class="lead text-muted">
                <?= $translations['faq_subtitle'] ?? 'Find answers to common questions about our platform and services' ?>
            </p>
        </div>

        <!-- Search Bar -->
        <div class="row justify-content-center mb-5">
            <div class="col-lg-6">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" class="form-control border-start-0" id="faqSearch" 
                           placeholder="<?= $translations['search_faq'] ?? 'Search questions...' ?>">
                </div>
            </div>
        </div>

        <!-- FAQ Categories -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex flex-wrap gap-2 justify-content-center" id="faqCategories">
                    <button class="btn btn-outline-primary active" data-category="all">
                        <?= $translations['all'] ?? 'All' ?>
                    </button>
                    <button class="btn btn-outline-primary" data-category="general">
                        <i class="bi bi-info-circle me-2"></i><?= $translations['general'] ?? 'General' ?>
                    </button>
                    <button class="btn btn-outline-primary" data-category="webinars">
                        <i class="bi bi-camera-video me-2"></i><?= $translations['webinars'] ?? 'Webinars' ?>
                    </button>
                    <button class="btn btn-outline-primary" data-category="account">
                        <i class="bi bi-person me-2"></i><?= $translations['account'] ?? 'Account' ?>
                    </button>
                    <button class="btn btn-outline-primary" data-category="technical">
                        <i class="bi bi-gear me-2"></i><?= $translations['technical'] ?? 'Technical' ?>
                    </button>
                    <button class="btn btn-outline-primary" data-category="billing">
                        <i class="bi bi-credit-card me-2"></i><?= $translations['billing'] ?? 'Billing' ?>
                    </button>
                </div>
            </div>
        </div>

        <!-- FAQ Content -->
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="accordion" id="faqAccordion">
                    
                    <!-- General Questions -->
                    <div class="accordion-item faq-item" data-category="general">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                <i class="bi bi-info-circle text-primary me-3"></i>
                                <?= $translations['what_is_tqrs'] ?? 'What is TQRS?' ?>
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p><?= $translations['tqrs_explanation'] ?? 'TQRS (The Qualitative Research Series) is a comprehensive platform dedicated to advancing qualitative research methodologies. We provide webinars, resources, tools, and a community for researchers to learn, collaborate, and share insights.' ?></p>
                                <p><?= $translations['tqrs_features'] ?? 'Our platform features live webinars, recorded sessions, research articles, methodology guides, and an AI-powered research assistant to help you excel in your qualitative research projects.' ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item faq-item" data-category="general">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                <i class="bi bi-info-circle text-primary me-3"></i>
                                <?= $translations['who_can_use'] ?? 'Who can use TQRS?' ?>
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p><?= $translations['user_eligibility'] ?? 'TQRS is designed for researchers, academics, students, and professionals interested in qualitative research. This includes:' ?></p>
                                <ul>
                                    <li><?= $translations['academic_researchers'] ?? 'Academic researchers and professors' ?></li>
                                    <li><?= $translations['graduate_students'] ?? 'Graduate and doctoral students' ?></li>
                                    <li><?= $translations['market_researchers'] ?? 'Market research professionals' ?></li>
                                    <li><?= $translations['consultants'] ?? 'Research consultants and analysts' ?></li>
                                    <li><?= $translations['policy_makers'] ?? 'Policy makers and government researchers' ?></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Webinar Questions -->
                    <div class="accordion-item faq-item" data-category="webinars">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                <i class="bi bi-camera-video text-primary me-3"></i>
                                <?= $translations['how_join_webinar'] ?? 'How do I join a webinar?' ?>
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p><?= $translations['webinar_joining_steps'] ?? 'To join a webinar, follow these steps:' ?></p>
                                <ol>
                                    <li><?= $translations['register_account'] ?? 'Register for a TQRS account (free)' ?></li>
                                    <li><?= $translations['browse_webinars'] ?? 'Browse available webinars on our webinars page' ?></li>
                                    <li><?= $translations['click_register'] ?? 'Click "Register" on the webinar you want to attend' ?></li>
                                    <li><?= $translations['receive_confirmation'] ?? 'You\'ll receive a confirmation email with login details' ?></li>
                                    <li><?= $translations['join_live'] ?? 'Join the live session at the scheduled time' ?></li>
                                </ol>
                                <p class="text-muted"><?= $translations['webinar_requirements'] ?? 'Most webinars are free, but some premium sessions may require a subscription.' ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item faq-item" data-category="webinars">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                <i class="bi bi-camera-video text-primary me-3"></i>
                                <?= $translations['webinar_recordings'] ?? 'Are webinars recorded?' ?>
                            </button>
                        </h2>
                        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p><?= $translations['recording_policy'] ?? 'Yes, most webinars are recorded and made available to registered participants within 24-48 hours after the live session.' ?></p>
                                <p><?= $translations['recording_access'] ?? 'You can access recordings through your account dashboard or by visiting the webinar page where you registered.' ?></p>
                                <p class="text-warning"><?= $translations['recording_note'] ?? 'Note: Some webinars may not be recorded due to technical issues or presenter preferences.' ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Account Questions -->
                    <div class="accordion-item faq-item" data-category="account">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                                <i class="bi bi-person text-primary me-3"></i>
                                <?= $translations['create_account'] ?? 'How do I create an account?' ?>
                            </button>
                        </h2>
                        <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p><?= $translations['account_creation_steps'] ?? 'Creating an account is simple and free:' ?></p>
                                <ol>
                                    <li><?= $translations['click_register'] ?? 'Click the "Register" button in the top navigation' ?></li>
                                    <li><?= $translations['fill_form'] ?? 'Fill out the registration form with your details' ?></li>
                                    <li><?= $translations['verify_email'] ?? 'Verify your email address' ?></li>
                                    <li><?= $translations['complete_profile'] ?? 'Complete your profile (optional but recommended)' ?></li>
                                </ol>
                                <p><?= $translations['account_benefits'] ?? 'With a free account, you can register for webinars, access basic resources, and participate in the community.' ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item faq-item" data-category="account">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq6">
                                <i class="bi bi-person text-primary me-3"></i>
                                <?= $translations['reset_password'] ?? 'How do I reset my password?' ?>
                            </button>
                        </h2>
                        <div id="faq6" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p><?= $translations['password_reset_steps'] ?? 'To reset your password:' ?></p>
                                <ol>
                                    <li><?= $translations['go_to_login'] ?? 'Go to the login page' ?></li>
                                    <li><?= $translations['click_forgot_password'] ?? 'Click "Forgot Password?" link' ?></li>
                                    <li><?= $translations['enter_email'] ?? 'Enter your registered email address' ?></li>
                                    <li><?= $translations['check_email'] ?? 'Check your email for reset instructions' ?></li>
                                    <li><?= $translations['create_new_password'] ?? 'Create a new password' ?></li>
                                </ol>
                                <p class="text-info"><?= $translations['password_tip'] ?? 'Tip: Use a strong password with a mix of letters, numbers, and special characters.' ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Technical Questions -->
                    <div class="accordion-item faq-item" data-category="technical">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq7">
                                <i class="bi bi-gear text-primary me-3"></i>
                                <?= $translations['browser_requirements'] ?? 'What browsers are supported?' ?>
                            </button>
                        </h2>
                        <div id="faq7" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p><?= $translations['supported_browsers'] ?? 'TQRS works best with modern browsers:' ?></p>
                                <ul>
                                    <li><strong>Chrome</strong> (version 90+)</li>
                                    <li><strong>Firefox</strong> (version 88+)</li>
                                    <li><strong>Safari</strong> (version 14+)</li>
                                    <li><strong>Edge</strong> (version 90+)</li>
                                </ul>
                                <p class="text-warning"><?= $translations['browser_warning'] ?? 'For the best experience, please update your browser to the latest version.' ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item faq-item" data-category="technical">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq8">
                                <i class="bi bi-gear text-primary me-3"></i>
                                <?= $translations['mobile_support'] ?? 'Does TQRS work on mobile devices?' ?>
                            </button>
                        </h2>
                        <div id="faq8" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p><?= $translations['mobile_compatibility'] ?? 'Yes! TQRS is fully responsive and works great on mobile devices including smartphones and tablets.' ?></p>
                                <p><?= $translations['mobile_features'] ?? 'You can browse webinars, read articles, and access most features from your mobile device. However, for the best webinar experience, we recommend using a desktop or laptop computer.' ?></p>
                                <p class="text-info"><?= $translations['pwa_info'] ?? 'You can also install TQRS as a Progressive Web App (PWA) on your mobile device for an app-like experience.' ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Billing Questions -->
                    <div class="accordion-item faq-item" data-category="billing">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq9">
                                <i class="bi bi-credit-card text-primary me-3"></i>
                                <?= $translations['pricing_plans'] ?? 'What are the pricing plans?' ?>
                            </button>
                        </h2>
                        <div id="faq9" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p><?= $translations['free_tier'] ?? 'TQRS offers a generous free tier with access to:' ?></p>
                                <ul>
                                    <li><?= $translations['free_webinars'] ?? 'Most live webinars' ?></li>
                                    <li><?= $translations['basic_resources'] ?? 'Basic research resources' ?></li>
                                    <li><?= $translations['community_access'] ?? 'Community access' ?></li>
                                </ul>
                                <p><?= $translations['premium_features'] ?? 'Premium plans include:' ?></p>
                                <ul>
                                    <li><?= $translations['exclusive_webinars'] ?? 'Exclusive premium webinars' ?></li>
                                    <li><?= $translations['advanced_resources'] ?? 'Advanced research tools and resources' ?></li>
                                    <li><?= $translations['ai_assistant'] ?? 'AI research assistant access' ?></li>
                                    <li><?= $translations['priority_support'] ?? 'Priority customer support' ?></li>
                                </ul>
                                <p><a href="pricing.php?lang=<?= urlencode($lang) ?>" class="btn btn-primary btn-sm"><?= $translations['view_pricing'] ?? 'View Pricing Plans' ?></a></p>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item faq-item" data-category="billing">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq10">
                                <i class="bi bi-credit-card text-primary me-3"></i>
                                <?= $translations['payment_methods'] ?? 'What payment methods do you accept?' ?>
                            </button>
                        </h2>
                        <div id="faq10" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p><?= $translations['accepted_payments'] ?? 'We accept the following payment methods:' ?></p>
                                <ul>
                                    <li><i class="bi bi-credit-card me-2"></i><?= $translations['credit_cards'] ?? 'All major credit cards (Visa, MasterCard, American Express)' ?></li>
                                    <li><i class="bi bi-paypal me-2"></i><?= $translations['paypal'] ?? 'PayPal' ?></li>
                                    <li><i class="bi bi-bank me-2"></i><?= $translations['bank_transfer'] ?? 'Bank transfer (for annual plans)' ?></li>
                                </ul>
                                <p class="text-success"><?= $translations['secure_payment'] ?? 'All payments are processed securely through Stripe, ensuring your financial information is protected.' ?></p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Still Have Questions -->
        <div class="row mt-5">
            <div class="col-lg-8 mx-auto">
                <div class="card bg-gradient-primary text-white text-center">
                    <div class="card-body py-5">
                        <h3 class="mb-3">
                            <i class="bi bi-chat-dots me-2"></i>
                            <?= $translations['still_have_questions'] ?? 'Still have questions?' ?>
                        </h3>
                        <p class="mb-4"><?= $translations['contact_support'] ?? 'Can\'t find what you\'re looking for? Our support team is here to help!' ?></p>
                        <div class="d-flex gap-3 justify-content-center flex-wrap">
                            <a href="contact.php?lang=<?= urlencode($lang) ?>" class="btn btn-light">
                                <i class="bi bi-envelope me-2"></i><?= $translations['contact_us'] ?? 'Contact Us' ?>
                            </a>
                            <a href="help.php?lang=<?= urlencode($lang) ?>" class="btn btn-outline-light">
                                <i class="bi bi-question-circle me-2"></i><?= $translations['help_center'] ?? 'Help Center' ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

    <!-- Custom CSS for FAQ -->
    <style>
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
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
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
        
        .btn-outline-primary {
            border-radius: 25px;
            transition: all 0.3s ease;
        }
        
        .btn-outline-primary:hover,
        .btn-outline-primary.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: transparent;
            transform: translateY(-2px);
        }
        
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .faq-item.hidden {
            display: none;
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
    </style>

    <!-- FAQ JavaScript -->
    <script>
        // Search functionality
        document.getElementById('faqSearch').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const faqItems = document.querySelectorAll('.faq-item');
            
            faqItems.forEach(item => {
                const question = item.querySelector('.accordion-button').textContent.toLowerCase();
                const answer = item.querySelector('.accordion-body').textContent.toLowerCase();
                
                if (question.includes(searchTerm) || answer.includes(searchTerm)) {
                    item.classList.remove('hidden');
                } else {
                    item.classList.add('hidden');
                }
            });
        });
        
        // Category filtering
        document.querySelectorAll('[data-category]').forEach(button => {
            button.addEventListener('click', function() {
                const category = this.dataset.category;
                
                // Update active button
                document.querySelectorAll('[data-category]').forEach(btn => {
                    btn.classList.remove('active');
                });
                this.classList.add('active');
                
                // Filter FAQ items
                const faqItems = document.querySelectorAll('.faq-item');
                faqItems.forEach(item => {
                    if (category === 'all' || item.dataset.category === category) {
                        item.classList.remove('hidden');
                    } else {
                        item.classList.add('hidden');
                    }
                });
            });
        });
        
        // Smooth scroll to FAQ item when searching
        function scrollToFAQ(searchTerm) {
            const faqItems = document.querySelectorAll('.faq-item');
            for (let item of faqItems) {
                const question = item.querySelector('.accordion-button').textContent.toLowerCase();
                if (question.includes(searchTerm.toLowerCase())) {
                    item.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    break;
                }
            }
        }
    </script>
</body>
</html> 