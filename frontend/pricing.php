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
    <title><?= $translations['pricing_title'] ?? 'Pricing Plans - TQRS' ?></title>
    <meta name="description" content="<?= $translations['pricing_description'] ?? 'Choose the perfect plan for your research needs' ?>">
    
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
                <i class="bi bi-tags text-primary me-3"></i>
                <?= $translations['pricing_plans'] ?? 'Pricing Plans' ?>
            </h1>
            <p class="lead text-muted mb-4">
                <?= $translations['pricing_subtitle'] ?? 'Choose the perfect plan for your research journey' ?>
            </p>
            
            <!-- Billing Toggle -->
            <div class="d-flex justify-content-center align-items-center mb-4">
                <span class="me-3"><?= $translations['monthly'] ?? 'Monthly' ?></span>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="billingToggle">
                    <label class="form-check-label" for="billingToggle"></label>
                </div>
                <span class="ms-3"><?= $translations['yearly'] ?? 'Yearly' ?></span>
                <span class="badge bg-success ms-2"><?= $translations['save_20'] ?? 'Save 20%' ?></span>
            </div>
        </div>

        <!-- Pricing Cards -->
        <div class="row g-4 justify-content-center">
            <!-- Free Plan -->
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm pricing-card">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                <i class="bi bi-person text-primary fs-4"></i>
                            </div>
                            <h4 class="card-title"><?= $translations['free'] ?? 'Free' ?></h4>
                            <div class="pricing-amount">
                                <span class="display-6 fw-bold">$0</span>
                                <span class="text-muted">/<?= $translations['month'] ?? 'month' ?></span>
                            </div>
                            <p class="text-muted"><?= $translations['free_plan_description'] ?? 'Perfect for getting started' ?></p>
                        </div>
                        
                        <ul class="list-unstyled mb-4">
                            <li class="mb-3">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <?= $translations['access_basic_webinars'] ?? 'Access to basic webinars' ?>
                            </li>
                            <li class="mb-3">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <?= $translations['basic_resources'] ?? 'Basic research resources' ?>
                            </li>
                            <li class="mb-3">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <?= $translations['community_access'] ?? 'Community access' ?>
                            </li>
                            <li class="mb-3">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <?= $translations['email_support'] ?? 'Email support' ?>
                            </li>
                            <li class="mb-3 text-muted">
                                <i class="bi bi-x-circle me-2"></i>
                                <?= $translations['no_premium_webinars'] ?? 'Premium webinars' ?>
                            </li>
                            <li class="mb-3 text-muted">
                                <i class="bi bi-x-circle me-2"></i>
                                <?= $translations['no_ai_assistant'] ?? 'AI research assistant' ?>
                            </li>
                        </ul>
                        
                        <div class="text-center">
                            <a href="register.php?lang=<?= urlencode($lang) ?>" class="btn btn-outline-primary w-100">
                                <?= $translations['get_started'] ?? 'Get Started' ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pro Plan -->
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow pricing-card featured">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                <i class="bi bi-star text-white fs-4"></i>
                            </div>
                            <h4 class="card-title"><?= $translations['pro'] ?? 'Pro' ?></h4>
                            <div class="pricing-amount">
                                <span class="display-6 fw-bold">$29</span>
                                <span class="text-muted">/<?= $translations['month'] ?? 'month' ?></span>
                            </div>
                            <p class="text-muted"><?= $translations['pro_plan_description'] ?? 'Most popular choice' ?></p>
                            <span class="badge bg-primary"><?= $translations['popular'] ?? 'Popular' ?></span>
                        </div>
                        
                        <ul class="list-unstyled mb-4">
                            <li class="mb-3">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <?= $translations['all_free_features'] ?? 'Everything in Free' ?>
                            </li>
                            <li class="mb-3">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <?= $translations['premium_webinars'] ?? 'Premium webinars' ?>
                            </li>
                            <li class="mb-3">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <?= $translations['advanced_resources'] ?? 'Advanced research resources' ?>
                            </li>
                            <li class="mb-3">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <?= $translations['ai_assistant'] ?? 'AI research assistant' ?>
                            </li>
                            <li class="mb-3">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <?= $translations['priority_support'] ?? 'Priority support' ?>
                            </li>
                            <li class="mb-3">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <?= $translations['certificate_access'] ?? 'Certificate access' ?>
                            </li>
                        </ul>
                        
                        <div class="text-center">
                            <a href="register.php?plan=pro&lang=<?= urlencode($lang) ?>" class="btn btn-primary w-100">
                                <?= $translations['choose_pro'] ?? 'Choose Pro' ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enterprise Plan -->
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm pricing-card">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <div class="bg-success rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                <i class="bi bi-building text-white fs-4"></i>
                            </div>
                            <h4 class="card-title"><?= $translations['enterprise'] ?? 'Enterprise' ?></h4>
                            <div class="pricing-amount">
                                <span class="display-6 fw-bold">$99</span>
                                <span class="text-muted">/<?= $translations['month'] ?? 'month' ?></span>
                            </div>
                            <p class="text-muted"><?= $translations['enterprise_plan_description'] ?? 'For teams and organizations' ?></p>
                        </div>
                        
                        <ul class="list-unstyled mb-4">
                            <li class="mb-3">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <?= $translations['all_pro_features'] ?? 'Everything in Pro' ?>
                            </li>
                            <li class="mb-3">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <?= $translations['team_management'] ?? 'Team management' ?>
                            </li>
                            <li class="mb-3">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <?= $translations['custom_webinars'] ?? 'Custom webinars' ?>
                            </li>
                            <li class="mb-3">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <?= $translations['dedicated_support'] ?? 'Dedicated support' ?>
                            </li>
                            <li class="mb-3">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <?= $translations['analytics_dashboard'] ?? 'Analytics dashboard' ?>
                            </li>
                            <li class="mb-3">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <?= $translations['api_access'] ?? 'API access' ?>
                            </li>
                        </ul>
                        
                        <div class="text-center">
                            <a href="contact.php?plan=enterprise&lang=<?= urlencode($lang) ?>" class="btn btn-outline-success w-100">
                                <?= $translations['contact_sales'] ?? 'Contact Sales' ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Comparison -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent border-0">
                        <h3 class="text-center mb-0">
                            <i class="bi bi-list-check text-primary me-2"></i>
                            <?= $translations['feature_comparison'] ?? 'Feature Comparison' ?>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th><?= $translations['feature'] ?? 'Feature' ?></th>
                                        <th class="text-center"><?= $translations['free'] ?? 'Free' ?></th>
                                        <th class="text-center"><?= $translations['pro'] ?? 'Pro' ?></th>
                                        <th class="text-center"><?= $translations['enterprise'] ?? 'Enterprise' ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?= $translations['webinars_per_month'] ?? 'Webinars per month' ?></td>
                                        <td class="text-center">5</td>
                                        <td class="text-center">Unlimited</td>
                                        <td class="text-center">Unlimited</td>
                                    </tr>
                                    <tr>
                                        <td><?= $translations['ai_research_assistant'] ?? 'AI Research Assistant' ?></td>
                                        <td class="text-center"><i class="bi bi-x text-muted"></i></td>
                                        <td class="text-center"><i class="bi bi-check text-success"></i></td>
                                        <td class="text-center"><i class="bi bi-check text-success"></i></td>
                                    </tr>
                                    <tr>
                                        <td><?= $translations['premium_resources'] ?? 'Premium Resources' ?></td>
                                        <td class="text-center"><i class="bi bi-x text-muted"></i></td>
                                        <td class="text-center"><i class="bi bi-check text-success"></i></td>
                                        <td class="text-center"><i class="bi bi-check text-success"></i></td>
                                    </tr>
                                    <tr>
                                        <td><?= $translations['certificate_access'] ?? 'Certificate Access' ?></td>
                                        <td class="text-center"><i class="bi bi-x text-muted"></i></td>
                                        <td class="text-center"><i class="bi bi-check text-success"></i></td>
                                        <td class="text-center"><i class="bi bi-check text-success"></i></td>
                                    </tr>
                                    <tr>
                                        <td><?= $translations['priority_support'] ?? 'Priority Support' ?></td>
                                        <td class="text-center"><i class="bi bi-x text-muted"></i></td>
                                        <td class="text-center"><i class="bi bi-check text-success"></i></td>
                                        <td class="text-center"><i class="bi bi-check text-success"></i></td>
                                    </tr>
                                    <tr>
                                        <td><?= $translations['team_management'] ?? 'Team Management' ?></td>
                                        <td class="text-center"><i class="bi bi-x text-muted"></i></td>
                                        <td class="text-center"><i class="bi bi-x text-muted"></i></td>
                                        <td class="text-center"><i class="bi bi-check text-success"></i></td>
                                    </tr>
                                    <tr>
                                        <td><?= $translations['custom_webinars'] ?? 'Custom Webinars' ?></td>
                                        <td class="text-center"><i class="bi bi-x text-muted"></i></td>
                                        <td class="text-center"><i class="bi bi-x text-muted"></i></td>
                                        <td class="text-center"><i class="bi bi-check text-success"></i></td>
                                    </tr>
                                    <tr>
                                        <td><?= $translations['api_access'] ?? 'API Access' ?></td>
                                        <td class="text-center"><i class="bi bi-x text-muted"></i></td>
                                        <td class="text-center"><i class="bi bi-x text-muted"></i></td>
                                        <td class="text-center"><i class="bi bi-check text-success"></i></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="row mt-5">
            <div class="col-lg-8 mx-auto">
                <div class="text-center mb-4">
                    <h3><?= $translations['pricing_faq'] ?? 'Frequently Asked Questions' ?></h3>
                    <p class="text-muted"><?= $translations['pricing_faq_subtitle'] ?? 'Common questions about our pricing plans' ?></p>
                </div>
                
                <div class="accordion" id="pricingFAQ">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                <?= $translations['can_change_plan'] ?? 'Can I change my plan later?' ?>
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#pricingFAQ">
                            <div class="accordion-body">
                                <?= $translations['plan_change_answer'] ?? 'Yes, you can upgrade or downgrade your plan at any time. Changes will be prorated and reflected in your next billing cycle.' ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                <?= $translations['refund_policy'] ?? 'What is your refund policy?' ?>
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#pricingFAQ">
                            <div class="accordion-body">
                                <?= $translations['refund_policy_answer'] ?? 'We offer a 30-day money-back guarantee. If you\'re not satisfied with your subscription, contact us within 30 days for a full refund.' ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                <?= $translations['team_discounts'] ?? 'Do you offer team discounts?' ?>
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#pricingFAQ">
                            <div class="accordion-body">
                                <?= $translations['team_discounts_answer'] ?? 'Yes, we offer volume discounts for teams of 5 or more users. Contact our sales team for custom pricing and enterprise solutions.' ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="row mt-5">
            <div class="col-lg-8 mx-auto">
                <div class="card bg-gradient-primary text-white text-center">
                    <div class="card-body py-5">
                        <h3 class="mb-3">
                            <i class="bi bi-rocket me-2"></i>
                            <?= $translations['ready_to_start'] ?? 'Ready to start your research journey?' ?>
                        </h3>
                        <p class="mb-4"><?= $translations['cta_description'] ?? 'Join thousands of researchers who trust TQRS for their qualitative research needs.' ?></p>
                        <div class="d-flex gap-3 justify-content-center flex-wrap">
                            <a href="register.php?lang=<?= urlencode($lang) ?>" class="btn btn-light btn-lg">
                                <i class="bi bi-person-plus me-2"></i><?= $translations['start_free_trial'] ?? 'Start Free Trial' ?>
                            </a>
                            <a href="contact.php?lang=<?= urlencode($lang) ?>" class="btn btn-outline-light btn-lg">
                                <i class="bi bi-chat-dots me-2"></i><?= $translations['talk_to_sales'] ?? 'Talk to Sales' ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

    <!-- Custom CSS for Pricing -->
    <style>
        .pricing-card {
            transition: all 0.3s ease;
            border-radius: 16px;
        }
        
        .pricing-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
        }
        
        .pricing-card.featured {
            border: 2px solid #667eea;
            transform: scale(1.05);
        }
        
        .pricing-card.featured:hover {
            transform: scale(1.05) translateY(-10px);
        }
        
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }
        
        .form-switch .form-check-input {
            width: 3rem;
            height: 1.5rem;
        }
        
        .table th {
            border-top: none;
            font-weight: 600;
        }
        
        .accordion-button:not(.collapsed) {
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
        
        .pricing-amount {
            margin: 1rem 0;
        }
        
        .badge {
            border-radius: 20px;
            padding: 0.5rem 1rem;
        }
    </style>

    <!-- Pricing JavaScript -->
    <script>
        // Billing toggle functionality
        document.getElementById('billingToggle').addEventListener('change', function() {
            const isYearly = this.checked;
            const monthlyPrices = document.querySelectorAll('.pricing-amount .display-6');
            const monthlyTexts = document.querySelectorAll('.pricing-amount .text-muted');
            
            if (isYearly) {
                // Show yearly prices (20% discount)
                monthlyPrices.forEach((price, index) => {
                    const originalPrice = parseInt(price.textContent.replace('$', ''));
                    if (originalPrice > 0) {
                        const yearlyPrice = Math.round(originalPrice * 0.8);
                        price.textContent = '$' + yearlyPrice;
                    }
                });
                
                monthlyTexts.forEach(text => {
                    text.textContent = '/year';
                });
            } else {
                // Show monthly prices
                const originalPrices = [0, 29, 99];
                monthlyPrices.forEach((price, index) => {
                    price.textContent = '$' + originalPrices[index];
                });
                
                monthlyTexts.forEach(text => {
                    text.textContent = '/month';
                });
            }
        });
        
        // Smooth scroll to pricing cards
        function scrollToPricing() {
            document.querySelector('.pricing-card').scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }
    </script>
</body>
</html> 