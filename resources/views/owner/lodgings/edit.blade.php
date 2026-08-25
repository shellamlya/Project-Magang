@extends('layouts.admin')

@section('title', 'Edit Tempat Usaha - Owner GREX')
@section('page-title', 'Edit Data Tempat Usaha')

@section('content')
<div class="mb-4">
    <a href="{{ route('owner.lodgings.index') }}" class="btn btn-outline-secondary rounded-pill btn-sm">
        <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Daftar
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4 p-md-5">
        <form action="{{ route('owner.lodgings.update', $place->id) }}" method="POST">
            @csrf
            @method('PUT')

            <input type="hidden" name="category" value="{{ $category }}">

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-bold small">Kategori Usaha</label>
                    <input type="text" class="form-control text-capitalize fw-bold" value="{{ $category }}" disabled>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small">Nama Tempat Usaha *</label>
                    <input type="text" name="name" id="placeName" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $place->name) }}" required>
                    @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small">Pengelola / Manager Name *</label>
                    <input type="text" name="manager_name" class="form-control @error('manager_name') is-invalid @enderror" value="{{ old('manager_name', $place->manager_name) }}" required>
                    @error('manager_name') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small">Jam Operasional *</label>
                    <input type="text" name="operational_hours" class="form-control" value="{{ old('operational_hours', $place->operational_hours) }}" required>
                </div>

                @if($category === 'penginapan')
                    <div class="col-md-3">
                        <label class="form-label fw-bold small">Waktu Check-in</label>
                        <input type="text" name="check_in" class="form-control" value="{{ old('check_in', $place->check_in) }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold small">Waktu Check-out</label>
                        <input type="text" name="check_out" class="form-control" value="{{ old('check_out', $place->check_out) }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold small">Tarif Mulai (Rp) *</label>
                        <input type="number" name="price_start" class="form-control @error('price_start') is-invalid @enderror" value="{{ old('price_start', $place->price_start) }}" required>
                        @error('price_start') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold small">Tarif Maksimal (Rp) *</label>
                        <input type="number" name="price_end" class="form-control @error('price_end') is-invalid @enderror" value="{{ old('price_end', $place->price_end) }}" required>
                        @error('price_end') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                @elseif($category === 'wisata')
                    <div class="col-md-6">
                        <label class="form-label fw-bold small">Harga Tiket Masuk</label>
                        <input type="text" name="ticket_price" class="form-control" value="{{ old('ticket_price', $place->ticket_price) }}">
                    </div>
                @endif

                <div class="col-md-6">
                    <label class="form-label fw-bold small">Kecamatan</label>
                    <input type="text" name="district" id="placeDistrict" class="form-control" value="{{ old('district', $place->district) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small">Desa / Kelurahan</label>
                    <input type="text" name="village" class="form-control" value="{{ old('village', $place->village) }}">
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold small">Alamat Lengkap</label>
                    <textarea name="address" class="form-control" rows="2">{{ old('address', $place->address) }}</textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small">Nomor Telepon (Kontak)</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $place->phone) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small">Email (Opsional)</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $place->email) }}">
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold small">Link Google Maps *</label>
                    <input type="text" name="google_maps" class="form-control @error('google_maps') is-invalid @enderror" value="{{ old('google_maps', $place->google_maps) }}" required>
                    @error('google_maps') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                        <label class="form-label fw-bold small mb-0">Deskripsi Tempat Usaha *</label>
                        <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3" onclick="generateAIDescription()">
                            <i class="fa-solid fa-wand-magic-sparkles me-1"></i> Perbarui Deskripsi dengan AI
                        </button>
                    </div>
                    <textarea name="description" id="descriptionInput" class="form-control @error('description') is-invalid @enderror" rows="5" required>{{ old('description', $place->description) }}</textarea>
                    @error('description') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold small d-block mb-2">Pilih Fasilitas Utama</label>
                    <div class="row g-2 p-3 bg-light rounded-3">
                        @php
                            $currentFacs = $place->facilities->pluck('id')->toArray();
                        @endphp
                        @foreach($facilities as $facility)
                            <div class="col-md-3 col-6">
                                <div class="form-check">
                                    <input class="form-check-input facility-checkbox" type="checkbox" name="facilities[]" value="{{ $facility->id }}" id="fac_{{ $facility->id }}" data-name="{{ $facility->facility_name }}" {{ in_array($facility->id, old('facilities', $currentFacs)) ? 'checked' : '' }}>
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
                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    async function generateAIDescription() {
        const name = document.getElementById('placeName').value;
        const category = "{{ $category }}";
        const district = document.getElementById('placeDistrict').value;
        const textarea = document.getElementById('descriptionInput');

        if (!name) {
            alert('Silakan isi Nama Tempat Usaha terlebih dahulu.');
            return;
        }

        const facilities = [];
        document.querySelectorAll('.facility-checkbox:checked').forEach(cb => {
            facilities.push(cb.getAttribute('data-name'));
        });

        textarea.value = "Sedang memperbarui deskripsi dengan AI...";
        textarea.disabled = true;

        try {
            const response = await fetch("{{ route('owner.ai.generate-description') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ name, category, district, facilities })
            });

            const data = await response.json();
            if (data.success) {
                textarea.value = data.description;
            } else {
                alert('Gagal menghasilkan deskripsi AI.');
            }
        } catch (err) {
            console.error(err);
            alert('Terjadi kesalahan jaringan.');
        } finally {
            textarea.disabled = false;
        }
    }
</script>
@endsection
