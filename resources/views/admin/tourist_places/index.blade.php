@extends('layouts.admin')

@section('title', 'Data Wisata (Nyimas) - Admin GREX')
@section('page-title', 'Kelola Data Tempat Wisata')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Daftar Tempat Wisata</h4>
        <p class="text-muted small mb-0">Kelola informasi tempat wisata, jam operasional, pengelola, dan fasilitas.</p>
    </div>
    <a href="{{ route('admin.tourist-places.create') }}" class="btn btn-primary rounded-pill px-4">
        <i class="fa-solid fa-plus me-1"></i> Tambah Wisata
    </a>
</div>

<!-- Card Filter & Search -->
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-3">
        <form action="{{ route('admin.tourist-places.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-search text-muted"></i></span>
                    <input type="text" name="search" class="form-select border-start-0" placeholder="Cari nama wisata, kecamatan, atau pengelola..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-4">
                <select name="status" class="form-select">
                    <option value="">-- Semua Status --</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <div class="col-md-2 d-grid">
                <button type="submit" class="btn btn-dark rounded-3 fw-bold">Filter</button>
            </div>
        </form>
    </div>
</div>

<!-- Table List -->
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">No</th>
                        <th>Nama Tempat Wisata</th>
                        <th>Kecamatan</th>
                        <th>Jam Operasional</th>
                        <th>Pengelola</th>
                        <th>Telepon</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($touristPlaces as $index => $place)
                        <tr>
                            <td class="ps-4 fw-bold">{{ $touristPlaces->firstItem() + $index }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $place->name }}</div>
                                <small class="text-muted"><i class="fa-solid fa-ticket me-1"></i>{{ $place->ticket_price ?? 'Gratis' }}</small>
                            </td>
                            <td><span class="badge bg-light text-dark border">{{ $place->district ?? '-' }}</span></td>
                            <td><small class="text-muted">{{ $place->operational_hours }}</small></td>
                            <td>{{ $place->manager_name }}</td>
                            <td>{{ $place->phone ?? '-' }}</td>
                            <td>
                                @if($place->status === 'approved')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">Disetujui</span>
                                @elseif($place->status === 'pending')
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill">Pending</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill">Ditolak</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.tourist-places.edit', $place->id) }}" class="btn btn-outline-primary" title="Edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('admin.tourist-places.destroy', $place->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data wisata ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">Belum ada data tempat wisata.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($touristPlaces->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $touristPlaces->links() }}
        </div>
    @endif
</div>
@endsection
