@extends('layouts.admin')

@section('title', 'Daftar Penginapan - Owner GREX Shella')
@section('page-title', 'Penginapan Saya')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold text-dark mb-1">Kelola Penginapan</h5>
        <p class="small text-muted mb-0">Daftar tempat penginapan yang Anda kelola</p>
    </div>
    <a href="{{ route('owner.lodgings.create') }}" class="btn btn-primary rounded-pill px-4 fw-bold">
        <i class="fa-solid fa-plus me-1"></i> Tambah Penginapan Baru
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3">Penginapan</th>
                        <th class="py-3">Kecamatan</th>
                        <th class="py-3">Tarif Sewa</th>
                        <th class="py-3">Status</th>
                        <th class="text-end pe-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lodgings as $l)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark">{{ $l->name }}</div>
                                <small class="text-muted">{{ Str::limit($l->address, 45) }}</small>
                            </td>
                            <td><span class="badge bg-light text-dark border">{{ $l->district ?? '-' }}</span></td>
                            <td class="fw-bold text-primary">Rp {{ number_format($l->price_start, 0, ',', '.') }} – Rp {{ number_format($l->price_end, 0, ',', '.') }}</td>
                            <td>
                                @if($l->status === 'approved')
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1">Disetujui</span>
                                @elseif($l->status === 'pending')
                                    <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-1">Pending</span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-1">Ditolak</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('owner.lodgings.edit', $l->id) }}" class="btn btn-light btn-sm rounded-circle me-1" title="Edit"><i class="fa-solid fa-pen text-primary"></i></a>
                                <form action="{{ route('owner.lodgings.destroy', $l->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data penginapan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-light btn-sm rounded-circle" title="Hapus"><i class="fa-solid fa-trash text-danger"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">Belum ada penginapan. Silakan tambah data baru.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4 d-flex justify-content-center">
    {{ $lodgings->links('pagination::bootstrap-5') }}
</div>
@endsection
