<x-layout title="Edit Profile – BlogHub">

    <section class="bh-section" style="padding-top: 7rem;">
        <div class="container">
            <h2 class="bh-section-title">Account Settings</h2>

            <div class="row g-4">
                {{-- Sidebar Nav --}}
                <div class="col-lg-3">
                    <div class="bh-card p-3">
                        <div class="text-center mb-3 pb-3 border-bottom">
                            <img src="{{ $user->profileImageUrl() }}" class="bh-avatar-lg mb-2" alt="{{ $user->name }}">
                            <h6 class="fw-bold mb-0">{{ $user->name }}</h6>
                            <p class="text-muted small mb-0">{{ $user->email }}</p>
                        </div>

                        <div class="nav flex-column nav-pills" id="profileTabs" role="tablist" aria-orientation="vertical">
                            <button class="nav-link text-start active d-flex align-items-center gap-2 mb-1" id="tab-view-btn" data-bs-toggle="pill" data-bs-target="#tab-view" type="button" role="tab">
                                <i class="bi bi-person-circle"></i> View Profile
                            </button>
                            <button class="nav-link text-start d-flex align-items-center gap-2 mb-1" id="tab-edit-btn" data-bs-toggle="pill" data-bs-target="#tab-edit" type="button" role="tab">
                                <i class="bi bi-pencil-square"></i> Edit Profile
                            </button>
                            <button class="nav-link text-start d-flex align-items-center gap-2 mb-1" id="tab-password-btn" data-bs-toggle="pill" data-bs-target="#tab-password" type="button" role="tab">
                                <i class="bi bi-shield-lock"></i> Update Password
                            </button>
                            <button class="nav-link text-start d-flex align-items-center gap-2 mb-1 text-danger" id="tab-delete-btn" data-bs-toggle="pill" data-bs-target="#tab-delete" type="button" role="tab">
                                <i class="bi bi-trash"></i> Delete Account
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Tab Content --}}
                <div class="col-lg-9">
                    <div class="tab-content" id="profileTabsContent">

                        {{-- VIEW PROFILE --}}
                        <div class="tab-pane fade show active" id="tab-view" role="tabpanel">
                            <div class="bh-card p-4">
                                <div class="text-center mb-4">
                                    <img src="{{ $user->profileImageUrl() }}" class="bh-avatar-lg mb-3" alt="{{ $user->name }}">
                                    <h4 class="fw-bold mb-1">{{ $user->name }}</h4>
                                    <p class="text-muted small mb-3">{{ $user->email }}</p>
                                    <p class="text-muted">{{ $user->bio ?: 'No bio added yet.' }}</p>
                                </div>

                                <div class="row text-center mb-3">
                                    <div class="col-4">
                                        <div class="fw-bold fs-5">{{ $totalBlogs }}</div>
                                        <div class="text-muted small">Blogs</div>
                                    </div>
                                    <div class="col-4">
                                        <div class="fw-bold fs-5">{{ $totalLikes }}</div>
                                        <div class="text-muted small">Likes</div>
                                    </div>
                                    <div class="col-4">
                                        <div class="fw-bold fs-5">{{ $totalComments }}</div>
                                        <div class="text-muted small">Comments</div>
                                    </div>
                                </div>

                                <p class="text-muted small text-center mb-0">Joined {{ $user->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>

                        {{-- EDIT PROFILE --}}
                        <div class="tab-pane fade" id="tab-edit" role="tabpanel">
                            <div class="bh-card p-4">
                                <h5 class="fw-bold mb-4">Edit Profile Information</h5>

                                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                                    @csrf
                                    @method('PATCH')

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Profile Image</label>
                                        <input type="file" name="profile_image" id="avatarInput" data-image-input="avatarPreview" accept="image/*" class="form-control bh-form-control @error('profile_image') is-invalid @enderror">
                                        @error('profile_image') <div class="invalid-feedback">{{ $message }}</div> @enderror

                                        <div id="avatarPreview" class="mt-3">
                                            <img src="{{ $user->profileImageUrl() }}" class="bh-avatar-lg" alt="preview">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Name</label>
                                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control bh-form-control @error('name') is-invalid @enderror">
                                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Email</label>
                                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control bh-form-control @error('email') is-invalid @enderror">
                                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label fw-semibold">Bio</label>
                                        <textarea name="bio" rows="3" class="form-control bh-form-control @error('bio') is-invalid @enderror" placeholder="Tell us about yourself">{{ old('bio', $user->bio) }}</textarea>
                                        @error('bio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <button type="submit" class="btn btn-gradient px-4">Save Changes</button>
                                </form>
                            </div>
                        </div>

                        {{-- UPDATE PASSWORD --}}
                        <div class="tab-pane fade" id="tab-password" role="tabpanel">
                            <div class="bh-card p-4">
                                <h5 class="fw-bold mb-4">Update Password</h5>

                                <form method="POST" action="{{ route('password.update') }}">
                                    @csrf
                                    @method('PUT')

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Current Password</label>
                                        <input type="password" name="current_password" class="form-control bh-form-control @error('current_password', 'updatePassword') is-invalid @enderror">
                                        @error('current_password', 'updatePassword') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">New Password</label>
                                        <input type="password" name="password" class="form-control bh-form-control @error('password', 'updatePassword') is-invalid @enderror">
                                        @error('password', 'updatePassword') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label fw-semibold">Confirm New Password</label>
                                        <input type="password" name="password_confirmation" class="form-control bh-form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror">
                                        @error('password_confirmation', 'updatePassword') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <button type="submit" class="btn btn-gradient px-4">Update Password</button>
                                </form>
                            </div>
                        </div>

                        {{-- DELETE ACCOUNT --}}
                        <div class="tab-pane fade" id="tab-delete" role="tabpanel">
                            <div class="bh-card p-4 border border-danger-subtle">
                                <h5 class="fw-bold text-danger mb-3">Delete Account</h5>
                                <p class="text-muted small">Once your account is deleted, all of its resources and data will be permanently deleted. This action cannot be undone.</p>

                                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                                    Delete My Account
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Delete Confirmation Modal --}}
    <div class="modal fade" id="deleteAccountModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 class="modal-title">Confirm Account Deletion</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted">Please enter your password to confirm you want to permanently delete your account.</p>
                        <input type="password" name="password" class="form-control bh-form-control @error('password', 'userDeletion') is-invalid @enderror" placeholder="Password">
                        @error('password', 'userDeletion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-gradient" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete Account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if ($errors->has('current_password') || $errors->has('password') && session('errors')?->getBag('updatePassword'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                new bootstrap.Tab(document.querySelector('#tab-password-btn')).show();
            });
        </script>
    @endif

</x-layout>