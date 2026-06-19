<x-layout title="Dashboard – BlogHub">

    <section class="bh-section" style="padding-top: 7rem;">
        <div class="container">
            <h2 class="bh-section-title">Welcome back, {{ auth()->user()->name }}!</h2>

            {{-- Stat Cards --}}
            <div class="row g-4 mb-5">
                <div class="col-lg-3 col-md-6">
                    <div class="bh-stat-card d-flex align-items-center gap-3">
                        <div class="stat-icon"><i class="bi bi-journal-text"></i></div>
                        <div>
                            <div class="stat-value">{{ $totalBlogs }}</div>
                            <div class="stat-label">Total Blogs</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="bh-stat-card d-flex align-items-center gap-3">
                        <div class="stat-icon"><i class="bi bi-check-circle"></i></div>
                        <div>
                            <div class="stat-value">{{ $publishedBlogs }}</div>
                            <div class="stat-label">Published</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="bh-stat-card d-flex align-items-center gap-3">
                        <div class="stat-icon"><i class="bi bi-file-earmark"></i></div>
                        <div>
                            <div class="stat-value">{{ $draftBlogs }}</div>
                            <div class="stat-label">Drafts</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="bh-stat-card d-flex align-items-center gap-3">
                        <div class="stat-icon"><i class="bi bi-heart"></i></div>
                        <div>
                            <div class="stat-value">{{ $totalLikes }}</div>
                            <div class="stat-label">Total Likes</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                {{-- Recent Blogs --}}
                <div class="col-lg-8">
                    <div class="bh-card p-4">
                        <h5 class="fw-bold mb-4">Recent Blogs</h5>

                        @forelse ($recentBlogs as $blog)
                            <div class="d-flex align-items-center justify-content-between py-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ $blog->coverImageUrl() }}" class="rounded-3" style="width:60px;height:60px;object-fit:cover;" alt="{{ $blog->title }}">
                                    <div>
                                        <a href="{{ route('blogs.show', $blog->slug) }}" class="fw-semibold text-dark">{{ Str::limit($blog->title, 40) }}</a>
                                        <div class="small text-muted">
                                            <span class="badge {{ $blog->status === 'published' ? 'bg-success' : 'bg-secondary' }}">{{ ucfirst($blog->status) }}</span>
                                            {{ $blog->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                                <div class="text-muted small">
                                    <i class="bi bi-heart-fill text-danger me-1"></i>{{ $blog->likes_count }}
                                    <i class="bi bi-chat-dots-fill text-primary ms-3 me-1"></i>{{ $blog->comments_count }}
                                </div>
                            </div>
                        @empty
                            <div class="bh-empty-state">
                                <i class="bi bi-journal-x"></i>
                                <p>You haven't written any blogs yet.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Quick Actions --}}
                <div class="col-lg-4">
                    <div class="bh-card p-4">
                        <h5 class="fw-bold mb-4">Quick Actions</h5>
                        <div class="d-flex flex-column gap-2">
                            <a href="{{ route('blogs.create') }}" class="btn btn-gradient"><i class="bi bi-plus-circle me-2"></i>Create Blog</a>
                            <a href="{{ route('my-blogs') }}" class="btn btn-outline-gradient"><i class="bi bi-journal-text me-2"></i>My Blogs</a>
                            <a href="{{ route('profile.edit') }}" class="btn btn-outline-gradient"><i class="bi bi-person me-2"></i>Edit Profile</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-gradient w-100"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</x-layout>