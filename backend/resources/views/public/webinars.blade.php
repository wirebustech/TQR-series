@extends('public.layout')

@section('title', 'Webinars')
@section('description', 'Join our expert-led webinars on qualitative research methodologies, data analysis, and best practices.')

@section('content')
<!-- Page Header -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-5 fw-bold mb-3">Webinars</h1>
                <p class="lead text-muted">Join our expert-led sessions on qualitative research methodologies and best practices.</p>
            </div>
        </div>
    </div>
</section>

<!-- Webinars List -->
<section class="py-5">
    <div class="container">
        <!-- Filters -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-center gap-2">
                    <a href="{{ route('webinars') }}" class="btn btn-outline-primary {{ !request('status') ? 'active' : '' }}">
                        All Webinars
                    </a>
                    <a href="{{ route('webinars', ['status' => 'published']) }}" class="btn btn-outline-primary {{ request('status') === 'published' ? 'active' : '' }}">
                        Upcoming
                    </a>
                    <a href="{{ route('webinars', ['status' => 'draft']) }}" class="btn btn-outline-primary {{ request('status') === 'draft' ? 'active' : '' }}">
                        Coming Soon
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            @forelse($webinars as $webinar)
            <div class="col-lg-6 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="card-title mb-0">{{ $webinar->title }}</h5>
                            <span class="badge bg-{{ $webinar->status === 'published' ? 'success' : 'warning' }}">
                                {{ ucfirst($webinar->status) }}
                            </span>
                        </div>
                        
                        <p class="card-text text-muted">{{ Str::limit($webinar->description, 150) }}</p>
                        
                        <div class="row mb-3">
                            <div class="col-6">
                                <small class="text-muted">
                                    <i class="bi bi-calendar me-1"></i>
                                    {{ $webinar->scheduled_at->format('M d, Y') }}
                                </small>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">
                                    <i class="bi bi-clock me-1"></i>
                                    {{ $webinar->duration }} min
                                </small>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-6">
                                <small class="text-muted">
                                    <i class="bi bi-people me-1"></i>
                                    {{ $webinar->registrations_count ?? 0 }} registered
                                </small>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">
                                    <i class="bi bi-camera-video me-1"></i>
                                    {{ ucfirst($webinar->platform) }}
                                </small>
                            </div>
                        </div>
                        
                        @if($webinar->tags)
                        <div class="mb-3">
                            @foreach(explode(',', $webinar->tags) as $tag)
                            <span class="badge bg-light text-dark me-1">{{ trim($tag) }}</span>
                            @endforeach
                        </div>
                        @endif
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('webinar', $webinar->id) }}" class="btn btn-primary">
                                Learn More
                            </a>
                            @if($webinar->status === 'published')
                            <small class="text-success">
                                <i class="bi bi-check-circle me-1"></i>Registration Open
                            </small>
                            @else
                            <small class="text-muted">
                                <i class="bi bi-clock me-1"></i>Coming Soon
                            </small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="bi bi-camera-video-off fs-1 text-muted mb-3"></i>
                    <h5>No webinars found</h5>
                    <p class="text-muted">
                        @if(request('status'))
                            No webinars match your filter criteria.
                        @else
                            Check back soon for upcoming sessions!
                        @endif
                    </p>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($webinars->hasPages())
        <div class="row">
            <div class="col-12">
                <nav aria-label="Webinar pagination">
                    {{ $webinars->appends(request()->query())->links() }}
                </nav>
            </div>
        </div>
        @endif
    </div>
</section>

<!-- Call to Action -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h3 class="mb-3">Interested in Hosting a Webinar?</h3>
                <p class="mb-4">Share your expertise with our community of researchers and practitioners.</p>
                <a href="{{ route('contact') }}" class="btn btn-light btn-lg">
                    <i class="bi bi-envelope me-2"></i>Contact Us
                </a>
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