<?php
session_start();

$pageTitle = 'Privacy Policy - TQRS';
$pageDescription = 'Privacy policy and data protection information for the TQRS platform.';

include_once __DIR__ . '/includes/header.php';

$texts = [
    'privacyPolicy' => 'Privacy Policy',
    'lastUpdated' => 'Last Updated',
    'effectiveDate' => 'Effective Date',
    'introduction' => 'Introduction',
    'introductionText' => 'The Qualitative Research Series (TQRS) is committed to protecting your privacy. This Privacy Policy explains how we collect, use, and safeguard your personal information.',
    'informationWeCollect' => 'Information We Collect',
    'personalInformation' => 'Personal Information',
    'personalInfoText' => 'We collect personal information that you provide directly to us, such as:',
    'personalInfoList' => [
        'Name and contact information (email, phone number)',
        'Account credentials and profile information',
        'Institution and research area details',
        'Payment and billing information',
        'Communication preferences'
    ],
    'usageInformation' => 'Usage Information',
    'usageInfoText' => 'We automatically collect certain information about your use of our platform:',
    'usageInfoList' => [
        'Log data (IP address, browser type, access times)',
        'Device information (device type, operating system)',
        'Usage patterns (pages visited, features used)',
        'Cookies and similar tracking technologies'
    ],
    'howWeUseInformation' => 'How We Use Your Information',
    'useForService' => 'To Provide Our Services',
    'useForServiceText' => 'We use your information to:',
    'useForServiceList' => [
        'Provide access to webinars and educational content',
        'Process registrations and payments',
        'Send important service updates and notifications',
        'Respond to your questions and support requests',
        'Personalize your experience on our platform'
    ],
    'useForCommunication' => 'Communication',
    'communicationText' => 'We may use your information to:',
    'communicationList' => [
        'Send you newsletters and educational updates',
        'Notify you about new webinars and content',
        'Provide customer support and assistance',
        'Send administrative messages and updates'
    ],
    'useForImprovement' => 'Improvement and Analytics',
    'improvementText' => 'We use aggregated data to:',
    'improvementList' => [
        'Improve our platform and services',
        'Analyze usage patterns and trends',
        'Develop new features and content',
        'Ensure platform security and performance'
    ],
    'informationSharing' => 'Information Sharing',
    'sharingPolicy' => 'We do not sell, trade, or rent your personal information to third parties. We may share your information in the following circumstances:',
    'sharingList' => [
        'With your consent or at your direction',
        'With service providers who assist in platform operations',
        'To comply with legal obligations or court orders',
        'To protect our rights, property, or safety',
        'In connection with a business transfer or merger'
    ],
    'dataSecurity' => 'Data Security',
    'securityMeasures' => 'We implement appropriate security measures to protect your personal information:',
    'securityList' => [
        'Encryption of data in transit and at rest',
        'Regular security assessments and updates',
        'Access controls and authentication measures',
    'Secure data centers and infrastructure',
        'Employee training on data protection'
    ],
    'dataRetention' => 'Data Retention',
    'retentionText' => 'We retain your personal information for as long as necessary to:',
    'retentionList' => [
        'Provide our services to you',
        'Comply with legal obligations',
        'Resolve disputes and enforce agreements',
        'Improve our services'
    ],
    'yourRights' => 'Your Rights',
    'accessRights' => 'You have the following rights regarding your personal information:',
    'rightsList' => [
        'Access and review your personal information',
        'Update or correct inaccurate information',
        'Request deletion of your personal information',
        'Object to or restrict certain processing',
        'Data portability (receive your data in a portable format)',
        'Withdraw consent where processing is based on consent'
    ],
    'cookies' => 'Cookies and Tracking',
    'cookiesText' => 'We use cookies and similar technologies to:',
    'cookiesList' => [
        'Remember your preferences and settings',
        'Analyze platform usage and performance',
        'Provide personalized content and features',
        'Ensure platform security and functionality'
    ],
    'thirdPartyServices' => 'Third-Party Services',
    'thirdPartyText' => 'Our platform may integrate with third-party services:',
    'thirdPartyList' => [
        'Payment processors (Stripe, PayPal)',
        'Analytics services (Google Analytics)',
        'Email marketing platforms (Mailchimp)',
        'Social media platforms (for sharing features)'
    ],
    'childrenPrivacy' => 'Children\'s Privacy',
    'childrenText' => 'Our platform is not intended for children under 13 years of age. We do not knowingly collect personal information from children under 13.',
    'internationalTransfers' => 'International Data Transfers',
    'internationalText' => 'Your information may be transferred to and processed in countries other than your own. We ensure appropriate safeguards are in place.',
    'changesToPolicy' => 'Changes to This Policy',
    'changesText' => 'We may update this Privacy Policy from time to time. We will notify you of any material changes by:',
    'changesList' => [
        'Posting the updated policy on our platform',
        'Sending you an email notification',
        'Displaying a prominent notice on our platform'
    ],
    'contactUs' => 'Contact Us',
    'contactText' => 'If you have any questions about this Privacy Policy or our data practices, please contact us:',
    'contactMethods' => [
        'Email: privacy@tqrs.org',
        'Phone: +1 (555) 123-4567',
        'Address: TQRS Privacy Office, 123 Research Street, Academic City, AC 12345'
    ],
    'dataProtectionOfficer' => 'Data Protection Officer',
    'dpoText' => 'For EU residents, you may also contact our Data Protection Officer at dpo@tqrs.org',
    'readMore' => 'Read More',
    'backToTop' => 'Back to Top',
    'acceptCookies' => 'Accept Cookies',
    'declineCookies' => 'Decline',
    'cookieSettings' => 'Cookie Settings',
    'necessaryCookies' => 'Necessary Cookies',
    'necessaryText' => 'These cookies are essential for the platform to function properly.',
    'analyticsCookies' => 'Analytics Cookies',
    'analyticsText' => 'These cookies help us understand how visitors interact with our platform.',
    'marketingCookies' => 'Marketing Cookies',
    'marketingText' => 'These cookies are used to deliver relevant advertisements and track marketing campaigns.',
    'functionalCookies' => 'Functional Cookies',
    'functionalText' => 'These cookies enable enhanced functionality and personalization.'
];
if ($lang !== 'en') {
    foreach ($texts as $k => $v) {
        $texts[$k] = translateText($v, $lang, 'en');
    }
}

