@extends('layouts.admin')

@section('title', 'Detail Verifikasi - Admin GREX')
@section('page-title', 'Detail Pengajuan Penginapan')

@section('content')
<div class="row g-4">
    <!-- Main Detail & Verification Actions -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                <div>
                    <span class="badge bg-light text-primary border me-2">Penginapan (Shella)</span>
                    <span class="text-muted small">ID Penginapan: #{{ $lodging->id }}</span>
                </div>
                <div>
                    @if($lodging->status === 'approved')
                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 fw-bold"><i class="fa-solid fa-check-circle me-1"></i> Status: Approved</span>
                    @elseif($lodging->status === 'pending')
                        <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-2 fw-bold"><i class="fa-solid fa-clock me-1"></i> Status: Pending Verifikasi</span>
                    @else
                        <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2 fw-bold"><i class="fa-solid fa-times-circle me-1"></i> Status: Rejected</span>
                    @endif
                </div>
            </div>

            <h4 class="fw-bold text-dark mb-2">{{ $lodging->name }}</h4>
            <p class="text-muted small mb-4">
                <i class="fa-solid fa-location-dot text-danger me-1"></i> {{ $lodging->address }}
                , Kec. {{ $lodging->district ?? 'Gresik' }}, Gresik
            </p>

            <h6 class="fw-bold text-dark mb-2">Deskripsi Penginapan:</h6>
            <p class="text-secondary small leading-relaxed mb-4" style="white-space: pre-line;">{{ $lodging->description }}</p>

            <h6 class="fw-bold text-dark mb-2">Fasilitas Penginapan:</h6>
            <div class="row g-2 mb-4">
                @forelse($lodging->facilities as $fac)
                    <div class="col-md-4 col-6">
                        <div class="p-2 border rounded-3 bg-light small fw-semibold">
                            <i class="fa-solid fa-circle-check text-primary me-2"></i> {{ $fac->facility_name }}
                        </div>
                    </div>
                @empty
                    <p class="text-muted small">Tidak ada fasilitas khusus.</p>
                @endforelse
            </div>

            <h6 class="fw-bold text-dark mb-2">Informasi Operasional & Harga:</h6>
            <div class="row g-3 p-3 bg-light rounded-3 mb-4">
                <div class="col-md-6">
                    <small class="text-muted d-block">Harga Mulai - Maksimal:</small>
                    <span class="fw-bold text-primary fs-5">Rp {{ number_format($lodging->price_start, 0, ',', '.') }} – Rp {{ number_format($lodging->price_end, 0, ',', '.') }}</span>
                </div>
                <div class="col-md-3">
                    <small class="text-muted d-block">Check-in:</small>
                    <span class="fw-bold text-dark">{{ $lodging->check_in ?? '14.00 WIB' }}</span>
                </div>
                <div class="col-md-3">
                    <small class="text-muted d-block">Check-out:</small>
                    <span class="fw-bold text-dark">{{ $lodging->check_out ?? '12.00 WIB' }}</span>
                </div>
            </div>

            <!-- Tombol Aksi Verifikasi Admin -->
            <div class="p-3 border rounded-4 bg-light d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h6 class="fw-bold text-dark mb-0">Keputusan Verifikasi Admin</h6>
                    <small class="text-muted">Setujui untuk menayangkan penginapan atau Tolak jika data tidak sesuai.</small>
                </div>

                <div class="d-flex gap-2">
                    <!-- Form Approve -->
                    <form action="{{ route('admin.verifications.approve', $lodging->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold" onclick="return confirm('Setujui pengajuan penginapan ini?')">
                            <i class="fa-solid fa-check me-1"></i> Setujui (Approve)
                        </button>
                    </form>

                    <!-- Tombol Trigger Modal Reject -->
                    <button type="button" class="btn btn-danger rounded-pill px-4 fw-bold" data-bs-toggle="modal" data-bs-target="#rejectModal">
                        <i class="fa-solid fa-times me-1"></i> Tolak (Reject)
                    </button>
                </div>
            </div>
        </div>

        <!-- Histori Verification Logs -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-history text-primary me-2"></i>Histori Verifikasi Log</h6>
            <div class="timeline">
                @forelse($lodging->verificationLogs as $log)
                    <div class="border-start border-2 border-primary ps-3 pb-3 mb-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge @if($log->status === 'approved') bg-success @else bg-danger @endif">
                                {{ strtoupper($log->status) }}
                            </span>
                            <small class="text-muted">{{ $log->created_at->format('d M Y, H:i') }}</small>
                        </div>
                        <small class="d-block text-dark mt-1">Oleh: <strong>{{ $log->admin->name }}</strong></small>
                        <p class="small text-muted mb-0 mt-1">{{ $log->notes }}</p>
                    </div>
                @empty
                    <p class="text-muted small mb-0">Belum ada histori verifikasi.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Sidebar Info Owner -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white sticky-top" style="top: 90px;">
            <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-user-tie text-primary me-2"></i>Informasi Pengaju (Owner / Pengelola)</h6>
            
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                    <i class="fa-solid fa-store fs-4"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0 text-dark">{{ $lodging->manager_name ?? 'Pengelola' }}</h6>
                    <small class="text-muted">Manajemen Penginapan</small>
                </div>
            </div>

            <ul class="list-unstyled small text-muted mb-4">
                <li class="mb-2"><i class="fa-solid fa-user me-2 text-primary"></i> <strong>Nama Owner:</strong> {{ $lodging->owner->user->name ?? 'Admin System' }}</li>
                <li class="mb-2"><i class="fa-solid fa-envelope me-2 text-primary"></i> <strong>Email:</strong> {{ $lodging->email ?? '-' }}</li>
                <li class="mb-2"><i class="fa-solid fa-phone me-2 text-primary"></i> <strong>No. Telepon:</strong> {{ $lodging->phone ?? '-' }}</li>
            </ul>

            <a href="{{ route('admin.verifications.index') }}" class="btn btn-outline-secondary w-100 rounded-pill">
                <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Daftar
            </a>
        </div>
    </div>
</div>

<!-- Modal Reject Penolakan -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('admin.verifications.reject', $lodging->id) }}" method="POST">
            @csrf
            <div class="modal-content rounded-4">
                <div class="modal-header bg-danger text-white">
                    <h6 class="modal-title fw-bold"><i class="fa-solid fa-circle-exclamation me-1"></i> Formulir Penolakan Pengajuan</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="small text-muted mb-3">Tuliskan alasan penolakan secara jelas agar Pemilik Penginapan (Owner) dapat memperbaiki pengajuannya.</p>
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label small fw-bold text-dark">Alasan Penolakan *</label>
                        <textarea name="rejection_reason" id="rejection_reason" rows="4" class="form-control @error('rejection_reason') is-invalid @enderror" placeholder="Contoh: Dokumen perizinan atau kontak telepon tidak valid..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm rounded-pill" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger btn-sm rounded-pill px-4 fw-bold">Konfirmasi Penolakan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
