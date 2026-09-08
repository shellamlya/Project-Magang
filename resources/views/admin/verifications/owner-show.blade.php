@extends('layouts.admin')

@section('title', 'Periksa Akun Owner - Admin Lokavino')
@section('page-title', 'Pemeriksaan Identitas Owner')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.owner-verifications.index') }}" class="btn btn-outline-secondary rounded-pill btn-sm">
        <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Daftar Verifikasi Owner
    </a>
</div>

@php
    // Ambil nomor telepon dari data owner (business_phone / phone / user->phone)
    $rawPhone = $owner->business_phone ?: ($owner->phone ?: ($owner->user->phone ?? ''));
    $cleanDigits = preg_replace('/[^0-9]/', '', $rawPhone);

    // Format nomor HP ke format internasional (0812... -> 62812...)
    if (str_starts_with($cleanDigits, '0')) {
        $formattedPhone = '62' . substr($cleanDigits, 1);
    } elseif (str_starts_with($cleanDigits, '62')) {
        $formattedPhone = $cleanDigits;
    } elseif (!empty($cleanDigits)) {
        $formattedPhone = '62' . $cleanDigits;
    } else {
        $formattedPhone = null;
    }

    $ownerName = $owner->user->name ?? 'Bapak/Ibu Mitra';
    $waMessage = "Halo {$ownerName}, saya Admin Lokavino ingin mengonfirmasi pendaftaran tempat usaha Anda.";
    $waValidationUrl = $formattedPhone ? "https://wa.me/{$formattedPhone}?text=" . urlencode($waMessage) : null;
@endphp

