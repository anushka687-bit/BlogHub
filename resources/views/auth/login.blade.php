<x-guest-layout title="Login – BlogHub">

    <div class="text-center mb-4">
        <i class="bi bi-pencil-square" style="font-size: 2.2rem; color: var(--bh-primary);"></i>
        <h3 class="fw-bold mt-2">Welcome Back</h3>
        <p class="text-muted small">Login to continue to BlogHub.</p>
    </div>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label fw-semibold">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   class="form-control bh-form-control @error('email') is-invalid @enderror"
                   required autofocus autocomplete="username" placeholder="you@example.com">
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label fw-semibold">Password</label>
            <input id="password" type="password" name="password"
                   class="form-control bh-form-control @error('password') is-invalid @enderror"
                   required autocomplete="current-password" placeholder="Your password">
            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label small" for="remember">Remember me</label>
            </div>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="small">Forgot password?</a>
            @endif
        </div>

        <button type="submit" class="btn btn-gradient w-100 py-2 mb-3">
            <i class="bi bi-box-arrow-in-right me-2"></i>Login
        </button>

        <p class="text-center text-muted small mb-0">
            Don't have an account? <a href="{{ route('register') }}" class="fw-semibold">Register</a>
        </p>
    </form>

</x-guest-layout>