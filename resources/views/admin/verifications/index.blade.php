@extends('layouts.admin')

@section('title', 'Verifikasi Tempat Usaha - Admin Lokavino')
@section('page-title', 'Verifikasi Listing Tempat Usaha (Tingkat 2)')

@section('content')
<!-- Ringkasan Statistik -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card d-flex align-items-center justify-content-between">
            <div>
                <small class="text-muted d-block fw-semibold mb-1">Listing Pending</small>
                <h3 class="fw-extrabold text-warning mb-0">{{ $stats['total_pending'] }}</h3>
                <small class="text-muted">Menunggu persetujuan Admin</small>
            </div>
            <div class="icon-box bg-warning bg-opacity-10 text-warning">
                <i class="fa-solid fa-clock"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card d-flex align-items-center justify-content-between">
            <div>
                <small class="text-muted d-block fw-semibold mb-1">Listing Disetujui (Approved)</small>
                <h3 class="fw-extrabold text-success mb-0">{{ $stats['total_approved'] }}</h3>
                <small class="text-muted">Tayang di pencarian publik</small>
            </div>
            <div class="icon-box bg-success bg-opacity-10 text-success">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card d-flex align-items-center justify-content-between">
            <div>
                <small class="text-muted d-block fw-semibold mb-1">Listing Ditolak</small>
                <h3 class="fw-extrabold text-danger mb-0">{{ $stats['total_rejected'] }}</h3>
                <small class="text-muted">Dapat direvisi oleh Owner</small>
            </div>
            <div class="icon-box bg-danger bg-opacity-10 text-danger">
                <i class="fa-solid fa-circle-xmark"></i>
            </div>
        </div>
    </div>
</div>

<!-- Header & Filter Tabs -->
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4 bg-white rounded-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h5 class="fw-bold text-dark mb-1"><i class="fa-solid fa-clipboard-check text-primary me-2"></i>Daftar Verifikasi Tempat Usaha</h5>
                <p class="small text-muted mb-0">Periksa kesesuaian data penginapan, tempat wisata, dan kafe/nongkrong sebelum dipublikasikan ke publik</p>
            </div>

            <div class="d-flex gap-2 flex-wrap">
                <!-- Filter Kategori -->
                <div class="btn-group rounded-pill p-1 bg-light border" role="group">
                    <a href="{{ route('admin.verifications.index', array_merge(request()->query(), ['type' => null])) }}" class="btn btn-sm rounded-pill {{ !request('type') ? 'btn-primary' : 'btn-light' }}">Semua Kategori</a>
                    <a href="{{ route('admin.verifications.index', array_merge(request()->query(), ['type' => 'penginapan'])) }}" class="btn btn-sm rounded-pill {{ request('type') === 'penginapan' ? 'btn-primary' : 'btn-light' }}">🏨 Penginapan</a>
                    <a href="{{ route('admin.verifications.index', array_merge(request()->query(), ['type' => 'wisata'])) }}" class="btn btn-sm rounded-pill {{ request('type') === 'wisata' ? 'btn-primary' : 'btn-light' }}">🏖️ Wisata</a>
                    <a href="{{ route('admin.verifications.index', array_merge(request()->query(), ['type' => 'nongkrong'])) }}" class="btn btn-sm rounded-pill {{ request('type') === 'nongkrong' ? 'btn-primary' : 'btn-light' }}">☕ Nongkrong</a>
                </div>

                <!-- Filter Status -->
                <div class="btn-group rounded-pill p-1 bg-light border" role="group">
                    <a href="{{ route('admin.verifications.index', array_merge(request()->query(), ['status' => null])) }}" class="btn btn-sm rounded-pill {{ !request('status') ? 'btn-dark' : 'btn-light' }}">Semua Status</a>
                    <a href="{{ route('admin.verifications.index', array_merge(request()->query(), ['status' => 'pending'])) }}" class="btn btn-sm rounded-pill {{ request('status') === 'pending' ? 'btn-warning text-dark fw-bold' : 'btn-light' }}">Pending</a>
                    <a href="{{ route('admin.verifications.index', array_merge(request()->query(), ['status' => 'approved'])) }}" class="btn btn-sm rounded-pill {{ request('status') === 'approved' ? 'btn-success text-white' : 'btn-light' }}">Disetujui</a>
                    <a href="{{ route('admin.verifications.index', array_merge(request()->query(), ['status' => 'rejected'])) }}" class="btn btn-sm rounded-pill {{ request('status') === 'rejected' ? 'btn-danger text-white' : 'btn-light' }}">Ditolak</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Table Tempat Usaha -->
<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3">Nama Tempat Usaha</th>
                        <th class="py-3">Kategori</th>
                        <th class="py-3">Owner / Kontak WA</th>
                        <th class="py-3">Kecamatan</th>
                        <th class="py-3">Status Listing</th>
                        <th class="text-end pe-4 py-3">Aksi Verifikasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($allListings as $item)
                        @php
                            $placeType = $item->place_type ?? 'penginapan';
                            $badgeTypeClass = match($placeType) {
                                'wisata'    => 'bg-info bg-opacity-10 text-info border border-info-subtle',
                                'nongkrong' => 'bg-warning bg-opacity-10 text-dark border border-warning-subtle',
                                default     => 'bg-primary bg-opacity-10 text-primary border border-primary-subtle',
                            };
                            $badgeTypeLabel = match($placeType) {
                                'wisata'    => '🏖️ Wisata',
                                'nongkrong' => '☕ Nongkrong',
                                default     => '🏨 Penginapan',
                            };
                        @endphp
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ $item->thumbnail_url }}" alt="{{ $item->name }}" class="rounded-3 shadow-sm border" style="width: 54px; height: 54px; object-fit: cover;">
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0">{{ $item->name }}</h6>
                                        <small class="text-muted"><i class="fa-regular fa-clock me-1"></i>Diajukan: {{ $item->created_at->format('d M Y, H:i') }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge {{ $badgeTypeClass }} rounded-pill px-3 py-1.5 fw-semibold">
                                    {{ $badgeTypeLabel }}
                                </span>
                            </td>
                            <td>
                                <span class="fw-bold text-dark d-block">{{ $item->owner->company_name ?? ($item->manager_name ?? 'Mitra Owner') }}</span>
                                <small class="text-muted"><i class="fa-solid fa-phone text-success me-1"></i>{{ $item->phone ?: '-' }}</small>
                            </td>
                            <td>Kec. {{ $item->district ?? 'Gresik' }}</td>
                            <td>
                                @if($item->status === 'approved')
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 fw-semibold">
                                        <i class="fa-solid fa-circle-check me-1"></i> Approved
                                    </span>
                                @elseif($item->status === 'pending')
                                    <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-2 fw-semibold">
                                        <i class="fa-solid fa-clock me-1"></i> Pending
                                    </span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2 fw-semibold">
                                        <i class="fa-solid fa-times-circle me-1"></i> Rejected
                                    </span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('admin.verifications.show', ['id' => $item->id, 'type' => $placeType]) }}" class="btn btn-primary btn-sm rounded-pill px-3 fw-bold">
                                    Detail & Aksi <i class="fa-solid fa-arrow-right ms-1"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-clipboard-question fs-1 d-block mb-2 text-secondary"></i>
                                Tidak ada pengajuan tempat usaha pada filter ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
