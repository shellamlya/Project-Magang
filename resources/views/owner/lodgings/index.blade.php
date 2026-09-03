@extends('layouts.admin')

@section('title', 'Daftar Usaha Saya - Owner Lokavino')
@section('page-title', 'Daftar Usaha Saya')

@section('content')
<!-- Modal Category Selection -->
@include('owner.lodgings.partials._category_modal')

<!-- Header Action -->
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3 bg-white rounded-4">
        <div>
            <h5 class="fw-bold text-dark mb-1"><i class="fa-solid fa-store text-primary me-2"></i>Daftar Tempat Usaha Anda</h5>
            <p class="small text-muted mb-0">Kelola penginapan, tempat nongkrong, dan destinasi wisata Anda</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <button type="button" class="btn btn-primary rounded-pill px-4 fw-bold" data-bs-toggle="modal" data-bs-target="#categoryModal">
                <i class="fa-solid fa-plus me-1"></i> Ajukan Usaha Baru
            </button>
        </div>
    </div>
</div>

<!-- Tab Filter Kategori Usaha -->
<div class="d-flex gap-2 mb-3 overflow-auto pb-2">
    <a href="{{ route('owner.lodgings.index') }}" class="btn btn-sm rounded-pill px-3 fw-bold {{ !$category ? 'btn-primary' : 'btn-light text-dark' }}">
        Semua Usaha
    </a>
    <a href="{{ route('owner.lodgings.index', ['category' => 'penginapan']) }}" class="btn btn-sm rounded-pill px-3 fw-bold {{ $category === 'penginapan' ? 'btn-primary' : 'btn-light text-dark' }}">
        <i class="fa-solid fa-hotel me-1"></i> Penginapan
    </a>
    <a href="{{ route('owner.lodgings.index', ['category' => 'nongkrong']) }}" class="btn btn-sm rounded-pill px-3 fw-bold {{ $category === 'nongkrong' ? 'btn-primary' : 'btn-light text-dark' }}">
        <i class="fa-solid fa-mug-hot me-1"></i> Kafe / Nongkrong
    </a>
    <a href="{{ route('owner.lodgings.index', ['category' => 'wisata']) }}" class="btn btn-sm rounded-pill px-3 fw-bold {{ $category === 'wisata' ? 'btn-primary' : 'btn-light text-dark' }}">
        <i class="fa-solid fa-mountain-sun me-1"></i> Tempat Wisata
    </a>
</div>

<!-- Table Multi Usaha -->
<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3">Nama Usaha</th>
                        <th class="py-3">Kategori</th>
                        <th class="py-3">Kecamatan</th>
                        <th class="py-3">Statistik (View / Map)</th>
                        <th class="py-3">Status Pengajuan</th>
                        <th class="text-end pe-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($allPlaces as $place)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark">{{ $place->name }}</div>
                                <small class="text-muted">{{ Str::limit($place->address, 45) }}</small>
                            </td>
                            <td>
                                <span class="badge {{ $place->category_badge_class }} rounded-pill px-3 py-1">
                                    {{ $place->category_label }}
                                </span>
                            </td>
                            <td>Kec. {{ $place->district ?? 'Gresik' }}</td>
                            <td>
                                <div class="small fw-semibold text-muted">
                                    <i class="fa-solid fa-eye text-info me-1"></i> {{ number_format($place->views_count ?? 0) }} Views
                                </div>
                                <div class="small fw-semibold text-muted">
                                    <i class="fa-solid fa-location-dot text-danger me-1"></i> {{ number_format($place->maps_clicks_count ?? 0) }} Map Clicks
                                </div>
                            </td>
                            <td>
                                @if($place->status === 'approved')
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 fw-semibold">
                                        <i class="fa-solid fa-check-circle me-1"></i> Disetujui
                                    </span>
                                @elseif($place->status === 'pending')
                                    <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-2 fw-semibold">
                                        <i class="fa-solid fa-clock me-1"></i> Pending Verifikasi
                                    </span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2 fw-semibold">
                                        <i class="fa-solid fa-times-circle me-1"></i> Ditolak
                                    </span>
                                @endif

                                @if($place->is_verified_official)
                                    <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-2 py-1 ms-1 small" title="Data Terverifikasi Resmi Lokavino">
                                        <i class="fa-solid fa-shield-check"></i> Resmi
                                    </span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('owner.lodgings.edit', ['lodging' => $place->id, 'category' => $place->place_category]) }}" class="btn btn-light btn-sm rounded-circle me-1" title="Edit"><i class="fa-solid fa-pen text-primary"></i></a>
                                <form action="{{ route('owner.lodgings.destroy', ['lodging' => $place->id, 'category' => $place->place_category]) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data tempat usaha ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-light btn-sm rounded-circle" title="Hapus"><i class="fa-solid fa-trash text-danger"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-folder-open display-6 d-block mb-2 opacity-25"></i>
                                Belum ada tempat usaha terdaftar. Klik "Ajukan Usaha Baru" atau "Klaim Tempat Usaha".
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
