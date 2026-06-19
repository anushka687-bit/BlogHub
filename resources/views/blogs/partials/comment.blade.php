<div class="d-flex gap-2 mb-3" id="comment-{{ $comment->id }}">
    <img src="{{ $comment->user->profileImageUrl() }}" class="bh-avatar" alt="{{ $comment->user->name }}">
    <div class="flex-grow-1">
        <div class="bg-light rounded-3 p-3">
            <div class="d-flex justify-content-between align-items-start">
                <span class="fw-semibold">{{ $comment->user->name }}</span>
                <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
            </div>
            <p class="mb-0 comment-text">{{ $comment->comment }}</p>
        </div>

        <div class="d-flex gap-3 mt-1 small">
            @auth
                <a href="#" class="text-muted bh-reply-toggle" data-comment-id="{{ $comment->id }}">Reply</a>
                @if ($comment->user_id === auth()->id())
                    <a href="#" class="text-muted bh-comment-edit" data-comment-id="{{ $comment->id }}">Edit</a>
                @endif
                @if ($comment->user_id === auth()->id() || auth()->user()->is_admin)
                    <a href="#" class="text-danger bh-comment-delete" data-comment-id="{{ $comment->id }}" data-url="{{ route('comments.destroy', $comment) }}">Delete</a>
                @endif
            @endauth
        </div>

        @auth
            <form class="bh-reply-form d-none mt-2" data-comment-id="{{ $comment->id }}" data-url="{{ route('comments.store', $blog) }}">
                @csrf
                <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                <div class="input-group">
                    <input type="text" name="comment" class="form-control bh-form-control" placeholder="Write a reply...">
                    <button type="submit" class="btn btn-gradient">Reply</button>
                </div>
            </form>
        @endauth

        <div class="bh-replies-list mt-3 ps-3 border-start" id="replies-{{ $comment->id }}">
            @foreach ($comment->replies as $reply)
                @include('blogs.partials.comment', ['comment' => $reply, 'blog' => $blog])
            @endforeach
        </div>
    </div>
</div>