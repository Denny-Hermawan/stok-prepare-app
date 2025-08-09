@extends('layouts.master')

@section('content')
<!-- Header Section -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
    <div>
        <h1 class="h2 mb-1 fw-bold">
            <i class="fas fa-boxes text-primary me-2"></i>Master Data Item
        </h1>
        <p class="text-muted mb-0">Kelola semua item inventory Anda di sini</p>
    </div>
    <div class="d-flex gap-2 mt-3 mt-md-0">
        <button class="btn btn-outline-secondary btn-modern d-md-inline-flex d-none" onclick="refreshData()">
            <i class="fas fa-sync-alt me-2"></i>Refresh
        </button>
        <a href="{{ route('items.create') }}" class="btn btn-modern btn-primary">
            <i class="fas fa-plus me-2"></i>Tambah Item
        </a>
    </div>
</div>

<!-- Quick Stats Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="modern-card h-100">
            <div class="card-body text-center py-3">
                <div class="rounded-circle bg-primary bg-opacity-10 p-3 d-inline-flex mb-2">
                    <i class="fas fa-list text-primary fa-lg"></i>
                </div>
                <h3 class="fw-bold mb-1 text-primary">{{ $items->total() }}</h3>
                <p class="text-muted mb-0 small">Total Item</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="modern-card h-100">
            <div class="card-body text-center py-3">
                <div class="rounded-circle bg-success bg-opacity-10 p-3 d-inline-flex mb-2">
                    <i class="fas fa-check-circle text-success fa-lg"></i>
                </div>
                <h3 class="fw-bold mb-1 text-success">{{ \App\Models\Item::has('prepareLogs')->count() }}</h3>
                <p class="text-muted mb-0 small">Item Aktif</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="modern-card h-100">
            <div class="card-body text-center py-3">
                <div class="rounded-circle bg-warning bg-opacity-10 p-3 d-inline-flex mb-2">
                    <i class="fas fa-calendar-day text-warning fa-lg"></i>
                </div>
                <h3 class="fw-bold mb-1 text-warning">{{ \App\Models\Item::whereHas('prepareLogs', function($q) { $q->whereDate('tanggal', today()); })->count() }}</h3>
                <p class="text-muted mb-0 small">Digunakan Hari Ini</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="modern-card h-100">
            <div class="card-body text-center py-3">
                <div class="rounded-circle bg-info bg-opacity-10 p-3 d-inline-flex mb-2">
                    <i class="fas fa-clock text-info fa-lg"></i>
                </div>
                <h3 class="fw-bold mb-1 text-info">{{ \App\Models\Item::doesntHave('prepareLogs')->count() }}</h3>
                <p class="text-muted mb-0 small">Belum Digunakan</p>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filter -->
<div class="modern-card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Cari Item</label>
                <div class="input-group">
                    <span class="input-group-text bg-light">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                    <input type="text" class="form-control" name="search"
                           placeholder="Nama item..."
                           value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Status</label>
                <select class="form-select" name="status">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif (Pernah Digunakan)</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Belum Digunakan</option>
                    <option value="today" {{ request('status') == 'today' ? 'selected' : '' }}>Digunakan Hari Ini</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Urutan</label>
                <select class="form-select" name="sort">
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nama A-Z</option>
                    <option value="usage" {{ request('sort') == 'usage' ? 'selected' : '' }}>Paling Sering Digunakan</option>
                </select>
            </div>
            <div class="col-md-3">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-modern flex-fill">
                        <i class="fas fa-filter me-2"></i>Filter
                    </button>
                    <a href="{{ route('items.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Items List -->
