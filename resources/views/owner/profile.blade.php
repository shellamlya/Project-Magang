@extends('layouts.admin')

@section('title', 'Profil Owner - Lokavino')
@section('page-title', 'Profil Usaha Owner')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-id-card text-primary me-2"></i>Profil Mitra Owner</h5>

            <form action="{{ route('owner.profile.update') }}" method="POST">
                @csrf
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Nama Penanggung Jawab *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Nama Badan Usaha / Penginapan *</label>
                        <input type="text" name="company_name" class="form-control" value="{{ old('company_name', $owner->company_name) }}" required>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Email Akun (Read-only)</label>
                        <input type="email" class="form-control bg-light" value="{{ $user->email }}" readonly>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold">No. Telepon / WhatsApp *</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $owner->phone ?? $user->phone) }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">NIK KTP (Opsional)</label>
                    <input type="text" name="nik" class="form-control" value="{{ old('nik', $owner->nik) }}">
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold">Alamat Usaha *</label>
                    <textarea name="address" rows="3" class="form-control" required>{{ old('address', $owner->address) }}</textarea>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">
                        <i class="fa-solid fa-save me-1"></i> Simpan Profil
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
