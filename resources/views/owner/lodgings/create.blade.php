@extends('layouts.admin')

@section('title', 'Tambah Penginapan Baru - Owner GREX')
@section('page-title', 'Tambah Penginapan')

@section('content')
<div class="mb-4">
    <a href="{{ route('owner.lodgings.index') }}" class="btn btn-outline-secondary rounded-pill btn-sm">
        <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Daftar
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4 p-md-5">
        <form action="{{ route('owner.lodgings.store') }}" method="POST">
            @csrf

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-bold small">Nama Penginapan *</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contoh: KHAS Gresik Hotel" required>
                    @error('name') <span class="text-danger small">{{ $message }}</span> @errorEnd
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small">Pengelola / Manager Name *</label>
                    <input type="text" name="manager_name" class="form-control @error('manager_name') is-invalid @enderror" value="{{ old('manager_name') }}" placeholder="Nama pengelola atau brand" required>
                    @error('manager_name') <span class="text-danger small">{{ $message }}</span> @errorEnd
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold small">Jam Operasional *</label>
                    <input type="text" name="operational_hours" class="form-control" value="{{ old('operational_hours', '24 Jam') }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold small">Waktu Check-in</label>
                    <input type="text" name="check_in" class="form-control" value="{{ old('check_in', '14.00 WIB') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold small">Waktu Check-out</label>
                    <input type="text" name="check_out" class="form-control" value="{{ old('check_out', '12.00 WIB') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small">Tarif Mulai (Rp) *</label>
                    <input type="number" name="price_start" class="form-control @error('price_start') is-invalid @enderror" value="{{ old('price_start') }}" placeholder="550000" required>
                    @error('price_start') <span class="text-danger small">{{ $message }}</span> @errorEnd
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small">Tarif Maksimal (Rp) *</label>
                    <input type="number" name="price_end" class="form-control @error('price_end') is-invalid @enderror" value="{{ old('price_end') }}" placeholder="900000" required>
                    @error('price_end') <span class="text-danger small">{{ $message }}</span> @errorEnd
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small">Kecamatan</label>
                    <input type="text" name="district" class="form-control" value="{{ old('district') }}" placeholder="Contoh: Gresik">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small">Desa / Kelurahan</label>
                    <input type="text" name="village" class="form-control" value="{{ old('village') }}" placeholder="Contoh: Sidokumpul">
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold small">Alamat Lengkap</label>
                    <textarea name="address" class="form-control" rows="2" placeholder="Jl. Panglima Sudirman No.1, Sidokumpul, Kec. Gresik, Kabupaten Gresik">{{ old('address') }}</textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small">Nomor Telepon (Kontak)</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="(031) 99006330">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small">Email (Opsional)</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="info@khasgresik.com">
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold small">Link Google Maps *</label>
                    <input type="text" name="google_maps" class="form-control @error('google_maps') is-invalid @enderror" value="{{ old('google_maps') }}" placeholder="https://maps.google.com/?q=..." required>
                    @error('google_maps') <span class="text-danger small">{{ $message }}</span> @errorEnd
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold small">Deskripsi Penginapan *</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4" placeholder="Jelaskan fasilitas, tipe kamar, dan keunggulan hotel..." required>{{ old('description') }}</textarea>
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
                <a href="{{ route('owner.lodgings.index') }}" class="btn btn-light rounded-pill px-4">Batal</a>
                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Ajukan Penginapan</button>
            </div>
        </form>
    </div>
</div>
@endsection