<div class="modern-card">
    <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
        <h5 class="mb-2 mb-md-0 fw-semibold">
            <i class="fas fa-table me-2"></i>Daftar Item
        </h5>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-light text-dark">
                Menampilkan {{ $items->count() }} dari {{ $items->total() }} item
            </span>
        </div>
    </div>

    @if($items->count() > 0)
        <!-- Desktop Table View -->
        <div class="d-none d-lg-block">
            <div class="table-responsive">
                <table class="table modern-table mb-0">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th>Nama Item</th>
                            <th class="text-center" width="12%">Total Penggunaan</th>
                            <th class="text-center" width="12%">Hari Ini</th>
                            <th class="text-center" width="15%">Terakhir Digunakan</th>
                            <th class="text-center" width="15%">Dibuat</th>
                            <th class="text-center" width="12%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $index => $item)
                        @php
                            $lastLog = $item->prepareLogs()->latest('tanggal')->first();
                            $todayUsage = $item->prepareLogs()->whereDate('tanggal', today())->count();
                            $totalUsage = $item->prepareLogs()->count();
                        @endphp
                        <tr>
                            <td class="text-muted fw-semibold">
                                {{ $items->firstItem() + $index }}
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3">
                                        <i class="fas fa-box text-primary"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $item->nama_item }}</div>
                                        @if($totalUsage == 0)
                                            <small class="text-warning">
                                                <i class="fas fa-exclamation-triangle me-1"></i>Belum pernah digunakan
                                            </small>
                                        @else
                                            <small class="text-success">
                                                <i class="fas fa-check-circle me-1"></i>Item aktif
                                            </small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-{{ $totalUsage > 0 ? 'primary' : 'secondary' }} fs-6">
                                    {{ number_format($totalUsage) }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($todayUsage > 0)
                                    <span class="badge bg-success fs-6">{{ $todayUsage }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($lastLog)
                                    <div>
                                        <div class="fw-semibold">{{ $lastLog->tanggal->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $lastLog->tanggal->diffForHumans() }}</small>
                                    </div>
                                @else
                                    <span class="text-muted">Tidak pernah</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="fw-semibold">{{ $item->created_at->format('d/m/Y') }}</div>
                                <small class="text-muted">{{ $item->created_at->diffForHumans() }}</small>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('items.edit', $item) }}"
                                       class="btn btn-sm btn-outline-warning"
                                       title="Edit Item"
                                       data-bs-toggle="tooltip">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button"
                                            class="btn btn-sm btn-outline-danger"
                                            title="Hapus Item"
                                            data-bs-toggle="tooltip"
                                            onclick="confirmDelete({{ $item->id }}, '{{ $item->nama_item }}', {{ $totalUsage }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile Card View -->
        <div class="d-lg-none">
            <div class="card-body p-0">
                @foreach($items as $item)
                @php
                    $lastLog = $item->prepareLogs()->latest('tanggal')->first();
                    $todayUsage = $item->prepareLogs()->whereDate('tanggal', today())->count();
                    $totalUsage = $item->prepareLogs()->count();
                @endphp
                <div class="border-bottom p-3">
                    <div class="d-flex align-items-start">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                            <i class="fas fa-box text-primary fa-lg"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="fw-bold mb-1">{{ $item->nama_item }}</h6>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('items.edit', $item) }}"
                                       class="btn btn-outline-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button"
                                            class="btn btn-outline-danger btn-sm"
                                            onclick="confirmDelete({{ $item->id }}, '{{ $item->nama_item }}', {{ $totalUsage }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Status Badge -->
                            @if($totalUsage == 0)
                                <span class="badge bg-warning mb-2">
                                    <i class="fas fa-exclamation-triangle me-1"></i>Belum digunakan
                                </span>
                            @else
                                <span class="badge bg-success mb-2">
                                    <i class="fas fa-check-circle me-1"></i>Item aktif
                                </span>
                            @endif

                            <!-- Stats -->
                            <div class="row g-2 text-center mb-2">
                                <div class="col-4">
                                    <small class="text-muted d-block">Total</small>
                                    <span class="fw-bold text-primary">{{ number_format($totalUsage) }}</span>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted d-block">Hari Ini</small>
                                    <span class="fw-bold {{ $todayUsage > 0 ? 'text-success' : 'text-muted' }}">
                                        {{ $todayUsage }}
                                    </span>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted d-block">Terakhir</small>
                                    <span class="fw-bold text-info">
                                        {{ $lastLog ? $lastLog->tanggal->format('d/m') : '-' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Created Date -->
                            <small class="text-muted">
                                <i class="fas fa-calendar-plus me-1"></i>
                                Dibuat {{ $item->created_at->diffForHumans() }}
                            </small>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Pagination -->
<!-- Pagination - PERBAIKAN -->
        @if($items->hasPages())
            <div class="card-footer">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <div class="text-muted small mb-2 mb-md-0 order-2 order-md-1">
                        <i class="fas fa-info-circle me-1"></i>
                        Menampilkan {{ $items->firstItem() }} sampai {{ $items->lastItem() }} dari {{ number_format($items->total()) }} item
                    </div>
                    <nav aria-label="Page navigation" class="order-1 order-md-2">
                        <ul class="pagination justify-content-center mb-0">
                            {{-- Previous Page Link --}}
                            @if ($items->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link">
                                        <i class="fas fa-chevron-left"></i>
                                        <span class="d-none d-md-inline ms-1">Previous</span>
                                    </span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $items->previousPageUrl() }}" rel="prev">
                                        <i class="fas fa-chevron-left"></i>
                                        <span class="d-none d-md-inline ms-1">Previous</span>
                                    </a>
                                </li>
                            @endif

                            {{-- Pagination Elements --}}
                            @php
                                $start = max($items->currentPage() - 2, 1);
                                $end = min($start + 4, $items->lastPage());
                                $start = max($end - 4, 1);
                            @endphp

                            {{-- First Page --}}
                            @if($start > 1)
                                <li class="page-item">
                                    <a class="page-link" href="{{ $items->url(1) }}">1</a>
                                </li>
                                @if($start > 2)
                                    <li class="page-item disabled">
                                        <span class="page-link">...</span>
                                    </li>
                                @endif
                            @endif

                            {{-- Page Numbers --}}
                            @for ($i = $start; $i <= $end; $i++)
                                @if ($i == $items->currentPage())
                                    <li class="page-item active">
                                        <span class="page-link">{{ $i }}</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $items->url($i) }}">{{ $i }}</a>
                                    </li>
                                @endif
                            @endfor

                            {{-- Last Page --}}
                            @if($end < $items->lastPage())
                                @if($end < $items->lastPage() - 1)
                                    <li class="page-item disabled">
                                        <span class="page-link">...</span>
                                    </li>
                                @endif
                                <li class="page-item">
                                    <a class="page-link" href="{{ $items->url($items->lastPage()) }}">{{ $items->lastPage() }}</a>
                                </li>
                            @endif

                            {{-- Next Page Link --}}
                            @if ($items->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $items->nextPageUrl() }}" rel="next">
                                        <span class="d-none d-md-inline me-1">Next</span>
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            @else
                                <li class="page-item disabled">
                                    <span class="page-link">
                                        <span class="d-none d-md-inline me-1">Next</span>
                                        <i class="fas fa-chevron-right"></i>
                                    </span>
                                </li>
                            @endif
                        </ul>
                    </nav>
                </div>

                <!-- Quick Jump (Optional) -->
                @if($items->lastPage() > 10)
                <div class="mt-3 pt-2 border-top">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <small class="text-muted">Lompat ke halaman:</small>
                        </div>
                        <div class="col-auto">
                            <select class="form-select form-select-sm" style="width: auto;" onchange="window.location.href=this.value">
                                @for($i = 1; $i <= $items->lastPage(); $i++)
                                    <option value="{{ $items->url($i) }}" {{ $i == $items->currentPage() ? 'selected' : '' }}>
                                        {{ $i }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-auto">
                            <small class="text-muted">dari {{ $items->lastPage() }}</small>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="card-body">
            <div class="text-center py-5">
                <div class="rounded-circle bg-light p-4 d-inline-flex mb-3">
                    <i class="fas fa-search fa-2x text-muted"></i>
                </div>
                @if(request()->has('search') || request()->has('status'))
                    <h6 class="text-muted">Tidak ada item yang ditemukan</h6>
                    <p class="text-muted mb-4">Coba ubah kriteria pencarian atau filter Anda.</p>
                    <a href="{{ route('items.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-times me-2"></i>Reset Filter
                    </a>
                @else
                    <h6 class="text-muted">Belum ada item</h6>
                    <p class="text-muted mb-4">Mulai dengan membuat item pertama Anda.</p>
                    <a href="{{ route('items.create') }}" class="btn btn-modern btn-primary">
                        <i class="fas fa-plus me-2"></i>Buat Item Pertama
                    </a>
                @endif
            </div>
        </div>
    @endif
</div>

<!-- Hidden Forms for Delete -->
@foreach($items as $item)
<form id="delete-form-{{ $item->id }}"
      action="{{ route('items.destroy', $item) }}"
      method="POST"
      style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endforeach

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                    Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <div class="rounded-circle bg-danger bg-opacity-10 p-3 d-inline-flex">
                        <i class="fas fa-trash text-danger fa-lg"></i>
                    </div>
                </div>

                <p class="text-center mb-3">Apakah Anda yakin ingin menghapus item ini?</p>

                <div class="bg-light p-3 rounded mb-3">
                    <div class="fw-bold text-center" id="itemNameToDelete"></div>
                    <div class="text-center mt-2">
                        <small class="text-muted">
                            <span id="usageCountText"></span>
                        </small>
                    </div>
                </div>

                <div class="alert alert-warning mb-0">
                    <div class="d-flex">
                        <i class="fas fa-exclamation-triangle me-2 mt-1"></i>
                        <div>
                            <strong>Perhatian:</strong> Tindakan ini tidak dapat dibatalkan dan akan menghapus semua data prepare log yang terkait dengan item ini.
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Batal
                </button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <i class="fas fa-trash me-2"></i>Ya, Hapus Item
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let deleteItemId = null;

function confirmDelete(itemId, itemName, usageCount) {
    deleteItemId = itemId;
    document.getElementById('itemNameToDelete').textContent = itemName;

    const usageText = usageCount > 0
        ? `Item ini telah digunakan ${usageCount} kali`
        : 'Item ini belum pernah digunakan';
    document.getElementById('usageCountText').textContent = usageText;

    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    if (deleteItemId) {
        // Show loading state
        this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menghapus...';
        this.disabled = true;

        // Submit the form
        document.getElementById('delete-form-' + deleteItemId).submit();
    }
});

function refreshData() {
    // Add loading state to refresh button
    const refreshBtn = event.target.closest('button');
    const originalHTML = refreshBtn.innerHTML;
    refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Refresh...';
    refreshBtn.disabled = true;

    // Reload page after a short delay
    setTimeout(() => {
        window.location.reload();
    }, 500);
}

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            if (alert.classList.contains('show')) {
                const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                bsAlert.close();
            }
        }, 5000);
    });

    // Add search input debounce
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const form = this.closest('form');

            searchTimeout = setTimeout(() => {
                // Auto-submit form after user stops typing for 1 second
                // form.submit();
            }, 1000);
        });
    }
});

// Add loading states to buttons
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function() {
        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn && !submitBtn.disabled) {
            const originalHTML = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
            submitBtn.disabled = true;
        }
    });
});
</script>
@endsection
