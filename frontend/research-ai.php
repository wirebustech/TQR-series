<?php
session_start();

$pageTitle = 'Research AI App - Coming Soon - TQRS';
$pageDescription = 'Introducing the Research AI App - The next generation of qualitative research tools powered by artificial intelligence.';

include_once __DIR__ . '/includes/header.php';

$texts = [
    'headline' => 'Introducing the Research AI App',
    'subtitle' => 'The next generation of qualitative research is almost here!',
    'teaser' => 'Our AI-powered app will help you analyze, code, and visualize qualitative data faster and smarter than ever before.',
    'cta' => 'Be among the first to experience the future of research. Sign up for early beta access and get a front-row seat when we launch!',
    'emailLabel' => 'Email address',
    'emailPlaceholder' => 'you@example.com',
    'signupBtn' => 'Subscribe for Beta',
    'signupSuccess' => 'Thank you for subscribing! You will be notified when the beta launches.',
    'signupError' => 'Subscription failed. Please try again.',
    'networkError' => 'Network error. Please try again later.',
    'featuresTitle' => 'What to Expect',
    'feature1Title' => 'AI-Powered Coding',
    'feature1Desc' => 'Automated theme discovery and intelligent coding suggestions',
    'feature2Title' => 'Data Visualization',
    'feature2Desc' => 'Instant qualitative data visualization and insights',
    'feature3Title' => 'Team Collaboration',
    'feature3Desc' => 'Real-time collaboration tools for research teams',
    'feature4Title' => 'Privacy First',
    'feature4Desc' => 'Secure, privacy-first data handling and storage',
    'feature5Title' => 'Global Support',
    'feature5Desc' => 'Multi-language support for global research projects',
    'feature6Title' => 'Smart Analysis',
    'feature6Desc' => 'Advanced AI algorithms for pattern recognition',
    'comingSoon' => 'Coming Soon',
    'betaAccess' => 'Beta Access',
    'learnMore' => 'Learn More',
    'getNotified' => 'Get Notified',
    'joinWaitlist' => 'Join Waitlist',
    'demoRequest' => 'Request Demo',
    'pricing' => 'Pricing',
    'documentation' => 'Documentation',
    'support' => 'Support',
    'privacyPolicy' => 'Privacy Policy',
    'termsOfService' => 'Terms of Service',
    'contactUs' => 'Contact Us',
    'followUs' => 'Follow Us',
    'newsletter' => 'Newsletter',
    'newsletterText' => 'Stay updated with the latest developments',
    'newsletterPlaceholder' => 'Enter your email',
    'newsletterBtn' => 'Subscribe',
    'newsletterSuccess' => 'Thank you for subscribing to our newsletter!',
    'newsletterError' => 'Newsletter subscription failed. Please try again.',
    'countdownTitle' => 'Launch Countdown',
    'days' => 'Days',
    'hours' => 'Hours',
    'minutes' => 'Minutes',
    'seconds' => 'Seconds',
    'launchDate' => 'Launch Date',
    'launchDateValue' => 'Q2 2024',
    'earlyAccess' => 'Early Access',
    'earlyAccessText' => 'Get exclusive early access to beta features',
    'pricingPlans' => 'Pricing Plans',
    'free' => 'Free',
    'pro' => 'Pro',
    'enterprise' => 'Enterprise',
    'freeFeatures' => [
        'Basic AI coding assistance',
        'Up to 100 documents',
        'Standard support',
        'Community access'
    ],
    'proFeatures' => [
        'Advanced AI features',
        'Unlimited documents',
        'Priority support',
        'Team collaboration',
        'Advanced analytics',
        'API access'
    ],
    'enterpriseFeatures' => [
        'Custom AI models',
        'White-label solution',
        'Dedicated support',
        'Custom integrations',
        'On-premise deployment',
        'SLA guarantee'
    ],
    'monthly' => 'Monthly',
    'yearly' => 'Yearly',
    'save' => 'Save',
    'perMonth' => '/month',
    'perYear' => '/year',
    'getStarted' => 'Get Started',
    'contactSales' => 'Contact Sales',
    'mostPopular' => 'Most Popular',
    'testimonials' => 'What Researchers Say',
    'testimonial1' => 'This AI tool has revolutionized how we analyze qualitative data. The speed and accuracy are incredible.',
    'testimonial1Author' => 'Dr. Sarah Johnson',
    'testimonial1Role' => 'Research Director, University of Research',
    'testimonial2' => 'The collaboration features make it easy for our team to work together on complex research projects.',
    'testimonial2Author' => 'Dr. Michael Chen',
    'testimonial2Role' => 'Principal Investigator, Research Institute',
    'testimonial3' => 'Privacy and security were our top concerns, and this platform exceeds our expectations.',
    'testimonial3Author' => 'Emily Rodriguez',
    'testimonial3Role' => 'Data Protection Officer, Healthcare Research'
];
if ($lang !== 'en') {
    foreach ($texts as $k => $v) {
        $texts[$k] = translateText($v, $lang, 'en');
    }
}
?>

