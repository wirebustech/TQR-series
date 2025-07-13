<?php
session_start();

$pageTitle = 'Contact Us - TQRS';
$pageDescription = 'Get in touch with TQRS team. We\'re here to help with your qualitative research needs and questions.';

include_once __DIR__ . '/includes/header.php';

$texts = [
    'contactTitle' => 'Contact Us',
    'contactSubtitle' => 'We\'d love to hear from you. Get in touch with our team.',
    'getInTouch' => 'Get in Touch',
    'contactForm' => 'Contact Form',
    'name' => 'Full Name',
    'email' => 'Email Address',
    'subject' => 'Subject',
    'message' => 'Message',
    'sendMessage' => 'Send Message',
    'contactInfo' => 'Contact Information',
    'address' => 'Address',
    'addressText' => '123 Research Street, Academic City, AC 12345',
    'phone' => 'Phone',
    'phoneText' => '+1 (234) 567-890',
    'emailContact' => 'Email',
    'emailText' => 'info@tqrs.org',
    'officeHours' => 'Office Hours',
    'officeHoursText' => 'Monday - Friday: 9:00 AM - 6:00 PM EST',
    'support' => 'Support',
    'supportText' => 'For technical support and general inquiries',
    'sales' => 'Sales',
    'salesText' => 'For partnership and business opportunities',
    'media' => 'Media',
    'mediaText' => 'For press inquiries and media relations',
    'locations' => 'Our Locations',
    'headquarters' => 'Headquarters',
    'headquartersText' => 'Academic City, USA',
    'europe' => 'Europe Office',
    'europeText' => 'London, UK',
    'asia' => 'Asia Office',
    'asiaText' => 'Singapore',
    'faqTitle' => 'Frequently Asked Questions',
    'faqSubtitle' => 'Find quick answers to common questions',
    'faq1' => 'How do I register for a webinar?',
    'faq1Answer' => 'You can register for webinars by visiting our webinars page and clicking on the registration button for your desired session.',
    'faq2' => 'Are webinars free to attend?',
    'faq2Answer' => 'We offer both free and premium webinars. Check individual webinar pages for pricing information.',
    'faq3' => 'Can I access webinar recordings?',
    'faq3Answer' => 'Yes, webinar recordings are available to registered participants for 30 days after the live session.',
    'faq4' => 'How do I become a contributor?',
    'faq4Answer' => 'You can submit your research or methodology through our contributions page. Our team will review and get back to you.',
    'successMessage' => 'Thank you! Your message has been sent successfully.',
    'errorMessage' => 'Sorry, there was an error sending your message. Please try again.'
];
if ($lang !== 'en') {
    foreach ($texts as $k => $v) {
        $texts[$k] = translateText($v, $lang, 'en');
    }
}

// Handle form submission
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $messageText = $_POST['message'] ?? '';
    
    if ($name && $email && $subject && $messageText) {
        // Here you would typically send the email
        // For now, we'll just show a success message
        $message = 'success';
    } else {
        $message = 'error';
    }
}
?>

<!-- Hero Section -->
<div class="bg-primary text-white py-5">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3"><?= htmlspecialchars($texts['contactTitle']) ?></h1>
                <p class="lead"><?= htmlspecialchars($texts['contactSubtitle']) ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Contact Form and Info -->
