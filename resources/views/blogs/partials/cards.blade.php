<article class="bh-card h-100">

```
<a href="{{ route('blogs.show', $blog->slug) }}" class="bh-card-img-wrap">
    <img
        src="{{ $blog->coverImageUrl() }}"
        alt="{{ $blog->title }}"
        class="img-fluid"
        loading="lazy"
    >

    @if($blog->category)
        <span class="bh-card-category">
            {{ $blog->category }}
        </span>
    @endif
</a>

<div class="bh-card-body">

    <div class="bh-card-meta">
        <span>{{ $blog->user->name ?? 'Unknown Author' }}</span>
        <span>•</span>
        <span>{{ $blog->created_at->format('M j, Y') }}</span>
        <span>•</span>
        <span>{{ $blog->readingTime() }} min read</span>
    </div>

    <a href="{{ route('blogs.show', $blog->slug) }}" class="bh-card-title">
        {{ $blog->title }}
    </a>

    @if($blog->short_description)
        <p class="bh-card-desc">
            {{ Str::limit($blog->short_description, 120) }}
        </p>
    @endif

    @if($blog->tags->count())
        <div class="bh-card-tags">
            @foreach($blog->tags->take(3) as $tag)
                <span class="bh-tag">{{ $tag->name }}</span>
            @endforeach
        </div>
    @endif

    <div class="bh-card-footer mt-auto">

        <div class="bh-card-stats">
            <span>
                <i class="bi bi-eye"></i>
                {{ number_format($blog->views) }}
            </span>

            <span>
                <i class="bi bi-heart"></i>
                {{ $blog->likes_count ?? $blog->likes->count() }}
            </span>

            <span>
                <i class="bi bi-chat"></i>
                {{ $blog->comments_count ?? $blog->comments->count() }}
            </span>
        </div>

        <a href="{{ route('blogs.show', $blog->slug) }}" class="bh-card-read-btn">
            Read →
        </a>

    </div>

</div>
```

</article>
