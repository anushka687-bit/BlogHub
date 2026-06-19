<x-layout :title="$blog->title . ' – BlogHub'">

    <article class="bh-section" style="padding-top: 7rem;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-9">

                    <span class="bh-badge-category mb-3 d-inline-block">{{ $blog->category }}</span>
                    <h1 class="fw-bold mb-3">{{ $blog->title }}</h1>

                    <div class="d-flex flex-wrap align-items-center gap-4 text-muted small mb-4">
                        <div class="d-flex align-items-center">
                            <img src="{{ $blog->user->profileImageUrl() }}" class="bh-avatar me-2" alt="{{ $blog->user->name }}">
                            <span class="fw-semibold text-dark">{{ $blog->user->name }}</span>
                        </div>
                        <span><i class="bi bi-calendar3 me-1"></i>{{ $blog->created_at->format('M d, Y') }}</span>
                        <span><i class="bi bi-clock me-1"></i>{{ $blog->readingTime() }} min read</span>
                        <span><i class="bi bi-eye me-1"></i>{{ number_format($blog->views) }} views</span>
                    </div>

                    <img src="{{ $blog->coverImageUrl() }}" class="img-fluid rounded-4 shadow-sm mb-4 w-100" style="max-height: 480px; object-fit: cover;" alt="{{ $blog->title }}">

                    <div class="bh-content fs-5 mb-4" style="line-height: 1.8;">
                        {!! $blog->content !!}
                    </div>

                    @if ($blog->tags->isNotEmpty())
                        <div class="mb-4">
                            @foreach ($blog->tags as $tag)
                                <span class="badge rounded-pill bg-light text-primary border me-2 mb-2 px-3 py-2">#{{ $tag->name }}</span>
                            @endforeach
                        </div>
                    @endif

                    <div class="bh-divider"></div>

                    {{-- Like + Share --}}
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                        <button
                            id="bhLikeBtn"
                            class="bh-like-btn {{ $blog->isLikedBy(auth()->id()) ? 'liked' : '' }}"
                            data-blog-id="{{ $blog->id }}"
                            data-like-url="{{ route('blogs.like', $blog) }}"
                            {{ auth()->guest() ? 'data-guest=true' : '' }}
                        >
                            <i class="bi {{ $blog->isLikedBy(auth()->id()) ? 'bi-heart-fill' : 'bi-heart' }} me-2"></i>
                            <span id="bhLikeCount">{{ $blog->likes->count() }}</span> Likes
                        </button>

                        <button class="btn btn-outline-gradient" onclick="navigator.clipboard.writeText(window.location.href); this.innerHTML='<i class=\'bi bi-check2\'></i> Copied!'">
                            <i class="bi bi-share me-2"></i>Share
                        </button>
                    </div>

                    <div class="bh-divider"></div>

                    {{-- Comments --}}
                    <h4 class="fw-bold mb-4"><i class="bi bi-chat-dots me-2"></i>Comments (<span id="bhCommentsCount">{{ $blog->allComments->count() }}</span>)</h4>

                    @auth
                        <form id="bhCommentForm" class="mb-4" data-url="{{ route('comments.store', $blog) }}">
                            @csrf
                            <div class="d-flex gap-2">
                                <img src="{{ auth()->user()->profileImageUrl() }}" class="bh-avatar" alt="{{ auth()->user()->name }}">
                                <div class="flex-grow-1">
                                    <textarea name="comment" class="form-control bh-form-control" rows="2" placeholder="Write a comment..." required></textarea>
                                    <div class="invalid-feedback-area text-danger small mt-1"></div>
                                    <button type="submit" class="btn btn-gradient btn-sm mt-2">Post Comment</button>
                                </div>
                            </div>
                        </form>
                    @else
                        <div class="alert alert-light border mb-4">
                            <a href="{{ route('login') }}">Login</a> to join the conversation.
                        </div>
                    @endauth

                    <div id="bhCommentsList">
                        @forelse ($blog->comments as $comment)
                            @include('blogs.partials.comment', ['comment' => $comment, 'blog' => $blog])
                        @empty
                            <p class="text-muted">No comments yet. Be the first to comment!</p>
                        @endforelse
                    </div>

                </div>
            </div>

            {{-- Related Blogs --}}
            @if ($relatedBlogs->isNotEmpty())
                <div class="row justify-content-center mt-5">
                    <div class="col-lg-9">
                        <h4 class="fw-bold mb-4">Related Blogs</h4>
                        <div class="row g-4">
                            @foreach ($relatedBlogs as $related)
                                <div class="col-md-4">
                                    @include('blogs.partials.card', ['blog' => $related])
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </article>

    @push('scripts')
   
    @endpush

</x-layout>