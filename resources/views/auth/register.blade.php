<x-guest-layout title="Register – BlogHub" column-class="col-lg-7 col-md-9">

    <style>
        .bh-card {
            border: none !important;
            border-radius: 20px !important;
            box-shadow: 0 15px 35px rgba(0,0,0,0.04), 0 5px 15px rgba(0,0,0,0.02) !important;
            background: #ffffff !important;
            border: 1px solid rgba(0, 0, 0, 0.05) !important;
            padding: 2.5rem !important;
        }

        .bh-form-label {
            color: var(--bh-text-dark);
            font-weight: 600;
            font-size: 0.92rem;
            margin-bottom: 0.4rem;
        }

        .bh-form-control {
            border-radius: 12px !important;
            border: 1.5px solid rgba(0, 0, 0, 0.08) !important;
            padding: 0.7rem 1rem !important;
            font-size: 0.95rem !important;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }

        .bh-form-control:focus {
            border-color: var(--bh-primary) !important;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.08) !important;
            background-color: #fff !important;
        }

        .btn-gradient {
            background: linear-gradient(135deg, var(--bh-primary) 0%, var(--bh-secondary) 100%);
            border: none;
            color: #fff;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .btn-gradient:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.2);
            opacity: 0.95;
        }

        .bh-password-toggle-btn {
            border: 1.5px solid rgba(0, 0, 0, 0.08);
            border-left: none;
            background-color: #fff;
            color: var(--bh-text-muted);
            transition: all 0.2s ease;
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }

        .bh-password-toggle-btn:hover {
            color: var(--bh-text-dark);
            background-color: #f9fafb;
        }

        .input-group:has(.is-invalid) .bh-password-toggle-btn {
            border-color: #dc3545 !important;
        }

        #profile-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        body {
            background: radial-gradient(circle at 10% 20%, rgba(235, 244, 255, 0.4) 0%, rgba(243, 244, 246, 0.4) 100%) !important;
        }
    </style>

    <div class="text-center mb-4">
        <i class="bi bi-pencil-square" style="font-size: 2.2rem; color: var(--bh-primary);"></i>
        <h3 class="fw-bold mt-2">Create Your Account</h3>
        <p class="text-muted small">Join BlogHub and start sharing your stories.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
        @csrf

        {{-- 1. Profile Picture Section --}}
        <div class="d-flex flex-column align-items-center mb-4">
            <div class="position-relative" style="cursor: pointer;" onclick="document.getElementById('profile_image').click();">
                <div id="profile-preview" class="rounded-circle border border-4 border-white shadow-sm overflow-hidden d-flex align-items-center justify-content-center" style="width: 130px; height: 130px; background: #f3f4f6; transition: all 0.3s ease;">
                    <i class="bi bi-person-circle text-secondary" style="font-size: 5.5rem; line-height: 1;"></i>
                </div>
                <div class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 36px; height: 36px; border: 3px solid #fff;">
                    <i class="bi bi-camera-fill" style="font-size: 1rem;"></i>
                </div>
            </div>
            <button type="button" class="btn btn-link btn-sm text-decoration-none mt-2 text-primary fw-semibold" onclick="document.getElementById('profile_image').click();">
                Upload Profile Photo
            </button>
            <input id="profile_image" type="file" name="profile_image" data-image-input="profile-preview" class="d-none @error('profile_image') is-invalid @enderror" accept="image/*">
            @error('profile_image') <div class="text-danger small mt-1 text-center">{{ $message }}</div> @enderror
        </div>

        {{-- 2. Full Name --}}
        <div class="mb-3">
            <label for="name" class="form-label bh-form-label">Full Name <span class="text-danger">*</span></label>
            <input id="name" type="text" name="name" value="{{ old('name') }}"
                   class="form-control bh-form-control @error('name') is-invalid @enderror"
                   required autofocus autocomplete="name" placeholder="John Doe">
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- 3. Email Address --}}
        <div class="mb-3">
            <label for="email" class="form-label bh-form-label">Email Address <span class="text-danger">*</span></label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   class="form-control bh-form-control @error('email') is-invalid @enderror"
                   required autocomplete="username" placeholder="you@example.com">
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- 4 & 5. Password and Confirm Password --}}
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="password" class="form-label bh-form-label">Password <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input id="password" type="password" name="password"
                           class="form-control bh-form-control @error('password') is-invalid @enderror"
                           required autocomplete="new-password" placeholder="At least 8 characters">
                    <button class="btn bh-password-toggle-btn" type="button" onclick="togglePasswordVisibility('password', this)" style="border-top-right-radius: 12px; border-bottom-right-radius: 12px;">
                        <i class="bi bi-eye"></i>
                    </button>
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <label for="password_confirmation" class="form-label bh-form-label">Confirm Password <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input id="password_confirmation" type="password" name="password_confirmation"
                           class="form-control bh-form-control @error('password_confirmation') is-invalid @enderror"
                           required autocomplete="new-password" placeholder="Re-enter password">
                    <button class="btn bh-password-toggle-btn" type="button" onclick="togglePasswordVisibility('password_confirmation', this)" style="border-top-right-radius: 12px; border-bottom-right-radius: 12px;">
                        <i class="bi bi-eye"></i>
                    </button>
                    @error('password_confirmation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        {{-- 6. Age and Gender (same row) --}}
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="age" class="form-label bh-form-label">Age</label>
                <input id="age" type="number" name="age" value="{{ old('age') }}"
                       class="form-control bh-form-control @error('age') is-invalid @enderror"
                       placeholder="e.g. 25">
                @error('age') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="gender" class="form-label bh-form-label">Gender</label>
                <select id="gender" name="gender" class="form-select bh-form-control @error('gender') is-invalid @enderror">
                    <option value="" disabled {{ old('gender') === null ? 'selected' : '' }}>Select Gender</option>
                    <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                    <option value="other" {{ old('gender') === 'other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        {{-- 7. Phone Number --}}
        <div class="mb-3">
            <label for="phone_number" class="form-label bh-form-label">Phone Number</label>
            <input id="phone_number" type="text" name="phone_number" value="{{ old('phone_number') }}"
                   class="form-control bh-form-control @error('phone_number') is-invalid @enderror"
                   placeholder="10-digit mobile number">
            @error('phone_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- 8. Bio --}}
        <div class="mb-4">
            <label for="bio" class="form-label bh-form-label">Bio</label>
            <textarea id="bio" name="bio" class="form-control bh-form-control @error('bio') is-invalid @enderror"
                      rows="3" placeholder="Tell us about yourself...">{{ old('bio') }}</textarea>
            @error('bio') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- 9. Register Button --}}
        <button type="submit" class="btn btn-gradient w-100 py-2 mb-3 shadow-sm">
            <i class="bi bi-person-plus-fill me-2"></i>Register Account
        </button>

        <p class="text-center text-muted small mb-0 mt-3">
            Already have an account? <a href="{{ route('login') }}" class="fw-semibold text-primary text-decoration-none">Login</a>
        </p>
    </form>

    <script>
        function togglePasswordVisibility(fieldId, button) {
            const field = document.getElementById(fieldId);
            const icon = button.querySelector('i');
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }
    </script>

</x-guest-layout>