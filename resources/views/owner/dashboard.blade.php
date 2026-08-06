@extends('layouts.admin')

@section('title', 'Dashboard Owner - GREX Shella')
@section('page-title', 'Dashboard Owner')

@section('content')
<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card d-flex align-items-center justify-content-between">
            <div>
                <small class="text-muted d-block fw-semibold mb-1">Total Penginapan</small>
                <h3 class="fw-extrabold text-dark mb-0">{{ $totalLodgings }}</h3>
            </div>
            <div class="icon-box bg-primary bg-opacity-10 text-primary">
                <i class="fa-solid fa-building"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card d-flex align-items-center justify-content-between">
            <div>
                <small class="text-muted d-block fw-semibold mb-1">Pengajuan Disetujui</small>
                <h3 class="fw-extrabold text-success mb-0">{{ $totalApproved }}</h3>
            </div>
            <div class="icon-box bg-success bg-opacity-10 text-success">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card d-flex align-items-center justify-content-between">
            <div>
                <small class="text-muted d-block fw-semibold mb-1">Menunggu (Pending)</small>
                <h3 class="fw-extrabold text-warning mb-0">{{ $totalPending }}</h3>
            </div>
            <div class="icon-box bg-warning bg-opacity-10 text-warning">
                <i class="fa-solid fa-clock"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card d-flex align-items-center justify-content-between">
            <div>
                <small class="text-muted d-block fw-semibold mb-1">Ditolak (Rejected)</small>
                <h3 class="fw-extrabold text-danger mb-0">{{ $totalRejected }}</h3>
            </div>
            <div class="icon-box bg-danger bg-opacity-10 text-danger">
                <i class="fa-solid fa-circle-xmark"></i>
            </div>
        </div>
    </div>
</div>

<!-- Header Action -->
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3 bg-white rounded-4">
        <div>
            <h5 class="fw-bold text-dark mb-1"><i class="fa-solid fa-hotel text-primary me-2"></i>Daftar Penginapan Anda</h5>
            <p class="small text-muted mb-0">Kelola dan pantau status pengajuan penginapan Anda di Kabupaten Gresik</p>
        </div>
        <a href="{{ route('owner.lodgings.create') }}" class="btn btn-grex-primary rounded-pill px-4 fw-bold">
            <i class="fa-solid fa-plus me-1"></i> Ajukan Penginapan Baru
        </a>
    </div>
</div>

<!-- Table Lodgings -->
<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3">Penginapan</th>
                        <th class="py-3">Kecamatan</th>
                        <th class="py-3">Rentang Harga</th>
                        <th class="py-3">Status Pengajuan</th>
                        <th class="text-end pe-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lodgings as $lodging)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark">{{ $lodging->name }}</div>
                                <small class="text-muted">{{ Str::limit($lodging->address, 45) }}</small>
                            </td>
                            <td>Kec. {{ $lodging->district ?? 'Gresik' }}</td>
                            <td class="fw-bold text-primary">Rp {{ number_format($lodging->price_start, 0, ',', '.') }} – Rp {{ number_format($lodging->price_end, 0, ',', '.') }}</td>
                            <td>
                                @if($lodging->status === 'approved')
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 fw-semibold">
                                        <i class="fa-solid fa-check-circle me-1"></i> Disetujui
                                    </span>
                                @elseif($lodging->status === 'pending')
                                    <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-2 fw-semibold">
                                        <i class="fa-solid fa-clock me-1"></i> Pending Verifikasi
                                    </span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2 fw-semibold">
                                        <i class="fa-solid fa-times-circle me-1"></i> Ditolak
                                    </span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('owner.lodgings.edit', $lodging->id) }}" class="btn btn-light btn-sm rounded-circle me-1" title="Edit"><i class="fa-solid fa-pen text-primary"></i></a>
                                <form action="{{ route('owner.lodgings.destroy', $lodging->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data penginapan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-light btn-sm rounded-circle" title="Hapus"><i class="fa-solid fa-trash text-danger"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-folder-open display-6 d-block mb-2 opacity-25"></i>
                                Anda belum memiliki penginapan terdaftar. Klik "Ajukan Penginapan Baru" untuk membuat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
