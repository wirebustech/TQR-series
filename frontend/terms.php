<?php
session_start();

$pageTitle = 'Terms of Service - TQRS';
$pageDescription = 'Terms of service and legal conditions for using the TQRS platform.';

include_once __DIR__ . '/includes/header.php';

$texts = [
    'termsOfService' => 'Terms of Service',
    'lastUpdated' => 'Last Updated',
    'effectiveDate' => 'Effective Date',
    'acceptance' => 'Acceptance of Terms',
    'acceptanceText' => 'By accessing and using The Qualitative Research Series (TQRS) platform, you accept and agree to be bound by the terms and provision of this agreement.',
    'description' => 'Description of Service',
    'descriptionText' => 'TQRS is an online platform that provides educational content, webinars, research resources, and community features for qualitative researchers.',
    'userAccounts' => 'User Accounts',
    'accountCreation' => 'Account Creation',
    'accountCreationText' => 'To access certain features of the platform, you must create an account. You are responsible for maintaining the confidentiality of your account information.',
    'accountSecurity' => 'Account Security',
    'accountSecurityText' => 'You are responsible for all activities that occur under your account. You must notify us immediately of any unauthorized use of your account.',
    'userConduct' => 'User Conduct',
    'acceptableUse' => 'Acceptable Use',
    'acceptableUseText' => 'You agree to use the platform only for lawful purposes and in accordance with these Terms of Service.',
    'prohibitedActivities' => 'Prohibited Activities',
    'prohibitedText' => 'You may not use the platform to:',
    'prohibitedList' => [
        'Violate any applicable laws or regulations',
        'Infringe upon the rights of others',
        'Upload or transmit harmful, offensive, or inappropriate content',
        'Attempt to gain unauthorized access to the platform',
        'Interfere with the proper functioning of the platform',
        'Use automated systems to access the platform',
        'Impersonate another person or entity'
    ],
    'contentOwnership' => 'Content Ownership',
    'userContent' => 'User Content',
    'userContentText' => 'You retain ownership of any content you submit to the platform. By submitting content, you grant us a license to use, display, and distribute your content.',
    'platformContent' => 'Platform Content',
    'platformContentText' => 'All content provided by TQRS, including but not limited to articles, webinars, and educational materials, is owned by TQRS or its licensors.',
    'intellectualProperty' => 'Intellectual Property',
    'copyright' => 'Copyright',
    'copyrightText' => 'The platform and its content are protected by copyright and other intellectual property laws.',
    'trademarks' => 'Trademarks',
    'trademarksText' => 'TQRS and related trademarks are the property of TQRS. You may not use these marks without our written permission.',
    'privacy' => 'Privacy',
    'privacyText' => 'Your privacy is important to us. Please review our Privacy Policy, which also governs your use of the platform.',
    'paymentTerms' => 'Payment Terms',
    'fees' => 'Fees',
    'feesText' => 'Some features of the platform may require payment. All fees are non-refundable unless otherwise stated.',
    'subscriptions' => 'Subscriptions',
    'subscriptionsText' => 'Subscription fees are billed in advance on a recurring basis. You may cancel your subscription at any time.',
    'refunds' => 'Refunds',
    'refundsText' => 'Refunds are provided at our discretion and in accordance with our refund policy.',
    'disclaimer' => 'Disclaimer of Warranties',
    'disclaimerText' => 'The platform is provided "as is" without warranties of any kind. We do not guarantee that the platform will be error-free or uninterrupted.',
    'limitationOfLiability' => 'Limitation of Liability',
    'liabilityText' => 'In no event shall TQRS be liable for any indirect, incidental, special, or consequential damages arising from your use of the platform.',
    'indemnification' => 'Indemnification',
    'indemnificationText' => 'You agree to indemnify and hold harmless TQRS from any claims arising from your use of the platform or violation of these terms.',
    'termination' => 'Termination',
    'accountTermination' => 'Account Termination',
    'accountTerminationText' => 'We may terminate or suspend your account at any time for violation of these terms or for any other reason.',
    'effectOfTermination' => 'Effect of Termination',
    'effectText' => 'Upon termination, your right to use the platform will cease immediately.',
    'governingLaw' => 'Governing Law',
    'governingLawText' => 'These terms shall be governed by and construed in accordance with the laws of the jurisdiction in which TQRS operates.',
    'disputeResolution' => 'Dispute Resolution',
    'disputeText' => 'Any disputes arising from these terms shall be resolved through binding arbitration.',
    'changesToTerms' => 'Changes to Terms',
    'changesText' => 'We reserve the right to modify these terms at any time. Continued use of the platform constitutes acceptance of modified terms.',
    'contact' => 'Contact Information',
    'contactText' => 'If you have any questions about these terms, please contact us.',
    'severability' => 'Severability',
    'severabilityText' => 'If any provision of these terms is found to be unenforceable, the remaining provisions will remain in full force and effect.',
    'entireAgreement' => 'Entire Agreement',
    'entireAgreementText' => 'These terms constitute the entire agreement between you and TQRS regarding the use of the platform.',
    'readMore' => 'Read More',
    'backToTop' => 'Back to Top'
];
if ($lang !== 'en') {
    foreach ($texts as $k => $v) {
        $texts[$k] = translateText($v, $lang, 'en');
    }
}