<div class="py-5">
    <div class="container">
        <div class="row">
            <!-- Contact Form -->
            <div class="col-lg-8 mb-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="card-title mb-4"><?= htmlspecialchars($texts['getInTouch']) ?></h3>
                        
                        <?php if ($message === 'success'): ?>
                            <div class="alert alert-success">
                                <i class="bi bi-check-circle"></i> <?= htmlspecialchars($texts['successMessage']) ?>
                            </div>
                        <?php elseif ($message === 'error'): ?>
                            <div class="alert alert-danger">
                                <i class="bi bi-exclamation-triangle"></i> <?= htmlspecialchars($texts['errorMessage']) ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="contact.php?lang=<?= urlencode($lang) ?>">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label"><?= htmlspecialchars($texts['name']) ?> *</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label"><?= htmlspecialchars($texts['email']) ?> *</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="subject" class="form-label"><?= htmlspecialchars($texts['subject']) ?> *</label>
                                <input type="text" class="form-control" id="subject" name="subject" required>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label"><?= htmlspecialchars($texts['message']) ?> *</label>
                                <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send"></i> <?= htmlspecialchars($texts['sendMessage']) ?>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h4 class="card-title mb-4"><?= htmlspecialchars($texts['contactInfo']) ?></h4>
                        
                        <div class="mb-3">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-geo-alt text-primary me-3 mt-1"></i>
                                <div>
                                    <h6><?= htmlspecialchars($texts['address']) ?></h6>
                                    <p class="text-muted mb-0"><?= htmlspecialchars($texts['addressText']) ?></p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-telephone text-primary me-3 mt-1"></i>
                                <div>
                                    <h6><?= htmlspecialchars($texts['phone']) ?></h6>
                                    <p class="text-muted mb-0"><?= htmlspecialchars($texts['phoneText']) ?></p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-envelope text-primary me-3 mt-1"></i>
                                <div>
                                    <h6><?= htmlspecialchars($texts['emailContact']) ?></h6>
                                    <p class="text-muted mb-0"><?= htmlspecialchars($texts['emailText']) ?></p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-clock text-primary me-3 mt-1"></i>
                                <div>
                                    <h6><?= htmlspecialchars($texts['officeHours']) ?></h6>
                                    <p class="text-muted mb-0"><?= htmlspecialchars($texts['officeHoursText']) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Department Contacts -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-4"><?= htmlspecialchars($texts['support']) ?></h5>
                        
                        <div class="mb-3">
                            <h6><?= htmlspecialchars($texts['support']) ?></h6>
                            <p class="text-muted small"><?= htmlspecialchars($texts['supportText']) ?></p>
                            <a href="mailto:support@tqrs.org" class="text-primary">support@tqrs.org</a>
                        </div>
                        
                        <div class="mb-3">
                            <h6><?= htmlspecialchars($texts['sales']) ?></h6>
                            <p class="text-muted small"><?= htmlspecialchars($texts['salesText']) ?></p>
                            <a href="mailto:sales@tqrs.org" class="text-primary">sales@tqrs.org</a>
                        </div>
                        
                        <div class="mb-3">
                            <h6><?= htmlspecialchars($texts['media']) ?></h6>
                            <p class="text-muted small"><?= htmlspecialchars($texts['mediaText']) ?></p>
                            <a href="mailto:media@tqrs.org" class="text-primary">media@tqrs.org</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Office Locations -->
<div class="bg-light py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold"><?= htmlspecialchars($texts['locations']) ?></h2>
        </div>
        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <div class="text-primary mb-3">
                            <i class="bi bi-building fs-1"></i>
                        </div>
                        <h5><?= htmlspecialchars($texts['headquarters']) ?></h5>
                        <p class="text-muted"><?= htmlspecialchars($texts['headquartersText']) ?></p>
                        <p class="text-muted small">Main office and research center</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <div class="text-primary mb-3">
                            <i class="bi bi-globe fs-1"></i>
                        </div>
                        <h5><?= htmlspecialchars($texts['europe']) ?></h5>
                        <p class="text-muted"><?= htmlspecialchars($texts['europeText']) ?></p>
                        <p class="text-muted small">European operations and partnerships</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <div class="text-primary mb-3">
                            <i class="bi bi-geo-alt fs-1"></i>
                        </div>
                        <h5><?= htmlspecialchars($texts['asia']) ?></h5>
                        <p class="text-muted"><?= htmlspecialchars($texts['asiaText']) ?></p>
                        <p class="text-muted small">Asia-Pacific regional office</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FAQ Section -->
<div class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold"><?= htmlspecialchars($texts['faqTitle']) ?></h2>
            <p class="lead text-muted"><?= htmlspecialchars($texts['faqSubtitle']) ?></p>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                <?= htmlspecialchars($texts['faq1']) ?>
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <?= htmlspecialchars($texts['faq1Answer']) ?>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                <?= htmlspecialchars($texts['faq2']) ?>
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <?= htmlspecialchars($texts['faq2Answer']) ?>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                <?= htmlspecialchars($texts['faq3']) ?>
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <?= htmlspecialchars($texts['faq3Answer']) ?>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                <?= htmlspecialchars($texts['faq4']) ?>
                            </button>
                        </h2>
                        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <?= htmlspecialchars($texts['faq4Answer']) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Call to Action -->
<div class="bg-primary text-white py-5">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h2 class="fw-bold mb-4">Ready to Get Started?</h2>
                <p class="lead mb-4">Join our community of researchers and start exploring qualitative research methodologies today.</p>
                <div class="d-flex gap-3 justify-content-center">
                    <a href="register.php?lang=<?= urlencode($lang) ?>" class="btn btn-light btn-lg">
                        <i class="bi bi-person-plus"></i> Join Now
                    </a>
                    <a href="webinars.php?lang=<?= urlencode($lang) ?>" class="btn btn-outline-light btn-lg">
                        <i class="bi bi-camera-video"></i> Browse Webinars
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/includes/footer.php'; ?> 