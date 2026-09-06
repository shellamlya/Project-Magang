@extends('layouts.app')

@section('title', 'Verifikasi Email - Lokavino')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-5">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden" style="background: #ffffff;">
                <div class="p-4 text-center text-white" style="background: linear-gradient(135deg, #450C3F 0%, #4D3EA3 100%);">
                    <div class="rounded-circle bg-white bg-opacity-20 d-inline-flex align-items-center justify-content-center p-3 mb-2" style="width: 64px; height: 64px;">
                        <i class="fa-solid fa-envelope-open-text fs-3 text-white"></i>
                    </div>
                    <h4 class="fw-bold mb-1">Verifikasi Alamat Email</h4>
                    <p class="small text-white-50 mb-0">Satu langkah lagi untuk mengaktifkan akun Anda</p>
                </div>

                <div class="card-body p-4 p-md-5 text-center">
                    @if (session('status') == 'verification-link-sent')
                        <div class="alert alert-success border-0 rounded-3 mb-4 text-start small d-flex align-items-center gap-2">
                            <i class="fa-solid fa-circle-check fs-5 text-success"></i>
                            <div>Tautan verifikasi baru telah berhasil dikirimkan ke alamat email Anda. Silakan periksa kotak masuk atau folder spam.</div>
                        </div>
                    @endif

                    <p class="text-muted mb-4" style="line-height: 1.6;">
                        Terima kasih telah mendaftar sebagai Owner di <strong>Lokavino</strong>! Sebelum dapat melanjutkan, silakan periksa inbox email Anda di <strong class="text-dark">{{ auth()->user()->email ?? '' }}</strong> dan klik tautan konfirmasi yang kami kirimkan.
                    </p>

                    <div class="p-3 bg-light rounded-3 mb-4 text-start small text-muted">
                        <div class="fw-bold text-dark mb-1"><i class="fa-solid fa-circle-info text-primary me-1"></i>Belum menerima email?</div>
                        Pastikan untuk memeriksa folder <em>Spam / Junk</em> atau klik tombol di bawah untuk meminta pengiriman ulang tautan verifikasi.
                    </div>

                    <form method="POST" action="{{ route('verification.send') }}" class="mb-3">
                        @csrf
                        <button type="submit" class="btn btn-grex-primary w-100 py-2.5 fw-bold rounded-pill shadow-sm">
                            <i class="fa-solid fa-paper-plane me-1"></i> Kirim Ulang Email Verifikasi
                        </button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-link text-muted text-decoration-none small">
                            <i class="fa-solid fa-arrow-right-from-bracket me-1"></i> Keluar / Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