<!-- Hero Section -->
<div class="bg-gradient-primary text-white py-5">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <div class="mb-4">
                    <i class="bi bi-robot display-1 text-warning"></i>
                </div>
                <h1 class="display-4 fw-bold mb-3"><?= htmlspecialchars($texts['headline']) ?></h1>
                <p class="lead mb-2"><?= htmlspecialchars($texts['subtitle']) ?></p>
                <p class="mb-4"><?= htmlspecialchars($texts['teaser']) ?></p>
                <p class="mb-4"><?= htmlspecialchars($texts['cta']) ?></p>
                
                <!-- Beta Signup Form -->
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card border-0 shadow-lg">
                            <div class="card-body p-4">
                                <form id="betaSignupForm" method="POST" action="research-ai.php?lang=<?= urlencode($lang) ?>">
                                    <div class="mb-3">
                                        <label for="betaEmail" class="form-label"><?= htmlspecialchars($texts['emailLabel']) ?></label>
                                        <input type="email" class="form-control form-control-lg" id="betaEmail" name="email" 
                                               placeholder="<?= htmlspecialchars($texts['emailPlaceholder']) ?>" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-lg w-100">
                                        <i class="bi bi-rocket-takeoff"></i> <?= htmlspecialchars($texts['signupBtn']) ?>
                                    </button>
                                    <div id="signupMsg" class="mt-3"></div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Countdown Section -->
<div class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h2 class="fw-bold mb-4"><?= htmlspecialchars($texts['countdownTitle']) ?></h2>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="display-6 fw-bold text-primary" id="days">00</div>
                                <div class="text-muted"><?= htmlspecialchars($texts['days']) ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="display-6 fw-bold text-primary" id="hours">00</div>
                                <div class="text-muted"><?= htmlspecialchars($texts['hours']) ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="display-6 fw-bold text-primary" id="minutes">00</div>
                                <div class="text-muted"><?= htmlspecialchars($texts['minutes']) ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="display-6 fw-bold text-primary" id="seconds">00</div>
                                <div class="text-muted"><?= htmlspecialchars($texts['seconds']) ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="text-muted mt-3">
                    <strong><?= htmlspecialchars($texts['launchDate']) ?>:</strong> <?= htmlspecialchars($texts['launchDateValue']) ?>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="py-5">
    <div class="container">
        <div class="row justify-content-center text-center mb-5">
            <div class="col-lg-8">
                <h2 class="fw-bold mb-3"><?= htmlspecialchars($texts['featuresTitle']) ?></h2>
                <p class="lead text-muted">Discover the powerful features that will transform your qualitative research</p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-brain text-primary fs-1 mb-3"></i>
                        <h5 class="card-title"><?= htmlspecialchars($texts['feature1Title']) ?></h5>
                        <p class="card-text text-muted"><?= htmlspecialchars($texts['feature1Desc']) ?></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-graph-up text-success fs-1 mb-3"></i>
                        <h5 class="card-title"><?= htmlspecialchars($texts['feature2Title']) ?></h5>
                        <p class="card-text text-muted"><?= htmlspecialchars($texts['feature2Desc']) ?></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-people text-info fs-1 mb-3"></i>
                        <h5 class="card-title"><?= htmlspecialchars($texts['feature3Title']) ?></h5>
                        <p class="card-text text-muted"><?= htmlspecialchars($texts['feature3Desc']) ?></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-shield-check text-warning fs-1 mb-3"></i>
                        <h5 class="card-title"><?= htmlspecialchars($texts['feature4Title']) ?></h5>
                        <p class="card-text text-muted"><?= htmlspecialchars($texts['feature4Desc']) ?></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-globe text-danger fs-1 mb-3"></i>
                        <h5 class="card-title"><?= htmlspecialchars($texts['feature5Title']) ?></h5>
                        <p class="card-text text-muted"><?= htmlspecialchars($texts['feature5Desc']) ?></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-lightbulb text-primary fs-1 mb-3"></i>
                        <h5 class="card-title"><?= htmlspecialchars($texts['feature6Title']) ?></h5>
                        <p class="card-text text-muted"><?= htmlspecialchars($texts['feature6Desc']) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pricing Section -->
