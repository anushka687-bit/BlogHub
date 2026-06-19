<nav class="navbar navbar-expand-lg bh-navbar fixed-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
            <i class="bi bi-pencil-square me-2"></i> BlogHub
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#bhNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="bhNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('blogs.index') ? 'active' : '' }}" href="{{ route('blogs.index') }}">Browse Blogs</a>
                </li>
                @auth
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('my-blogs') ? 'active' : '' }}" href="{{ route('my-blogs') }}">My Blogs</a>
                    </li>
                    @if (auth()->user()->is_admin)
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Admin</a>
                        </li>
                    @endif
                @endauth
            </ul>

            <div class="d-flex align-items-center gap-2">
                @auth
                    <div class="dropdown">
                        <a class="d-flex align-items-center text-decoration-none dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <img src="{{ auth()->user()->profileImageUrl() }}" class="bh-avatar me-2" alt="{{ auth()->user()->name }}">
                            <span class="fw-semibold text-dark">{{ Str::limit(auth()->user()->name, 15) }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                            <li><a class="dropdown-item" href="{{ route('dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person me-2"></i>Edit Profile</a></li>
                            <li><a class="dropdown-item" href="{{ route('my-blogs') }}"><i class="bi bi-journal-text me-2"></i>My Blogs</a></li>
                            <li><a class="dropdown-item" href="{{ route('blogs.create') }}"><i class="bi bi-plus-circle me-2"></i>Create Blog</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-gradient">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-gradient">Register</a>
                @endauth
            </div>
        </div>
    </div>
</nav>