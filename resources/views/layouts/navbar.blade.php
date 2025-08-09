<header class="navbar modern-navbar">
    <div class="container-fluid px-3">
        <div class="d-flex align-items-center">
            <!-- Mobile Sidebar Toggle -->
            <button class="btn btn-link text-white p-0 me-3 d-md-none" id="sidebarToggle" type="button">
                <i class="fas fa-bars fs-5"></i>
            </button>

            <!-- Brand -->
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="fas fa-boxes"></i>
                <span class="d-none d-sm-inline">Stok Prepare</span>
                <span class="d-inline d-sm-none">SP</span>
            </a>
        </div>

        <!-- Right side items -->
        <div class="d-flex align-items-center">
            <!-- User Info (hidden on mobile) -->
            <div class="text-white me-3 d-none d-md-block">
                <small class="opacity-75">Welcome back,</small>
                <div class="fw-semibold">{{ Auth::user()->name }}</div>
            </div>

            <!-- User Avatar & Dropdown -->
            <div class="dropdown">
                <button class="btn btn-link text-white p-0 dropdown-toggle-no-caret" type="button" data-bs-toggle="dropdown">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-white bg-opacity-20 p-2 me-2">
                            <i class="fas fa-user"></i>
                        </div>
                        <span class="d-none d-sm-inline me-2">{{ Auth::user()->name }}</span>
                        <i class="fas fa-chevron-down small"></i>
                    </div>
                </button>

                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0" style="border-radius: 12px; min-width: 200px;">
                    <li>
                        <h6 class="dropdown-header">
                            <i class="fas fa-user-circle me-2"></i>
                            {{ Auth::user()->name }}
                        </h6>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('profile.edit') }}">
                            <i class="fas fa-cog me-2 text-muted"></i>
                            Profile Settings
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="dropdown-item d-flex align-items-center text-danger" type="submit">
                                <i class="fas fa-sign-out-alt me-2"></i>
                                Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>
