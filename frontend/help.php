<?php
session_start();

$pageTitle = 'Help Center - TQRS';
$pageDescription = 'Get help and support for using the TQRS platform.';

include_once __DIR__ . '/includes/header.php';

$texts = [
    'helpCenter' => 'Help Center',
    'helpSubtitle' => 'Find answers to common questions and get support',
    'searchHelp' => 'Search help articles...',
    'popularTopics' => 'Popular Topics',
    'gettingStarted' => 'Getting Started',
    'accountManagement' => 'Account Management',
    'webinars' => 'Webinars',
    'articles' => 'Articles',
    'technicalSupport' => 'Technical Support',
    'billing' => 'Billing & Payments',
    'faq' => 'Frequently Asked Questions',
    'contactSupport' => 'Contact Support',
    'stillNeedHelp' => 'Still Need Help?',
    'contactUs' => 'Contact Us',
    'liveChat' => 'Live Chat',
    'emailSupport' => 'Email Support',
    'phoneSupport' => 'Phone Support',
    'tutorials' => 'Tutorials',
    'videoGuides' => 'Video Guides',
    'stepByStep' => 'Step-by-Step Guides',
    'troubleshooting' => 'Troubleshooting',
    'commonIssues' => 'Common Issues',
    'solutions' => 'Solutions',
    'howTo' => 'How To',
    'createAccount' => 'Create an Account',
    'resetPassword' => 'Reset Password',
    'updateProfile' => 'Update Profile',
    'joinWebinar' => 'Join a Webinar',
    'registerWebinar' => 'Register for Webinars',
    'accessRecordings' => 'Access Recordings',
    'downloadMaterials' => 'Download Materials',
    'searchContent' => 'Search Content',
    'useChat' => 'Use Live Chat',
    'reportIssue' => 'Report an Issue',
    'cancelSubscription' => 'Cancel Subscription',
    'updatePayment' => 'Update Payment Method',
    'requestRefund' => 'Request Refund',
    'privacySettings' => 'Privacy Settings',
    'notificationSettings' => 'Notification Settings',
    'languageSettings' => 'Language Settings',
    'mobileApp' => 'Mobile App',
    'browserCompatibility' => 'Browser Compatibility',
    'systemRequirements' => 'System Requirements',
    'downloadSpeed' => 'Download Speed Issues',
    'videoPlayback' => 'Video Playback Issues',
    'audioProblems' => 'Audio Problems',
    'loginIssues' => 'Login Issues',
    'registrationProblems' => 'Registration Problems',
    'paymentIssues' => 'Payment Issues',
    'webinarAccess' => 'Webinar Access Issues',
    'contentAccess' => 'Content Access Issues',
    'searchResults' => 'Search Results',
    'noResults' => 'No results found',
    'tryDifferentKeywords' => 'Try different keywords or browse categories below',
    'relatedArticles' => 'Related Articles',
    'wasThisHelpful' => 'Was this helpful?',
    'yes' => 'Yes',
    'no' => 'No',
    'thankYou' => 'Thank you for your feedback!',
    'submitFeedback' => 'Submit Feedback',
    'feedbackText' => 'Help us improve by sharing your feedback',
    'suggestImprovement' => 'Suggest an Improvement',
    'reportBug' => 'Report a Bug',
    'requestFeature' => 'Request a Feature',
    'generalInquiry' => 'General Inquiry'
];
if ($lang !== 'en') {
    foreach ($texts as $k => $v) {
        $texts[$k] = translateText($v, $lang, 'en');
    }
}

