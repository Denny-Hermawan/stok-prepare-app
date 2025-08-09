@extends('layouts.master')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Input Data Prepare</h1>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#prepareModal">
        <i class="fas fa-plus me-2"></i>Tambah Data
    </button>
</div>

<div class="card">
    <div class="card-header">
        <h5>Data Prepare Hari Ini - {{ now()->format('d/m/Y') }}</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Shift</th>
                        <th>Stok Awal</th>
                        <th>Prepare</th>
                        <th>Terpakai</th>
                        <th>Sisa</th>
                        <th>Input By</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($prepareLogs as $log)
                    <tr>
                        <td>{{ $log->item->nama_item }}</td>
                        <td><span class="badge bg-info">Shift {{ $log->shift }}</span></td>
                        <td>{{ number_format($log->stok_awal) }}</td>
                        <td>{{ number_format($log->stok_prepare) }}</td>
                        <td>{{ number_format($log->stok_terpakai) }}</td>
                        <td>
                            <span class="badge bg-{{ $log->stok_sisa > 0 ? 'success' : 'warning' }}">
                                {{ number_format($log->stok_sisa) }}
                            </span>
                        </td>
                        <td>{{ $log->user->name }}</td>
                        <td>{{ $log->updated_at->format('H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">Belum ada data prepare hari ini</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $prepareLogs->links() }}
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="prepareModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Input Data Prepare</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('prepare.store') }}" method="POST" id="prepareForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tanggal" class="form-label">Tanggal</label>
                                <input type="date" class="form-control" id="tanggal" name="tanggal"
                                       value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="shift" class="form-label">Shift</label>
                                <select class="form-select" id="shift" name="shift" required>
                                    <option value="">Pilih Shift</option>
                                    <option value="1">Shift 1</option>
                                    <option value="2">Shift 2</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="item_id" class="form-label">Item</label>
                        <select class="form-select" id="item_id" name="item_id" required>
                            <option value="">Pilih Item</option>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}">{{ $item->nama_item }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Alert untuk info stok awal otomatis -->
                    <div class="alert alert-info d-none" id="stokAwalAlert">
                        <i class="fas fa-info-circle me-2"></i>
                        <span id="stokAwalMessage"></span>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="stok_awal" class="form-label">
                                    Stok Awal
                                    <small class="text-muted">(Otomatis dari data sebelumnya)</small>
                                </label>
                                <input type="number" class="form-control" id="stok_awal" name="stok_awal"
                                       min="0" required readonly style="background-color: #f8f9fa;">
                                <div class="form-text">
                                    <i class="fas fa-lightbulb me-1"></i>
                                    Stok awal diambil otomatis dari stok sisa terakhir
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="stok_prepare" class="form-label">Stok Prepare</label>
                                <input type="number" class="form-control" id="stok_prepare" name="stok_prepare"
                                       min="0" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="stok_terpakai" class="form-label">Stok Terpakai</label>
                                <input type="number" class="form-control" id="stok_terpakai" name="stok_terpakai"
                                       min="0" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Stok Sisa (Auto Calculate)</label>
                                <input type="text" class="form-control fw-bold" id="stok_sisa_display" readonly
                                       style="background-color: #e8f5e8; color: #155724;">
                            </div>
                        </div>
                    </div>

                    <!-- Summary Card -->
                    <div class="card bg-light mt-3">
                        <div class="card-body">
                            <h6 class="card-title">Ringkasan Perhitungan:</h6>
                            <div class="row text-center">
                                <div class="col-3">
                                    <small class="text-muted d-block">Stok Awal</small>
                                    <span class="fw-bold" id="summary_awal">0</span>
                                </div>
                                <div class="col-1 align-self-center">
                                    <i class="fas fa-plus text-success"></i>
                                </div>
                                <div class="col-3">
                                    <small class="text-muted d-block">Prepare</small>
                                    <span class="fw-bold" id="summary_prepare">0</span>
                                </div>
                                <div class="col-1 align-self-center">
                                    <i class="fas fa-minus text-danger"></i>
                                </div>
                                <div class="col-3">
                                    <small class="text-muted d-block">Terpakai</small>
                                    <span class="fw-bold" id="summary_terpakai">0</span>
                                </div>
                                <div class="col-1 align-self-center">
                                    <i class="fas fa-equals"></i>
                                </div>
                            </div>
                            <div class="text-center mt-2">
                                <small class="text-muted d-block">Stok Sisa</small>
                                <span class="fw-bold fs-5 text-success" id="summary_sisa">0</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tanggalInput = document.getElementById('tanggal');
    const shiftInput = document.getElementById('shift');
    const itemInput = document.getElementById('item_id');
    const stokAwal = document.getElementById('stok_awal');
    const stokPrepare = document.getElementById('stok_prepare');
    const stokTerpakai = document.getElementById('stok_terpakai');
    const stokSisaDisplay = document.getElementById('stok_sisa_display');
    const stokAwalAlert = document.getElementById('stokAwalAlert');
    const stokAwalMessage = document.getElementById('stokAwalMessage');

    // Summary elements
    const summaryAwal = document.getElementById('summary_awal');
    const summaryPrepare = document.getElementById('summary_prepare');
    const summaryTerpakai = document.getElementById('summary_terpakai');
    const summarySisa = document.getElementById('summary_sisa');

    function updateSummary() {
        const awal = parseInt(stokAwal.value) || 0;
        const prepare = parseInt(stokPrepare.value) || 0;
        const terpakai = parseInt(stokTerpakai.value) || 0;

        summaryAwal.textContent = awal.toLocaleString();
        summaryPrepare.textContent = prepare.toLocaleString();
        summaryTerpakai.textContent = terpakai.toLocaleString();
        summarySisa.textContent = (awal + prepare - terpakai).toLocaleString();
    }

    function calculateStokSisa() {
        const awal = parseInt(stokAwal.value) || 0;
        const prepare = parseInt(stokPrepare.value) || 0;
        const terpakai = parseInt(stokTerpakai.value) || 0;
        const sisa = awal + prepare - terpakai;

        stokSisaDisplay.value = sisa.toLocaleString();

        // Update color based on result
        if (sisa < 0) {
            stokSisaDisplay.style.backgroundColor = '#f8d7da';
            stokSisaDisplay.style.color = '#721c24';
        } else if (sisa === 0) {
            stokSisaDisplay.style.backgroundColor = '#fff3cd';
            stokSisaDisplay.style.color = '#856404';
        } else {
            stokSisaDisplay.style.backgroundColor = '#e8f5e8';
            stokSisaDisplay.style.color = '#155724';
        }

        updateSummary();
    }

    function getStokAwal() {
        const tanggal = tanggalInput.value;
        const shift = shiftInput.value;
        const itemId = itemInput.value;

        if (tanggal && shift && itemId) {
            // Reset stok awal
            stokAwal.value = 0;
            stokAwalAlert.classList.add('d-none');

            fetch('{{ route("prepare.stok-awal") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    tanggal: tanggal,
                    shift: shift,
                    item_id: itemId
                })
            })
            .then(response => response.json())
            .then(data => {
                stokAwal.value = data.stok_awal;
                stokAwalMessage.textContent = data.message;
                stokAwalAlert.classList.remove('d-none');

                // Update alert color
                if (data.stok_awal > 0) {
                    stokAwalAlert.className = 'alert alert-success';
                } else {
                    stokAwalAlert.className = 'alert alert-warning';
                }

                calculateStokSisa();
            })
            .catch(error => {
                console.error('Error:', error);
                stokAwalMessage.textContent = 'Error mengambil data stok awal';
                stokAwalAlert.className = 'alert alert-danger';
                stokAwalAlert.classList.remove('d-none');
            });
        }
    }

    // Check existing data (untuk edit mode)
    function checkExisting() {
        const tanggal = tanggalInput.value;
        const shift = shiftInput.value;
        const itemId = itemInput.value;

        if (tanggal && shift && itemId) {
            fetch('{{ route("prepare.check") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    tanggal: tanggal,
                    shift: shift,
                    item_id: itemId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.exists) {
                    // Jika data sudah ada, load data existing
                    stokAwal.value = data.data.stok_awal;
                    stokPrepare.value = data.data.stok_prepare;
                    stokTerpakai.value = data.data.stok_terpakai;

                    stokAwalMessage.textContent = 'Data sudah ada, sedang dalam mode edit';
                    stokAwalAlert.className = 'alert alert-info';
                    stokAwalAlert.classList.remove('d-none');

                    calculateStokSisa();
                } else {
                    // Jika data belum ada, ambil stok awal otomatis
                    getStokAwal();
                }
            });
        }
    }

    // Event listeners
    stokAwal.addEventListener('input', calculateStokSisa);
    stokPrepare.addEventListener('input', calculateStokSisa);
    stokTerpakai.addEventListener('input', calculateStokSisa);

    tanggalInput.addEventListener('change', checkExisting);
    shiftInput.addEventListener('change', checkExisting);
    itemInput.addEventListener('change', checkExisting);

    // Reset form when modal is hidden
    document.getElementById('prepareModal').addEventListener('hidden.bs.modal', function() {
        document.getElementById('prepareForm').reset();
        stokAwalAlert.classList.add('d-none');
        stokSisaDisplay.value = '';
        updateSummary();
    });

    // Initialize summary on page load
    updateSummary();
});
</script>
@endsection
