<x-layout title="BlogHub – Community Blogging Platform">

    {{-- HERO SECTION --}}
    <section class="bh-hero">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <h1 class="mb-4">Share Your Story. <br>Inspire the World.</h1>
                    <p class="lead mb-5">
                        BlogHub is a community blogging platform where writers, thinkers, and creators
                        publish ideas that matter. Join thousands of authors today.
                    </p>
                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        @guest
                            <a href="{{ route('register') }}" class="btn btn-light btn-lg fw-semibold px-4">
                                <i class="bi bi-person-plus me-2"></i>Register
                            </a>
                            <a href="{{ route('login') }}" class="btn btn-light-outline btn-lg px-4">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Login
                            </a>
                        @endguest
                        <a href="{{ route('blogs.index') }}" class="btn btn-light-outline btn-lg px-4">
                            <i class="bi bi-collection me-2"></i>Browse Blogs
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- LATEST BLOGS --}}
    <section class="bh-section bh-bg-white-section">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <h2 class="bh-section-title">Latest Blogs</h2>
                <a href="{{ route('blogs.index') }}" class="btn btn-outline-gradient btn-sm mb-3">View All <i class="bi bi-arrow-right ms-1"></i></a>
            </div>

            @if ($latestBlogs->isEmpty())
                <div class="bh-empty-state">
                    <i class="bi bi-journal-x"></i>
                    <p>No blogs published yet. Be the first to share your story!</p>
                </div>
            @else
                <div class="row g-4">
                    @foreach ($latestBlogs as $blog)
                        <div class="col-lg-4 col-md-6">
                            @include('blogs.partials.card', ['blog' => $blog])
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- TRENDING BLOGS --}}
    @if ($trendingBlogs->isNotEmpty())
        <section class="bh-section">
            <div class="container">
                <h2 class="bh-section-title"><i class="bi bi-fire text-danger me-2"></i>Trending Blogs</h2>

                <div class="row g-4">
                    @foreach ($trendingBlogs as $blog)
                        <div class="col-lg-3 col-md-6">
                            @include('blogs.partials.card', ['blog' => $blog])
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- FEATURED AUTHORS --}}
    @if ($featuredAuthors->isNotEmpty())
        <section class="bh-section bh-bg-white-section">
            <div class="container">
                <h2 class="bh-section-title text-center">Featured Authors</h2>

                <div class="row g-4 justify-content-center">
                    @foreach ($featuredAuthors as $author)
                        <div class="col-lg-3 col-md-6">
                            <div class="bh-author-card">
                                <img src="{{ $author->profileImageUrl() }}" class="bh-avatar-lg mb-3" alt="{{ $author->name }}">
                                <h6 class="fw-bold mb-1">{{ $author->name }}</h6>
                                <p class="text-muted small mb-2">{{ $author->blogs_count }} {{ Str::plural('blog', $author->blogs_count) }}</p>
                                <p class="text-muted small">{{ Str::limit($author->bio, 60) ?: 'BlogHub author' }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- CATEGORIES --}}
    @if ($categories->isNotEmpty())
        <section class="bh-section">
            <div class="container">
                <h2 class="bh-section-title text-center">Explore Categories</h2>

                <div class="row g-3 justify-content-center">
                    @foreach ($categories as $cat)
                        <div class="col-auto">
                            <a href="{{ route('blogs.index', ['category' => $cat->category]) }}"
                               class="btn btn-outline-gradient px-4 py-2">
                                {{ $cat->category }}
                                <span class="badge bg-light text-primary ms-2">{{ $cat->total }}</span>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

</x-layout>