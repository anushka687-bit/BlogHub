<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'BlogHub – Community Blogging Platform' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

    @include('partials.navbar')

    <main class="d-flex align-items-center justify-content-center" style="min-height: 100vh; background: var(--bh-light-bg); padding-top: 6rem; padding-bottom: 3rem;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="{{ $columnClass ?? 'col-lg-5 col-md-7' }}">

                    @if (session('status'))
                        <div class="alert alert-success bh-fade-in">{{ session('status') }}</div>
                    @endif

                    <div class="bh-card p-4 p-md-5">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </main>

</body>
</html>