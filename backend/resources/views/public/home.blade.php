@extends('public.layout')

@section('title', 'Home')
@section('description', 'The Qualitative Research Series - Advancing research methodologies and fostering academic collaboration through comprehensive resources, webinars, and community engagement.')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Advancing Qualitative Research</h1>
                <p class="lead mb-4">Join our community of researchers, academics, and practitioners in exploring innovative methodologies and best practices in qualitative research.</p>
                <div class="d-flex gap-3">
                    <a href="{{ route('webinars') }}" class="btn btn-light btn-lg">
                        <i class="bi bi-camera-video me-2"></i>Join Webinars
                    </a>
                    <a href="{{ route('blogs') }}" class="btn btn-outline-light btn-lg">
                        <i class="bi bi-journal-text me-2"></i>Read Blog
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <img src="https://via.placeholder.com/600x400/0d6efd/ffffff?text=TQRS" alt="TQRS Hero" class="img-fluid rounded">
            </div>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="stats-section">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="stat-item">
                    <span class="stat-number">{{ number_format($stats['total_users']) }}</span>
                    <span class="stat-label">Active Members</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-item">
                    <span class="stat-number">{{ number_format($stats['total_webinars']) }}</span>
                    <span class="stat-label">Webinars Hosted</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-item">
                    <span class="stat-number">{{ number_format($stats['total_contributions']) }}</span>
                    <span class="stat-label">Research Contributions</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Webinars -->
<section class="py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="text-center mb-4">Upcoming Webinars</h2>
                <p class="text-center text-muted">Join our expert-led sessions on qualitative research methodologies</p>
            </div>
        </div>
        
        <div class="row">
            @forelse($featuredWebinars as $webinar)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ $webinar->title }}</h5>
                        <p class="card-text text-muted">{{ Str::limit($webinar->description, 100) }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="bi bi-calendar me-1"></i>
                                {{ $webinar->scheduled_at->format('M d, Y') }}
                            </small>
                            <a href="{{ route('webinar', $webinar->id) }}" class="btn btn-primary btn-sm">Learn More</a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="bi bi-camera-video-off fs-1 text-muted mb-3"></i>
                    <h5>No upcoming webinars</h5>
                    <p class="text-muted">Check back soon for new sessions!</p>
                </div>
            </div>
            @endforelse
        </div>
        
        <div class="text-center mt-4">
            <a href="{{ route('webinars') }}" class="btn btn-outline-primary">View All Webinars</a>
        </div>
    </div>
</section>

<!-- Recent Blog Posts -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="text-center mb-4">Latest from Our Blog</h2>
                <p class="text-center text-muted">Insights, tips, and discussions on qualitative research</p>
            </div>
        </div>
        
        <div class="row">
            @forelse($recentBlogs as $blog)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ $blog->title }}</h5>
                        <p class="card-text">{{ Str::limit($blog->excerpt, 120) }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="bi bi-calendar me-1"></i>
                                {{ $blog->published_at->format('M d, Y') }}
                            </small>
                            <a href="{{ route('blog', $blog->slug) }}" class="btn btn-outline-primary btn-sm">Read More</a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="bi bi-journal-text fs-1 text-muted mb-3"></i>
                    <h5>No blog posts yet</h5>
                    <p class="text-muted">Check back soon for new content!</p>
                </div>
            </div>
            @endforelse
        </div>
        
        <div class="text-center mt-4">
            <a href="{{ route('blogs') }}" class="btn btn-primary">View All Posts</a>
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="newsletter-section" id="newsletter">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h2 class="mb-4">Stay Updated</h2>
                <p class="lead mb-4">Subscribe to our newsletter for the latest research insights, webinar announcements, and community updates.</p>
                
                <form class="newsletter-form">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="input-group input-group-lg">
                                <input type="email" class="form-control" placeholder="Enter your email address" required>
                                <button class="btn btn-primary" type="submit">
                                    <i class="bi bi-envelope me-2"></i>Subscribe
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
                
                <small class="text-muted mt-3 d-block">
                    We respect your privacy. Unsubscribe at any time.
                </small>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h2 class="mb-4">Support Our Mission</h2>
                <p class="lead mb-4">Help us continue providing high-quality research resources and fostering academic collaboration.</p>
                
                <div class="d-flex justify-content-center gap-3">
                    <button class="btn btn-primary btn-lg" onclick="showDonationModal()">
                        <i class="bi bi-heart me-2"></i>Make a Donation
                    </button>
                    <a href="{{ route('contact') }}" class="btn btn-outline-primary btn-lg">
                        <i class="bi bi-envelope me-2"></i>Get in Touch
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Donation Modal -->
<div class="modal fade" id="donationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Support TQRS</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="donationForm">
                    <div class="mb-3">
                        <label for="donor_name" class="form-label">Your Name</label>
                        <input type="text" class="form-control" id="donor_name" name="donor_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="donor_email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="donor_email" name="donor_email" required>
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Donation Amount</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control" id="amount" name="amount" min="1" step="0.01" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Message (Optional)</label>
                        <textarea class="form-control" id="message" name="message" rows="3" placeholder="Share why you're supporting TQRS..."></textarea>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_anonymous" name="is_anonymous">
                            <label class="form-check-label" for="is_anonymous">
                                Make this donation anonymous
                            </label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitDonation()">Submit Donation</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function showDonationModal() {
    const modal = new bootstrap.Modal(document.getElementById('donationModal'));
    modal.show();
}

function submitDonation() {
    const form = document.getElementById('donationForm');
    const formData = new FormData(form);
    
    // Handle anonymous donation
    if (document.getElementById('is_anonymous').checked) {
        formData.set('donor_name', '');
        formData.set('donor_email', '');
    }
    
    $.post('{{ route("donate") }}', Object.fromEntries(formData))
        .done(function(response) {
            alert(response.message);
            bootstrap.Modal.getInstance(document.getElementById('donationModal')).hide();
            form.reset();
        })
        .fail(function(xhr) {
            const errors = xhr.responseJSON.errors;
            if (errors) {
                let errorMessage = 'Please fix the following errors:\n';
                Object.values(errors).forEach(error => {
                    errorMessage += '- ' + error[0] + '\n';
                });
                alert(errorMessage);
            } else {
                alert('An error occurred. Please try again.');
            }
        });
}

// Handle anonymous checkbox
document.getElementById('is_anonymous').addEventListener('change', function() {
    const nameField = document.getElementById('donor_name');
    const emailField = document.getElementById('donor_email');
    
    if (this.checked) {
        nameField.disabled = true;
        emailField.disabled = true;
        nameField.value = '';
        emailField.value = '';
    } else {
        nameField.disabled = false;
        emailField.disabled = false;
    }
});
</script>
@endpush 