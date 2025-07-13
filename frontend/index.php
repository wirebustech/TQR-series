<?php
session_start();

$pageTitle = 'The Qualitative Research Series (TQRS) - Home';
$pageDescription = 'A modern, AI-powered platform for qualitative research methodology, webinars, and expert insights.';

$lang = $_GET['lang'] ?? 'en';

// Load translations
require_once __DIR__ . '/includes/translation.php';
$translations = getTranslations($lang);

$texts = [
    'hero_title' => 'The Qualitative Research Series',
    'hero_subtitle' => 'Advancing qualitative research through technology, community, and expert insights',
    'hero_cta' => 'Explore Our Platform',
    'hero_cta_secondary' => 'Join Our Community',
    'features_title' => 'Why Choose TQRS?',
    'features_subtitle' => 'Comprehensive tools and resources for qualitative researchers',
    'feature_webinars_title' => 'Expert Webinars',
    'feature_webinars_desc' => 'Live and recorded sessions from leading qualitative research experts',
    'feature_ai_title' => 'AI-Powered Research',
    'feature_ai_desc' => 'Advanced AI tools to enhance your qualitative research workflow',
    'feature_community_title' => 'Research Community',
    'feature_community_desc' => 'Connect with fellow researchers and share insights',
    'feature_resources_title' => 'Rich Resources',
    'feature_resources_desc' => 'Articles, guides, and methodologies for every research need',
    'ai_section_title' => 'Research AI - Coming Soon',
    'ai_section_subtitle' => 'Revolutionary AI-powered tools for qualitative research',
    'ai_features_title' => 'AI Features',
    'ai_feature_analysis' => 'Automated Data Analysis',
    'ai_feature_coding' => 'Smart Coding Assistance',
    'ai_feature_insights' => 'Pattern Recognition',
    'ai_feature_reporting' => 'Automated Reporting',
    'ai_cta' => 'Join Beta Waitlist',
    'stats_title' => 'Platform Statistics',
    'stats_webinars' => 'Webinars',
    'stats_articles' => 'Articles',
    'stats_members' => 'Members',
    'stats_countries' => 'Countries',
    'cta_title' => 'Ready to Transform Your Research?',
    'cta_subtitle' => 'Join thousands of researchers already using TQRS',
    'cta_primary' => 'Get Started Free',
    'cta_secondary' => 'Learn More'
];

// Apply translations
if ($lang !== 'en') {
    foreach ($texts as $k => $v) {
        $texts[$k] = translateText($v, $lang, 'en');
    }
}
?>

