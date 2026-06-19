<x-layout title="Edit Blog – BlogHub">

    <section class="bh-section" style="padding-top: 7rem;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="bh-card p-4 p-md-5">
                        <h2 class="bh-section-title">Edit Blog</h2>

                        <form action="{{ route('blogs.update', $blog) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Title</label>
                                <input type="text" name="title" class="form-control bh-form-control @error('title') is-invalid @enderror" value="{{ old('title', $blog->title) }}">
                                @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Cover Image</label>
                                <input type="file" name="cover_image" id="coverImageInput" data-image-input="coverPreview" accept="image/*" class="form-control bh-form-control @error('cover_image') is-invalid @enderror">
                                @error('cover_image') <div class="invalid-feedback">{{ $message }}</div> @enderror

                                <div id="coverPreview" class="bh-image-preview mt-3">
                                    <img src="{{ $blog->coverImageUrl() }}" alt="{{ $blog->title }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Category</label>
                                    <select name="category" class="form-select bh-form-control @error('category') is-invalid @enderror">
                                        @foreach ($categories as $cat)
                                            <option value="{{ $cat }}" {{ old('category', $blog->category) === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                        @endforeach
                                    </select>
                                    @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Tags <span class="text-muted small">(comma separated)</span></label>
                                    <input type="text" name="tags" class="form-control bh-form-control" value="{{ old('tags', $selectedTags) }}">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Short Description</label>
                                <textarea name="short_description" rows="2" class="form-control bh-form-control @error('short_description') is-invalid @enderror">{{ old('short_description', $blog->short_description) }}</textarea>
                                @error('short_description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Content</label>
                                <textarea name="content" rows="10" class="form-control bh-form-control @error('content') is-invalid @enderror">{{ old('content', $blog->content) }}</textarea>
                                @error('content') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold d-block">Status</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" value="draft" id="statusDraft" {{ old('status', $blog->status) === 'draft' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="statusDraft">Draft</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" value="published" id="statusPublished" {{ old('status', $blog->status) === 'published' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="statusPublished">Published</label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-gradient px-5 py-2">
                                <i class="bi bi-check2-circle me-2"></i>Update Blog
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

</x-layout>