// Mock FAQ data
$faqs = [
    'getting-started' => [
        [
            'question' => 'How do I create an account?',
            'answer' => 'To create an account, click the "Register" button in the top navigation. Fill in your details including name, email, and password. You\'ll receive a confirmation email to verify your account.',
            'tags' => ['account', 'registration']
        ],
        [
            'question' => 'How do I reset my password?',
            'answer' => 'If you\'ve forgotten your password, click "Forgot Password" on the login page. Enter your email address and we\'ll send you a link to reset your password.',
            'tags' => ['account', 'password']
        ],
        [
            'question' => 'How do I update my profile information?',
            'answer' => 'Go to your profile page and click "Edit Profile". You can update your personal information, research area, institution, and other details.',
            'tags' => ['account', 'profile']
        ]
    ],
    'webinars' => [
        [
            'question' => 'How do I register for a webinar?',
            'answer' => 'Browse our webinar listings and click on any webinar that interests you. Click the "Register" button and follow the instructions. Some webinars may require payment.',
            'tags' => ['webinars', 'registration']
        ],
        [
            'question' => 'How do I join a live webinar?',
            'answer' => 'When a webinar is live, you\'ll see a "Join Now" button. Click it to enter the webinar room. Make sure your audio and video are working properly.',
            'tags' => ['webinars', 'live']
        ],
        [
            'question' => 'Can I access webinar recordings?',
            'answer' => 'Yes, webinar recordings are typically available within 24-48 hours after the live event. You can find them in your account dashboard or the webinar details page.',
            'tags' => ['webinars', 'recordings']
        ]
    ],
    'technical' => [
        [
            'question' => 'What browsers are supported?',
            'answer' => 'We support Chrome, Firefox, Safari, and Edge. For the best experience, we recommend using the latest version of Chrome.',
            'tags' => ['technical', 'browsers']
        ],
        [
            'question' => 'What are the system requirements for webinars?',
            'answer' => 'You need a stable internet connection (minimum 1 Mbps), a modern web browser, and speakers or headphones. For interactive features, a microphone and webcam are recommended.',
            'tags' => ['technical', 'requirements']
        ],
        [
            'question' => 'I\'m having audio/video issues during webinars',
            'answer' => 'Check your browser permissions for microphone and camera access. Try refreshing the page or using a different browser. Contact support if issues persist.',
            'tags' => ['technical', 'audio', 'video']
        ]
    ],
    'billing' => [
        [
            'question' => 'How do I update my payment method?',
            'answer' => 'Go to your account settings and select "Payment Methods". You can add, remove, or update your payment information there.',
            'tags' => ['billing', 'payment']
        ],
        [
            'question' => 'Can I get a refund?',
            'answer' => 'Refunds are available within 30 days of purchase for most items. Contact our support team with your order details to request a refund.',
            'tags' => ['billing', 'refund']
        ],
        [
            'question' => 'How do I cancel my subscription?',
            'answer' => 'Go to your account settings and select "Subscriptions". Click "Cancel Subscription" and follow the confirmation steps.',
            'tags' => ['billing', 'subscription']
        ]
    ]
];

// Mock tutorials
$tutorials = [
    [
        'title' => 'Getting Started with TQRS',
        'description' => 'Learn the basics of using our platform',
        'duration' => '5 min',
        'video_url' => '#',
        'category' => 'getting-started'
    ],
    [
        'title' => 'How to Join a Webinar',
        'description' => 'Step-by-step guide to joining live webinars',
        'duration' => '3 min',
        'video_url' => '#',
        'category' => 'webinars'
    ],
    [
        'title' => 'Using the Search Feature',
        'description' => 'Find articles, webinars, and resources quickly',
        'duration' => '2 min',
        'video_url' => '#',
        'category' => 'search'
    ],
    [
        'title' => 'Managing Your Profile',
        'description' => 'Update your information and preferences',
        'duration' => '4 min',
        'video_url' => '#',
        'category' => 'account'
    ]
];

// Mock troubleshooting guides
$troubleshooting = [
    [
        'issue' => 'Can\'t log in to my account',
        'solutions' => [
            'Check that your email and password are correct',
            'Try resetting your password',
            'Clear your browser cache and cookies',
            'Try using a different browser'
        ]
    ],
    [
        'issue' => 'Webinar video not playing',
        'solutions' => [
            'Check your internet connection',
            'Try refreshing the page',
            'Disable browser extensions',
            'Update your browser to the latest version'
        ]
    ],
    [
        'issue' => 'Payment not going through',
        'solutions' => [
            'Verify your payment information',
            'Check with your bank for any restrictions',
            'Try a different payment method',
            'Contact our support team'
        ]
    ]
];
?>

