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
        <label class="form-label fw-bold small">Kecamatan</label>
        <input type="text" name="district" id="placeDistrict" class="form-control" value="{{ old('district') }}" placeholder="Contoh: Gresik / Kebomas / Manyar">
    </div>

    <div class="col-md-6">
        <label class="form-label fw-bold small">Desa / Kelurahan</label>
        <input type="text" name="village" class="form-control" value="{{ old('village') }}" placeholder="Contoh: Kebungson">
    </div>

    <div class="col-md-6">
        <label class="form-label fw-bold small">Nomor Telepon / WhatsApp Kafe</label>
        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="081234567890">
    </div>

    <div class="col-12">
        <label class="form-label fw-bold small">Alamat Lengkap</label>
        <textarea name="address" class="form-control" rows="2" placeholder="Jl. Basuki Rahmat No. 12, Kebungson, Gresik">{{ old('address') }}</textarea>
    </div>

    <div class="col-md-6">
        <label class="form-label fw-bold small">Email Kafe (Opsional)</label>
        <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="info@kafe.com">
    </div>

    <div class="col-md-6">
        <label class="form-label fw-bold small">Link Google Maps *</label>
        <input type="text" name="google_maps" class="form-control @error('google_maps') is-invalid @enderror" value="{{ old('google_maps') }}" placeholder="https://maps.google.com/?q=..." required>
        @error('google_maps') <span class="text-danger small">{{ $message }}</span> @enderror
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
