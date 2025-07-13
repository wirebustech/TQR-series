@extends('public.layout')

@section('title', $blog->title)
@section('description', $blog->excerpt)
@section('og_image', asset('images/blog-' . $blog->id . '.jpg'))

@section('content')
<!-- Blog Post -->
<article class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('blogs') }}">Blog</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $blog->title }}</li>
                    </ol>
                </nav>

                <!-- Article Header -->
                <header class="mb-4">
                    <h1 class="display-4 fw-bold mb-3">{{ $blog->title }}</h1>
                    
                    <div class="d-flex align-items-center text-muted mb-4">
                        <i class="bi bi-calendar me-2"></i>
                        <span>{{ $blog->published_at->format('F d, Y') }}</span>
                        
                        @if($blog->author)
                        <span class="mx-3">•</span>
                        <i class="bi bi-person me-2"></i>
                        <span>{{ $blog->author->name }}</span>
                        @endif
                    </div>

                    @if($blog->excerpt)
                    <div class="lead text-muted mb-4">
                        {{ $blog->excerpt }}
                    </div>
                    @endif
                </header>

                <!-- Article Content -->
                <div class="article-content mb-5">
                    {!! $blog->content !!}
                </div>

                <!-- Social Sharing -->
                <div class="border-top pt-4 mb-5">
                    <h6 class="mb-3">Share this article:</h6>
                    <div class="d-flex gap-2">
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($blog->title) }}" 
                           class="btn btn-outline-primary btn-sm" target="_blank">
                            <i class="bi bi-twitter me-1"></i>Twitter
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" 
                           class="btn btn-outline-primary btn-sm" target="_blank">
                            <i class="bi bi-facebook me-1"></i>Facebook
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}" 
                           class="btn btn-outline-primary btn-sm" target="_blank">
                            <i class="bi bi-linkedin me-1"></i>LinkedIn
                        </a>
                        <button class="btn btn-outline-secondary btn-sm" onclick="copyToClipboard()">
                            <i class="bi bi-link-45deg me-1"></i>Copy Link
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</article>

<!-- Related Posts -->
@if($relatedBlogs->count() > 0)
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h3 class="mb-4">Related Articles</h3>
            </div>
        </div>
        
        <div class="row">
            @foreach($relatedBlogs as $relatedBlog)
            <div class="col-lg-4 col-md-6 mb-4">
                <article class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="{{ route('blog', $relatedBlog->slug) }}" class="text-decoration-none text-dark">
                                {{ $relatedBlog->title }}
                            </a>
                        </h5>
                        <p class="card-text text-muted">{{ Str::limit($relatedBlog->excerpt, 100) }}</p>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                {{ $relatedBlog->published_at->format('M d, Y') }}
                            </small>
                            <a href="{{ route('blog', $relatedBlog->slug) }}" class="btn btn-outline-primary btn-sm">
                                Read More
                            </a>
                        </div>
                    </div>
                </article>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Newsletter Section -->
<section class="newsletter-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h3 class="mb-3">Enjoyed this article?</h3>
                <p class="text-muted mb-4">Subscribe to our newsletter for more insights and updates.</p>
                
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
function copyToClipboard() {
    navigator.clipboard.writeText(window.location.href).then(function() {
        // Show success message
        const button = event.target.closest('button');
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="bi bi-check me-1"></i>Copied!';
        button.classList.remove('btn-outline-secondary');
        button.classList.add('btn-success');
        
        setTimeout(function() {
            button.innerHTML = originalText;
            button.classList.remove('btn-success');
            button.classList.add('btn-outline-secondary');
        }, 2000);
    });
}
</script>
@endpush 