<div class="row g-4">
    <!-- Kolom Kiri: Detail Akun & Dokumen KTP -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom flex-wrap gap-2">
                <div>
                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle me-2">Akun Owner #{{ $owner->id }}</span>
                    <span class="text-muted small">Terdaftar: {{ $owner->created_at->format('d M Y, H:i') }}</span>
                </div>
                <div>
                    @if($owner->verification_status === 'approved' || $owner->account_status === 'account_verified')
                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 fw-bold"><i class="fa-solid fa-circle-check me-1"></i> Akun Terverifikasi</span>
                    @elseif($owner->verification_status === 'pending' || $owner->account_status === 'pending_account')
                        <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-2 fw-bold"><i class="fa-solid fa-clock me-1"></i> Menunggu Validasi Admin</span>
                    @else
                        <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2 fw-bold"><i class="fa-solid fa-times-circle me-1"></i> Akun Ditolak</span>
                    @endif
                </div>
            </div>

            <!-- Identitas Pemohon -->
            <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-user-shield text-primary me-2"></i>Informasi Pemohon & Penanggung Jawab</h5>
            <div class="row g-3 p-3 bg-light rounded-3 mb-4">
                <div class="col-md-6">
                    <small class="text-muted d-block">Nama Lengkap Penanggung Jawab:</small>
                    <span class="fw-bold text-dark fs-6">{{ $owner->user->name }}</span>
                </div>
                <div class="col-md-6">
                    <small class="text-muted d-block">Alamat Email Login:</small>
                    <span class="fw-bold text-dark fs-6">{{ $owner->user->email }}</span>
                </div>
                <div class="col-md-6">
                    <small class="text-muted d-block">No. HP / WhatsApp Personal (Validasi WA):</small>
                    <div class="d-flex align-items-center gap-2 mt-1">
                        <span class="fw-bold text-success fs-6"><i class="fa-brands fa-whatsapp me-1"></i>{{ $owner->phone ?? ($owner->business_phone ?? ($owner->user->phone ?? '-')) }}</span>
                        @if($formattedPhone)
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill font-monospace small">+{{ $formattedPhone }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <small class="text-muted d-block">Nama Usaha / Brand Utama:</small>
                    <span class="fw-bold text-dark fs-6">{{ $owner->company_name ?: 'Perorangan' }}</span>
                </div>
                @if($owner->nik)
                    <div class="col-md-6">
                        <small class="text-muted d-block">Nomor Induk Kependudukan (NIK):</small>
                        <span class="fw-bold text-dark fs-6">{{ $owner->nik }}</span>
                    </div>
                @endif
                @if($owner->address)
                    <div class="col-12">
                        <small class="text-muted d-block">Alamat Domisili / Usaha:</small>
                        <span class="fw-bold text-dark small">{{ $owner->address }}</span>
                    </div>
                @endif
            </div>

            <!-- Dokumen Foto KTP -->
            <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-id-card text-primary me-2"></i>Dokumen Foto KTP Penanggung Jawab</h5>
            <div class="p-3 border rounded-3 bg-light mb-4 text-center">
                @if($owner->ktp_photo)
                    <div class="mb-3 position-relative d-inline-block">
                        <img src="{{ route('admin.owner-verifications.ktp', $owner->id) }}" alt="Foto KTP Owner" class="img-fluid rounded-3 shadow-sm border" style="max-height: 320px; object-fit: contain; background: #ffffff;" id="ktpImagePreview">
                    </div>
                    <div class="d-flex justify-content-center gap-2">
                        <a href="{{ route('admin.owner-verifications.ktp', $owner->id) }}" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                            <i class="fa-solid fa-up-right-from-square me-1"></i> Buka KTP Ukuran Penuh
                        </a>
                    </div>
                @else
                    <div class="py-4 text-muted">
                        <i class="fa-solid fa-file-circle-xmark fs-1 d-block mb-2 text-secondary"></i>
                        <p class="mb-0">Foto KTP belum diunggah oleh pemohon.</p>
                    </div>
                @endif
            </div>

            <!-- Keputusan Verifikasi Admin -->
            <div class="p-4 border rounded-4 bg-light">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h6 class="fw-bold text-dark mb-1">Keputusan Verifikasi Akun Owner</h6>
                        <small class="text-muted">
                            @if($owner->verification_status === 'pending')
                                Setelah memvalidasi KTP dan menghubungi via WhatsApp, tentukan status persetujuan akun.
                            @else
                                Status keputusan akhir untuk verifikasi akun owner ini.
                            @endif
                        </small>
                    </div>

                    @if($owner->verification_status === 'pending')
                        <div class="d-flex gap-2">
                            <!-- Form Approve -->
                            <form action="{{ route('admin.owner-verifications.approve', $owner->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm" onclick="return confirm('Apakah Anda yakin ingin menyetujui akun Owner ini?')">
                                    <i class="fa-solid fa-check me-1"></i> Setujui Akun (Approve)
                                </button>
                            </form>

                            <!-- Button Trigger Modal Reject -->
                            <button type="button" class="btn btn-danger rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#rejectOwnerModal">
                                <i class="fa-solid fa-times me-1"></i> Tolak Akun (Reject)
                            </button>
                        </div>
                    @else
                        <!-- Tampilan Jika Keputusan Sudah Diambil (Approved / Rejected) -->
                        <div>
                            @if($owner->verification_status === 'approved')
                                <span class="badge bg-success fs-6 rounded-pill px-3 py-2">
                                    <i class="fa-solid fa-circle-check me-1"></i> Akun Telah Disetujui
                                </span>
                            @else
                                <span class="badge bg-danger fs-6 rounded-pill px-3 py-2">
                                    <i class="fa-solid fa-circle-xmark me-1"></i> Akun Telah Ditolak
                                </span>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Tampilkan Alasan Penolakan jika Status Rejected -->
                @if($owner->verification_status === 'rejected' && ($owner->account_rejection_reason || $owner->rejection_reason))
                    <div class="alert alert-danger mb-0 mt-3 rounded-3 border-0">
                        <small class="fw-bold text-dark d-block mb-1"><i class="fa-solid fa-triangle-exclamation me-1"></i> Alasan Penolakan Admin:</small>
                        <span class="small text-dark">{{ $owner->account_rejection_reason ?? $owner->rejection_reason }}</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Log Verifikasi Akun -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-history text-primary me-2"></i>Histori Log Verifikasi Akun</h6>
            @forelse($verificationLogs as $log)
                <div class="border-start border-3 border-primary ps-3 pb-3 mb-2">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="badge @if($log->status === 'approved') bg-success @else bg-danger @endif">
                            {{ strtoupper($log->status) }}
                        </span>
                        <small class="text-muted">{{ $log->created_at->format('d M Y, H:i') }}</small>
                    </div>
                    <p class="small text-dark mb-0"><strong>Admin:</strong> {{ $log->admin->name ?? 'Administrator' }}</p>
                    @if($log->notes)
                        <p class="small text-muted mb-0"><strong>Catatan / Alasan:</strong> {{ $log->notes }}</p>
                    @endif
                </div>
            @empty
                <p class="text-muted small mb-0">Belum ada histori tindakan verifikasi untuk akun ini.</p>
            @endforelse
        </div>
    </div>

    <!-- Kolom Kanan: Aksi Cepat WA & Kontak -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
            <h5 class="fw-bold text-dark mb-3"><i class="fa-brands fa-whatsapp text-success me-2"></i>Aksi Validasi WhatsApp</h5>
            <p class="small text-muted mb-3">Hubungi penanggung jawab via WhatsApp resmi untuk memvalidasi keaslian usaha sebelum menyetujui.</p>

            @if($waValidationUrl)
                <a href="{{ $waValidationUrl }}" target="_blank" class="btn btn-success rounded-pill py-2.5 fw-bold w-100 shadow-sm mb-3">
                    <i class="fa-brands fa-whatsapp me-2 fs-5"></i> Hubungi via WhatsApp
                </a>
            @else
                <button class="btn btn-secondary rounded-pill py-2.5 fw-bold w-100 mb-3" disabled>No. HP Tidak Tersedia</button>
            @endif

            <div class="p-3 bg-light rounded-3">
                <small class="fw-bold text-dark d-block mb-1">Tips Validasi Identitas:</small>
                <ul class="small text-muted ps-3 mb-0">
                    <li>Pastikan foto KTP jelas, tidak buram, dan NIK terbaca.</li>
                    <li>Konfirmasi nama usaha dan kesesuaian lokasi tempat usaha yang didaftarkan.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Modal Reject Akun Owner -->
<div class="modal fade" id="rejectOwnerModal" tabindex="-1" aria-labelledby="rejectOwnerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header bg-danger text-white border-0">
                <h5 class="modal-title fw-bold" id="rejectOwnerModalLabel"><i class="fa-solid fa-circle-exclamation me-2"></i>Tolak Akun Owner</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.owner-verifications.reject', $owner->id) }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <p class="text-muted small mb-3">Berikan alasan penolakan secara jelas. Pesan ini akan dikirimkan dan ditampilkan pada dashboard owner agar dapat diperbaiki.</p>
                    
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label fw-bold small">Alasan Penolakan Akun *</label>
                        <textarea name="rejection_reason" id="rejection_reason" rows="4" class="form-control" placeholder="Contoh: Foto KTP terpotong/buram, mohon unggah ulang foto KTP yang jelas..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold">Konfirmasi Penolakan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection