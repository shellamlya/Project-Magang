@extends('layouts.app')

@section('title', 'Lupa Password - Lokavino')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="text-white p-4 text-center" style="background: #450C3F;">
                    <h4 class="fw-bold mb-1"><i class="fa-solid fa-key me-2"></i>Lupa Kata Sandi</h4>
                    <p class="small text-white-50 mb-0">Masukkan email akun Lokavino kamu</p>
                </div>

                <div class="card-body p-4">
                    @if (session('status'))
                        <div class="alert alert-success small mb-3">
                            <i class="fa-solid fa-check-circle me-1"></i> {{ session('status') }}
                        </div>
                    @endif

                    <form action="{{ route('password.email') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="email" class="form-label small fw-bold">Alamat Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-envelope text-muted"></i></span>
                                <input type="email" name="email" id="email" class="form-control bg-light border-start-0 @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="nama@email.com" required autofocus>
                            </div>
                            @error('email')
                                <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-grex-primary w-100 rounded-3 py-2 fw-bold mb-3">
                            <i class="fa-solid fa-paper-plane me-1"></i> Kirim Link Reset Password
                        </button>

                        <div class="text-center mt-3">
                            <a href="{{ route('login') }}" class="small text-decoration-none text-muted">
                                <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Login
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection