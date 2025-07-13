    </main>

    <!-- Footer -->
    <footer class="bg-gradient-dark text-white py-5 mt-5 position-relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10">
            <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);"></div>
        </div>
        
        <div class="container position-relative">
            <div class="row g-4">
                <!-- About Section -->
                <div class="col-lg-4 mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary rounded-circle p-2 me-3">
                            <i class="bi bi-book text-white fs-4"></i>
                        </div>
                        <h4 class="mb-0 fw-bold text-white">TQRS</h4>
                    </div>
                    <p class="text-white-75 fs-6 lh-base mb-4">
                        The Qualitative Research Series - Empowering researchers with cutting-edge methodologies, 
                        insights, and collaborative learning opportunities.
                    </p>
                    <div class="social-links">
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle me-2" title="Facebook">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle me-2" title="Twitter">
                            <i class="bi bi-twitter"></i>
                        </a>
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle me-2" title="LinkedIn">
                            <i class="bi bi-linkedin"></i>
                        </a>
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle me-2" title="YouTube">
                            <i class="bi bi-youtube"></i>
                        </a>
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle" title="Instagram">
                            <i class="bi bi-instagram"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="col-lg-2 mb-4">
                    <h6 class="text-white fw-bold mb-3 border-bottom border-light pb-2">
                        <i class="bi bi-link-45deg me-2"></i>Quick Links
                    </h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="index.php?lang=<?= urlencode($lang) ?>" class="text-white-75 text-decoration-none d-flex align-items-center hover-lift">
                                <i class="bi bi-chevron-right me-2 text-primary"></i>Home
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="about.php?lang=<?= urlencode($lang) ?>" class="text-white-75 text-decoration-none d-flex align-items-center hover-lift">
                                <i class="bi bi-chevron-right me-2 text-primary"></i>About
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="webinars.php?lang=<?= urlencode($lang) ?>" class="text-white-75 text-decoration-none d-flex align-items-center hover-lift">
                                <i class="bi bi-chevron-right me-2 text-primary"></i>Webinars
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="blog.php?lang=<?= urlencode($lang) ?>" class="text-white-75 text-decoration-none d-flex align-items-center hover-lift">
                                <i class="bi bi-chevron-right me-2 text-primary"></i>Blog
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="research.php?lang=<?= urlencode($lang) ?>" class="text-white-75 text-decoration-none d-flex align-items-center hover-lift">
                                <i class="bi bi-chevron-right me-2 text-primary"></i>Research
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Resources -->
                <div class="col-lg-2 mb-4">
                    <h6 class="text-white fw-bold mb-3 border-bottom border-light pb-2">
                        <i class="bi bi-folder me-2"></i>Resources
                    </h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="methodologies.php?lang=<?= urlencode($lang) ?>" class="text-white-75 text-decoration-none d-flex align-items-center hover-lift">
                                <i class="bi bi-chevron-right me-2 text-primary"></i>Methodologies
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="case-studies.php?lang=<?= urlencode($lang) ?>" class="text-white-75 text-decoration-none d-flex align-items-center hover-lift">
                                <i class="bi bi-chevron-right me-2 text-primary"></i>Case Studies
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="publications.php?lang=<?= urlencode($lang) ?>" class="text-white-75 text-decoration-none d-flex align-items-center hover-lift">
                                <i class="bi bi-chevron-right me-2 text-primary"></i>Publications
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="tools.php?lang=<?= urlencode($lang) ?>" class="text-white-75 text-decoration-none d-flex align-items-center hover-lift">
                                <i class="bi bi-chevron-right me-2 text-primary"></i>Tools
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="faq.php?lang=<?= urlencode($lang) ?>" class="text-white-75 text-decoration-none d-flex align-items-center hover-lift">
                                <i class="bi bi-chevron-right me-2 text-primary"></i>FAQ
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div class="col-lg-4 mb-4">
                    <h6 class="text-white fw-bold mb-3 border-bottom border-light pb-2">
                        <i class="bi bi-envelope me-2"></i>Contact Us
                    </h6>
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-primary rounded-circle p-2 me-3">
                                <i class="bi bi-geo-alt text-white"></i>
                            </div>
                            <span class="text-white-75">123 Research Street, Academic City, AC 12345</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-primary rounded-circle p-2 me-3">
                                <i class="bi bi-envelope text-white"></i>
                            </div>
                            <a href="mailto:info@tqrs.org" class="text-white-75 text-decoration-none">info@tqrs.org</a>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-primary rounded-circle p-2 me-3">
                                <i class="bi bi-telephone text-white"></i>
                            </div>
                            <a href="tel:+1234567890" class="text-white-75 text-decoration-none">+1 (234) 567-890</a>
                        </div>
                    </div>
                    
                    <!-- Newsletter Signup -->
                    <div class="bg-white bg-opacity-10 rounded p-3">
                        <h6 class="text-white fw-bold mb-2">
                            <i class="bi bi-bell me-2"></i>Newsletter
                        </h6>
                        <p class="text-white-75 small mb-3">Stay updated with latest research insights and events</p>
                        <form class="d-flex gap-2">
                            <input type="email" class="form-control form-control-sm" placeholder="Your email" required>
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="bi bi-arrow-right"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Bottom Footer -->
            <hr class="my-4 border-light opacity-25">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0 text-white-75">
                        <i class="bi bi-c-circle me-1"></i>
                        <?= date('Y') ?> TQRS. All rights reserved.
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="privacy.php?lang=<?= urlencode($lang) ?>" class="text-white-75 text-decoration-none me-3 hover-lift">Privacy Policy</a>
                    <a href="terms.php?lang=<?= urlencode($lang) ?>" class="text-white-75 text-decoration-none me-3 hover-lift">Terms of Service</a>
                    <a href="sitemap.xml" class="text-white-75 text-decoration-none hover-lift">Sitemap</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- PWA Installation Prompt -->
    <div id="pwaInstallPrompt" class="position-fixed bottom-0 end-0 m-3" style="display: none; z-index: 1050;">
        <div class="card shadow-lg border-0" style="max-width: 320px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="card-body text-white">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-white bg-opacity-25 rounded-circle p-2 me-3">
                        <i class="bi bi-download text-white"></i>
                    </div>
                    <h6 class="card-title mb-0 fw-bold">Install TQRS App</h6>
                </div>
                <p class="card-text small text-white-75 mb-3">
                    Install our app for a better experience with offline access and notifications.
                </p>
                <div class="d-flex gap-2">
                    <button class="btn btn-light btn-sm flex-fill" onclick="installPWA()">
                        <i class="bi bi-download me-1"></i>Install
                    </button>
                    <button class="btn btn-outline-light btn-sm" onclick="dismissPWA()">Not Now</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Back to Top Button -->
    <button id="backToTop" class="btn btn-primary position-fixed bottom-0 end-0 m-3 rounded-circle shadow-lg" style="display: none; z-index: 1040; width: 50px; height: 50px;">
        <i class="bi bi-arrow-up"></i>
    </button>

    <!-- Custom CSS for Footer -->
    <style>
        .bg-gradient-dark {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        }
        
        .text-white-75 {
            color: rgba(255, 255, 255, 0.85) !important;
        }
        
        .hover-lift {
            transition: all 0.3s ease;
        }
        
        .hover-lift:hover {
            color: #fff !important;
            transform: translateX(5px);
            text-decoration: none;
        }
        
        .social-links .btn {
            transition: all 0.3s ease;
            width: 40px;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        .social-links .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
        
        .bg-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
    </style>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script src="assets/js/main.js"></script>
    
    <!-- PWA Service Worker -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('sw.js')
                    .then(function(registration) {
                        console.log('ServiceWorker registration successful');
                    })
                    .catch(function(err) {
                        console.log('ServiceWorker registration failed');
                    });
            });
        }
    </script>

    <!-- PWA Installation Logic -->
    <script>
        let deferredPrompt;
        
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            showInstallPrompt();
        });

        function showInstallPrompt() {
            const prompt = document.getElementById('pwaInstallPrompt');
            prompt.style.display = 'block';
        }

        function installPWA() {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then((choiceResult) => {
                    if (choiceResult.outcome === 'accepted') {
                        console.log('User accepted the install prompt');
                    } else {
                        console.log('User dismissed the install prompt');
                    }
                    deferredPrompt = null;
                    dismissPWA();
                });
            }
        }

        function dismissPWA() {
            const prompt = document.getElementById('pwaInstallPrompt');
            prompt.style.display = 'none';
        }
    </script>

    <!-- Back to Top Logic -->
    <script>
        const backToTopButton = document.getElementById('backToTop');
        
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                backToTopButton.style.display = 'block';
            } else {
                backToTopButton.style.display = 'none';
            }
        });
        
        backToTopButton.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    </script>

    <!-- Search Functionality -->
    <script>
        document.querySelector('.search-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const query = document.getElementById('searchInput').value.trim();
            if (query) {
                window.location.href = `search.php?q=${encodeURIComponent(query)}&lang=<?= urlencode($lang) ?>`;
            }
        });
    </script>

</body>
</html> 