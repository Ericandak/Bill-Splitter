<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <!-- Brand -->
        <a class="navbar-brand" href="{{ route('bills.index') }}">
            BillSplitter
        </a>

        <!-- Hamburger Button -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navigation Content -->
        <div class="collapse navbar-collapse" id="navbarContent">
            @auth
                <!-- Left Side Navigation -->
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('bills.index') ? 'active' : '' }}" 
                           href="{{ route('bills.index') }}">
                            <i class="fas fa-file-invoice me-1"></i>My Bills
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ request()->routeIs('friends.*') ? 'active' : '' }}" 
                           href="#" 
                           id="friendsDropdown" 
                           role="button" 
                           data-bs-toggle="dropdown">
                            <i class="fas fa-users me-1"></i>Friends
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="{{ route('friends.search') }}">
                                    <i class="fas fa-search me-2"></i>Find Friends
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('friends.requests') }}">
                                    <i class="fas fa-user-plus me-2"></i>Friend Requests
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>

                <!-- Right Side Navigation -->
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i>{{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt me-2"></i>Log Out
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            @else
                <!-- Guest Navigation -->
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">Register</a>
                    </li>
                </ul>
            @endauth
        </div>
    </div>
</nav>

<style>
.navbar {
    padding: 1rem 0;
}

.navbar-brand {
    font-weight: 600;
    font-size: 1.25rem;
    color: #4A5CFF;
}

.nav-link {
    font-weight: 500;
    color: #1a1a1a;
    padding: 0.5rem 1rem;
    transition: color 0.2s;
}

.nav-link:hover {
    color: #4A5CFF;
}

.nav-link.active {
    color: #4A5CFF;
}

.dropdown-item {
    padding: 0.5rem 1.5rem;
    transition: background-color 0.2s;
}

.dropdown-item:active {
    background-color: #4A5CFF;
}

.dropdown-menu {
    border: none;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

@media (max-width: 991.98px) {
    .navbar-collapse {
        padding: 1rem 0;
    }
    
    .dropdown-menu {
        border: none;
        padding: 0.5rem 0;
        box-shadow: none;
    }
}
</style>
