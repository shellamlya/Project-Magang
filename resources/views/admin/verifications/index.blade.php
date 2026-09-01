@extends('layouts.admin')

@section('title', 'Verifikasi Penginapan - Admin Lokavino')
@section('page-title', 'Verifikasi Data Penginapan')

@section('content')
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4 bg-white rounded-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h5 class="fw-bold text-dark mb-1"><i class="fa-solid fa-clipboard-check text-primary me-2"></i>Daftar Verifikasi Penginapan</h5>
                <p class="small text-muted mb-0">Verifikasi kelengkapan dan keabsahan data penginapan yang diajukan oleh Owner</p>
            </div>

            <!-- Filter Status Tab -->
            <div class="btn-group rounded-pill p-1 bg-light border" role="group">
                <a href="{{ route('admin.verifications.index') }}" class="btn btn-sm rounded-pill {{ !request('status') ? 'btn-primary' : 'btn-light' }}">Semua</a>
                <a href="{{ route('admin.verifications.index', ['status' => 'pending']) }}" class="btn btn-sm rounded-pill {{ request('status') == 'pending' ? 'btn-warning text-dark fw-bold' : 'btn-light' }}">Pending</a>
                <a href="{{ route('admin.verifications.index', ['status' => 'approved']) }}" class="btn btn-sm rounded-pill {{ request('status') == 'approved' ? 'btn-success text-white' : 'btn-light' }}">Disetujui</a>
                <a href="{{ route('admin.verifications.index', ['status' => 'rejected']) }}" class="btn btn-sm rounded-pill {{ request('status') == 'rejected' ? 'btn-danger text-white' : 'btn-light' }}">Ditolak</a>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3">Nama Penginapan</th>
                        <th class="py-3">Owner / Usaha</th>
                        <th class="py-3">Kecamatan</th>
                        <th class="py-3">Status Verifikasi</th>
                        <th class="text-end pe-4 py-3">Aksi Verifikasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lodgings as $item)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0">{{ $item->name }}</h6>
                                        <small class="text-muted">Diajukan: {{ $item->created_at->format('d M Y, H:i') }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="fw-semibold text-dark d-block">{{ $item->owner->company_name ?? 'Perorangan / Admin' }}</span>
                                <small class="text-muted"><i class="fa-solid fa-user me-1"></i>{{ $item->owner->user->name ?? 'Admin' }}</small>
                            </td>
                            <td>Kec. {{ $item->district ?? '-' }}</td>
                            <td>
                                @if($item->status === 'approved')
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 fw-semibold">
                                        <i class="fa-solid fa-check-circle me-1"></i> Approved
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
                                <a href="{{ route('admin.verifications.show', $item->id) }}" class="btn btn-primary btn-sm rounded-pill px-3 fw-bold">
                                    Detail & Action <i class="fa-solid fa-arrow-right ms-1"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">Tidak ada pengajuan penginapan untuk verifikasi.</td>
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