$lastUpdated = 'February 15, 2024';
$effectiveDate = 'February 15, 2024';
?>

<!-- Privacy Header -->
<div class="bg-primary text-white py-5">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3"><?= htmlspecialchars($texts['privacyPolicy']) ?></h1>
                <p class="lead"><?= htmlspecialchars($texts['lastUpdated']) ?>: <?= htmlspecialchars($lastUpdated) ?></p>
                <p class="mb-0"><?= htmlspecialchars($texts['effectiveDate']) ?>: <?= htmlspecialchars($effectiveDate) ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Privacy Content -->
<div class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Table of Contents -->
                <div class="card border-0 shadow-sm mb-5">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">Table of Contents</h5>
                    </div>
                    <div class="card-body">
                        <nav class="nav flex-column">
                            <a class="nav-link" href="#introduction"><?= htmlspecialchars($texts['introduction']) ?></a>
                            <a class="nav-link" href="#informationWeCollect"><?= htmlspecialchars($texts['informationWeCollect']) ?></a>
                            <a class="nav-link" href="#howWeUseInformation"><?= htmlspecialchars($texts['howWeUseInformation']) ?></a>
                            <a class="nav-link" href="#informationSharing"><?= htmlspecialchars($texts['informationSharing']) ?></a>
                            <a class="nav-link" href="#dataSecurity"><?= htmlspecialchars($texts['dataSecurity']) ?></a>
                            <a class="nav-link" href="#dataRetention"><?= htmlspecialchars($texts['dataRetention']) ?></a>
                            <a class="nav-link" href="#yourRights"><?= htmlspecialchars($texts['yourRights']) ?></a>
                            <a class="nav-link" href="#cookies"><?= htmlspecialchars($texts['cookies']) ?></a>
                            <a class="nav-link" href="#thirdPartyServices"><?= htmlspecialchars($texts['thirdPartyServices']) ?></a>
                            <a class="nav-link" href="#childrenPrivacy"><?= htmlspecialchars($texts['childrenPrivacy']) ?></a>
                            <a class="nav-link" href="#internationalTransfers"><?= htmlspecialchars($texts['internationalTransfers']) ?></a>
                            <a class="nav-link" href="#changesToPolicy"><?= htmlspecialchars($texts['changesToPolicy']) ?></a>
                            <a class="nav-link" href="#contactUs"><?= htmlspecialchars($texts['contactUs']) ?></a>
                        </nav>
                    </div>
                </div>

                <!-- Privacy Sections -->
                <div class="privacy-content">
                    <!-- Introduction -->
                    <section id="introduction" class="mb-5">
                        <h2 class="fw-bold mb-3"><?= htmlspecialchars($texts['introduction']) ?></h2>
                        <p><?= htmlspecialchars($texts['introductionText']) ?></p>
                        <a href="#top" class="btn btn-outline-primary btn-sm"><?= htmlspecialchars($texts['backToTop']) ?></a>
                    </section>

                    <!-- Information We Collect -->
                    <section id="informationWeCollect" class="mb-5">
                        <h2 class="fw-bold mb-3"><?= htmlspecialchars($texts['informationWeCollect']) ?></h2>
                        
                        <h3 class="h5 mb-3"><?= htmlspecialchars($texts['personalInformation']) ?></h3>
                        <p><?= htmlspecialchars($texts['personalInfoText']) ?></p>
                        <ul>
                            <?php foreach ($texts['personalInfoList'] as $item): ?>
                                <li><?= htmlspecialchars($item) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        
                        <h3 class="h5 mb-3"><?= htmlspecialchars($texts['usageInformation']) ?></h3>
                        <p><?= htmlspecialchars($texts['usageInfoText']) ?></p>
                        <ul>
                            <?php foreach ($texts['usageInfoList'] as $item): ?>
                                <li><?= htmlspecialchars($item) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        
                        <a href="#top" class="btn btn-outline-primary btn-sm"><?= htmlspecialchars($texts['backToTop']) ?></a>
                    </section>

                    <!-- How We Use Information -->
                    <section id="howWeUseInformation" class="mb-5">
                        <h2 class="fw-bold mb-3"><?= htmlspecialchars($texts['howWeUseInformation']) ?></h2>
                        
                        <h3 class="h5 mb-3"><?= htmlspecialchars($texts['useForService']) ?></h3>
                        <p><?= htmlspecialchars($texts['useForServiceText']) ?></p>
                        <ul>
                            <?php foreach ($texts['useForServiceList'] as $item): ?>
                                <li><?= htmlspecialchars($item) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        
                        <h3 class="h5 mb-3"><?= htmlspecialchars($texts['useForCommunication']) ?></h3>
                        <p><?= htmlspecialchars($texts['communicationText']) ?></p>
                        <ul>
                            <?php foreach ($texts['communicationList'] as $item): ?>
                                <li><?= htmlspecialchars($item) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        
                        <h3 class="h5 mb-3"><?= htmlspecialchars($texts['useForImprovement']) ?></h3>
                        <p><?= htmlspecialchars($texts['improvementText']) ?></p>
                        <ul>
                            <?php foreach ($texts['improvementList'] as $item): ?>
                                <li><?= htmlspecialchars($item) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        
                        <a href="#top" class="btn btn-outline-primary btn-sm"><?= htmlspecialchars($texts['backToTop']) ?></a>
                    </section>

                    <!-- Information Sharing -->
                    <section id="informationSharing" class="mb-5">
                        <h2 class="fw-bold mb-3"><?= htmlspecialchars($texts['informationSharing']) ?></h2>
                        <p><?= htmlspecialchars($texts['sharingPolicy']) ?></p>
                        <ul>
                            <?php foreach ($texts['sharingList'] as $item): ?>
                                <li><?= htmlspecialchars($item) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <a href="#top" class="btn btn-outline-primary btn-sm"><?= htmlspecialchars($texts['backToTop']) ?></a>
                    </section>

                    <!-- Data Security -->
                    <section id="dataSecurity" class="mb-5">
                        <h2 class="fw-bold mb-3"><?= htmlspecialchars($texts['dataSecurity']) ?></h2>
                        <p><?= htmlspecialchars($texts['securityMeasures']) ?></p>
                        <ul>
                            <?php foreach ($texts['securityList'] as $item): ?>
                                <li><?= htmlspecialchars($item) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <a href="#top" class="btn btn-outline-primary btn-sm"><?= htmlspecialchars($texts['backToTop']) ?></a>
                    </section>

                    <!-- Data Retention -->
                    <section id="dataRetention" class="mb-5">
                        <h2 class="fw-bold mb-3"><?= htmlspecialchars($texts['dataRetention']) ?></h2>
                        <p><?= htmlspecialchars($texts['retentionText']) ?></p>
                        <ul>
                            <?php foreach ($texts['retentionList'] as $item): ?>
                                <li><?= htmlspecialchars($item) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <a href="#top" class="btn btn-outline-primary btn-sm"><?= htmlspecialchars($texts['backToTop']) ?></a>
                    </section>

                    <!-- Your Rights -->
                    <section id="yourRights" class="mb-5">
                        <h2 class="fw-bold mb-3"><?= htmlspecialchars($texts['yourRights']) ?></h2>
                        <p><?= htmlspecialchars($texts['accessRights']) ?></p>
                        <ul>
                            <?php foreach ($texts['rightsList'] as $item): ?>
                                <li><?= htmlspecialchars($item) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i>
                            <strong>Exercise Your Rights:</strong> To exercise any of these rights, please contact us using the information provided in the Contact Us section.
                        </div>
                        <a href="#top" class="btn btn-outline-primary btn-sm"><?= htmlspecialchars($texts['backToTop']) ?></a>
                    </section>

                    <!-- Cookies -->
                    <section id="cookies" class="mb-5">
                        <h2 class="fw-bold mb-3"><?= htmlspecialchars($texts['cookies']) ?></h2>
                        <p><?= htmlspecialchars($texts['cookiesText']) ?></p>
                        <ul>
                            <?php foreach ($texts['cookiesList'] as $item): ?>
                                <li><?= htmlspecialchars($item) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        
                        <div class="card mt-4">
                            <div class="card-header">
                                <h5 class="mb-0"><?= htmlspecialchars($texts['cookieSettings']) ?></h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="necessaryCookies" checked disabled>
                                        <label class="form-check-label" for="necessaryCookies">
                                            <strong><?= htmlspecialchars($texts['necessaryCookies']) ?></strong>
                                        </label>
                                        <p class="text-muted small"><?= htmlspecialchars($texts['necessaryText']) ?></p>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="analyticsCookies">
                                        <label class="form-check-label" for="analyticsCookies">
                                            <strong><?= htmlspecialchars($texts['analyticsCookies']) ?></strong>
                                        </label>
                                        <p class="text-muted small"><?= htmlspecialchars($texts['analyticsText']) ?></p>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="marketingCookies">
                                        <label class="form-check-label" for="marketingCookies">
                                            <strong><?= htmlspecialchars($texts['marketingCookies']) ?></strong>
                                        </label>
                                        <p class="text-muted small"><?= htmlspecialchars($texts['marketingText']) ?></p>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="functionalCookies">
                                        <label class="form-check-label" for="functionalCookies">
                                            <strong><?= htmlspecialchars($texts['functionalCookies']) ?></strong>
                                        </label>
                                        <p class="text-muted small"><?= htmlspecialchars($texts['functionalText']) ?></p>
                                    </div>
                                </div>
                                
                                <div class="d-flex gap-2">
                                    <button class="btn btn-primary btn-sm" onclick="saveCookiePreferences()">
                                        <?= htmlspecialchars($texts['acceptCookies']) ?>
                                    </button>
                                    <button class="btn btn-outline-secondary btn-sm" onclick="declineCookies()">
                                        <?= htmlspecialchars($texts['declineCookies']) ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <a href="#top" class="btn btn-outline-primary btn-sm"><?= htmlspecialchars($texts['backToTop']) ?></a>
                    </section>

                    <!-- Third-Party Services -->
                    <section id="thirdPartyServices" class="mb-5">
                        <h2 class="fw-bold mb-3"><?= htmlspecialchars($texts['thirdPartyServices']) ?></h2>
                        <p><?= htmlspecialchars($texts['thirdPartyText']) ?></p>
                        <ul>
                            <?php foreach ($texts['thirdPartyList'] as $item): ?>
                                <li><?= htmlspecialchars($item) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i>
                            <strong>Note:</strong> These third-party services have their own privacy policies. We encourage you to review their policies as well.
                        </div>
                        <a href="#top" class="btn btn-outline-primary btn-sm"><?= htmlspecialchars($texts['backToTop']) ?></a>
                    </section>

                    <!-- Children's Privacy -->
                    <section id="childrenPrivacy" class="mb-5">
                        <h2 class="fw-bold mb-3"><?= htmlspecialchars($texts['childrenPrivacy']) ?></h2>
                        <p><?= htmlspecialchars($texts['childrenText']) ?></p>
                        <a href="#top" class="btn btn-outline-primary btn-sm"><?= htmlspecialchars($texts['backToTop']) ?></a>
                    </section>

                    <!-- International Transfers -->
                    <section id="internationalTransfers" class="mb-5">
                        <h2 class="fw-bold mb-3"><?= htmlspecialchars($texts['internationalTransfers']) ?></h2>
                        <p><?= htmlspecialchars($texts['internationalText']) ?></p>
                        <a href="#top" class="btn btn-outline-primary btn-sm"><?= htmlspecialchars($texts['backToTop']) ?></a>
                    </section>

                    <!-- Changes to Policy -->
                    <section id="changesToPolicy" class="mb-5">
                        <h2 class="fw-bold mb-3"><?= htmlspecialchars($texts['changesToPolicy']) ?></h2>
                        <p><?= htmlspecialchars($texts['changesText']) ?></p>
                        <ul>
                            <?php foreach ($texts['changesList'] as $item): ?>
                                <li><?= htmlspecialchars($item) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <a href="#top" class="btn btn-outline-primary btn-sm"><?= htmlspecialchars($texts['backToTop']) ?></a>
                    </section>

                    <!-- Contact Us -->
                    <section id="contactUs" class="mb-5">
                        <h2 class="fw-bold mb-3"><?= htmlspecialchars($texts['contactUs']) ?></h2>
                        <p><?= htmlspecialchars($texts['contactText']) ?></p>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Email</h5>
                                <p><a href="mailto:privacy@tqrs.org">privacy@tqrs.org</a></p>
                            </div>
                            <div class="col-md-6">
                                <h5>Phone</h5>
                                <p><a href="tel:+15551234567">+1 (555) 123-4567</a></p>
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <h5>Address</h5>
                            <p>
                                TQRS Privacy Office<br>
                                123 Research Street<br>
                                Academic City, AC 12345<br>
                                United States
                            </p>
                        </div>
                        
                        <div class="alert alert-info mt-4">
                            <h6><?= htmlspecialchars($texts['dataProtectionOfficer']) ?></h6>
                            <p class="mb-0"><?= htmlspecialchars($texts['dpoText']) ?></p>
                        </div>
                        
                        <a href="#top" class="btn btn-outline-primary btn-sm"><?= htmlspecialchars($texts['backToTop']) ?></a>
                    </section>
                </div>

                <!-- Footer Actions -->
                <div class="text-center mt-5">
                    <div class="d-flex justify-content-center gap-3">
                        <a href="terms.php?lang=<?= urlencode($lang) ?>" class="btn btn-outline-primary">
                            <i class="bi bi-file-text"></i> Terms of Service
                        </a>
                        <a href="contact.php?lang=<?= urlencode($lang) ?>" class="btn btn-outline-primary">
                            <i class="bi bi-envelope"></i> Contact Us
                        </a>
                        <a href="index.php?lang=<?= urlencode($lang) ?>" class="btn btn-primary">
                            <i class="bi bi-house"></i> Back to Home
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cookie Consent Banner -->
<div class="cookie-banner position-fixed bottom-0 start-0 end-0 bg-dark text-white p-3" id="cookieBanner" style="display: none; z-index: 1050;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <p class="mb-0">
                    We use cookies to enhance your experience on our platform. By continuing to use our site, you agree to our use of cookies.
                    <a href="#cookies" class="text-light text-decoration-underline">Learn more</a>
                </p>
            </div>
            <div class="col-md-4 text-md-end">
                <button class="btn btn-primary btn-sm me-2" onclick="acceptAllCookies()">
                    <?= htmlspecialchars($texts['acceptCookies']) ?>
                </button>
                <button class="btn btn-outline-light btn-sm" onclick="declineCookies()">
                    <?= htmlspecialchars($texts['declineCookies']) ?>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
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

