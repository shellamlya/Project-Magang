@extends('layouts.admin')

@section('title', 'Profil Owner - Lokavino')
@section('page-title', 'Profil Usaha & Identitas Owner')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-9">
        <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
            <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom flex-wrap gap-2">
                <div>
                    <h5 class="fw-bold text-dark mb-1"><i class="fa-solid fa-id-card text-primary me-2"></i>Profil Identitas & Badan Usaha</h5>
                    <p class="small text-muted mb-0">Kelola informasi penanggung jawab, kontak WhatsApp, dan dokumen KTP Anda</p>
                </div>
                <div>
                    @if($owner->isAccountVerified())
                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 fw-semibold">
                            <i class="fa-solid fa-circle-check me-1"></i> Akun Terverifikasi
                        </span>
                    @elseif($owner->isAccountPending())
                        <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-2 fw-semibold">
                            <i class="fa-solid fa-clock me-1"></i> Menunggu Validasi Admin
                        </span>
                    @else
                        <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2 fw-semibold">
                            <i class="fa-solid fa-times-circle me-1"></i> Verifikasi Ditolak
                        </span>
                    @endif
                </div>
            </div>

            @if($owner->isAccountRejected())
                <div class="alert alert-danger rounded-3 mb-4">
                    <strong>Alasan Penolakan Akun:</strong> {{ $owner->account_rejection_reason }}
                    <p class="small mb-0 mt-1">Silakan perbarui data di bawah ini dan unggah ulang foto KTP yang valid dan jelas.</p>
                </div>
            @endif

            <form action="{{ route('owner.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Nama Lengkap Penanggung Jawab *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Nama Badan Usaha / Brand Utama *</label>
                        <input type="text" name="company_name" class="form-control" value="{{ old('company_name', $owner->company_name) }}" required>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Email Login (Read-only)</label>
                        <input type="email" class="form-control bg-light" value="{{ $user->email }}" readonly>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold">No. HP Personal (Khusus Admin WA) *</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $owner->phone ?? $user->phone) }}" required>
                        <small class="text-muted fs-8">Digunakan Admin untuk verifikasi.</small>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold">No. WA Bisnis (Tampil Publik / Booking)</label>
                        <input type="text" name="business_phone" class="form-control" value="{{ old('business_phone', $owner->business_phone ?? $owner->phone) }}">
                        <small class="text-muted fs-8">Digunakan pengunjung untuk pesan.</small>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">NIK KTP (Opsional)</label>
                        <input type="text" name="nik" class="form-control" value="{{ old('nik', $owner->nik) }}" placeholder="16 digit NIK">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Foto KTP Penanggung Jawab</label>
                        <input type="file" name="ktp_photo" class="form-control" accept="image/*,application/pdf">
                        <small class="text-muted fs-8">
                            @if($owner->ktp_photo)
                                <span class="text-success"><i class="fa-solid fa-check me-1"></i>KTP tersimpan di private storage aman.</span> Unggah jika ingin mengganti.
                            @else
                                <span class="text-danger">Belum ada file KTP.</span> Mohon unggah untuk verifikasi.
                            @endif
                        </small>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold">Alamat Usaha / Domisili</label>
                    <textarea name="address" rows="3" class="form-control" placeholder="Alamat lengkap usaha di Kabupaten Gresik">{{ old('address', $owner->address) }}</textarea>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                        <i class="fa-solid fa-save me-1"></i> Simpan Profil
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