<div class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center text-center mb-5">
            <div class="col-lg-8">
                <h2 class="fw-bold mb-3"><?= htmlspecialchars($texts['pricingPlans']) ?></h2>
                <p class="lead text-muted">Choose the plan that fits your research needs</p>
            </div>
        </div>
        
        <div class="row">
            <!-- Free Plan -->
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h5 class="card-title"><?= htmlspecialchars($texts['free']) ?></h5>
                        <div class="mb-3">
                            <span class="display-6 fw-bold">$0</span>
                            <span class="text-muted"><?= htmlspecialchars($texts['perMonth']) ?></span>
                        </div>
                        <ul class="list-unstyled">
                            <?php foreach ($texts['freeFeatures'] as $feature): ?>
                                <li class="mb-2">
                                    <i class="bi bi-check text-success me-2"></i>
                                    <?= htmlspecialchars($feature) ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <button class="btn btn-outline-primary w-100"><?= htmlspecialchars($texts['getStarted']) ?></button>
                    </div>
                </div>
            </div>
            
            <!-- Pro Plan -->
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-lg h-100 position-relative">
                    <div class="position-absolute top-0 start-50 translate-middle-x">
                        <span class="badge bg-primary"><?= htmlspecialchars($texts['mostPopular']) ?></span>
                    </div>
                    <div class="card-body p-4">
                        <h5 class="card-title"><?= htmlspecialchars($texts['pro']) ?></h5>
                        <div class="mb-3">
                            <span class="display-6 fw-bold">$29</span>
                            <span class="text-muted"><?= htmlspecialchars($texts['perMonth']) ?></span>
                        </div>
                        <ul class="list-unstyled">
                            <?php foreach ($texts['proFeatures'] as $feature): ?>
                                <li class="mb-2">
                                    <i class="bi bi-check text-success me-2"></i>
                                    <?= htmlspecialchars($feature) ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <button class="btn btn-primary w-100"><?= htmlspecialchars($texts['getStarted']) ?></button>
                    </div>
                </div>
            </div>
            
            <!-- Enterprise Plan -->
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h5 class="card-title"><?= htmlspecialchars($texts['enterprise']) ?></h5>
                        <div class="mb-3">
                            <span class="display-6 fw-bold"><?= htmlspecialchars($texts['contactSales']) ?></span>
                        </div>
                        <ul class="list-unstyled">
                            <?php foreach ($texts['enterpriseFeatures'] as $feature): ?>
                                <li class="mb-2">
                                    <i class="bi bi-check text-success me-2"></i>
                                    <?= htmlspecialchars($feature) ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <button class="btn btn-outline-primary w-100"><?= htmlspecialchars($texts['contactSales']) ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Testimonials Section -->
