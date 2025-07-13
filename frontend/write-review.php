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
    <title><?= $translations['write_review_title'] ?? 'Write a Review - TQRS' ?></title>
    <meta name="description" content="<?= $translations['write_review_description'] ?? 'Share your experience with TQRS platform' ?>">
    
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
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Header -->
                <div class="text-center mb-5">
                    <h1 class="display-5 fw-bold mb-3">
                        <i class="bi bi-pencil-square text-primary me-3"></i>
                        <?= $translations['write_review'] ?? 'Write a Review' ?>
                    </h1>
                    <p class="lead text-muted">
                        <?= $translations['review_subtitle'] ?? 'Share your experience and help other researchers discover TQRS' ?>
                    </p>
                </div>

                <!-- Review Form -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <form id="reviewForm" class="needs-validation" novalidate>
                            <!-- Overall Rating -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    <?= $translations['overall_rating'] ?? 'Overall Rating' ?> *
                                </label>
                                <div class="rating-container text-center">
                                    <div class="stars mb-2">
                                        <i class="bi bi-star star" data-rating="1"></i>
                                        <i class="bi bi-star star" data-rating="2"></i>
                                        <i class="bi bi-star star" data-rating="3"></i>
                                        <i class="bi bi-star star" data-rating="4"></i>
                                        <i class="bi bi-star star" data-rating="5"></i>
                                    </div>
                                    <p class="text-muted mb-0" id="ratingText">
                                        <?= $translations['click_to_rate'] ?? 'Click to rate' ?>
                                    </p>
                                </div>
                            </div>

                            <!-- Review Title -->
                            <div class="mb-4">
                                <label for="reviewTitle" class="form-label fw-bold">
                                    <?= $translations['review_title'] ?? 'Review Title' ?> *
                                </label>
                                <input type="text" class="form-control" id="reviewTitle" 
                                       placeholder="<?= $translations['review_title_placeholder'] ?? 'Summarize your experience in a few words' ?>" 
                                       maxlength="100" required>
                                <div class="form-text">
                                    <span id="titleCharCount">0</span>/100 <?= $translations['characters'] ?? 'characters' ?>
                                </div>
                            </div>

                            <!-- Review Content -->
                            <div class="mb-4">
                                <label for="reviewContent" class="form-label fw-bold">
                                    <?= $translations['review_content'] ?? 'Your Review' ?> *
                                </label>
                                <textarea class="form-control" id="reviewContent" rows="6" 
                                          placeholder="<?= $translations['review_content_placeholder'] ?? 'Tell us about your experience with TQRS. What did you like? What could be improved?' ?>" 
                                          maxlength="1000" required></textarea>
                                <div class="form-text">
                                    <span id="contentCharCount">0</span>/1000 <?= $translations['characters'] ?? 'characters' ?>
                                </div>
                            </div>

                            <!-- Category Selection -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    <?= $translations['your_category'] ?? 'Your Category' ?> *
                                </label>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-check category-option">
                                            <input class="form-check-input" type="radio" name="category" id="academic" value="academic" required>
                                            <label class="form-check-label" for="academic">
                                                <i class="bi bi-mortarboard text-primary me-2"></i>
                                                <?= $translations['academic'] ?? 'Academic' ?>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check category-option">
                                            <input class="form-check-input" type="radio" name="category" id="industry" value="industry">
                                            <label class="form-check-label" for="industry">
                                                <i class="bi bi-building text-success me-2"></i>
                                                <?= $translations['industry'] ?? 'Industry' ?>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check category-option">
                                            <input class="form-check-input" type="radio" name="category" id="student" value="student">
                                            <label class="form-check-label" for="student">
                                                <i class="bi bi-person-badge text-info me-2"></i>
                                                <?= $translations['student'] ?? 'Student' ?>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check category-option">
                                            <input class="form-check-input" type="radio" name="category" id="consultant" value="consultant">
                                            <label class="form-check-label" for="consultant">
                                                <i class="bi bi-briefcase text-warning me-2"></i>
                                                <?= $translations['consultant'] ?? 'Consultant' ?>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Specific Ratings -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    <?= $translations['specific_ratings'] ?? 'Specific Ratings' ?>
                                </label>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="rating-item">
                                            <label class="form-label"><?= $translations['webinar_quality'] ?? 'Webinar Quality' ?></label>
                                            <div class="mini-stars">
                                                <i class="bi bi-star mini-star" data-rating="1" data-category="webinar"></i>
                                                <i class="bi bi-star mini-star" data-rating="2" data-category="webinar"></i>
                                                <i class="bi bi-star mini-star" data-rating="3" data-category="webinar"></i>
                                                <i class="bi bi-star mini-star" data-rating="4" data-category="webinar"></i>
                                                <i class="bi bi-star mini-star" data-rating="5" data-category="webinar"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="rating-item">
                                            <label class="form-label"><?= $translations['content_quality'] ?? 'Content Quality' ?></label>
                                            <div class="mini-stars">
                                                <i class="bi bi-star mini-star" data-rating="1" data-category="content"></i>
                                                <i class="bi bi-star mini-star" data-rating="2" data-category="content"></i>
                                                <i class="bi bi-star mini-star" data-rating="3" data-category="content"></i>
                                                <i class="bi bi-star mini-star" data-rating="4" data-category="content"></i>
                                                <i class="bi bi-star mini-star" data-rating="5" data-category="content"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="rating-item">
                                            <label class="form-label"><?= $translations['user_interface'] ?? 'User Interface' ?></label>
                                            <div class="mini-stars">
                                                <i class="bi bi-star mini-star" data-rating="1" data-category="ui"></i>
                                                <i class="bi bi-star mini-star" data-rating="2" data-category="ui"></i>
                                                <i class="bi bi-star mini-star" data-rating="3" data-category="ui"></i>
                                                <i class="bi bi-star mini-star" data-rating="4" data-category="ui"></i>
                                                <i class="bi bi-star mini-star" data-rating="5" data-category="ui"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="rating-item">
                                            <label class="form-label"><?= $translations['customer_support'] ?? 'Customer Support' ?></label>
                                            <div class="mini-stars">
                                                <i class="bi bi-star mini-star" data-rating="1" data-category="support"></i>
                                                <i class="bi bi-star mini-star" data-rating="2" data-category="support"></i>
                                                <i class="bi bi-star mini-star" data-rating="3" data-category="support"></i>
                                                <i class="bi bi-star mini-star" data-rating="4" data-category="support"></i>
                                                <i class="bi bi-star mini-star" data-rating="5" data-category="support"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Pros and Cons -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="pros" class="form-label fw-bold">
                                        <i class="bi bi-plus-circle text-success me-2"></i>
                                        <?= $translations['pros'] ?? 'What did you like?' ?>
                                    </label>
                                    <textarea class="form-control" id="pros" rows="3" 
                                              placeholder="<?= $translations['pros_placeholder'] ?? 'What worked well for you?' ?>"></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="cons" class="form-label fw-bold">
                                        <i class="bi bi-dash-circle text-danger me-2"></i>
                                        <?= $translations['cons'] ?? 'What could be improved?' ?>
                                    </label>
                                    <textarea class="form-control" id="cons" rows="3" 
                                              placeholder="<?= $translations['cons_placeholder'] ?? 'What would you like to see improved?' ?>"></textarea>
                                </div>
                            </div>

                            <!-- Would Recommend -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    <?= $translations['would_recommend'] ?? 'Would you recommend TQRS?' ?> *
                                </label>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="recommend" id="recommendYes" value="yes" required>
                                        <label class="form-check-label" for="recommendYes">
                                            <i class="bi bi-hand-thumbs-up text-success me-2"></i>
                                            <?= $translations['yes'] ?? 'Yes' ?>
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="recommend" id="recommendNo" value="no">
                                        <label class="form-check-label" for="recommendNo">
                                            <i class="bi bi-hand-thumbs-down text-danger me-2"></i>
                                            <?= $translations['no'] ?? 'No' ?>
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="recommend" id="recommendMaybe" value="maybe">
                                        <label class="form-check-label" for="recommendMaybe">
                                            <i class="bi bi-question-circle text-warning me-2"></i>
                                            <?= $translations['maybe'] ?? 'Maybe' ?>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Privacy Options -->
                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="anonymousReview">
                                    <label class="form-check-label" for="anonymousReview">
                                        <?= $translations['anonymous_review'] ?? 'Submit this review anonymously' ?>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="allowContact" checked>
                                    <label class="form-check-label" for="allowContact">
                                        <?= $translations['allow_contact'] ?? 'Allow TQRS to contact me about my review' ?>
                                    </label>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="d-flex gap-3 justify-content-center">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-send me-2"></i>
                                    <?= $translations['submit_review'] ?? 'Submit Review' ?>
                                </button>
                                <a href="testimonials.php?lang=<?= urlencode($lang) ?>" class="btn btn-outline-secondary btn-lg">
                                    <i class="bi bi-arrow-left me-2"></i>
                                    <?= $translations['back_to_testimonials'] ?? 'Back to Testimonials' ?>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Review Guidelines -->
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-header bg-transparent border-0">
                        <h5 class="mb-0">
                            <i class="bi bi-info-circle text-primary me-2"></i>
                            <?= $translations['review_guidelines'] ?? 'Review Guidelines' ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                <?= $translations['guideline_constructive'] ?? 'Be constructive and specific in your feedback' ?>
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                <?= $translations['guideline_respectful'] ?? 'Keep your review respectful and professional' ?>
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                <?= $translations['guideline_truthful'] ?? 'Share your honest, truthful experience' ?>
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                <?= $translations['guideline_helpful'] ?? 'Focus on what would be helpful to other researchers' ?>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

    <!-- Custom CSS for Write Review -->
    <style>
        .stars {
            font-size: 2rem;
            color: #dee2e6;
        }
        
        .star {
            cursor: pointer;
            transition: all 0.2s ease;
            margin: 0 0.25rem;
        }
        
        .star:hover,
        .star.active {
            color: #ffc107;
            transform: scale(1.1);
        }
        
        .mini-stars {
            font-size: 1.2rem;
            color: #dee2e6;
        }
        
        .mini-star {
            cursor: pointer;
            transition: all 0.2s ease;
            margin: 0 0.1rem;
        }
        
        .mini-star:hover,
        .mini-star.active {
            color: #ffc107;
        }
        
        .category-option {
            padding: 1rem;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .category-option:hover {
            border-color: #667eea;
            background-color: #f8f9ff;
        }
        
        .form-check-input:checked + .form-check-label {
            color: #667eea;
            font-weight: 600;
        }
        
        .rating-item {
            padding: 1rem;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            background: #f8f9fa;
        }
        
        .btn {
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            transform: translateY(-2px);
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
    </style>

    <!-- Write Review JavaScript -->
    <script>
        let currentRating = 0;
        let specificRatings = {
            webinar: 0,
            content: 0,
            ui: 0,
            support: 0
        };

        // Main rating stars
        document.querySelectorAll('.star').forEach(star => {
            star.addEventListener('click', function() {
                const rating = parseInt(this.dataset.rating);
                currentRating = rating;
                updateStars(rating);
                updateRatingText(rating);
            });
        });

        // Mini rating stars
        document.querySelectorAll('.mini-star').forEach(star => {
            star.addEventListener('click', function() {
                const rating = parseInt(this.dataset.rating);
                const category = this.dataset.category;
                specificRatings[category] = rating;
                updateMiniStars(category, rating);
            });
        });

        function updateStars(rating) {
            document.querySelectorAll('.star').forEach((star, index) => {
                if (index < rating) {
                    star.classList.add('active');
                    star.classList.remove('bi-star');
                    star.classList.add('bi-star-fill');
                } else {
                    star.classList.remove('active');
                    star.classList.remove('bi-star-fill');
                    star.classList.add('bi-star');
                }
            });
        }

        function updateMiniStars(category, rating) {
            document.querySelectorAll(`[data-category="${category}"]`).forEach((star, index) => {
                if (index < rating) {
                    star.classList.add('active');
                    star.classList.remove('bi-star');
                    star.classList.add('bi-star-fill');
                } else {
                    star.classList.remove('active');
                    star.classList.remove('bi-star-fill');
                    star.classList.add('bi-star');
                }
            });
        }

        function updateRatingText(rating) {
            const ratingTexts = {
                1: '<?= $translations['poor'] ?? 'Poor' ?>',
                2: '<?= $translations['fair'] ?? 'Fair' ?>',
                3: '<?= $translations['good'] ?? 'Good' ?>',
                4: '<?= $translations['very_good'] ?? 'Very Good' ?>',
                5: '<?= $translations['excellent'] ?? 'Excellent' ?>'
            };
            document.getElementById('ratingText').textContent = ratingTexts[rating] || '<?= $translations['click_to_rate'] ?? 'Click to rate' ?>';
        }

        // Character counters
        document.getElementById('reviewTitle').addEventListener('input', function() {
            const count = this.value.length;
            document.getElementById('titleCharCount').textContent = count;
        });

        document.getElementById('reviewContent').addEventListener('input', function() {
            const count = this.value.length;
            document.getElementById('contentCharCount').textContent = count;
        });

        // Form validation and submission
        document.getElementById('reviewForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!validateForm()) {
                return;
            }
            
            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i><?= $translations['submitting'] ?? 'Submitting' ?>...';
            submitBtn.disabled = true;
            
            // Simulate form submission
            setTimeout(() => {
                submitBtn.innerHTML = '<i class="bi bi-check-circle me-2"></i><?= $translations['submitted'] ?? 'Submitted!' ?>';
                setTimeout(() => {
                    window.location.href = 'testimonials.php?lang=<?= urlencode($lang) ?>';
                }, 1500);
            }, 2000);
        });

        function validateForm() {
            const form = document.getElementById('reviewForm');
            
            if (currentRating === 0) {
                alert('<?= $translations['please_rate'] ?? 'Please provide an overall rating' ?>');
                return false;
            }
            
            if (!form.checkValidity()) {
                form.classList.add('was-validated');
                return false;
            }
            
            return true;
        }
    </script>
</body>
</html> 