$lastUpdated = 'February 15, 2024';
$effectiveDate = 'February 15, 2024';
?>

<!-- Terms Header -->
<div class="bg-primary text-white py-5">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3"><?= htmlspecialchars($texts['termsOfService']) ?></h1>
                <p class="lead"><?= htmlspecialchars($texts['lastUpdated']) ?>: <?= htmlspecialchars($lastUpdated) ?></p>
                <p class="mb-0"><?= htmlspecialchars($texts['effectiveDate']) ?>: <?= htmlspecialchars($effectiveDate) ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Terms Content -->
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
                            <a class="nav-link" href="#acceptance"><?= htmlspecialchars($texts['acceptance']) ?></a>
                            <a class="nav-link" href="#description"><?= htmlspecialchars($texts['description']) ?></a>
                            <a class="nav-link" href="#userAccounts"><?= htmlspecialchars($texts['userAccounts']) ?></a>
                            <a class="nav-link" href="#userConduct"><?= htmlspecialchars($texts['userConduct']) ?></a>
                            <a class="nav-link" href="#contentOwnership"><?= htmlspecialchars($texts['contentOwnership']) ?></a>
                            <a class="nav-link" href="#intellectualProperty"><?= htmlspecialchars($texts['intellectualProperty']) ?></a>
                            <a class="nav-link" href="#privacy"><?= htmlspecialchars($texts['privacy']) ?></a>
                            <a class="nav-link" href="#paymentTerms"><?= htmlspecialchars($texts['paymentTerms']) ?></a>
                            <a class="nav-link" href="#disclaimer"><?= htmlspecialchars($texts['disclaimer']) ?></a>
                            <a class="nav-link" href="#limitationOfLiability"><?= htmlspecialchars($texts['limitationOfLiability']) ?></a>
                            <a class="nav-link" href="#indemnification"><?= htmlspecialchars($texts['indemnification']) ?></a>
                            <a class="nav-link" href="#termination"><?= htmlspecialchars($texts['termination']) ?></a>
                            <a class="nav-link" href="#governingLaw"><?= htmlspecialchars($texts['governingLaw']) ?></a>
                            <a class="nav-link" href="#changesToTerms"><?= htmlspecialchars($texts['changesToTerms']) ?></a>
                            <a class="nav-link" href="#contact"><?= htmlspecialchars($texts['contact']) ?></a>
                        </nav>
                    </div>
                </div>

                <!-- Terms Sections -->
                <div class="terms-content">
                    <!-- Acceptance of Terms -->
                    <section id="acceptance" class="mb-5">
                        <h2 class="fw-bold mb-3"><?= htmlspecialchars($texts['acceptance']) ?></h2>
                        <p><?= htmlspecialchars($texts['acceptanceText']) ?></p>
                        <a href="#top" class="btn btn-outline-primary btn-sm"><?= htmlspecialchars($texts['backToTop']) ?></a>
                    </section>

                    <!-- Description of Service -->
                    <section id="description" class="mb-5">
                        <h2 class="fw-bold mb-3"><?= htmlspecialchars($texts['description']) ?></h2>
                        <p><?= htmlspecialchars($texts['descriptionText']) ?></p>
                        <a href="#top" class="btn btn-outline-primary btn-sm"><?= htmlspecialchars($texts['backToTop']) ?></a>
                    </section>

                    <!-- User Accounts -->
                    <section id="userAccounts" class="mb-5">
                        <h2 class="fw-bold mb-3"><?= htmlspecialchars($texts['userAccounts']) ?></h2>
                        
                        <h3 class="h5 mb-3"><?= htmlspecialchars($texts['accountCreation']) ?></h3>
                        <p><?= htmlspecialchars($texts['accountCreationText']) ?></p>
                        
                        <h3 class="h5 mb-3"><?= htmlspecialchars($texts['accountSecurity']) ?></h3>
                        <p><?= htmlspecialchars($texts['accountSecurityText']) ?></p>
                        
                        <a href="#top" class="btn btn-outline-primary btn-sm"><?= htmlspecialchars($texts['backToTop']) ?></a>
                    </section>

                    <!-- User Conduct -->
                    <section id="userConduct" class="mb-5">
                        <h2 class="fw-bold mb-3"><?= htmlspecialchars($texts['userConduct']) ?></h2>
                        
                        <h3 class="h5 mb-3"><?= htmlspecialchars($texts['acceptableUse']) ?></h3>
                        <p><?= htmlspecialchars($texts['acceptableUseText']) ?></p>
                        
                        <h3 class="h5 mb-3"><?= htmlspecialchars($texts['prohibitedActivities']) ?></h3>
                        <p><?= htmlspecialchars($texts['prohibitedText']) ?></p>
                        <ul>
                            <?php foreach ($texts['prohibitedList'] as $item): ?>
                                <li><?= htmlspecialchars($item) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        
                        <a href="#top" class="btn btn-outline-primary btn-sm"><?= htmlspecialchars($texts['backToTop']) ?></a>
                    </section>

                    <!-- Content Ownership -->
                    <section id="contentOwnership" class="mb-5">
                        <h2 class="fw-bold mb-3"><?= htmlspecialchars($texts['contentOwnership']) ?></h2>
                        
                        <h3 class="h5 mb-3"><?= htmlspecialchars($texts['userContent']) ?></h3>
                        <p><?= htmlspecialchars($texts['userContentText']) ?></p>
                        
                        <h3 class="h5 mb-3"><?= htmlspecialchars($texts['platformContent']) ?></h3>
                        <p><?= htmlspecialchars($texts['platformContentText']) ?></p>
                        
                        <a href="#top" class="btn btn-outline-primary btn-sm"><?= htmlspecialchars($texts['backToTop']) ?></a>
                    </section>

                    <!-- Intellectual Property -->
                    <section id="intellectualProperty" class="mb-5">
                        <h2 class="fw-bold mb-3"><?= htmlspecialchars($texts['intellectualProperty']) ?></h2>
                        
                        <h3 class="h5 mb-3"><?= htmlspecialchars($texts['copyright']) ?></h3>
                        <p><?= htmlspecialchars($texts['copyrightText']) ?></p>
                        
                        <h3 class="h5 mb-3"><?= htmlspecialchars($texts['trademarks']) ?></h3>
                        <p><?= htmlspecialchars($texts['trademarksText']) ?></p>
                        
                        <a href="#top" class="btn btn-outline-primary btn-sm"><?= htmlspecialchars($texts['backToTop']) ?></a>
                    </section>

                    <!-- Privacy -->
                    <section id="privacy" class="mb-5">
                        <h2 class="fw-bold mb-3"><?= htmlspecialchars($texts['privacy']) ?></h2>
                        <p><?= htmlspecialchars($texts['privacyText']) ?></p>
                        <a href="privacy.php?lang=<?= urlencode($lang) ?>" class="btn btn-primary btn-sm">
                            <?= htmlspecialchars($texts['readMore']) ?>
                        </a>
                        <a href="#top" class="btn btn-outline-primary btn-sm ms-2"><?= htmlspecialchars($texts['backToTop']) ?></a>
                    </section>

                    <!-- Payment Terms -->
                    <section id="paymentTerms" class="mb-5">
                        <h2 class="fw-bold mb-3"><?= htmlspecialchars($texts['paymentTerms']) ?></h2>
                        
                        <h3 class="h5 mb-3"><?= htmlspecialchars($texts['fees']) ?></h3>
                        <p><?= htmlspecialchars($texts['feesText']) ?></p>
                        
                        <h3 class="h5 mb-3"><?= htmlspecialchars($texts['subscriptions']) ?></h3>
                        <p><?= htmlspecialchars($texts['subscriptionsText']) ?></p>
                        
                        <h3 class="h5 mb-3"><?= htmlspecialchars($texts['refunds']) ?></h3>
                        <p><?= htmlspecialchars($texts['refundsText']) ?></p>
                        
                        <a href="#top" class="btn btn-outline-primary btn-sm"><?= htmlspecialchars($texts['backToTop']) ?></a>
                    </section>

                    <!-- Disclaimer of Warranties -->
                    <section id="disclaimer" class="mb-5">
                        <h2 class="fw-bold mb-3"><?= htmlspecialchars($texts['disclaimer']) ?></h2>
                        <p><?= htmlspecialchars($texts['disclaimerText']) ?></p>
                        <a href="#top" class="btn btn-outline-primary btn-sm"><?= htmlspecialchars($texts['backToTop']) ?></a>
                    </section>

                    <!-- Limitation of Liability -->
                    <section id="limitationOfLiability" class="mb-5">
                        <h2 class="fw-bold mb-3"><?= htmlspecialchars($texts['limitationOfLiability']) ?></h2>
                        <p><?= htmlspecialchars($texts['liabilityText']) ?></p>
                        <a href="#top" class="btn btn-outline-primary btn-sm"><?= htmlspecialchars($texts['backToTop']) ?></a>
                    </section>

                    <!-- Indemnification -->
                    <section id="indemnification" class="mb-5">
                        <h2 class="fw-bold mb-3"><?= htmlspecialchars($texts['indemnification']) ?></h2>
                        <p><?= htmlspecialchars($texts['indemnificationText']) ?></p>
                        <a href="#top" class="btn btn-outline-primary btn-sm"><?= htmlspecialchars($texts['backToTop']) ?></a>
                    </section>

                    <!-- Termination -->
                    <section id="termination" class="mb-5">
                        <h2 class="fw-bold mb-3"><?= htmlspecialchars($texts['termination']) ?></h2>
                        
                        <h3 class="h5 mb-3"><?= htmlspecialchars($texts['accountTermination']) ?></h3>
                        <p><?= htmlspecialchars($texts['accountTerminationText']) ?></p>
                        
                        <h3 class="h5 mb-3"><?= htmlspecialchars($texts['effectOfTermination']) ?></h3>
                        <p><?= htmlspecialchars($texts['effectText']) ?></p>
                        
                        <a href="#top" class="btn btn-outline-primary btn-sm"><?= htmlspecialchars($texts['backToTop']) ?></a>
                    </section>

                    <!-- Governing Law -->
                    <section id="governingLaw" class="mb-5">
                        <h2 class="fw-bold mb-3"><?= htmlspecialchars($texts['governingLaw']) ?></h2>
                        <p><?= htmlspecialchars($texts['governingLawText']) ?></p>
                        
                        <h3 class="h5 mb-3"><?= htmlspecialchars($texts['disputeResolution']) ?></h3>
                        <p><?= htmlspecialchars($texts['disputeText']) ?></p>
                        
                        <a href="#top" class="btn btn-outline-primary btn-sm"><?= htmlspecialchars($texts['backToTop']) ?></a>
                    </section>

                    <!-- Changes to Terms -->
                    <section id="changesToTerms" class="mb-5">
                        <h2 class="fw-bold mb-3"><?= htmlspecialchars($texts['changesToTerms']) ?></h2>
                        <p><?= htmlspecialchars($texts['changesText']) ?></p>
                        <a href="#top" class="btn btn-outline-primary btn-sm"><?= htmlspecialchars($texts['backToTop']) ?></a>
                    </section>

                    <!-- Contact Information -->
                    <section id="contact" class="mb-5">
                        <h2 class="fw-bold mb-3"><?= htmlspecialchars($texts['contact']) ?></h2>
                        <p><?= htmlspecialchars($texts['contactText']) ?></p>
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Email</h5>
                                <p><a href="mailto:legal@tqrs.org">legal@tqrs.org</a></p>
                            </div>
                            <div class="col-md-6">
                                <h5>Address</h5>
                                <p>
                                    TQRS Legal Department<br>
                                    123 Research Street<br>
                                    Academic City, AC 12345<br>
                                    United States
                                </p>
                            </div>
                        </div>
                        <a href="#top" class="btn btn-outline-primary btn-sm"><?= htmlspecialchars($texts['backToTop']) ?></a>
                    </section>

                    <!-- Additional Legal Sections -->
                    <section id="severability" class="mb-5">
                        <h2 class="fw-bold mb-3"><?= htmlspecialchars($texts['severability']) ?></h2>
                        <p><?= htmlspecialchars($texts['severabilityText']) ?></p>
                        <a href="#top" class="btn btn-outline-primary btn-sm"><?= htmlspecialchars($texts['backToTop']) ?></a>
                    </section>

                    <section id="entireAgreement" class="mb-5">
                        <h2 class="fw-bold mb-3"><?= htmlspecialchars($texts['entireAgreement']) ?></h2>
                        <p><?= htmlspecialchars($texts['entireAgreementText']) ?></p>
                        <a href="#top" class="btn btn-outline-primary btn-sm"><?= htmlspecialchars($texts['backToTop']) ?></a>
                    </section>
                </div>

                <!-- Footer Actions -->
                <div class="text-center mt-5">
                    <div class="d-flex justify-content-center gap-3">
                        <a href="privacy.php?lang=<?= urlencode($lang) ?>" class="btn btn-outline-primary">
                            <i class="bi bi-shield-check"></i> Privacy Policy
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

// Print functionality
function printTerms() {
    window.print();
}

// Copy to clipboard functionality
function copyTerms() {
    const termsText = document.querySelector('.terms-content').innerText;
    navigator.clipboard.writeText(termsText).then(() => {
        showToast('Success', 'Terms of Service copied to clipboard');
    });
}
</script>

<style>
@media print {
    .navbar, .footer, .btn, .card-header {
        display: none !important;
    }
    
    .terms-content {
        font-size: 12pt;
        line-height: 1.6;
    }
    
    .terms-content h2 {
        page-break-before: always;
        margin-top: 2rem;
    }
    
    .terms-content h3 {
        margin-top: 1.5rem;
    }
}

.terms-content h2 {
    color: #2c3e50;
    border-bottom: 2px solid #3498db;
    padding-bottom: 0.5rem;
    margin-bottom: 1.5rem;
}

.terms-content h3 {
    color: #34495e;
    margin-top: 1.5rem;
    margin-bottom: 1rem;
}

.terms-content p {
    margin-bottom: 1rem;
    line-height: 1.7;
}

.terms-content ul {
    margin-bottom: 1rem;
    padding-left: 1.5rem;
}

.terms-content li {
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
</style>

<?php include_once __DIR__ . '/includes/footer.php'; ?> 