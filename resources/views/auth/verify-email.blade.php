<x-guest-layout title="Verify Email – BlogHub">

    <div class="text-center mb-4">
        <i class="bi bi-envelope-check" style="font-size: 2.2rem; color: var(--bh-primary);"></i>
        <h3 class="fw-bold mt-2">Verify Your Email</h3>
        <p class="text-muted small">
            Thanks for signing up! Before getting started, please verify your email by clicking the link we just emailed you.
        </p>
    </div>

    @if (session('status') === 'verification-link-sent')
        <div class="alert alert-success">
            A new verification link has been sent to the email address you provided during registration.
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn btn-gradient">Resend Verification Email</button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-gradient">Log Out</button>
        </form>
    </div>

</x-guest-layout>