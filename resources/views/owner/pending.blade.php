@extends('layouts.app')

@section('title', 'Verifikasi Akun Sedang Diproses - Lokavino')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-9">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden" style="background: #ffffff;">
                <!-- Header Banner -->
                <div class="p-4 p-md-5 text-center text-white" style="background: linear-gradient(135deg, #450C3F 0%, #4D3EA3 100%);">
                    <div class="rounded-circle bg-white bg-opacity-20 d-inline-flex align-items-center justify-content-center p-3 mb-3" style="width: 72px; height: 72px;">
                        <i class="fa-solid fa-hourglass-half fs-2 text-warning"></i>
                    </div>
                    <h3 class="fw-bold mb-2">Akun Anda Sedang Dalam Peninjauan Admin</h3>
                    <p class="small text-white-50 mb-0">Terima kasih telah bergabung bersama platform kemitraan Lokavino</p>
                </div>

                <!-- Body Card -->
                <div class="card-body p-4 p-md-5">
                    @if(session('info'))
                        <div class="alert alert-info border-0 rounded-3 mb-4 small">
                            <i class="fa-solid fa-circle-info me-1"></i> {{ session('info') }}
                        </div>
                    @endif

                    <!-- Highlight Message -->
                    <div class="alert alert-warning border-0 rounded-4 p-3 mb-4 d-flex align-items-start gap-3" style="background-color: #FFFBEB;">
                        <i class="fa-solid fa-shield-halved fs-4 text-warning mt-1"></i>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">Pemeriksaan Dokumen & Validasi WhatsApp</h6>
                            <p class="small text-muted mb-0" style="line-height: 1.5;">
                                Demi menjaga kualitas dan keaslian mitra tempat usaha di Lokavino, Tim Admin kami sedang meninjau kelayakan data dan foto KTP penanggung jawab yang Anda unggah.
                            </p>
                        </div>
                    </div>

                    <!-- Data Pendaftaran Owner -->
                    <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-id-card-clip text-primary me-2"></i>Rincian Pengajuan Akun Anda</h6>
                    <div class="bg-light rounded-3 p-3 mb-4 border">
                        <div class="row g-2 small">
                            <div class="col-sm-5 text-muted">Penanggung Jawab:</div>
                            <div class="col-sm-7 fw-bold text-dark">{{ auth()->user()->name }}</div>

                            <div class="col-sm-5 text-muted">Alamat Email:</div>
                            <div class="col-sm-7 fw-bold text-dark">{{ auth()->user()->email }}</div>

                            <div class="col-sm-5 text-muted">Nama Tempat Usaha:</div>
                            <div class="col-sm-7 fw-bold text-dark">{{ auth()->user()->owner->company_name ?? '-' }}</div>

                            <div class="col-sm-5 text-muted">No. WhatsApp Validasi:</div>
                            <div class="col-sm-7 fw-bold text-success">
                                <i class="fa-brands fa-whatsapp me-1"></i>{{ auth()->user()->owner->phone ?? (auth()->user()->phone ?? '-') }}
                            </div>

                            <div class="col-sm-5 text-muted">Status Saat Ini:</div>
                            <div class="col-sm-7">
                                <span class="badge bg-warning bg-opacity-25 text-dark border border-warning rounded-pill px-3 py-1 fw-bold">
                                    <i class="fa-solid fa-clock me-1 text-warning"></i> Menunggu Persetujuan Admin
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Petunjuk Admin WhatsApp -->
                    <div class="p-3 border rounded-3 mb-4" style="background-color: #F8FAFC;">
                        <h6 class="fw-bold text-dark small mb-1"><i class="fa-brands fa-whatsapp text-success me-1"></i> Perhatian Penting:</h6>
                        <p class="small text-muted mb-0">
                            Admin Lokavino akan melakukan <strong>konfirmasi data & KTP melalui WhatsApp</strong> ke nomor HP Personal yang terdaftar di atas. Mohon pastikan WhatsApp Anda aktif untuk mempercepat proses persetujuan akun.
                        </p>
                    </div>

                    <!-- Tombol Aksi: Cek Status & Logout -->
                    <div class="row g-2 pt-2">
                        <div class="col-sm-6">
                            <a href="{{ route('owner.dashboard') }}" class="btn btn-grex-primary w-100 py-2.5 fw-bold rounded-pill shadow-sm">
                                <i class="fa-solid fa-arrows-rotate me-1"></i> Cek Status / Refresh
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
