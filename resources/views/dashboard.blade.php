@extends('layouts.master')

@section('content')
<!-- Header Section -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
    <div>
        <h1 class="h2 mb-1 fw-bold">Dashboard</h1>
        <p class="text-muted mb-0">Welcome back, {{ Auth::user()->name }}! Here's what's happening today.</p>
    </div>
    <div class="mt-3 mt-md-0">
        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">
            <i class="fas fa-calendar-day me-2"></i>
            {{ now()->format('l, F j, Y') }}
        </span>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <!-- Total Items -->
    <div class="col-6 col-lg-3">
        <div class="modern-card stats-card h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h3 class="fw-bold mb-1">{{ \App\Models\Item::count() }}</h3>
                        <p class="mb-0 opacity-90 small">Total Items</p>
                    </div>
                    <div class="rounded-circle bg-white bg-opacity-20 p-3">
                        <i class="fas fa-list fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Prepare -->
    <div class="col-6 col-lg-3">
        <div class="modern-card stats-card h-100" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h3 class="fw-bold mb-1">{{ \App\Models\PrepareLog::whereDate('tanggal', today())->count() }}</h3>
                        <p class="mb-0 opacity-90 small">Today's Prepare</p>
                    </div>
                    <div class="rounded-circle bg-white bg-opacity-20 p-3">
                        <i class="fas fa-plus-circle fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- This Week -->
    <div class="col-6 col-lg-3">
        <div class="modern-card stats-card h-100" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h3 class="fw-bold mb-1">{{ \App\Models\PrepareLog::whereBetween('tanggal', [now()->startOfWeek(), now()->endOfWeek()])->count() }}</h3>
                        <p class="mb-0 opacity-90 small">This Week</p>
                    </div>
                    <div class="rounded-circle bg-white bg-opacity-20 p-3">
                        <i class="fas fa-calendar-week fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- This Month -->
    <div class="col-6 col-lg-3">
        <div class="modern-card stats-card h-100" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h3 class="fw-bold mb-1">{{ \App\Models\PrepareLog::whereMonth('tanggal', now()->month)->count() }}</h3>
                        <p class="mb-0 opacity-90 small">This Month</p>
                    </div>
                    <div class="rounded-circle bg-white bg-opacity-20 p-3">
                        <i class="fas fa-calendar-alt fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="modern-card">
            <div class="card-header">
                <h5 class="mb-0 fw-semibold">
                    <i class="fas fa-bolt text-warning me-2"></i>
                    Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6 col-md-3">
                        <a href="{{ route('prepare.index') }}" class="btn btn-modern btn-primary w-100">
                            <i class="fas fa-plus"></i>
                            <span class="d-none d-sm-inline">Add Prepare</span>
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="{{ route('items.index') }}" class="btn btn-modern btn-outline-secondary w-100">
                            <i class="fas fa-list"></i>
                            <span class="d-none d-sm-inline">Manage Items</span>
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="{{ route('reports.index') }}" class="btn btn-modern btn-outline-info w-100">
                            <i class="fas fa-chart-bar"></i>
                            <span class="d-none d-sm-inline">View Reports</span>
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="{{ route('items.create') }}" class="btn btn-modern btn-outline-success w-100">
                            <i class="fas fa-plus-square"></i>
                            <span class="d-none d-sm-inline">New Item</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Today's Data -->
