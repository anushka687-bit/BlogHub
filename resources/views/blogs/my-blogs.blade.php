<x-layout title="My Blogs – BlogHub">

    <section class="bh-section" style="padding-top: 7rem;">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
                <h2 class="bh-section-title mb-0">My Blogs</h2>
                <a href="{{ route('blogs.create') }}" class="btn btn-gradient">
                    <i class="bi bi-plus-circle me-2"></i>Create Blog
                </a>
            </div>

            <div class="bh-card p-3 p-md-4">
                @if ($blogs->isEmpty())
                    <div class="bh-empty-state">
                        <i class="bi bi-journal-x"></i>
                        <p>You haven't created any blogs yet.</p>
                        <a href="{{ route('blogs.create') }}" class="btn btn-gradient mt-2">Write Your First Blog</a>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr class="text-muted small">
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Likes</th>
                                    <th>Comments</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($blogs as $blog)
                                    <tr>
                                        <td>
                                            <img src="{{ $blog->coverImageUrl() }}" style="width:60px;height:60px;object-fit:cover;border-radius:10px;" alt="{{ $blog->title }}">
                                        </td>
                                        <td>
                                            <a href="{{ route('blogs.show', $blog->slug) }}" class="fw-semibold text-dark">{{ Str::limit($blog->title, 40) }}</a>
                                            <div class="text-muted small">{{ $blog->category }}</div>
                                        </td>
                                        <td>
                                            <span class="badge {{ $blog->status === 'published' ? 'bg-success' : 'bg-secondary' }}">
                                                {{ ucfirst($blog->status) }}
                                            </span>
                                        </td>
                                        <td><i class="bi bi-heart-fill text-danger me-1"></i>{{ $blog->likes_count }}</td>
                                        <td><i class="bi bi-chat-dots-fill text-primary me-1"></i>{{ $blog->comments_count }}</td>
                                        <td class="text-muted small">{{ $blog->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('blogs.show', $blog->slug) }}" class="btn btn-sm btn-outline-gradient" title="View"><i class="bi bi-eye"></i></a>
                                                <a href="{{ route('blogs.edit', $blog) }}" class="btn btn-sm btn-outline-gradient" title="Edit"><i class="bi bi-pencil"></i></a>

                                                @if ($blog->status === 'draft')
                                                    <form action="{{ route('blogs.publish', $blog) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-sm btn-outline-gradient" title="Publish"><i class="bi bi-upload"></i></button>
                                                    </form>
                                                @endif

                                                <form action="{{ route('blogs.destroy', $blog) }}" method="POST" onsubmit="return confirm('Delete this blog permanently?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $blogs->links() }}
                    </div>
                @endif
            </div>
        </div>
    </section>

</x-layout>