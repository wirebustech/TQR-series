    </main>

    <!-- Footer -->
    <footer class="bg-dark text-light py-5 mt-5">
        <div class="container">
            <div class="row">
                <!-- About Section -->
                <div class="col-lg-4 mb-4">
                    <h5 class="mb-3">
                        <i class="bi bi-book"></i> TQRS
                    </h5>
                    <p class="text-muted">
                        The Qualitative Research Series - Empowering researchers with cutting-edge methodologies, 
                        insights, and collaborative learning opportunities.
                    </p>
                    <div class="social-links">
                        <a href="#" class="text-light me-3"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-light me-3"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="text-light me-3"><i class="bi bi-linkedin"></i></a>
                        <a href="#" class="text-light me-3"><i class="bi bi-youtube"></i></a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="col-lg-2 mb-4">
                    <h6 class="mb-3">Quick Links</h6>
                    <ul class="list-unstyled">
                        <li><a href="index.php?lang=<?= urlencode($lang) ?>" class="text-muted text-decoration-none">Home</a></li>
                        <li><a href="about.php?lang=<?= urlencode($lang) ?>" class="text-muted text-decoration-none">About</a></li>
                        <li><a href="webinars.php?lang=<?= urlencode($lang) ?>" class="text-muted text-decoration-none">Webinars</a></li>
                        <li><a href="blog.php?lang=<?= urlencode($lang) ?>" class="text-muted text-decoration-none">Blog</a></li>
                        <li><a href="research.php?lang=<?= urlencode($lang) ?>" class="text-muted text-decoration-none">Research</a></li>
                    </ul>
                </div>

                <!-- Resources -->
                <div class="col-lg-2 mb-4">
                    <h6 class="mb-3">Resources</h6>
                    <ul class="list-unstyled">
                        <li><a href="methodologies.php?lang=<?= urlencode($lang) ?>" class="text-muted text-decoration-none">Methodologies</a></li>
                        <li><a href="case-studies.php?lang=<?= urlencode($lang) ?>" class="text-muted text-decoration-none">Case Studies</a></li>
                        <li><a href="publications.php?lang=<?= urlencode($lang) ?>" class="text-muted text-decoration-none">Publications</a></li>
                        <li><a href="tools.php?lang=<?= urlencode($lang) ?>" class="text-muted text-decoration-none">Tools</a></li>
                        <li><a href="faq.php?lang=<?= urlencode($lang) ?>" class="text-muted text-decoration-none">FAQ</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div class="col-lg-4 mb-4">
                    <h6 class="mb-3">Contact Us</h6>
                    <div class="mb-2">
                        <i class="bi bi-geo-alt text-primary me-2"></i>
                        <span class="text-muted">123 Research Street, Academic City, AC 12345</span>
                    </div>
                    <div class="mb-2">
                        <i class="bi bi-envelope text-primary me-2"></i>
                        <a href="mailto:info@tqrs.org" class="text-muted text-decoration-none">info@tqrs.org</a>
                    </div>
                    <div class="mb-2">
                        <i class="bi bi-telephone text-primary me-2"></i>
                        <a href="tel:+1234567890" class="text-muted text-decoration-none">+1 (234) 567-890</a>
                    </div>
                    
                    <!-- Newsletter Signup -->
                    <div class="mt-3">
                        <h6 class="mb-2">Newsletter</h6>
                        <form class="d-flex">
                            <input type="email" class="form-control form-control-sm me-2" placeholder="Your email">
                            <button type="submit" class="btn btn-primary btn-sm">Subscribe</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Bottom Footer -->
            <hr class="my-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0 text-muted">
                        &copy; <?= date('Y') ?> TQRS. All rights reserved.
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="privacy.php?lang=<?= urlencode($lang) ?>" class="text-muted text-decoration-none me-3">Privacy Policy</a>
                    <a href="terms.php?lang=<?= urlencode($lang) ?>" class="text-muted text-decoration-none me-3">Terms of Service</a>
                    <a href="sitemap.xml" class="text-muted text-decoration-none">Sitemap</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- PWA Installation Prompt -->
    <div id="pwaInstallPrompt" class="position-fixed bottom-0 end-0 m-3" style="display: none; z-index: 1050;">
        <div class="card shadow-lg" style="max-width: 300px;">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="bi bi-download"></i> Install TQRS App
                </h6>
                <p class="card-text small">
                    Install our app for a better experience with offline access and notifications.
                </p>
                <div class="d-flex gap-2">
                    <button class="btn btn-primary btn-sm" onclick="installPWA()">Install</button>
                    <button class="btn btn-outline-secondary btn-sm" onclick="dismissPWA()">Not Now</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Back to Top Button -->
    <button id="backToTop" class="btn btn-primary position-fixed bottom-0 end-0 m-3" style="display: none; z-index: 1040;">
        <i class="bi bi-arrow-up"></i>
    </button>

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