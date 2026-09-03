@extends('layouts.admin')

@section('title', 'Verifikasi Akun Owner - Admin Lokavino')
@section('page-title', 'Verifikasi Akun Owner (Tingkat 1)')

@section('content')
<!-- Header & Filter Stats -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card d-flex align-items-center justify-content-between">
            <div>
                <small class="text-muted d-block fw-semibold mb-1">Menunggu Validasi</small>
                <h3 class="fw-extrabold text-warning mb-0">{{ $stats['total_pending'] }}</h3>
                <small class="text-muted">Perlu konfirmasi WA & KTP</small>
            </div>
            <div class="icon-box bg-warning bg-opacity-10 text-warning">
                <i class="fa-solid fa-clock"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card d-flex align-items-center justify-content-between">
            <div>
                <small class="text-muted d-block fw-semibold mb-1">Akun Disetujui</small>
                <h3 class="fw-extrabold text-success mb-0">{{ $stats['total_verified'] }}</h3>
                <small class="text-muted">Dapat mendaftarkan usaha</small>
            </div>
            <div class="icon-box bg-success bg-opacity-10 text-success">
                <i class="fa-solid fa-user-check"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card d-flex align-items-center justify-content-between">
            <div>
                <small class="text-muted d-block fw-semibold mb-1">Akun Ditolak</small>
                <h3 class="fw-extrabold text-danger mb-0">{{ $stats['total_rejected'] }}</h3>
                <small class="text-muted">Data KTP / NIK tidak sesuai</small>
            </div>
            <div class="icon-box bg-danger bg-opacity-10 text-danger">
                <i class="fa-solid fa-user-xmark"></i>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4 bg-white rounded-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h5 class="fw-bold text-dark mb-1"><i class="fa-solid fa-id-card text-primary me-2"></i>Daftar Verifikasi Identitas Owner</h5>
                <p class="small text-muted mb-0">Validasi KTP dan nomor WhatsApp personal pemohon sebelum dapat mengelola tempat usaha</p>
            </div>

            <!-- Filter Status Tab -->
            <div class="btn-group rounded-pill p-1 bg-light border" role="group">
                <a href="{{ route('admin.owner-verifications.index', ['status' => 'all']) }}" class="btn btn-sm rounded-pill {{ request('status') === 'all' ? 'btn-primary' : 'btn-light' }}">Semua</a>
                <a href="{{ route('admin.owner-verifications.index', ['status' => 'pending_account']) }}" class="btn btn-sm rounded-pill {{ (!request('status') || request('status') === 'pending_account') ? 'btn-warning text-dark fw-bold' : 'btn-light' }}">Pending ({{ $stats['total_pending'] }})</a>
                <a href="{{ route('admin.owner-verifications.index', ['status' => 'account_verified']) }}" class="btn btn-sm rounded-pill {{ request('status') === 'account_verified' ? 'btn-success text-white' : 'btn-light' }}">Disetujui</a>
                <a href="{{ route('admin.owner-verifications.index', ['status' => 'rejected']) }}" class="btn btn-sm rounded-pill {{ request('status') === 'rejected' ? 'btn-danger text-white' : 'btn-light' }}">Ditolak</a>
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
                        <th class="ps-4 py-3">Nama Owner / Penanggung Jawab</th>
                        <th class="py-3">No. HP Personal (WA)</th>
                        <th class="py-3">Badan Usaha</th>
                        <th class="py-3">Status KTP</th>
                        <th class="py-3">Status Verifikasi</th>
                        <th class="text-end pe-4 py-3">Aksi Validasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($owners as $item)
                        @php
                            $cleanPhone = preg_replace('/[^0-9]/', '', $item->phone ?? ($item->user->phone ?? ''));
                            if (str_starts_with($cleanPhone, '0')) {
                                $cleanPhone = '62' . substr($cleanPhone, 1);
                            }
                            $waValidationUrl = $cleanPhone ? "https://wa.me/{$cleanPhone}?text=" . urlencode("Halo Bapak/Ibu {$item->user->name}, kami dari Tim Admin Lokavino ingin melakukan verifikasi akun pendaftaran Owner Anda.") : null;
                        @endphp
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold" style="width: 42px; height: 42px;">
                                        {{ strtoupper(substr($item->user->name ?? 'O', 0, 1)) }}
                                    </div>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0">{{ $item->user->name }}</h6>
                                        <small class="text-muted"><i class="fa-solid fa-envelope me-1"></i>{{ $item->user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($cleanPhone)
                                    <span class="fw-semibold text-dark d-block">{{ $item->phone ?? $item->user->phone }}</span>
                                    <a href="{{ $waValidationUrl }}" target="_blank" class="btn btn-sm btn-outline-success rounded-pill px-2 py-0 fs-8 mt-1">
                                        <i class="fa-brands fa-whatsapp me-1"></i> Chat WA Admin
                                    </a>
                                @else
                                    <span class="text-muted small">Tidak tersedia</span>
                                @endif
                            </td>
                            <td>
                                <span class="fw-bold text-dark">{{ $item->company_name ?: 'Perorangan' }}</span>
                                @if($item->address)
                                    <small class="text-muted d-block text-truncate" style="max-width: 180px;">{{ $item->address }}</small>
                                @endif
                            </td>
                            <td>
                                @if($item->ktp_photo)
                                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 fw-semibold">
                                        <i class="fa-solid fa-id-card me-1"></i> KTP Diunggah
                                    </span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-muted rounded-pill px-3 py-2 fw-semibold">
                                        <i class="fa-solid fa-minus me-1"></i> Belum ada KTP
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($item->account_status === 'account_verified')
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 fw-semibold">
                                        <i class="fa-solid fa-check-circle me-1"></i> Terverifikasi
                                    </span>
                                @elseif($item->account_status === 'pending_account')
                                    <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-2 fw-semibold">
                                        <i class="fa-solid fa-clock me-1"></i> Menunggu Validasi
                                    </span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2 fw-semibold">
                                        <i class="fa-solid fa-times-circle me-1"></i> Ditolak
                                    </span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('admin.owner-verifications.show', $item->id) }}" class="btn btn-primary btn-sm rounded-pill px-3 fw-bold">
                                    Periksa Data <i class="fa-solid fa-arrow-right ms-1"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-user-clock fs-1 d-block mb-2 text-muted"></i>
                                Tidak ada akun owner pada filter ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4 d-flex justify-content-center">
    {{ $owners->links('pagination::bootstrap-5') }}
</div>
@endsection
