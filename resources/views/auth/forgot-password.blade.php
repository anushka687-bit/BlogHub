<x-guest-layout title="Forgot Password – BlogHub">

    <div class="text-center mb-4">
        <i class="bi bi-key" style="font-size: 2.2rem; color: var(--bh-primary);"></i>
        <h3 class="fw-bold mt-2">Forgot Password</h3>
        <p class="text-muted small">Enter your email and we'll send you a password reset link.</p>
    </div>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-4">
            <label for="email" class="form-label fw-semibold">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   class="form-control bh-form-control @error('email') is-invalid @enderror"
                   required autofocus placeholder="you@example.com">
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-gradient w-100 py-2">
            <i class="bi bi-send me-2"></i>Email Password Reset Link
        </button>
    </form>

</x-guest-layout>