<!-- Help Header -->
<div class="bg-primary text-white py-5">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3"><?= htmlspecialchars($texts['helpCenter']) ?></h1>
                <p class="lead mb-4"><?= htmlspecialchars($texts['helpSubtitle']) ?></p>
                
                <!-- Search Bar -->
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <form action="help.php" method="GET" class="d-flex">
                            <input type="hidden" name="lang" value="<?= htmlspecialchars($lang) ?>">
                            <input type="text" class="form-control form-control-lg" name="q" 
                                   placeholder="<?= htmlspecialchars($texts['searchHelp']) ?>" 
                                   value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                            <button type="submit" class="btn btn-light btn-lg ms-2">
                                <i class="bi bi-search"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Help Content -->
<div class="py-5">
    <div class="container">
        <!-- Popular Topics -->
        <div class="row mb-5">
            <div class="col-12">
                <h2 class="fw-bold mb-4"><?= htmlspecialchars($texts['popularTopics']) ?></h2>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-rocket-takeoff text-primary fs-1 mb-3"></i>
                        <h5 class="card-title"><?= htmlspecialchars($texts['gettingStarted']) ?></h5>
                        <p class="card-text">Learn the basics and get started with TQRS</p>
                        <a href="#getting-started" class="btn btn-outline-primary"><?= htmlspecialchars($texts['readMore']) ?></a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-person-circle text-primary fs-1 mb-3"></i>
                        <h5 class="card-title"><?= htmlspecialchars($texts['accountManagement']) ?></h5>
                        <p class="card-text">Manage your account and profile settings</p>
                        <a href="#account-management" class="btn btn-outline-primary"><?= htmlspecialchars($texts['readMore']) ?></a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-camera-video text-primary fs-1 mb-3"></i>
                        <h5 class="card-title"><?= htmlspecialchars($texts['webinars']) ?></h5>
                        <p class="card-text">Everything about webinars and live events</p>
                        <a href="#webinars" class="btn btn-outline-primary"><?= htmlspecialchars($texts['readMore']) ?></a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-tools text-primary fs-1 mb-3"></i>
                        <h5 class="card-title"><?= htmlspecialchars($texts['technicalSupport']) ?></h5>
                        <p class="card-text">Technical issues and troubleshooting</p>
                        <a href="#technical-support" class="btn btn-outline-primary"><?= htmlspecialchars($texts['readMore']) ?></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Results or Default Content -->
        <?php if (isset($_GET['q']) && !empty($_GET['q'])): ?>
            <!-- Search Results -->
            <div class="row">
                <div class="col-12">
                    <h3 class="mb-4"><?= htmlspecialchars($texts['searchResults']) ?>: "<?= htmlspecialchars($_GET['q']) ?>"</h3>
                    
                    <?php
                    $searchResults = [];
                    foreach ($faqs as $category => $items) {
                        foreach ($items as $item) {
                            if (stripos($item['question'], $_GET['q']) !== false || 
                                stripos($item['answer'], $_GET['q']) !== false) {
                                $searchResults[] = $item;
                            }
                        }
                    }
                    ?>
                    
                    <?php if (empty($searchResults)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-search-x fs-1 text-muted mb-3"></i>
                            <h4 class="text-muted"><?= htmlspecialchars($texts['noResults']) ?></h4>
                            <p class="text-muted"><?= htmlspecialchars($texts['tryDifferentKeywords']) ?></p>
                        </div>
                    <?php else: ?>
                        <div class="accordion" id="searchResultsAccordion">
                            <?php foreach ($searchResults as $index => $item): ?>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button <?= $index > 0 ? 'collapsed' : '' ?>" type="button" 
                                                data-bs-toggle="collapse" data-bs-target="#searchResult<?= $index ?>">
                                            <?= htmlspecialchars($item['question']) ?>
                                        </button>
                                    </h2>
                                    <div id="searchResult<?= $index ?>" class="accordion-collapse collapse <?= $index === 0 ? 'show' : '' ?>" 
                                         data-bs-parent="#searchResultsAccordion">
                                        <div class="accordion-body">
                                            <?= htmlspecialchars($item['answer']) ?>
                                            <div class="mt-3">
                                                <small class="text-muted">
                                                    <?= htmlspecialchars($texts['wasThisHelpful']) ?>
                                                    <button class="btn btn-sm btn-outline-primary ms-2" onclick="markHelpful(<?= $index ?>, true)">
                                                        <?= htmlspecialchars($texts['yes']) ?>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-secondary ms-1" onclick="markHelpful(<?= $index ?>, false)">
                                                        <?= htmlspecialchars($texts['no']) ?>
                                                    </button>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <!-- Default Help Content -->
            <div class="row">
                <!-- FAQs -->
                <div class="col-lg-8">
                    <h2 class="fw-bold mb-4"><?= htmlspecialchars($texts['faq']) ?></h2>
                    
                    <!-- Getting Started FAQ -->
                    <div class="mb-5">
                        <h3 id="getting-started" class="h4 mb-3"><?= htmlspecialchars($texts['gettingStarted']) ?></h3>
                        <div class="accordion" id="gettingStartedAccordion">
                            <?php foreach ($faqs['getting-started'] as $index => $item): ?>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button <?= $index > 0 ? 'collapsed' : '' ?>" type="button" 
                                                data-bs-toggle="collapse" data-bs-target="#gettingStarted<?= $index ?>">
                                            <?= htmlspecialchars($item['question']) ?>
                                        </button>
                                    </h2>
                                    <div id="gettingStarted<?= $index ?>" class="accordion-collapse collapse <?= $index === 0 ? 'show' : '' ?>" 
                                         data-bs-parent="#gettingStartedAccordion">
                                        <div class="accordion-body">
                                            <?= htmlspecialchars($item['answer']) ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <!-- Webinars FAQ -->
                    <div class="mb-5">
                        <h3 id="webinars" class="h4 mb-3"><?= htmlspecialchars($texts['webinars']) ?></h3>
                        <div class="accordion" id="webinarsAccordion">
                            <?php foreach ($faqs['webinars'] as $index => $item): ?>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" 
                                                data-bs-toggle="collapse" data-bs-target="#webinars<?= $index ?>">
                                            <?= htmlspecialchars($item['question']) ?>
                                        </button>
                                    </h2>
                                    <div id="webinars<?= $index ?>" class="accordion-collapse collapse" 
                                         data-bs-parent="#webinarsAccordion">
                                        <div class="accordion-body">
                                            <?= htmlspecialchars($item['answer']) ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <!-- Technical Support FAQ -->
                    <div class="mb-5">
                        <h3 id="technical-support" class="h4 mb-3"><?= htmlspecialchars($texts['technicalSupport']) ?></h3>
                        <div class="accordion" id="technicalAccordion">
                            <?php foreach ($faqs['technical'] as $index => $item): ?>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" 
                                                data-bs-toggle="collapse" data-bs-target="#technical<?= $index ?>">
                                            <?= htmlspecialchars($item['question']) ?>
                                        </button>
                                    </h2>
                                    <div id="technical<?= $index ?>" class="accordion-collapse collapse" 
                                         data-bs-parent="#technicalAccordion">
                                        <div class="accordion-body">
                                            <?= htmlspecialchars($item['answer']) ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <!-- Billing FAQ -->
                    <div class="mb-5">
                        <h3 id="billing" class="h4 mb-3"><?= htmlspecialchars($texts['billing']) ?></h3>
                        <div class="accordion" id="billingAccordion">
                            <?php foreach ($faqs['billing'] as $index => $item): ?>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" 
                                                data-bs-toggle="collapse" data-bs-target="#billing<?= $index ?>">
                                            <?= htmlspecialchars($item['question']) ?>
                                        </button>
                                    </h2>
                                    <div id="billing<?= $index ?>" class="accordion-collapse collapse" 
                                         data-bs-parent="#billingAccordion">
                                        <div class="accordion-body">
                                            <?= htmlspecialchars($item['answer']) ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Tutorials -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-play-circle text-primary"></i> <?= htmlspecialchars($texts['tutorials']) ?>
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php foreach ($tutorials as $tutorial): ?>
                                <div class="d-flex align-items-start mb-3">
                                    <i class="bi bi-play-circle text-primary me-2 mt-1"></i>
                                    <div>
                                        <h6 class="mb-1"><?= htmlspecialchars($tutorial['title']) ?></h6>
                                        <p class="text-muted small mb-1"><?= htmlspecialchars($tutorial['description']) ?></p>
                                        <small class="text-muted"><?= htmlspecialchars($tutorial['duration']) ?></small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <!-- Troubleshooting -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-wrench text-warning"></i> <?= htmlspecialchars($texts['troubleshooting']) ?>
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php foreach ($troubleshooting as $issue): ?>
                                <div class="mb-3">
                                    <h6 class="mb-2"><?= htmlspecialchars($issue['issue']) ?></h6>
                                    <ul class="list-unstyled small">
                                        <?php foreach ($issue['solutions'] as $solution): ?>
                                            <li class="mb-1">
                                                <i class="bi bi-check text-success me-1"></i>
                                                <?= htmlspecialchars($solution) ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <!-- Contact Support -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-headset text-info"></i> <?= htmlspecialchars($texts['stillNeedHelp']) ?>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="contact.php?lang=<?= urlencode($lang) ?>" class="btn btn-primary">
                                    <i class="bi bi-envelope"></i> <?= htmlspecialchars($texts['contactSupport']) ?>
                                </a>
                                <button class="btn btn-outline-primary" onclick="startLiveChat()">
                                    <i class="bi bi-chat-dots"></i> <?= htmlspecialchars($texts['liveChat']) ?>
                                </button>
                                <a href="tel:+15551234567" class="btn btn-outline-secondary">
                                    <i class="bi bi-telephone"></i> <?= htmlspecialchars($texts['phoneSupport']) ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Feedback Modal -->
<div class="modal fade" id="feedbackModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= htmlspecialchars($texts['submitFeedback']) ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="feedbackForm">
                    <div class="mb-3">
                        <label for="feedbackType" class="form-label">Feedback Type</label>
                        <select class="form-select" id="feedbackType" required>
                            <option value="">Select feedback type</option>
                            <option value="suggestion"><?= htmlspecialchars($texts['suggestImprovement']) ?></option>
                            <option value="bug"><?= htmlspecialchars($texts['reportBug']) ?></option>
                            <option value="feature"><?= htmlspecialchars($texts['requestFeature']) ?></option>
                            <option value="general"><?= htmlspecialchars($texts['generalInquiry']) ?></option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="feedbackSubject" class="form-label">Subject</label>
                        <input type="text" class="form-control" id="feedbackSubject" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="feedbackMessage" class="form-label">Message</label>
                        <textarea class="form-control" id="feedbackMessage" rows="4" required></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="feedbackEmail" class="form-label">Your Email (optional)</label>
                        <input type="email" class="form-control" id="feedbackEmail">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitFeedback()">Submit</button>
            </div>
        </div>
    </div>
</div>

<script>
// Mark helpful functionality
function markHelpful(index, helpful) {
    // In a real app, this would send analytics data
    console.log('Feedback:', { index, helpful, timestamp: new Date().toISOString() });
    
    showToast('Success', '<?= htmlspecialchars($texts['thankYou']) ?>');
}

// Live chat functionality
function startLiveChat() {
    // In a real app, this would open a live chat widget
    showToast('Info', 'Live chat feature coming soon. Please use email support for now.');
}

// Feedback functionality
function submitFeedback() {
    const form = document.getElementById('feedbackForm');
    const formData = new FormData(form);
    
    // In a real app, this would submit to your backend
    console.log('Submitting feedback:', Object.fromEntries(formData));
    
    showToast('Success', 'Thank you for your feedback! We\'ll review it shortly.');
    
    // Close modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('feedbackModal'));
    modal.hide();
    
    // Reset form
    form.reset();
}

// Search functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.querySelector('form[action="help.php"]');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            const searchInput = this.querySelector('input[name="q"]');
            if (!searchInput.value.trim()) {
                e.preventDefault();
                searchInput.focus();
            }
        });
    }
    
    // Auto-focus search on page load if no query
    <?php if (!isset($_GET['q']) || empty($_GET['q'])): ?>
        const searchInput = document.querySelector('input[name="q"]');
        if (searchInput) {
            searchInput.focus();
        }
    <?php endif; ?>
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + K to focus search
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
        e.preventDefault();
        const searchInput = document.querySelector('input[name="q"]');
        if (searchInput) {
            searchInput.focus();
        }
    }
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
.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
}

.accordion-button:not(.collapsed) {
    background-color: #e3f2fd;
    color: #1976d2;
}

.accordion-button:focus {
    box-shadow: 0 0 0 0.25rem rgba(25, 118, 210, 0.25);
}

.bi {
    transition: transform 0.2s ease-in-out;
}

.card:hover .bi {
    transform: scale(1.1);
}
</style>

<?php include_once __DIR__ . '/includes/footer.php'; ?> 