@extends('layouts.app')

@section('title', 'Registrasi Owner Sederhana - GREX Gresik Explore')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden" style="background: #ffffff;">
                <div class="p-4 text-center text-white" style="background: #450C3F;">
                    <h4 class="fw-bold mb-1"><i class="fa-solid fa-user-plus me-2"></i>Daftar Akun Owner</h4>
                    <p class="small text-white-50 mb-0">Daftar sekarang untuk mengelola penginapan, tempat wisata, atau nongkrong Anda di GREX</p>
                </div>

                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('register.owner') }}" method="POST">
                        @csrf

                        <!-- Field 1: Nama -->
                        <div class="mb-3">
                            <label for="name" class="form-label small fw-bold">Nama Lengkap *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-user text-muted"></i></span>
                                <input type="text" name="name" id="name" class="form-control border-start-0 @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Nama Anda" required>
                            </div>
                            @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <!-- Field 2: Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label small fw-bold">Alamat Email *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-envelope text-muted"></i></span>
                                <input type="email" name="email" id="email" class="form-control border-start-0 @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="email@domain.com" required>
                            </div>
                            @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <!-- Field 3: Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label small fw-bold">Kata Sandi *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-lock text-muted"></i></span>
                                <input type="password" name="password" id="password" class="form-control border-start-0 @error('password') is-invalid @enderror" placeholder="Minimal 8 karakter" required>
                            </div>
                            @error('password') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <!-- Field 4: Konfirmasi Password -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label small fw-bold">Konfirmasi Kata Sandi *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-shield-halved text-muted"></i></span>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control border-start-0" placeholder="Ulangi kata sandi" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-grex-primary w-100 py-2.5 fw-bold mb-3">
                            <i class="fa-solid fa-right-to-bracket me-1"></i> Register Sebagai Owner
                        </button>
                    </form>

                    <div class="text-center mt-3 pt-3 border-top">
                        <p class="small text-muted mb-0">Sudah memiliki akun? 
                            <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none">Masuk Ke Sistem</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
