<div class="row g-3 mb-4">
    <div class="col-md-6">
        <label class="form-label fw-bold small">Nama Penginapan *</label>
        <input type="text" name="name" id="placeName" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contoh: KHAS Gresik Hotel / Homestay Giri" required>
        @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-bold small">Pengelola / Brand Manager *</label>
        <input type="text" name="manager_name" class="form-control @error('manager_name') is-invalid @enderror" value="{{ old('manager_name') }}" placeholder="Nama pengelola atau manajemen hotel" required>
        @error('manager_name') <span class="text-danger small">{{ $message }}</span> @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label fw-bold small">Jam Operasional Resepsionis *</label>
        <input type="text" name="operational_hours" class="form-control" value="{{ old('operational_hours', '24 Jam') }}" placeholder="24 Jam" required>
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
        <label class="form-label fw-bold small">Tarif Sewa Mulai (Rp) *</label>
        <input type="number" name="price_start" class="form-control @error('price_start') is-invalid @enderror" value="{{ old('price_start') }}" placeholder="350000" required>
        @error('price_start') <span class="text-danger small">{{ $message }}</span> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-bold small">Tarif Sewa Maksimal (Rp) *</label>
        <input type="number" name="price_end" class="form-control @error('price_end') is-invalid @enderror" value="{{ old('price_end') }}" placeholder="850000" required>
        @error('price_end') <span class="text-danger small">{{ $message }}</span> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-bold small">Kecamatan</label>
        <input type="text" name="district" id="placeDistrict" class="form-control" value="{{ old('district') }}" placeholder="Contoh: Gresik / Kebomas">
    </div>

    <div class="col-md-6">
        <label class="form-label fw-bold small">Desa / Kelurahan</label>
        <input type="text" name="village" class="form-control" value="{{ old('village') }}" placeholder="Contoh: Sidokumpul">
    </div>

    <div class="col-12">
        <label class="form-label fw-bold small">Alamat Lengkap</label>
        <textarea name="address" class="form-control" rows="2" placeholder="Jl. Panglima Sudirman No. 1, Sidokumpul, Gresik">{{ old('address') }}</textarea>
    </div>

    <div class="col-md-6">
        <label class="form-label fw-bold small">Nomor Telepon (Kontak Resepsionis)</label>
        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="(031) 99006330 / 08123456789">
    </div>

    <div class="col-md-6">
        <label class="form-label fw-bold small">Email Reservasi (Opsional)</label>
        <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="reservasi@hotel.com">
    </div>

    <div class="col-12">
        <label class="form-label fw-bold small">Link Google Maps *</label>
        <input type="text" name="google_maps" class="form-control @error('google_maps') is-invalid @enderror" value="{{ old('google_maps') }}" placeholder="https://maps.google.com/?q=..." required>
        @error('google_maps') <span class="text-danger small">{{ $message }}</span> @enderror
    </div>

    <!-- AI Description Generator Integration -->
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
            <label class="form-label fw-bold small mb-0">Deskripsi Penginapan *</label>
            <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3" onclick="generateAIDescription()">
                <i class="fa-solid fa-wand-magic-sparkles me-1"></i> Buat Deskripsi dengan AI
            </button>
        </div>
        <textarea name="description" id="descriptionInput" class="form-control @error('description') is-invalid @enderror" rows="5" placeholder="Tulis deskripsi fasilitas kamar, sarapan, dan kenyamanan penginapan Anda..." required>{{ old('description') }}</textarea>
        @error('description') <span class="text-danger small">{{ $message }}</span> @enderror
    </div>

    <!-- Fasilitas Penginapan -->
    <div class="col-12">
        <label class="form-label fw-bold small d-block mb-2">Fasilitas Penginapan</label>
        <div class="row g-2 p-3 bg-light rounded-3">
            @foreach($lodgingFacilities as $facility)
                <div class="col-md-3 col-6">
                    <div class="form-check">
                        <input class="form-check-input facility-checkbox" type="checkbox" name="facilities[]" value="{{ $facility->id }}" id="fac_p_{{ $facility->id }}" data-name="{{ $facility->facility_name }}">
                        <label class="form-check-label small" for="fac_p_{{ $facility->id }}">
                            {{ $facility->facility_name }}
                        </label>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