<div class="row g-4">
    <div class="col-12">
        <div class="modern-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-semibold">
                    <i class="fas fa-calendar-day text-primary me-2"></i>
                    Today's Prepare Data
                </h5>
                <span class="badge bg-primary">{{ now()->format('M j, Y') }}</span>
            </div>
            <div class="card-body">
                @php
                    $todayLogs = \App\Models\PrepareLog::with('item')->whereDate('tanggal', today())->latest()->get();
                @endphp

                @if($todayLogs->count() > 0)
                    <div class="table-responsive">
                        <table class="table modern-table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th class="text-center">Shift</th>
                                    <th class="text-end">Stok Awal</th>
                                    <th class="text-end">Prepare</th>
                                    <th class="text-end">Terpakai</th>
                                    <th class="text-end">Sisa</th>
                                    <th class="d-none d-md-table-cell">Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($todayLogs as $log)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3">
                                                <i class="fas fa-box text-primary small"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $log->item->nama_item }}</div>
                                                <small class="text-muted d-md-none">Shift {{ $log->shift }} • {{ $log->updated_at->format('H:i') }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $log->shift == 1 ? 'info' : 'warning' }}">
                                            Shift {{ $log->shift }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <span class="fw-semibold">{{ number_format($log->stok_awal) }}</span>
                                    </td>
                                    <td class="text-end">
                                        <span class="fw-semibold text-success">+{{ number_format($log->stok_prepare) }}</span>
                                    </td>
                                    <td class="text-end">
                                        <span class="fw-semibold text-danger">-{{ number_format($log->stok_terpakai) }}</span>
                                    </td>
                                    <td class="text-end">
                                        <span class="badge bg-{{ $log->stok_sisa > 0 ? 'success' : ($log->stok_sisa == 0 ? 'warning' : 'danger') }} fs-6">
                                            {{ number_format($log->stok_sisa) }}
                                        </span>
                                    </td>
                                    <td class="d-none d-md-table-cell">
                                        <small class="text-muted">{{ $log->updated_at->format('H:i') }}</small>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Summary Row -->
                    <div class="border-top mt-3 pt-3">
                        <div class="row text-center g-4">
                            <div class="col-3">
                                <div class="fw-bold text-primary fs-5">{{ number_format($todayLogs->sum('stok_prepare')) }}</div>
                                <small class="text-muted">Total Prepare</small>
                            </div>
                            <div class="col-3">
                                <div class="fw-bold text-danger fs-5">{{ number_format($todayLogs->sum('stok_terpakai')) }}</div>
                                <small class="text-muted">Total Terpakai</small>
                            </div>
                            <div class="col-3">
                                <div class="fw-bold text-success fs-5">{{ number_format($todayLogs->sum('stok_sisa')) }}</div>
                                <small class="text-muted">Total Sisa</small>
                            </div>
                            <div class="col-3">
                                <div class="fw-bold text-info fs-5">{{ $todayLogs->count() }}</div>
                                <small class="text-muted">Items</small>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="rounded-circle bg-light p-4 d-inline-flex mb-3">
                            <i class="fas fa-inbox fa-2x text-muted"></i>
                        </div>
                        <h6 class="text-muted">No prepare data for today</h6>
                        <p class="text-muted mb-4">Start by adding some prepare data to see it here.</p>
                        <a href="{{ route('prepare.index') }}" class="btn btn-modern btn-primary">
                            <i class="fas fa-plus me-2"></i>Add First Prepare
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity (Optional) -->
@if(\App\Models\PrepareLog::count() > 0)
<div class="row g-4 mt-2">
    <div class="col-12">
        <div class="modern-card">
            <div class="card-header">
                <h5 class="mb-0 fw-semibold">
                    <i class="fas fa-history text-secondary me-2"></i>
                    Recent Activity
                </h5>
            </div>
            <div class="card-body">
                @php
                    $recentLogs = \App\Models\PrepareLog::with(['item', 'user'])->latest()->take(5)->get();
                @endphp

                <div class="timeline">
                    @foreach($recentLogs as $log)
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3">
                            <i class="fas fa-plus text-primary small"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex flex-column flex-md-row justify-content-between">
                                <div>
                                    <span class="fw-semibold">{{ $log->item->nama_item }}</span>
                                    <span class="text-muted">prepared by {{ $log->user->name }}</span>
                                </div>
                                <div class="text-muted small">
                                    <i class="fas fa-clock me-1"></i>
                                    {{ $log->created_at->diffForHumans() }}
                                </div>
                            </div>
                            <small class="text-muted">
                                {{ $log->tanggal->format('M j') }} • Shift {{ $log->shift }} •
                                Sisa: {{ number_format($log->stok_sisa) }}
                            </small>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="text-center mt-3">
                    <a href="{{ route('prepare.index') }}" class="btn btn-outline-primary btn-sm">
                        View All Activity
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