// Highlight current section in navigation
window.addEventListener('scroll', function() {
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('.nav-link[href^="#"]');
    
    let current = '';
    sections.forEach(section => {
        const sectionTop = section.offsetTop;
        const sectionHeight = section.clientHeight;
        if (pageYOffset >= sectionTop - 200) {
            current = section.getAttribute('id');
        }
    });
    
    navLinks.forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('href') === '#' + current) {
            link.classList.add('active');
        }
    });
});

// Cookie management
function saveCookiePreferences() {
    const analytics = document.getElementById('analyticsCookies').checked;
    const marketing = document.getElementById('marketingCookies').checked;
    const functional = document.getElementById('functionalCookies').checked;
    
    // Save preferences to localStorage
    localStorage.setItem('cookiePreferences', JSON.stringify({
        analytics: analytics,
        marketing: marketing,
        functional: functional,
        timestamp: new Date().toISOString()
    }));
    
    showToast('Success', 'Cookie preferences saved successfully');
}

function acceptAllCookies() {
    document.getElementById('analyticsCookies').checked = true;
    document.getElementById('marketingCookies').checked = true;
    document.getElementById('functionalCookies').checked = true;
    saveCookiePreferences();
    hideCookieBanner();
}

function declineCookies() {
    document.getElementById('analyticsCookies').checked = false;
    document.getElementById('marketingCookies').checked = false;
    document.getElementById('functionalCookies').checked = false;
    saveCookiePreferences();
    hideCookieBanner();
}

