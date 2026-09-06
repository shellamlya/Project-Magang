@extends('layouts.app')

@section('title', 'Reset Password - Lokavino')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="text-white p-4 text-center" style="background: #450C3F;">
                    <h4 class="fw-bold mb-1"><i class="fa-solid fa-lock me-2"></i>Atur Password Baru</h4>
                    <p class="small text-white-50 mb-0">Buat kata sandi baru untuk akun kamu</p>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('password.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="mb-3">
                            <label for="email" class="form-label small fw-bold">Alamat Email</label>
                            <input type="email" name="email" id="email" class="form-control bg-light @error('email') is-invalid @enderror" value="{{ $email ?? old('email') }}" required readonly>
                            @error('email')
                                <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label small fw-bold">Kata Sandi Baru</label>
                            <input type="password" name="password" id="password" class="form-control bg-light @error('password') is-invalid @enderror" placeholder="••••••••" required>
                            @error('password')
                                <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label small fw-bold">Konfirmasi Kata Sandi Baru</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control bg-light" placeholder="••••••••" required>
                        </div>

                        <button type="submit" class="btn btn-grex-primary w-100 rounded-3 py-2 fw-bold">
                            <i class="fa-solid fa-save me-1"></i> Simpan Password Baru
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection