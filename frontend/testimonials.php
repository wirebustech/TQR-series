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
    <title><?= $translations['testimonials_title'] ?? 'Testimonials - TQRS' ?></title>
    <meta name="description" content="<?= $translations['testimonials_description'] ?? 'See what researchers say about TQRS platform' ?>">
    
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
                <i class="bi bi-chat-quote text-primary me-3"></i>
                <?= $translations['testimonials'] ?? 'Testimonials' ?>
            </h1>
            <p class="lead text-muted mb-4">
                <?= $translations['testimonials_subtitle'] ?? 'Hear from researchers who have transformed their work with TQRS' ?>
            </p>
            
            <!-- Overall Rating -->
            <div class="d-flex justify-content-center align-items-center mb-4">
                <div class="text-center">
                    <div class="display-6 fw-bold text-primary mb-2">4.8</div>
                    <div class="mb-2">
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                    </div>
                    <p class="text-muted mb-0"><?= $translations['based_on'] ?? 'Based on' ?> 1,247 <?= $translations['reviews'] ?? 'reviews' ?></p>
                </div>
            </div>
        </div>

        <!-- Filter Buttons -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="d-flex flex-wrap gap-2 justify-content-center" id="testimonialFilters">
                    <button class="btn btn-outline-primary active" data-filter="all">
                        <?= $translations['all_testimonials'] ?? 'All Testimonials' ?>
                    </button>
                    <button class="btn btn-outline-primary" data-filter="academic">
                        <i class="bi bi-mortarboard me-2"></i><?= $translations['academic'] ?? 'Academic' ?>
                    </button>
                    <button class="btn btn-outline-primary" data-filter="industry">
                        <i class="bi bi-building me-2"></i><?= $translations['industry'] ?? 'Industry' ?>
                    </button>
                    <button class="btn btn-outline-primary" data-filter="student">
                        <i class="bi bi-person-badge me-2"></i><?= $translations['student'] ?? 'Student' ?>
                    </button>
                    <button class="btn btn-outline-primary" data-filter="consultant">
                        <i class="bi bi-briefcase me-2"></i><?= $translations['consultant'] ?? 'Consultant' ?>
                    </button>
                </div>
            </div>
        </div>

        <!-- Featured Testimonials -->
        <div class="row mb-5">
            <div class="col-12">
                <h3 class="text-center mb-4">
                    <i class="bi bi-star text-warning me-2"></i>
                    <?= $translations['featured_testimonials'] ?? 'Featured Testimonials' ?>
                </h3>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <!-- Featured Testimonial 1 -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-lg h-100 testimonial-card featured">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <img src="assets/images/testimonial-1.jpg" alt="Dr. Sarah Johnson" class="rounded-circle me-3" width="60" height="60">
                            <div>
                                <h6 class="mb-1">Dr. Sarah Johnson</h6>
                                <p class="text-muted mb-0"><?= $translations['associate_professor'] ?? 'Associate Professor' ?>, Stanford University</p>
                                <div class="text-warning">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                </div>
                            </div>
                        </div>
                        <blockquote class="mb-3">
                            <p class="fst-italic"><?= $translations['sarah_testimonial'] ?? 'TQRS has revolutionized how I approach qualitative research. The webinars are incredibly insightful, and the AI assistant has saved me countless hours in data analysis. This platform is a game-changer for researchers.' ?></p>
                        </blockquote>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-primary me-2"><?= $translations['academic'] ?? 'Academic' ?></span>
                            <small class="text-muted"><?= $translations['member_since'] ?? 'Member since' ?> 2023</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Featured Testimonial 2 -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-lg h-100 testimonial-card featured">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <img src="assets/images/testimonial-2.jpg" alt="Michael Chen" class="rounded-circle me-3" width="60" height="60">
                            <div>
                                <h6 class="mb-1">Michael Chen</h6>
                                <p class="text-muted mb-0"><?= $translations['research_director'] ?? 'Research Director' ?>, TechCorp</p>
                                <div class="text-warning">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                </div>
                            </div>
                        </div>
                        <blockquote class="mb-3">
                            <p class="fst-italic"><?= $translations['michael_testimonial'] ?? 'The enterprise features of TQRS have transformed our research team\'s productivity. The team management tools and custom webinars have been invaluable for our organization.' ?></p>
                        </blockquote>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-success me-2"><?= $translations['industry'] ?? 'Industry' ?></span>
                            <small class="text-muted"><?= $translations['member_since'] ?? 'Member since' ?> 2022</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- All Testimonials -->
        <div class="row mb-5">
            <div class="col-12">
                <h3 class="text-center mb-4">
                    <i class="bi bi-people text-primary me-2"></i>
                    <?= $translations['all_testimonials'] ?? 'All Testimonials' ?>
                </h3>
            </div>
        </div>

        <div class="row g-4" id="testimonialsGrid">
            <!-- Testimonial 1 -->
            <div class="col-lg-4 col-md-6 testimonial-item" data-category="academic">
                <div class="card border-0 shadow-sm h-100 testimonial-card">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <img src="assets/images/testimonial-3.jpg" alt="Dr. Emily Rodriguez" class="rounded-circle me-3" width="50" height="50">
                            <div>
                                <h6 class="mb-1">Dr. Emily Rodriguez</h6>
                                <p class="text-muted mb-0"><?= $translations['phd_candidate'] ?? 'PhD Candidate' ?></p>
                                <div class="text-warning">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                </div>
                            </div>
                        </div>
                        <blockquote class="mb-3">
                            <p class="fst-italic"><?= $translations['emily_testimonial'] ?? 'As a PhD student, TQRS has been my go-to resource for learning advanced qualitative methods. The community is incredibly supportive.' ?></p>
                        </blockquote>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-info me-2"><?= $translations['student'] ?? 'Student' ?></span>
                            <small class="text-muted">2 <?= $translations['months_ago'] ?? 'months ago' ?></small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Testimonial 2 -->
            <div class="col-lg-4 col-md-6 testimonial-item" data-category="consultant">
                <div class="card border-0 shadow-sm h-100 testimonial-card">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <img src="assets/images/testimonial-4.jpg" alt="Lisa Thompson" class="rounded-circle me-3" width="50" height="50">
                            <div>
                                <h6 class="mb-1">Lisa Thompson</h6>
                                <p class="text-muted mb-0"><?= $translations['research_consultant'] ?? 'Research Consultant' ?></p>
                                <div class="text-warning">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star"></i>
                                </div>
                            </div>
                        </div>
                        <blockquote class="mb-3">
                            <p class="fst-italic"><?= $translations['lisa_testimonial'] ?? 'The AI research assistant has been a game-changer for my consulting work. It helps me analyze data faster and more accurately.' ?></p>
                        </blockquote>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-warning me-2"><?= $translations['consultant'] ?? 'Consultant' ?></span>
                            <small class="text-muted">1 <?= $translations['month_ago'] ?? 'month ago' ?></small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Testimonial 3 -->
            <div class="col-lg-4 col-md-6 testimonial-item" data-category="industry">
                <div class="card border-0 shadow-sm h-100 testimonial-card">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <img src="assets/images/testimonial-5.jpg" alt="David Park" class="rounded-circle me-3" width="50" height="50">
                            <div>
                                <h6 class="mb-1">David Park</h6>
                                <p class="text-muted mb-0"><?= $translations['senior_analyst'] ?? 'Senior Analyst' ?></p>
                                <div class="text-warning">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                </div>
                            </div>
                        </div>
                        <blockquote class="mb-3">
                            <p class="fst-italic"><?= $translations['david_testimonial'] ?? 'The premium webinars have provided insights that directly improved our market research methodologies. Highly recommended!' ?></p>
                        </blockquote>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-success me-2"><?= $translations['industry'] ?? 'Industry' ?></span>
                            <small class="text-muted">3 <?= $translations['weeks_ago'] ?? 'weeks ago' ?></small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Testimonial 4 -->
            <div class="col-lg-4 col-md-6 testimonial-item" data-category="academic">
                <div class="card border-0 shadow-sm h-100 testimonial-card">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <img src="assets/images/testimonial-6.jpg" alt="Prof. James Wilson" class="rounded-circle me-3" width="50" height="50">
                            <div>
                                <h6 class="mb-1">Prof. James Wilson</h6>
                                <p class="text-muted mb-0"><?= $translations['professor'] ?? 'Professor' ?>, Harvard</p>
                                <div class="text-warning">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                </div>
                            </div>
                        </div>
                        <blockquote class="mb-3">
                            <p class="fst-italic"><?= $translations['james_testimonial'] ?? 'I\'ve been using TQRS for my research methodology courses. The platform provides excellent resources for both teaching and research.' ?></p>
                        </blockquote>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-primary me-2"><?= $translations['academic'] ?? 'Academic' ?></span>
                            <small class="text-muted">1 <?= $translations['week_ago'] ?? 'week ago' ?></small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Testimonial 5 -->
            <div class="col-lg-4 col-md-6 testimonial-item" data-category="student">
                <div class="card border-0 shadow-sm h-100 testimonial-card">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <img src="assets/images/testimonial-7.jpg" alt="Alex Kim" class="rounded-circle me-3" width="50" height="50">
                            <div>
                                <h6 class="mb-1">Alex Kim</h6>
                                <p class="text-muted mb-0"><?= $translations['masters_student'] ?? 'Masters Student' ?></p>
                                <div class="text-warning">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star"></i>
                                </div>
                            </div>
                        </div>
                        <blockquote class="mb-3">
                            <p class="fst-italic"><?= $translations['alex_testimonial'] ?? 'The free tier helped me get started with qualitative research. Now I\'m considering upgrading to Pro for my thesis work.' ?></p>
                        </blockquote>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-info me-2"><?= $translations['student'] ?? 'Student' ?></span>
                            <small class="text-muted">5 <?= $translations['days_ago'] ?? 'days ago' ?></small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Testimonial 6 -->
            <div class="col-lg-4 col-md-6 testimonial-item" data-category="consultant">
                <div class="card border-0 shadow-sm h-100 testimonial-card">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <img src="assets/images/testimonial-8.jpg" alt="Maria Garcia" class="rounded-circle me-3" width="50" height="50">
                            <div>
                                <h6 class="mb-1">Maria Garcia</h6>
                                <p class="text-muted mb-0"><?= $translations['independent_consultant'] ?? 'Independent Consultant' ?></p>
                                <div class="text-warning">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                </div>
                            </div>
                        </div>
                        <blockquote class="mb-3">
                            <p class="fst-italic"><?= $translations['maria_testimonial'] ?? 'As an independent consultant, TQRS has been invaluable for staying current with research methodologies and networking with other professionals.' ?></p>
                        </blockquote>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-warning me-2"><?= $translations['consultant'] ?? 'Consultant' ?></span>
                            <small class="text-muted">2 <?= $translations['days_ago'] ?? 'days ago' ?></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Load More Button -->
        <div class="text-center mt-5">
            <button class="btn btn-outline-primary" onclick="loadMoreTestimonials()">
                <i class="bi bi-arrow-down me-2"></i><?= $translations['load_more'] ?? 'Load More' ?>
            </button>
        </div>

        <!-- Write Review Section -->
        <div class="row mt-5">
            <div class="col-lg-8 mx-auto">
                <div class="card bg-gradient-primary text-white">
                    <div class="card-body text-center py-5">
                        <h3 class="mb-3">
                            <i class="bi bi-pencil-square me-2"></i>
                            <?= $translations['share_your_experience'] ?? 'Share Your Experience' ?>
                        </h3>
                        <p class="mb-4"><?= $translations['write_review_description'] ?? 'Help other researchers by sharing your experience with TQRS' ?></p>
                        <a href="write-review.php?lang=<?= urlencode($lang) ?>" class="btn btn-light btn-lg">
                            <i class="bi bi-chat-dots me-2"></i><?= $translations['write_review'] ?? 'Write a Review' ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

    <!-- Custom CSS for Testimonials -->
    <style>
        .testimonial-card {
            transition: all 0.3s ease;
            border-radius: 16px;
        }
        
        .testimonial-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
        }
        
        .testimonial-card.featured {
            border: 2px solid #667eea;
        }
        
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        
        .badge {
            border-radius: 20px;
            padding: 0.5rem 1rem;
        }
        
        .testimonial-item.hidden {
            display: none;
        }
        
        blockquote {
            border-left: 4px solid #667eea;
            padding-left: 1rem;
            margin: 0;
        }
        
        .text-warning {
            color: #ffc107 !important;
        }
    </style>

    <!-- Testimonials JavaScript -->
    <script>
        // Filter functionality
        document.querySelectorAll('[data-filter]').forEach(button => {
            button.addEventListener('click', function() {
                const filter = this.dataset.filter;
                
                // Update active button
                document.querySelectorAll('[data-filter]').forEach(btn => {
                    btn.classList.remove('active');
                });
                this.classList.add('active');
                
                // Filter testimonials
                const testimonialItems = document.querySelectorAll('.testimonial-item');
                testimonialItems.forEach(item => {
                    if (filter === 'all' || item.dataset.category === filter) {
                        item.classList.remove('hidden');
                    } else {
                        item.classList.add('hidden');
                    }
                });
            });
        });
        
        // Load more testimonials
        function loadMoreTestimonials() {
            const button = event.target;
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="bi bi-hourglass-split me-2"></i><?= $translations['loading'] ?? 'Loading' ?>...';
            button.disabled = true;
            
            setTimeout(() => {
                button.innerHTML = originalText;
                button.disabled = false;
                // Add more testimonials here
            }, 2000);
        }
        
        // Animate testimonials on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);
        
        document.querySelectorAll('.testimonial-card').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(card);
        });
    </script>
</body>
</html> 