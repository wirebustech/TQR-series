@extends('public.layout')

@section('title', $webinar->title)
@section('description', $webinar->description)

@section('content')
<!-- Webinar Details -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('webinars') }}">Webinars</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $webinar->title }}</li>
                    </ol>
                </nav>

                <!-- Webinar Header -->
                <header class="mb-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h1 class="display-5 fw-bold">{{ $webinar->title }}</h1>
                        <span class="badge bg-{{ $webinar->status === 'published' ? 'success' : 'warning' }} fs-6">
                            {{ ucfirst($webinar->status) }}
                        </span>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center text-muted mb-2">
                                <i class="bi bi-calendar me-2"></i>
                                <span>{{ $webinar->scheduled_at->format('l, F d, Y') }}</span>
                            </div>
                            <div class="d-flex align-items-center text-muted mb-2">
                                <i class="bi bi-clock me-2"></i>
                                <span>{{ $webinar->scheduled_at->format('g:i A') }} ({{ $webinar->duration }} minutes)</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center text-muted mb-2">
                                <i class="bi bi-camera-video me-2"></i>
                                <span>{{ ucfirst($webinar->platform) }}</span>
                            </div>
                            <div class="d-flex align-items-center text-muted mb-2">
                                <i class="bi bi-people me-2"></i>
                                <span>{{ $webinar->registrations_count ?? 0 }} registered</span>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Webinar Description -->
                <div class="mb-5">
                    <h3>About This Webinar</h3>
                    <p class="lead">{{ $webinar->description }}</p>
                </div>

                <!-- Tags -->
                @if($webinar->tags)
                <div class="mb-5">
                    <h5>Topics Covered:</h5>
                    <div>
                        @foreach(explode(',', $webinar->tags) as $tag)
                        <span class="badge bg-light text-dark me-2 mb-2">{{ trim($tag) }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Registration Requirements -->
                @if($webinar->requires_registration)
                <div class="alert alert-info">
                    <h6><i class="bi bi-info-circle me-2"></i>Registration Required</h6>
                    <p class="mb-0">This webinar requires registration. Please fill out the form to secure your spot.</p>
                </div>
                @endif

                <!-- Meeting Link (if published) -->
                @if($webinar->status === 'published' && $webinar->meeting_url)
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="bi bi-link-45deg me-2"></i>Meeting Link
                        </h5>
                        <p class="card-text">Join the webinar using the link below:</p>
                        <a href="{{ $webinar->meeting_url }}" class="btn btn-primary" target="_blank">
                            <i class="bi bi-camera-video me-2"></i>Join Webinar
                        </a>
                    </div>
                </div>
                @endif
            </div>

            <!-- Registration Sidebar -->
            <div class="col-lg-4">
                <div class="card sticky-top" style="top: 2rem;">
                    <div class="card-body">
                        <h5 class="card-title">Register for This Webinar</h5>
                        
                        @if($webinar->status === 'published')
                            @if($webinar->requires_registration)
                            <form id="registrationForm">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="organization" class="form-label">Organization (Optional)</label>
                                    <input type="text" class="form-control" id="organization" name="organization">
                                </div>
                                <div class="mb-3">
                                    <label for="role" class="form-label">Role (Optional)</label>
                                    <select class="form-select" id="role" name="role">
                                        <option value="">Select your role</option>
                                        <option value="student">Student</option>
                                        <option value="researcher">Researcher</option>
                                        <option value="academic">Academic</option>
                                        <option value="practitioner">Practitioner</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-calendar-check me-2"></i>Register Now
                                </button>
                            </form>
                            @else
                            <div class="text-center">
                                <p class="text-muted">No registration required for this webinar.</p>
                                <a href="{{ $webinar->meeting_url }}" class="btn btn-primary" target="_blank">
                                    <i class="bi bi-camera-video me-2"></i>Join Webinar
                                </a>
                            </div>
                            @endif
                        @else
                        <div class="text-center">
                            <i class="bi bi-clock fs-1 text-muted mb-3"></i>
                            <h6>Coming Soon</h6>
                            <p class="text-muted">Registration will open soon. Check back later!</p>
                        </div>
                        @endif
                        
                        <hr>
                        
                        <div class="text-center">
                            <h6>Share This Webinar</h6>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($webinar->title) }}" 
                                   class="btn btn-outline-primary btn-sm" target="_blank">
                                    <i class="bi bi-twitter"></i>
                                </a>
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" 
                                   class="btn btn-outline-primary btn-sm" target="_blank">
                                    <i class="bi bi-facebook"></i>
                                </a>
                                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}" 
                                   class="btn btn-outline-primary btn-sm" target="_blank">
                                    <i class="bi bi-linkedin"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="newsletter-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h3 class="mb-3">Stay Updated</h3>
                <p class="text-muted mb-4">Get notified about upcoming webinars and research events.</p>
                
                <form class="newsletter-form">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="email" class="form-control" placeholder="Enter your email address" required>
                                <button class="btn btn-primary" type="submit">
                                    <i class="bi bi-envelope me-2"></i>Subscribe
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
$('#registrationForm').on('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    // Here you would typically send to your backend
    alert('Registration functionality would be implemented here. Thank you for your interest!');
    
    // Reset form
    this.reset();
});
</script>
@endpush 