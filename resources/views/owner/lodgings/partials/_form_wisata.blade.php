<div class="row g-3 mb-4">
    <div class="col-md-6">
        <label class="form-label fw-bold small">Nama Tempat Wisata *</label>
        <input type="text" name="name" id="placeName" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contoh: Wisata Pantai Delegan / Bukit Jamur" required>
        @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-bold small">Pengelola / BUMDes / Management *</label>
        <input type="text" name="manager_name" class="form-control @error('manager_name') is-invalid @enderror" value="{{ old('manager_name') }}" placeholder="Nama pengelola atau kelompok sadar wisata" required>
        @error('manager_name') <span class="text-danger small">{{ $message }}</span> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-bold small">Jam Buka / Operasional Wisata *</label>
        <input type="text" name="operational_hours" class="form-control @error('operational_hours') is-invalid @enderror" value="{{ old('operational_hours', '07.00 – 17.00 WIB') }}" placeholder="Contoh: 07.00 – 17.00 WIB / 24 Jam" required>
        @error('operational_hours') <span class="text-danger small">{{ $message }}</span> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-bold small">Harga Tiket Masuk</label>
        <input type="text" name="ticket_price" class="form-control" value="{{ old('ticket_price') }}" placeholder="Contoh: Rp 10.000 / Orang atau Gratis">
    </div>

    <div class="col-md-6">
        <label class="form-label fw-bold small">Kecamatan *</label>
        <input type="text" name="district" id="placeDistrict" class="form-control @error('district') is-invalid @enderror" value="{{ old('district') }}" placeholder="Contoh: Panceng / Bungah / Kebomas" required>
        @error('district') <span class="text-danger small">{{ $message }}</span> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-bold small">Desa / Kelurahan</label>
        <input type="text" name="village" class="form-control" value="{{ old('village') }}" placeholder="Contoh: Delegan">
    </div>

    <div class="col-12">
        <label class="form-label fw-bold small">Alamat Lengkap</label>
        <textarea name="address" class="form-control" rows="2" placeholder="Desa Delegan, Kecamatan Panceng, Kabupaten Gresik">{{ old('address') }}</textarea>
    </div>

    <!-- No WA Bisnis Wisata -->
    <div class="col-md-6">
        <label class="form-label fw-bold small text-success">
            <i class="fa-brands fa-whatsapp me-1"></i> No. WhatsApp Pengelola / Reservasi Tiket *
        </label>
        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="081234567890" required>
        <small class="text-muted d-block mt-1">Nomor ini akan tampil di Halaman Detail dan digunakan pengunjung untuk bertanya / reservasi tiket.</small>
        @error('phone') <span class="text-danger small">{{ $message }}</span> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-bold small">Link Google Maps *</label>
        <input type="text" name="google_maps" class="form-control @error('google_maps') is-invalid @enderror" value="{{ old('google_maps') }}" placeholder="https://maps.google.com/?q=..." required>
        @error('google_maps') <span class="text-danger small">{{ $message }}</span> @enderror
    </div>

    <!-- Media / Foto Upload Section -->
    <div class="col-12">
        <div class="p-3 border rounded-3 bg-light">
            <h6 class="fw-bold text-dark mb-1"><i class="fa-solid fa-camera text-primary me-2"></i>Foto Daya Tarik & Wahana Wisata (Maksimal 3 Foto)</h6>
            <p class="small text-muted mb-3">Unggah foto pemandangan, spot foto, atau wahana utama tempat wisata (JPG, PNG, WEBP, maks 2MB).</p>

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-bold small">Foto Thumbnail Utama *</label>
                    <input type="file" name="thumbnail" class="form-control @error('thumbnail') is-invalid @enderror" accept="image/*" required>
                    <small class="text-muted d-block mt-1">Tampil di landing page & card wisata.</small>
                    @error('thumbnail') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold small">Foto Pendukung #1 (Opsional)</label>
                    <input type="file" name="photo_1" class="form-control @error('photo_1') is-invalid @enderror" accept="image/*">
                    <small class="text-muted d-block mt-1">Contoh: Spot foto ikonik / wahana.</small>
                    @error('photo_1') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold small">Foto Pendukung #2 (Opsional)</label>
                    <input type="file" name="photo_2" class="form-control @error('photo_2') is-invalid @enderror" accept="image/*">
                    <small class="text-muted d-block mt-1">Contoh: Fasilitas umum / resto / gazebo.</small>
                    @error('photo_2') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
    </div>

    <!-- AI Description Generator Integration -->
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
            <label class="form-label fw-bold small mb-0">Deskripsi Daya Tarik Wisata *</label>
            <button type="button" class="btn btn-outline-info text-dark btn-sm rounded-pill px-3" onclick="generateAIDescription()">
                <i class="fa-solid fa-wand-magic-sparkles me-1"></i> Buat Deskripsi dengan AI
            </button>
        </div>
        <textarea name="description" id="descriptionInput" class="form-control @error('description') is-invalid @enderror" rows="5" placeholder="Jelaskan keindahan alam, daya tarik utama, wahana permainan, dan sejarah singkat tempat wisata..." required>{{ old('description') }}</textarea>
        @error('description') <span class="text-danger small">{{ $message }}</span> @enderror
    </div>

    <!-- Fasilitas Wisata -->
    <div class="col-12">
        <label class="form-label fw-bold small d-block mb-2">Fasilitas Umum Tempat Wisata</label>
        <div class="row g-2 p-3 bg-light rounded-3">
            @foreach($touristFacilities as $facility)
                <div class="col-md-3 col-6">
                    <div class="form-check">
                        <input class="form-check-input facility-checkbox" type="checkbox" name="facilities[]" value="{{ $facility->id }}" id="fac_w_{{ $facility->id }}" data-name="{{ $facility->facility_name }}">
                        <label class="form-check-label small" for="fac_w_{{ $facility->id }}">
                            {{ $facility->facility_name }}
                        </label>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
