@extends('layouts.app')

@section('title', 'Login - GREX Gresik Explore')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="text-white p-4 text-center" style="background: #450C3F;">
                    <h4 class="fw-bold mb-1"><i class="fa-solid fa-compass me-2"></i>GREX </h4>
                    <p class="small text-white-50 mb-0">Masuk ke Akun Admin atau Owner Penginapan</p>
                </div>

                <div class="card-body p-4">
                    @if(session('error'))
                        <div class="alert alert-danger small mb-3">
                            <i class="fa-solid fa-exclamation-circle me-1"></i> {{ session('error') }}
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success small mb-3">
                            <i class="fa-solid fa-check-circle me-1"></i> {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label small fw-bold">Alamat Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-envelope text-muted"></i></span>
                                <input type="email" name="email" id="email" class="form-control bg-light border-start-0 @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="nama@email.com" required autofocus>
                            </div>
                            @error('email')
                                <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label small fw-bold">Kata Sandi</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-lock text-muted"></i></span>
                                <input type="password" name="password" id="password" class="form-control bg-light border-start-0 @error('password') is-invalid @enderror" placeholder="••••••••" required>
                            </div>
                            @error('password')
                                <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                <label class="form-check-label small text-muted" for="remember">Ingat Saya</label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-grex-primary w-100 rounded-3 py-2 fw-bold mb-3">
                            <i class="fa-solid fa-sign-in-alt me-1"></i> Masuk Sekarang
                        </button>
                    </form>

                    <!-- Hint Akun Demo -->
                    <div class="p-3 bg-light rounded-3 mt-4 border border-dashed">
                        <h6 class="fw-bold small text-dark mb-2"><i class="fa-solid fa-key text-warning me-1"></i> Akun Testing Demo:</h6>
                        <ul class="list-unstyled small text-muted mb-0">
                            <li><strong>Admin:</strong> <code>admin@grex.id</code> / <code>password</code></li>
                            <li><strong>Owner 1:</strong> <code>owner1@grex.id</code> / <code>password</code></li>
                            <li><strong>Owner 2:</strong> <code>owner2@grex.id</code> / <code>password</code></li>
                        </ul>
                    </div>

                    <div class="text-center mt-4 pt-2 border-top">
                        <p class="small text-muted mb-0">Belum memiliki akun owner? 
                            <a href="{{ route('register.owner') }}" class="text-primary fw-bold text-decoration-none">Daftar Mitra Owner</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
