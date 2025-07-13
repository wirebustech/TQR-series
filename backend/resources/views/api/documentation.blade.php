<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TQRS API Documentation</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Swagger UI CSS -->
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/swagger-ui-dist@5.9.0/swagger-ui.css" />
    
    <style>
        body {
            background-color: #f8f9fa;
        }
        
        .navbar-brand {
            font-weight: 700;
        }
        
        .swagger-ui .topbar {
            display: none;
        }
        
        .swagger-ui .info {
            margin: 20px 0;
        }
        
        .api-header {
            background: linear-gradient(135deg, #0d6efd 0%, #0056b3 100%);
            color: white;
            padding: 60px 0;
        }
        
        .feature-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .feature-icon {
            font-size: 2rem;
            color: #0d6efd;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="bi bi-book-half me-2"></i>TQRS API
            </a>
            
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="{{ route('home') }}">Home</a>
                <a class="nav-link" href="{{ route('admin.login') }}">Admin</a>
            </div>
        </div>
    </nav>

    <!-- API Header -->
    <section class="api-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-4 fw-bold mb-3">TQRS API Documentation</h1>
                    <p class="lead">Comprehensive API for managing The Qualitative Research Series platform</p>
                    <div class="d-flex justify-content-center gap-3 mt-4">
                        <a href="#swagger-ui" class="btn btn-light btn-lg">View API Docs</a>
                        <a href="{{ url('/api/v1/specification') }}" class="btn btn-outline-light btn-lg" target="_blank">Download Spec</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- API Features -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="feature-card text-center">
                        <div class="feature-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h5>Secure Authentication</h5>
                        <p class="text-muted">JWT-based authentication with role-based access control for all protected endpoints.</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="feature-card text-center">
                        <div class="feature-icon">
                            <i class="bi bi-people"></i>
                        </div>
                        <h5>User Management</h5>
                        <p class="text-muted">Complete user CRUD operations with profile management and role assignments.</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="feature-card text-center">
                        <div class="feature-icon">
                            <i class="bi bi-camera-video"></i>
                        </div>
                        <h5>Webinar Management</h5>
                        <p class="text-muted">Create, update, and manage webinars with registration tracking and scheduling.</p>
                    </div>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-lg-4">
                    <div class="feature-card text-center">
                        <div class="feature-icon">
                            <i class="bi bi-journal-text"></i>
                        </div>
                        <h5>Content Management</h5>
                        <p class="text-muted">Manage blog posts, pages, and research contributions with full CRUD operations.</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="feature-card text-center">
                        <div class="feature-icon">
                            <i class="bi bi-envelope"></i>
                        </div>
                        <h5>Newsletter Integration</h5>
                        <p class="text-muted">Subscribe users to newsletters and manage subscription lists programmatically.</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="feature-card text-center">
                        <div class="feature-icon">
                            <i class="bi bi-heart"></i>
                        </div>
                        <h5>Donation Processing</h5>
                        <p class="text-muted">Handle donations with payment processing and donor management capabilities.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- API Documentation -->
    <section id="swagger-ui" class="py-5 bg-white">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div id="swagger-ui-container"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h6>The Qualitative Research Series</h6>
                    <p class="text-muted mb-0">Advancing research methodologies through comprehensive APIs and tools.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="text-muted mb-0">
                        API Version: 1.0.0 | 
                        <a href="{{ route('contact') }}" class="text-muted">Support</a>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Swagger UI JS -->
    <script src="https://unpkg.com/swagger-ui-dist@5.9.0/swagger-ui-bundle.js"></script>
    <script src="https://unpkg.com/swagger-ui-dist@5.9.0/swagger-ui-standalone-preset.js"></script>
    
    <script>
        window.onload = function() {
            const ui = SwaggerUIBundle({
                url: '{{ url("/api/v1/specification") }}',
                dom_id: '#swagger-ui-container',
                deepLinking: true,
                presets: [
                    SwaggerUIBundle.presets.apis,
                    SwaggerUIStandalonePreset
                ],
                plugins: [
                    SwaggerUIBundle.plugins.DownloadUrl
                ],
                layout: "StandaloneLayout",
                validatorUrl: null,
                docExpansion: 'list',
                filter: true,
                showRequestHeaders: true,
                showCommonExtensions: true,
                tryItOutEnabled: true
            });
        };
    </script>
</body>
</html> 