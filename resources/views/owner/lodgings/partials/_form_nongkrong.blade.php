<div class="row g-3 mb-4">
    <div class="col-md-6">
        <label class="form-label fw-bold small">Nama Kafe / Tempat Nongkrong *</label>
        <input type="text" name="name" id="placeName" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contoh: Kopi Giras Kebomas / Bandar Grisse Heritage Cafe" required>
        @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-bold small">Pengelola / Brand Manager *</label>
        <input type="text" name="manager_name" class="form-control @error('manager_name') is-invalid @enderror" value="{{ old('manager_name') }}" placeholder="Nama pemilik atau penanggung jawab kafe" required>
        @error('manager_name') <span class="text-danger small">{{ $message }}</span> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-bold small">Jam Operasional Kafe *</label>
        <input type="text" name="operational_hours" class="form-control @error('operational_hours') is-invalid @enderror" value="{{ old('operational_hours', '10.00 – 23.00 WIB') }}" placeholder="Contoh: Setiap hari 10.00 – 23.00 WIB / 24 Jam" required>
        @error('operational_hours') <span class="text-danger small">{{ $message }}</span> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-bold small">Kecamatan *</label>
        <input type="text" name="district" id="placeDistrict" class="form-control @error('district') is-invalid @enderror" value="{{ old('district') }}" placeholder="Contoh: Kebomas / Manyar / Kecamatan" required>
        @error('district') <span class="text-danger small">{{ $message }}</span> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-bold small">Desa / Kelurahan</label>
        <input type="text" name="village" class="form-control" value="{{ old('village') }}" placeholder="Contoh: Kebungson">
    </div>

    <!-- No WA Bisnis Kafe -->
    <div class="col-md-6">
        <label class="form-label fw-bold small text-success">
            <i class="fa-brands fa-whatsapp me-1"></i> No. WhatsApp Bisnis / Reservasi Meja *
        </label>
        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="081234567890" required>
        <small class="text-muted d-block mt-1">Nomor ini akan tampil di Halaman Detail dan digunakan pengunjung untuk reservasi / pesan via WA.</small>
        @error('phone') <span class="text-danger small">{{ $message }}</span> @enderror
    </div>

    <div class="col-12">
        <label class="form-label fw-bold small">Alamat Lengkap</label>
        <textarea name="address" class="form-control" rows="2" placeholder="Contoh: Jl. Basuki Rahmat No. 12">{{ old('address') }}</textarea>
    </div>

    <div class="col-md-6">
        <label class="form-label fw-bold small">Email Kafe (Opsional)</label>
        <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="info@kafe.com">
    </div>

    <!-- Instagram Usaha -->
    <div class="col-md-6">
        <label class="form-label fw-bold small">
            <i class="fa-brands fa-instagram text-danger me-1"></i> Instagram Usaha
        </label>
        <input type="text" name="instagram" class="form-control @error('instagram') is-invalid @enderror" value="{{ old('instagram') }}" placeholder="@username atau link Instagram">
        <small class="text-muted d-block mt-1">Masukkan username Instagram atau link Instagram usaha Anda.</small>
        @error('instagram') <span class="text-danger small">{{ $message }}</span> @enderror
    </div>

    <div class="col-12">
        <label class="form-label fw-bold small">Link Google Maps *</label>
        <input type="text" name="google_maps" class="form-control @error('google_maps') is-invalid @enderror" value="{{ old('google_maps') }}" placeholder="https://maps.google.com/?q=..." required>
        @error('google_maps') <span class="text-danger small">{{ $message }}</span> @enderror
    </div>

    <!-- Media / Foto Upload Section -->
    <div class="col-12">
        <div class="p-3 border rounded-3 bg-light">
            <h6 class="fw-bold text-dark mb-1"><i class="fa-solid fa-camera text-primary me-2"></i>Foto Kafe, Menu & Suasana (Maksimal 3 Foto)</h6>
            <p class="small text-muted mb-3">Unggah foto suasana tempat nongkrong dan foto menu andalan (JPG, PNG, WEBP, maks 2MB).</p>

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-bold small">Foto Thumbnail Utama *</label>
                    <input type="file" name="thumbnail" class="form-control @error('thumbnail') is-invalid @enderror" accept="image/*" required>
                    <small class="text-muted d-block mt-1">Tampil di beranda & card kafe.</small>
                    @error('thumbnail') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold small">Foto Pendukung #1 (Opsional)</label>
                    <input type="file" name="photo_1" class="form-control @error('photo_1') is-invalid @enderror" accept="image/*">
                    <small class="text-muted d-block mt-1">Contoh: Foto Menu / Makanan / Minuman.</small>
                    @error('photo_1') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold small">Foto Pendukung #2 (Opsional)</label>
                    <input type="file" name="photo_2" class="form-control @error('photo_2') is-invalid @enderror" accept="image/*">
                    <small class="text-muted d-block mt-1">Contoh: Suasana indoor / outdoor / live music.</small>
                    @error('photo_2') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
    </div>

    <!-- AI Description Generator Integration -->
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
            <label class="form-label fw-bold small mb-0">Deskripsi Kafe / Suasana Nongkrong *</label>
            <button type="button" class="btn btn-outline-warning text-dark btn-sm rounded-pill px-3" onclick="generateAIDescription()">
                <i class="fa-solid fa-wand-magic-sparkles me-1"></i> Buat Deskripsi dengan AI
            </button>
        </div>
        <textarea name="description" id="descriptionInput" class="form-control @error('description') is-invalid @enderror" rows="5" placeholder="Jelaskan konsep kafe, menu andalan (kopi kopyok, snack), suasana outdoor/indoor..." required>{{ old('description') }}</textarea>
        @error('description') <span class="text-danger small">{{ $message }}</span> @enderror
    </div>

    <!-- Fasilitas Nongkrong -->
    <div class="col-12">
        <label class="form-label fw-bold small d-block mb-2">Fasilitas Tempat Nongkrong</label>
        <div class="row g-2 p-3 bg-light rounded-3">
            @foreach($hangoutFacilities as $facility)
                <div class="col-md-3 col-6">
                    <div class="form-check">
                        <input class="form-check-input facility-checkbox" type="checkbox" name="facilities[]" value="{{ $facility->id }}" id="fac_n_{{ $facility->id }}" data-name="{{ $facility->facility_name }}">
                        <label class="form-check-label small" for="fac_n_{{ $facility->id }}">
                            {{ $facility->facility_name }}
                        </label>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