<div class="py-5">
    <div class="container">
        <div class="row justify-content-center text-center mb-5">
            <div class="col-lg-8">
                <h2 class="fw-bold mb-3"><?= htmlspecialchars($texts['testimonials']) ?></h2>
                <p class="lead text-muted">Hear from researchers who have experienced the future of qualitative analysis</p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                        </div>
                        <p class="card-text"><?= htmlspecialchars($texts['testimonial1']) ?></p>
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <img src="assets/images/authors/sarah.jpg" alt="<?= htmlspecialchars($texts['testimonial1Author']) ?>" 
                                     class="rounded-circle" style="width: 48px; height: 48px; object-fit: cover;">
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0"><?= htmlspecialchars($texts['testimonial1Author']) ?></h6>
                                <small class="text-muted"><?= htmlspecialchars($texts['testimonial1Role']) ?></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                        </div>
                        <p class="card-text"><?= htmlspecialchars($texts['testimonial2']) ?></p>
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <img src="assets/images/authors/michael.jpg" alt="<?= htmlspecialchars($texts['testimonial2Author']) ?>" 
                                     class="rounded-circle" style="width: 48px; height: 48px; object-fit: cover;">
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0"><?= htmlspecialchars($texts['testimonial2Author']) ?></h6>
                                <small class="text-muted"><?= htmlspecialchars($texts['testimonial2Role']) ?></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                        </div>
                        <p class="card-text"><?= htmlspecialchars($texts['testimonial3']) ?></p>
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <img src="assets/images/authors/emily.jpg" alt="<?= htmlspecialchars($texts['testimonial3Author']) ?>" 
                                     class="rounded-circle" style="width: 48px; height: 48px; object-fit: cover;">
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0"><?= htmlspecialchars($texts['testimonial3Author']) ?></h6>
                                <small class="text-muted"><?= htmlspecialchars($texts['testimonial3Role']) ?></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Newsletter Section -->
<div class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-6">
                <h3 class="fw-bold mb-3"><?= htmlspecialchars($texts['newsletter']) ?></h3>
                <p class="mb-4"><?= htmlspecialchars($texts['newsletterText']) ?></p>
                <form id="newsletterForm" class="d-flex gap-2">
                    <input type="email" class="form-control" placeholder="<?= htmlspecialchars($texts['newsletterPlaceholder']) ?>" required>
                    <button type="submit" class="btn btn-light">
                        <?= htmlspecialchars($texts['newsletterBtn']) ?>
                    </button>
                </form>
                <div id="newsletterMsg" class="mt-3"></div>
            </div>
        </div>
    </div>
</div>

<script>
// Countdown timer
function updateCountdown() {
    const launchDate = new Date('2024-06-01T00:00:00').getTime();
    const now = new Date().getTime();
    const distance = launchDate - now;
    
    if (distance > 0) {
        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
        document.getElementById('days').textContent = days.toString().padStart(2, '0');
        document.getElementById('hours').textContent = hours.toString().padStart(2, '0');
        document.getElementById('minutes').textContent = minutes.toString().padStart(2, '0');
        document.getElementById('seconds').textContent = seconds.toString().padStart(2, '0');
    }
}

setInterval(updateCountdown, 1000);
updateCountdown();

// Beta signup form
document.getElementById('betaSignupForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const email = document.getElementById('betaEmail').value;
    const msg = document.getElementById('signupMsg');
    
    // In a real app, this would make an API call
    msg.innerHTML = '<div class="alert alert-success"><?= htmlspecialchars($texts['signupSuccess']) ?></div>';
    this.reset();
});

// Newsletter form
document.getElementById('newsletterForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const email = this.querySelector('input[type="email"]').value;
    const msg = document.getElementById('newsletterMsg');
    
    // In a real app, this would make an API call
    msg.innerHTML = '<div class="alert alert-success"><?= htmlspecialchars($texts['newsletterSuccess']) ?></div>';
    this.reset();
});

// Smooth scrolling for anchor links
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
</script>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-5px);
}

.bi {
    transition: transform 0.2s ease-in-out;
}

.card:hover .bi {
    transform: scale(1.1);
}
</style>

<?php include_once __DIR__ . '/includes/footer.php'; ?> 