<nav class="modern-sidebar" id="modernSidebar">
    <div class="sidebar-nav">
        <!-- User info card for mobile -->
        <div class="d-md-none mb-4">
            <div class="modern-card">
                <div class="card-body text-center py-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3 d-inline-flex mb-2">
                        <i class="fas fa-user text-primary fs-4"></i>
                    </div>
                    <h6 class="mb-1">{{ Auth::user()->name }}</h6>
                    <small class="text-muted">{{ Auth::user()->email }}</small>
                </div>
            </div>
        </div>

        <!-- Navigation Menu -->
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                   href="{{ route('dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i>
                    Dashboard
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('items.*') ? 'active' : '' }}"
                   href="{{ route('items.index') }}">
                    <i class="fas fa-list"></i>
                    Master Item
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('prepare.*') ? 'active' : '' }}"
                   href="{{ route('prepare.index') }}">
                    <i class="fas fa-plus-circle"></i>
                    Input Prepare
                    @if(\App\Models\PrepareLog::whereDate('tanggal', today())->count() > 0)
                        <span class="badge bg-success ms-auto">
                            {{ \App\Models\PrepareLog::whereDate('tanggal', today())->count() }}
                        </span>
                    @endif
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}"
                   href="{{ route('reports.index') }}">
                    <i class="fas fa-chart-bar"></i>
                    Laporan
                </a>
            </li>
        </ul>

        <!-- Divider -->
        <hr class="my-4 opacity-25">



        <!-- Version Info -->
        <div class="mt-auto px-3">
            <small class="text-muted">
                <i class="fas fa-code me-1"></i>
                Version 1.0.0
            </small>
        </div>
    </div>
</nav>
