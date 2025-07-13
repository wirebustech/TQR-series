@extends('admin.layouts.app')

@section('title', 'Dashboard - TQRS Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-speedometer2"></i> Dashboard</h2>
    <div class="text-muted">
        Welcome back, {{ Auth::user()->name }}!
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $stats['users'] }}</h4>
                        <p class="card-text">Total Users</p>
                        <small>+12% from last month</small>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-people fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $stats['webinars'] }}</h4>
                        <p class="card-text">Total Webinars</p>
                        <small>+8% from last month</small>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-camera-video fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $stats['contributions'] }}</h4>
                        <p class="card-text">Contributions</p>
                        <small>+15% from last month</small>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-file-earmark-text fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $stats['blogs'] }}</h4>
                        <p class="card-text">Blog Posts</p>
                        <small>+5% from last month</small>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-journal-text fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.users') }}" class="btn btn-outline-primary w-100">
                            <i class="bi bi-people"></i><br>
                            Manage Users
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.webinars') }}" class="btn btn-outline-success w-100">
                            <i class="bi bi-camera-video"></i><br>
                            Manage Webinars
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.contributions') }}" class="btn btn-outline-warning w-100">
                            <i class="bi bi-file-earmark-text"></i><br>
                            Review Contributions
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.analytics') }}" class="btn btn-outline-info w-100">
                            <i class="bi bi-graph-up"></i><br>
                            View Analytics
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SEO Tools -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">SEO Tools</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <button id="generateSitemap" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-diagram-3"></i><br>
                            Generate Sitemap
                        </button>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="/sitemap.xml" target="_blank" class="btn btn-outline-info w-100">
                            <i class="bi bi-eye"></i><br>
                            View Sitemap
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="/robots.txt" target="_blank" class="btn btn-outline-dark w-100">
                            <i class="bi bi-robot"></i><br>
                            View Robots.txt
                        </a>
                    </div>
                </div>
                <div id="sitemapStatus" class="mt-3" style="display: none;">
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle"></i>
                        <span id="sitemapMessage">Sitemap generated successfully!</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Recent Activity</h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between align-items-start">
                        <div class="ms-2 me-auto">
                            <div class="fw-bold">New User Registration</div>
                            <small class="text-muted">John Doe joined the platform</small>
                        </div>
                        <small class="text-muted">2 hours ago</small>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-start">
                        <div class="ms-2 me-auto">
                            <div class="fw-bold">Webinar Created</div>
                            <small class="text-muted">Introduction to Qualitative Research scheduled</small>
                        </div>
                        <small class="text-muted">4 hours ago</small>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-start">
                        <div class="ms-2 me-auto">
                            <div class="fw-bold">Contribution Submitted</div>
                            <small class="text-muted">Research paper submitted by Jane Smith</small>
                        </div>
                        <small class="text-muted">6 hours ago</small>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-start">
                        <div class="ms-2 me-auto">
                            <div class="fw-bold">Blog Published</div>
                            <small class="text-muted">New blog post: "Research Methodologies"</small>
                        </div>
                        <small class="text-muted">1 day ago</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">System Status</h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <i class="bi bi-check-circle text-success"></i>
                            Database Connection
                        </div>
                        <span class="badge bg-success">Online</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <i class="bi bi-check-circle text-success"></i>
                            Email Service
                        </div>
                        <span class="badge bg-success">Online</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <i class="bi bi-check-circle text-success"></i>
                            File Storage
                        </div>
                        <span class="badge bg-success">Online</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <i class="bi bi-exclamation-triangle text-warning"></i>
                            Backup Status
                        </div>
                        <span class="badge bg-warning">Pending</span>
                    </div>
                </div>
                
                <div class="mt-3">
                    <small class="text-muted">
                        <i class="bi bi-clock"></i>
                        Last updated: {{ now()->format('M j, Y g:i A') }}
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-refresh dashboard every 5 minutes
    setTimeout(function() {
        location.reload();
    }, 300000);

    // Sitemap generation
    document.getElementById('generateSitemap').addEventListener('click', function() {
        const button = this;
        const originalText = button.innerHTML;
        
        button.disabled = true;
        button.innerHTML = '<i class="bi bi-hourglass-split"></i><br>Generating...';
        
        fetch('/api/v1/sitemap/generate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            const statusDiv = document.getElementById('sitemapStatus');
            const messageSpan = document.getElementById('sitemapMessage');
            
            if (data.success) {
                statusDiv.className = 'mt-3 alert alert-success';
                messageSpan.innerHTML = `Sitemap generated successfully! Total URLs: ${data.total_urls}`;
            } else {
                statusDiv.className = 'mt-3 alert alert-danger';
                messageSpan.innerHTML = 'Error generating sitemap: ' + data.message;
            }
            
            statusDiv.style.display = 'block';
            
            // Hide status after 5 seconds
            setTimeout(() => {
                statusDiv.style.display = 'none';
            }, 5000);
        })
        .catch(error => {
            const statusDiv = document.getElementById('sitemapStatus');
            const messageSpan = document.getElementById('sitemapMessage');
            
            statusDiv.className = 'mt-3 alert alert-danger';
            messageSpan.innerHTML = 'Error generating sitemap: ' + error.message;
            statusDiv.style.display = 'block';
            
            setTimeout(() => {
                statusDiv.style.display = 'none';
            }, 5000);
        })
        .finally(() => {
            button.disabled = false;
            button.innerHTML = originalText;
        });
    });
</script>
@endpush 