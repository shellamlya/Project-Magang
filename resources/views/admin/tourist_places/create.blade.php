@extends('layouts.admin')

@section('title', 'Tambah Wisata - Admin Lokavino')
@section('page-title', 'Tambah Data Wisata')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.tourist-places.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill">
        <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Daftar Wisata
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-3">Form Data Tempat Wisata Baru</h5>
        <form action="{{ route('admin.tourist-places.store') }}" method="POST">
            @csrf

            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Nama Tempat Wisata <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Status Pengajuan <span class="text-danger">*</span></label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="approved" {{ old('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="rejected" {{ old('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Deskripsi Wisata <span class="text-danger">*</span></label>
                    <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror" required>{{ old('description') }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Jam Operasional <span class="text-danger">*</span></label>
                    <input type="text" name="operational_hours" class="form-control @error('operational_hours') is-invalid @enderror" placeholder="Contoh: Setiap hari 08.00–17.00 WIB" value="{{ old('operational_hours') }}" required>
                    @error('operational_hours') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Pengelola / PIC <span class="text-danger">*</span></label>
                    <input type="text" name="manager_name" class="form-control @error('manager_name') is-invalid @enderror" placeholder="Contoh: Pokdarwis Desa Gosari" value="{{ old('manager_name') }}" required>
                    @error('manager_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Telepon / HP</label>
                    <input type="text" name="phone" class="form-control" placeholder="Contoh: 08123456789" value="{{ old('phone') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Contoh: wisata@gresik.go.id" value="{{ old('email') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Harga Tiket</label>
                    <input type="text" name="ticket_price" class="form-control" placeholder="Contoh: Rp 10.000 / Gratis" value="{{ old('ticket_price') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Kecamatan</label>
                    <input type="text" name="district" class="form-control" placeholder="Contoh: Menganti" value="{{ old('district') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Desa / Kelurahan</label>
                    <input type="text" name="village" class="form-control" placeholder="Contoh: Pelemwatu" value="{{ old('village') }}">
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Alamat Lengkap</label>
                    <input type="text" name="address" class="form-control" placeholder="Alamat jalan lengkap..." value="{{ old('address') }}">
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Link Google Maps <span class="text-danger">*</span></label>
                    <input type="text" name="google_maps" class="form-control @error('google_maps') is-invalid @enderror" placeholder="https://maps.google.com/?q=..." value="{{ old('google_maps') }}" required>
                    @error('google_maps') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold mb-2">Fasilitas Wisata</label>
                    <div class="row g-2">
                        @foreach($facilities as $facility)
                            <div class="col-md-3 col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="facilities[]" value="{{ $facility->id }}" id="fac_{{ $facility->id }}">
                                    <label class="form-check-label small" for="fac_{{ $facility->id }}">
                                        {{ $facility->facility_name }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="col-12 text-end mt-4">
                    <button type="submit" class="btn btn-primary px-5 rounded-pill fw-bold">Simpan Wisata</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