function hideCookieBanner() {
    const banner = document.getElementById('cookieBanner');
    banner.style.display = 'none';
    localStorage.setItem('cookieBannerShown', 'true');
}

// Show cookie banner if not previously shown
document.addEventListener('DOMContentLoaded', function() {
    const bannerShown = localStorage.getItem('cookieBannerShown');
    if (!bannerShown) {
        setTimeout(() => {
            document.getElementById('cookieBanner').style.display = 'block';
        }, 2000);
    }
    
    // Load saved cookie preferences
    const savedPreferences = localStorage.getItem('cookiePreferences');
    if (savedPreferences) {
        const preferences = JSON.parse(savedPreferences);
        document.getElementById('analyticsCookies').checked = preferences.analytics;
        document.getElementById('marketingCookies').checked = preferences.marketing;
        document.getElementById('functionalCookies').checked = preferences.functional;
    }
});

// Print functionality
function printPrivacy() {
    window.print();
}

// Copy to clipboard functionality
function copyPrivacy() {
    const privacyText = document.querySelector('.privacy-content').innerText;
    navigator.clipboard.writeText(privacyText).then(() => {
        showToast('Success', 'Privacy Policy copied to clipboard');
    });
}
</script>

<style>
@media print {
    .navbar, .footer, .btn, .card-header, .cookie-banner {
        display: none !important;
    }
    
    .privacy-content {
        font-size: 12pt;
        line-height: 1.6;
    }
    
    .privacy-content h2 {
        page-break-before: always;
        margin-top: 2rem;
    }
    
    .privacy-content h3 {
        margin-top: 1.5rem;
    }
}

.privacy-content h2 {
    color: #2c3e50;
    border-bottom: 2px solid #3498db;
    padding-bottom: 0.5rem;
    margin-bottom: 1.5rem;
}

.privacy-content h3 {
    color: #34495e;
    margin-top: 1.5rem;
    margin-bottom: 1rem;
}

.privacy-content p {
    margin-bottom: 1rem;
    line-height: 1.7;
}

.privacy-content ul {
    margin-bottom: 1rem;
    padding-left: 1.5rem;
}

.privacy-content li {
    margin-bottom: 0.5rem;
}

.nav-link.active {
    background-color: #e3f2fd;
    color: #1976d2;
    font-weight: 600;
}

.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
}

.cookie-banner {
    box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
}
</style>

<?php include_once __DIR__ . '/includes/footer.php'; ?> 