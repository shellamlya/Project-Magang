@extends('layouts.app')

@section('title', 'Registrasi Owner - Lokavino')

@section('styles')
<style>
    .security-notice {
        background: #fdf2f8;
        border-left: 4px solid #450C3F;
        border-radius: 12px;
        padding: 0.85rem 1rem;
    }
</style>
@endsection

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden" style="background: #ffffff;">
                <div class="p-4 text-center text-white" style="background: #450C3F;">
                    <h4 class="fw-bold mb-1"><i class="fa-solid fa-user-plus me-2"></i>Daftar Akun Owner</h4>
                    <p class="small text-white-50 mb-0">Daftarkan akun pemilik usaha untuk mulai mengelola dan mempromosikan tempat usaha Anda di Lokavino</p>
                </div>

                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('register.owner') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Field 1: Nama Lengkap -->
                        <div class="mb-3">
                            <label for="name" class="form-label small fw-bold">Nama Lengkap Owner / Penanggung Jawab *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-user text-muted"></i></span>
                                <input type="text" name="name" id="name" class="form-control border-start-0 @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contoh: Budi Santoso" required>
                            </div>
                            @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <!-- Field 2: Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label small fw-bold">Alamat Email Login *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-envelope text-muted"></i></span>
                                <input type="email" name="email" id="email" class="form-control border-start-0 @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="email@domain.com" required>
                            </div>
                            @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <!-- Field 3: No HP Personal Owner (Validasi Admin) -->
                        <div class="mb-3">
                            <label for="phone" class="form-label small fw-bold">No. HP / WhatsApp Personal Owner *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fa-brands fa-whatsapp text-success"></i></span>
                                <input type="text" name="phone" id="phone" class="form-control border-start-0 @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="081234567890" required>
                            </div>
                            <small class="text-muted d-block mt-1"><i class="fa-solid fa-circle-info me-1 text-primary"></i>Khusus digunakan oleh Admin untuk verifikasi identitas & konfirmasi via WA.</small>
                            @error('phone') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <!-- Field 4: Nama Usaha / Brand Utama -->
                        <div class="mb-3">
                            <label for="company_name" class="form-label small fw-bold">Nama Tempat Usaha</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-store text-muted"></i></span>
                                <input type="text" name="company_name" id="company_name" class="form-control border-start-0 @error('company_name') is-invalid @enderror" value="{{ old('company_name') }}" placeholder="Contoh: CV Giri Mandiri / Usaha Budi">
                            </div>
                            @error('company_name') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <!-- Field 5: Upload Foto KTP (Privat) -->
                        <div class="mb-3">
                            <label for="ktp_photo" class="form-label small fw-bold">Foto KTP Penanggung Jawab *</label>
                            <input type="file" name="ktp_photo" id="ktp_photo" class="form-control @error('ktp_photo') is-invalid @enderror" accept="image/jpeg,image/png,image/jpg,image/webp,application/pdf" required>
                            <div class="security-notice mt-2">
                                <small class="text-dark d-block">
                                    <i class="fa-solid fa-shield-halved text-success me-1"></i> <strong>Keamanan Data Terjamin:</strong> File KTP Anda disimpan dalam <em>private storage</em> yang aman dan hanya dapat diakses oleh Admin berwenang untuk validasi akun.
                                </small>
                            </div>
                            @error('ktp_photo') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <!-- Field 6: Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label small fw-bold">Kata Sandi *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-lock text-muted"></i></span>
                                <input type="password" name="password" id="password" class="form-control border-start-0 @error('password') is-invalid @enderror" placeholder="Minimal 8 karakter" required>
                            </div>
                            @error('password') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <!-- Field 7: Konfirmasi Password -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label small fw-bold">Konfirmasi Kata Sandi *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-shield-halved text-muted"></i></span>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control border-start-0" placeholder="Ulangi kata sandi" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-grex-primary w-100 py-2.5 fw-bold mb-3 shadow-sm">
                            <i class="fa-solid fa-right-to-bracket me-1"></i> Daftar Sebagai Owner
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
