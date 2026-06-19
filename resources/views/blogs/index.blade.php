<x-layout title="Browse Blogs – BlogHub">

    <section class="bh-section" style="padding-top: 7rem;">
        <div class="container">

            <h2 class="bh-section-title">Browse Blogs</h2>

            {{-- Search + Filter Bar --}}
            <div class="row g-3 mb-4">
                <div class="col-md-7">
                    <div class="input-group">
                        <span class="input-group-text bg-white" style="border:1.5px solid var(--bh-border); border-right:none; border-radius:10px 0 0 10px;">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" id="bhSearchInput"
                               class="form-control bh-form-control"
                               style="border-left:none; border-radius:0 10px 10px 0;"
                               placeholder="Search by title, author, category, or tag..."
                               value="{{ request('search') }}"
                               autocomplete="off">
                    </div>
                </div>
                <div class="col-md-5">
                    <select id="bhCategoryFilter" class="form-select bh-form-control">
                        <option value="all">All Categories</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div id="bhResultsInfo" class="text-muted small mb-3 d-none"></div>

            {{-- Blog grid --}}
            <div id="bhBlogGrid" class="row g-4">
                @forelse ($blogs as $blog)
                    <div class="col-lg-4 col-md-6">
                        @include('blogs.partials.card', ['blog' => $blog])
                    </div>
                @empty
                    <div class="col-12">
                        <div class="bh-empty-state">
                            <i class="bi bi-journal-x"></i>
                            <p>No blogs found.</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <div id="bhPagination" class="mt-5 d-flex justify-content-center">
                {{ $blogs->links('pagination::bootstrap-5') }}
            </div>

        </div>
    </section>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput    = document.getElementById('bhSearchInput');
            const categoryFilter = document.getElementById('bhCategoryFilter');
            const grid           = document.getElementById('bhBlogGrid');
            const pagination     = document.getElementById('bhPagination');
            const resultsInfo    = document.getElementById('bhResultsInfo');
            let debounceTimer;

            function renderCards(html, count) {
                grid.innerHTML = html ||
                    '<div class="col-12"><div class="bh-empty-state">' +
                    '<i class="bi bi-journal-x"></i><p>No blogs found.</p>' +
                    '</div></div>';
                pagination.style.display = 'none';
                resultsInfo.classList.remove('d-none');
                resultsInfo.textContent = count + (count === 1 ? ' blog found' : ' blogs found');
            }

            function fetchFiltered() {
                const search   = searchInput.value.trim();
                const category = categoryFilter.value;

                if (!search && category === 'all') {
                    pagination.style.display = '';
                    resultsInfo.classList.add('d-none');
                    return;
                }

                if (search) {
                    fetch(`{{ route('blogs.search') }}?q=${encodeURIComponent(search)}`)
                        .then(r => r.json())
                        .then(data => renderCards(data.html, data.count));
                } else {
                    fetch(`{{ route('blogs.category') }}?category=${encodeURIComponent(category)}`)
                        .then(r => r.json())
                        .then(data => renderCards(data.html, data.count));
                }
            }

            searchInput.addEventListener('input', () => {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(fetchFiltered, 350);
            });

            categoryFilter.addEventListener('change', fetchFiltered);
        });
    </script>
    @endpush

</x-layout>