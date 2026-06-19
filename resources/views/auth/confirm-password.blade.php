<x-guest-layout title="Confirm Password – BlogHub">

    <div class="text-center mb-4">
        <i class="bi bi-shield-lock" style="font-size: 2.2rem; color: var(--bh-primary);"></i>
        <h3 class="fw-bold mt-2">Confirm Password</h3>
        <p class="text-muted small">Please confirm your password before continuing.</p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="mb-4">
            <label for="password" class="form-label fw-semibold">Password</label>
            <input id="password" type="password" name="password"
                   class="form-control bh-form-control @error('password') is-invalid @enderror"
                   required autocomplete="current-password" autofocus>
            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-gradient w-100 py-2">Confirm</button>
    </form>

</x-guest-layout>