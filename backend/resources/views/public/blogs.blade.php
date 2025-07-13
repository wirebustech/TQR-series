@extends('public.layout')

@section('title', 'Blog')
@section('description', 'Read the latest insights, tips, and discussions on qualitative research from The Qualitative Research Series.')

@section('content')
<!-- Page Header -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-5 fw-bold mb-3">Our Blog</h1>
                <p class="lead text-muted">Insights, tips, and discussions on qualitative research methodologies and best practices.</p>
                
                <!-- Search Form -->
                <form method="GET" action="{{ route('blogs') }}" class="mt-4">
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" placeholder="Search articles..." value="{{ request('search') }}">
                                <button class="btn btn-primary" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Blog Posts -->
<section class="py-5">
    <div class="container">
        @if(request('search'))
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="bi bi-search me-2"></i>
                    Search results for: <strong>"{{ request('search') }}"</strong>
                    <a href="{{ route('blogs') }}" class="float-end">Clear search</a>
                </div>
            </div>
        </div>
        @endif

        <div class="row">
            @forelse($blogs as $blog)
            <div class="col-lg-4 col-md-6 mb-4">
                <article class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="{{ route('blog', $blog->slug) }}" class="text-decoration-none text-dark">
                                {{ $blog->title }}
                            </a>
                        </h5>
                        <p class="card-text text-muted">{{ Str::limit($blog->excerpt, 120) }}</p>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="bi bi-calendar me-1"></i>
                                {{ $blog->published_at->format('M d, Y') }}
                            </small>
                            <a href="{{ route('blog', $blog->slug) }}" class="btn btn-outline-primary btn-sm">
                                Read More
                            </a>
                        </div>
                    </div>
                </article>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="bi bi-journal-text fs-1 text-muted mb-3"></i>
                    <h5>No blog posts found</h5>
                    <p class="text-muted">
                        @if(request('search'))
                            No posts match your search criteria.
                        @else
                            Check back soon for new content!
                        @endif
                    </p>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($blogs->hasPages())
        <div class="row">
            <div class="col-12">
                <nav aria-label="Blog pagination">
                    {{ $blogs->appends(request()->query())->links() }}
                </nav>
            </div>
        </div>
        @endif
    </div>
</section>

<!-- Newsletter Section -->
<section class="newsletter-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h3 class="mb-3">Stay Updated</h3>
                <p class="text-muted mb-4">Get notified when we publish new articles and insights.</p>
                
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