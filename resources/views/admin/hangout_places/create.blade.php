@extends('layouts.admin')

@section('title', 'Tambah Tempat Nongkrong - Admin GREX')
@section('page-title', 'Tambah Data Tempat Nongkrong Baru')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.hangout-places.index') }}" class="btn btn-outline-secondary rounded-pill btn-sm">
        <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Daftar
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4 p-md-5">
        <form action="{{ route('admin.hangout-places.store') }}" method="POST">
            @csrf

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-bold small">Nama Tempat Nongkrong *</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contoh: Turbean Space" required>
                    @error('name') <span class="text-danger small">{{ $message }}</span> @errorEnd
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small">Pengelola / Manager Name *</label>
                    <input type="text" name="manager_name" class="form-control @error('manager_name') is-invalid @enderror" value="{{ old('manager_name') }}" placeholder="Nama pengelola atau brand" required>
                    @error('manager_name') <span class="text-danger small">{{ $message }}</span> @errorEnd
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small">Jam Operasional *</label>
                    <input type="text" name="operational_hours" class="form-control @error('operational_hours') is-invalid @enderror" value="{{ old('operational_hours') }}" placeholder="Contoh: Setiap hari 09.00–23.00 WIB" required>
                    @error('operational_hours') <span class="text-danger small">{{ $message }}</span> @errorEnd
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small">Status Publikasi *</label>
                    <select name="status" class="form-select" required>
                        <option value="approved" {{ old('status') == 'approved' ? 'selected' : '' }}>Disetujui (Approved)</option>
                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="rejected" {{ old('status') == 'rejected' ? 'selected' : '' }}>Ditolak (Rejected)</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small">Kecamatan</label>
                    <input type="text" name="district" class="form-control" value="{{ old('district') }}" placeholder="Contoh: Kebomas">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small">Desa / Kelurahan</label>
                    <input type="text" name="village" class="form-control" value="{{ old('village') }}" placeholder="Contoh: Randuagung">
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold small">Alamat Lengkap</label>
                    <textarea name="address" class="form-control" rows="2" placeholder="Jl. Dr. Wahidin Sudirohusodo No. 120, Kebomas, Gresik">{{ old('address') }}</textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small">Nomor Telepon (Kontak)</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="+62 823-3210-6101">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small">Email (Opsional)</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="info@turbean.id">
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold small">Link Google Maps *</label>
                    <input type="text" name="google_maps" class="form-control @error('google_maps') is-invalid @enderror" value="{{ old('google_maps') }}" placeholder="https://maps.google.com/?q=..." required>
                    @error('google_maps') <span class="text-danger small">{{ $message }}</span> @errorEnd
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold small">Deskripsi Tempat Nongkrong *</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4" placeholder="Jelaskan suasana, konsep cafe, fasilitas, dan keunikan tempat nongkrong..." required>{{ old('description') }}</textarea>
                    @error('description') <span class="text-danger small">{{ $message }}</span> @errorEnd
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold small d-block mb-2">Pilih Fasilitas</label>
                    <div class="row g-2 p-3 bg-light rounded-3">
                        @foreach($facilities as $facility)
                            <div class="col-md-3 col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="facilities[]" value="{{ $facility->id }}" id="fac_{{ $facility->id }}" {{ is_array(old('facilities')) && in_array($facility->id, old('facilities')) ? 'checked' : '' }}>
                                    <label class="form-check-label small" for="fac_{{ $facility->id }}">
                                        {{ $facility->facility_name }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.hangout-places.index') }}" class="btn btn-light rounded-pill px-4">Batal</a>
                <button type="submit" class="btn btn-grex-primary rounded-pill px-4 fw-bold">Simpan Data</button>
            </div>
        </form>
    </div>
</div>
@endsection