<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <meta name="description" content="<?= htmlspecialchars($pageDescription) ?>">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="assets/css/style.css" rel="stylesheet">
    
    <!-- PWA Manifest -->
    <link rel="manifest" href="manifest.json">
    <meta name="theme-color" content="#667eea">
    
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 100px 0;
        }
        
        .feature-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            border-radius: 15px;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .ai-section {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }
        
        .stats-card {
            text-align: center;
            padding: 2rem;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .stats-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: #667eea;
        }
        
        .cta-section {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
        }
        
        .btn-custom {
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4"><?= htmlspecialchars($texts['hero_title']) ?></h1>
                    <p class="lead mb-4"><?= htmlspecialchars($texts['hero_subtitle']) ?></p>
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="webinars.php?lang=<?= urlencode($lang) ?>" class="btn btn-light btn-custom">
                            <i class="bi bi-play-circle me-2"></i><?= htmlspecialchars($texts['hero_cta']) ?>
                        </a>
                        <a href="register.php?lang=<?= urlencode($lang) ?>" class="btn btn-outline-light btn-custom">
                            <i class="bi bi-people me-2"></i><?= htmlspecialchars($texts['hero_cta_secondary']) ?>
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <img src="assets/images/hero-illustration.svg" alt="TQRS Platform" class="img-fluid" style="max-width: 500px;">
                </div>
            </div>
        </div>
    </section>

    <!-- Opportunities News Reel -->
    <?php include 'components/opportunities-reel.php'; ?>

    <!-- Features Section -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold mb-3"><?= htmlspecialchars($texts['features_title']) ?></h2>
                <p class="lead text-muted"><?= htmlspecialchars($texts['features_subtitle']) ?></p>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center p-4">
                            <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="bi bi-camera-video text-primary fs-4"></i>
                            </div>
                            <h5 class="card-title"><?= htmlspecialchars($texts['feature_webinars_title']) ?></h5>
                            <p class="card-text text-muted"><?= htmlspecialchars($texts['feature_webinars_desc']) ?></p>
                            <a href="webinars.php?lang=<?= urlencode($lang) ?>" class="btn btn-outline-primary">Explore Webinars</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center p-4">
                            <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="bi bi-robot text-success fs-4"></i>
                            </div>
                            <h5 class="card-title"><?= htmlspecialchars($texts['feature_ai_title']) ?></h5>
                            <p class="card-text text-muted"><?= htmlspecialchars($texts['feature_ai_desc']) ?></p>
                            <a href="#ai-section" class="btn btn-outline-success">Learn More</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center p-4">
                            <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="bi bi-people text-warning fs-4"></i>
                            </div>
                            <h5 class="card-title"><?= htmlspecialchars($texts['feature_community_title']) ?></h5>
                            <p class="card-text text-muted"><?= htmlspecialchars($texts['feature_community_desc']) ?></p>
                            <a href="register.php?lang=<?= urlencode($lang) ?>" class="btn btn-outline-warning">Join Community</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center p-4">
                            <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="bi bi-journal-text text-info fs-4"></i>
                            </div>
                            <h5 class="card-title"><?= htmlspecialchars($texts['feature_resources_title']) ?></h5>
                            <p class="card-text text-muted"><?= htmlspecialchars($texts['feature_resources_desc']) ?></p>
                            <a href="blog.php?lang=<?= urlencode($lang) ?>" class="btn btn-outline-info">Browse Resources</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- AI Section -->
    <section id="ai-section" class="ai-section py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="display-5 fw-bold mb-4"><?= htmlspecialchars($texts['ai_section_title']) ?></h2>
                    <p class="lead mb-4"><?= htmlspecialchars($texts['ai_section_subtitle']) ?></p>
                    
                    <h5 class="mb-3"><?= htmlspecialchars($texts['ai_features_title']) ?>:</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="bi bi-check-circle me-2"></i><?= htmlspecialchars($texts['ai_feature_analysis']) ?></li>
                        <li class="mb-2"><i class="bi bi-check-circle me-2"></i><?= htmlspecialchars($texts['ai_feature_coding']) ?></li>
                        <li class="mb-2"><i class="bi bi-check-circle me-2"></i><?= htmlspecialchars($texts['ai_feature_insights']) ?></li>
                        <li class="mb-2"><i class="bi bi-check-circle me-2"></i><?= htmlspecialchars($texts['ai_feature_reporting']) ?></li>
                    </ul>
                    
                    <button class="btn btn-light btn-custom mt-3" onclick="joinBetaWaitlist()">
                        <i class="bi bi-robot me-2"></i><?= htmlspecialchars($texts['ai_cta']) ?>
                    </button>
                </div>
                <div class="col-lg-6 text-center">
                    <img src="assets/images/ai-illustration.svg" alt="Research AI" class="img-fluid" style="max-width: 400px;">
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold"><?= htmlspecialchars($texts['stats_title']) ?></h2>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="stats-card">
                        <div class="stats-number">150+</div>
                        <div class="text-muted"><?= htmlspecialchars($texts['stats_webinars']) ?></div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stats-card">
                        <div class="stats-number">500+</div>
                        <div class="text-muted"><?= htmlspecialchars($texts['stats_articles']) ?></div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stats-card">
                        <div class="stats-number">10K+</div>
                        <div class="text-muted"><?= htmlspecialchars($texts['stats_members']) ?></div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stats-card">
                        <div class="stats-number">50+</div>
                        <div class="text-muted"><?= htmlspecialchars($texts['stats_countries']) ?></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section py-5">
        <div class="container text-center">
            <h2 class="display-5 fw-bold mb-4"><?= htmlspecialchars($texts['cta_title']) ?></h2>
            <p class="lead mb-4"><?= htmlspecialchars($texts['cta_subtitle']) ?></p>
            <div class="d-flex gap-3 justify-content-center flex-wrap">
                <a href="register.php?lang=<?= urlencode($lang) ?>" class="btn btn-light btn-custom">
                    <i class="bi bi-person-plus me-2"></i><?= htmlspecialchars($texts['cta_primary']) ?>
                </a>
                <a href="about.php?lang=<?= urlencode($lang) ?>" class="btn btn-outline-light btn-custom">
                    <i class="bi bi-info-circle me-2"></i><?= htmlspecialchars($texts['cta_secondary']) ?>
                </a>
            </div>
        </div>
    </section>

    <!-- Beta Waitlist Modal -->
    <div class="modal fade" id="betaWaitlistModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Join Research AI Beta Waitlist</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Be among the first to experience our revolutionary AI-powered qualitative research tools.</p>
                    <form id="betaWaitlistForm">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="research_area" class="form-label">Research Area</label>
                            <select class="form-select" id="research_area">
                                <option value="">Select your research area</option>
                                <option value="social_sciences">Social Sciences</option>
                                <option value="healthcare">Healthcare</option>
                                <option value="education">Education</option>
                                <option value="business">Business</option>
                                <option value="psychology">Psychology</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="experience" class="form-label">Research Experience</label>
                            <select class="form-select" id="experience">
                                <option value="">Select your experience level</option>
                                <option value="student">Student</option>
                                <option value="early_career">Early Career Researcher</option>
                                <option value="experienced">Experienced Researcher</option>
                                <option value="expert">Expert/Professor</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="submitBetaWaitlist()">Join Waitlist</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- API Client -->
    <script src="assets/js/api.js"></script>
    <!-- Main JS -->
    <script src="assets/js/main.js"></script>
    
    <script>
        function joinBetaWaitlist() {
            const modal = new bootstrap.Modal(document.getElementById('betaWaitlistModal'));
            modal.show();
        }
        
        function submitBetaWaitlist() {
            const email = document.getElementById('email').value;
            const researchArea = document.getElementById('research_area').value;
            const experience = document.getElementById('experience').value;
            
            if (!email) {
                alert('Please enter your email address.');
                return;
            }
            
            // Submit to API
            if (typeof api !== 'undefined') {
                api.post('/beta-waitlist', {
                    email: email,
                    research_area: researchArea,
                    experience: experience
                }).then(response => {
                    if (response.success) {
                        alert('Thank you for joining our beta waitlist! We\'ll notify you when the AI app is ready.');
                        bootstrap.Modal.getInstance(document.getElementById('betaWaitlistModal')).hide();
                    } else {
                        alert('Error: ' + response.message);
                    }
                }).catch(error => {
                    alert('Thank you for your interest! We\'ll add you to our waitlist.');
                    bootstrap.Modal.getInstance(document.getElementById('betaWaitlistModal')).hide();
                });
            } else {
                alert('Thank you for your interest! We\'ll add you to our waitlist.');
                bootstrap.Modal.getInstance(document.getElementById('betaWaitlistModal')).hide();
            }
        }
    </script>

    <?php include 'includes/footer.php'; ?>
</body>
</html> 