{{-- resources/views/blogs/partials/card.blade.php --}}
<article class="bh-card">

    {{-- Cover image --}}
    <a href="{{ route('blogs.show', $blog->slug) }}" class="bh-card-img-wrap" tabindex="-1">
        <img
            src="{{ $blog->coverImageUrl() }}"
            alt="{{ $blog->title }}"
            loading="lazy"
        >
        @if ($blog->category)
            <span class="bh-card-category">{{ $blog->category }}</span>
        @endif
    </a>

    {{-- Body --}}
    <div class="bh-card-body">

        {{-- Author + date --}}
        <div class="bh-card-meta">
            <span>{{ $blog->user->name ?? 'Unknown' }}</span>
            <span>·</span>
            <span>{{ $blog->created_at->format('M j, Y') }}</span>
            <span>·</span>
            <span>{{ $blog->readingTime() }} min read</span>
        </div>

        {{-- Title --}}
        <a href="{{ route('blogs.show', $blog->slug) }}" class="bh-card-title">
            {{ $blog->title }}
        </a>

        {{-- Short description --}}
        @if ($blog->short_description)
            <p class="bh-card-desc">{{ $blog->short_description }}</p>
        @endif

        {{-- Tags --}}
        @if ($blog->tags->count())
            <div class="bh-card-tags mb-3">
                @foreach ($blog->tags->take(3) as $tag)
                    <span class="bh-tag">{{ $tag->name }}</span>
                @endforeach
            </div>
        @endif

        {{-- Footer: stats + read link --}}
        <div class="bh-card-footer">
            <div class="bh-card-stats">
                <span title="Views">
                    <i class="bi bi-eye"></i> {{ number_format($blog->views) }}
                </span>
                <span title="Likes">
                    <i class="bi bi-heart"></i> {{ $blog->likes_count ?? $blog->likes->count() }}
                </span>
                <span title="Comments">
                    <i class="bi bi-chat"></i> {{ $blog->comments_count ?? $blog->comments->count() }}
                </span>
            </div>
            <a href="{{ route('blogs.show', $blog->slug) }}" class="bh-card-read-btn">
                Read <i class="bi bi-arrow-right"></i>
            </a>
        </div>

    </div>
</article>