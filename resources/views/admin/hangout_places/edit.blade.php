@extends('layouts.admin')

@section('title', 'Edit Tempat Nongkrong - Admin GREX')
@section('page-title', 'Edit Data Tempat Nongkrong')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.hangout-places.index') }}" class="btn btn-outline-secondary rounded-pill btn-sm">
        <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Daftar
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4 p-md-5">
        <form action="{{ route('admin.hangout-places.update', $hangoutPlace->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-bold small">Nama Tempat Nongkrong *</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $hangoutPlace->name) }}" required>
                    @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small">Pengelola / Manager Name *</label>
                    <input type="text" name="manager_name" class="form-control @error('manager_name') is-invalid @enderror" value="{{ old('manager_name', $hangoutPlace->manager_name) }}" required>
                    @error('manager_name') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small">Jam Operasional *</label>
                    <input type="text" name="operational_hours" class="form-control @error('operational_hours') is-invalid @enderror" value="{{ old('operational_hours', $hangoutPlace->operational_hours) }}" required>
                    @error('operational_hours') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small">Status Publikasi *</label>
                    <select name="status" class="form-select" required>
                        <option value="approved" {{ old('status', $hangoutPlace->status) == 'approved' ? 'selected' : '' }}>Disetujui (Approved)</option>
                        <option value="pending" {{ old('status', $hangoutPlace->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="rejected" {{ old('status', $hangoutPlace->status) == 'rejected' ? 'selected' : '' }}>Ditolak (Rejected)</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small">Kecamatan</label>
                    <input type="text" name="district" class="form-control" value="{{ old('district', $hangoutPlace->district) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small">Desa / Kelurahan</label>
                    <input type="text" name="village" class="form-control" value="{{ old('village', $hangoutPlace->village) }}">
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold small">Alamat Lengkap</label>
                    <textarea name="address" class="form-control" rows="2">{{ old('address', $hangoutPlace->address) }}</textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small">Nomor Telepon (Kontak)</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $hangoutPlace->phone) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small">Email (Opsional)</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $hangoutPlace->email) }}">
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold small">Link Google Maps *</label>
                    <input type="text" name="google_maps" class="form-control @error('google_maps') is-invalid @enderror" value="{{ old('google_maps', $hangoutPlace->google_maps) }}" required>
                    @error('google_maps') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold small">Deskripsi Tempat Nongkrong *</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4" required>{{ old('description', $hangoutPlace->description) }}</textarea>
                    @error('description') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold small d-block mb-2">Pilih Fasilitas</label>
                    @php $selectedFacs = $hangoutPlace->facilities->pluck('id')->toArray(); @endphp
                    <div class="row g-2 p-3 bg-light rounded-3">
                        @foreach($facilities as $facility)
                            <div class="col-md-3 col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="facilities[]" value="{{ $facility->id }}" id="fac_{{ $facility->id }}" {{ in_array($facility->id, old('facilities', $selectedFacs)) ? 'checked' : '' }}>
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
                <button type="submit" class="btn btn-grex-primary rounded-pill px-4 fw-bold">Update Data</button>
            </div>
        </form>
    </div>
</div>
@endsection
