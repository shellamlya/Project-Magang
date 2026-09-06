@extends('layouts.app')

@section('title', 'Status Verifikasi Akun - Lokavino')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-9">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden" style="background: #ffffff;">
                <!-- Header Banner -->
                <div class="p-4 p-md-5 text-center text-white" style="background: linear-gradient(135deg, #7F1D1D 0%, #DC2626 100%);">
                    <div class="rounded-circle bg-white bg-opacity-20 d-inline-flex align-items-center justify-content-center p-3 mb-3" style="width: 72px; height: 72px;">
                        <i class="fa-solid fa-circle-xmark fs-2 text-white"></i>
                    </div>
                    <h3 class="fw-bold mb-2">Verifikasi Akun Belum Disetujui</h3>
                    <p class="small text-white-50 mb-0">Pengajuan akun Owner Anda memerlukan perbaikan dokumen atau informasi</p>
                </div>

                <!-- Body Card -->
                <div class="card-body p-4 p-md-5">
                    <!-- Box Alasan Penolakan -->
                    <div class="alert alert-danger border-0 rounded-4 p-3 mb-4" style="background-color: #FEF2F2; border-left: 5px solid #DC2626 !important;">
                        <h6 class="fw-bold text-danger mb-1"><i class="fa-solid fa-triangle-exclamation me-1"></i> Alasan Penolakan dari Admin:</h6>
                        <p class="small text-dark mb-0 fw-semibold" style="line-height: 1.6;">
                            {{ auth()->user()->owner->account_rejection_reason ?? 'Data identitas atau foto KTP yang diunggah belum memenuhi ketentuan verifikasi Lokavino.' }}
                        </p>
                    </div>

                    <!-- Rincian Akun -->
                    <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-user-circle text-primary me-2"></i>Rincian Akun Terdaftar</h6>
                    <div class="bg-light rounded-3 p-3 mb-4 border">
                        <div class="row g-2 small">
                            <div class="col-sm-5 text-muted">Nama Pemilik:</div>
                            <div class="col-sm-7 fw-bold text-dark">{{ auth()->user()->name }}</div>

                            <div class="col-sm-5 text-muted">Email:</div>
                            <div class="col-sm-7 fw-bold text-dark">{{ auth()->user()->email }}</div>

                            <div class="col-sm-5 text-muted">Nama Usaha:</div>
                            <div class="col-sm-7 fw-bold text-dark">{{ auth()->user()->owner->company_name ?? '-' }}</div>

                            <div class="col-sm-5 text-muted">No. WhatsApp:</div>
                            <div class="col-sm-7 fw-bold text-dark">{{ auth()->user()->owner->phone ?? (auth()->user()->phone ?? '-') }}</div>
                        </div>
                    </div>

                    <!-- Langkah Selanjutnya -->
                    <div class="p-3 border rounded-3 mb-4" style="background-color: #F8FAFC;">
                        <h6 class="fw-bold text-dark small mb-2"><i class="fa-solid fa-lightbulb text-warning me-1"></i> Saran Tindak Lanjut:</h6>
                        <ul class="small text-muted ps-3 mb-0">
                            <li>Pastikan dokumen foto KTP jelas, tidak buram, dan NIK dapat terbaca oleh tim kurasi.</li>
                            <li>Pastikan nomor WhatsApp Anda aktif agar tim admin kami dapat melakukan konfirmasi langsung.</li>
                            <li>Jika ingin mengajukan peninjauan kembali, Anda dapat memperbarui profil atau menghubungi admin.</li>
                        </ul>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="row g-2 pt-2">
                        <div class="col-sm-6">
                            <a href="{{ route('owner.profile') }}" class="btn btn-grex-primary w-100 py-2.5 fw-bold rounded-pill shadow-sm">
                                <i class="fa-solid fa-user-pen me-1"></i> Perbarui Profil & KTP
                            </a>
                        </div>
                        <div class="col-sm-6">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-secondary w-100 py-2.5 fw-bold rounded-pill">
                                    <i class="fa-solid fa-arrow-right-from-bracket me-1"></i> Keluar / Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
