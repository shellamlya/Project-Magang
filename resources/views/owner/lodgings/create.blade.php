@extends('layouts.admin')

@section('title', 'Ajukan Tempat Usaha Baru - Owner Lokavino')
@section('page-title', 'Ajukan Tempat Usaha Baru')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
    <a href="{{ route('owner.lodgings.index') }}" class="btn btn-outline-secondary rounded-pill btn-sm">
        <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Daftar
    </a>
</div>

<!-- Modal Pilihan Kategori Usaha (Included) -->
@include('owner.lodgings.partials._category_modal')

<!-- Step 1: Pilihan Kategori Usaha Visual Cards -->
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3">
            <div>
                <h5 class="fw-bold text-dark mb-1"><i class="fa-solid fa-layer-group text-primary me-2"></i>Pilih Kategori Tempat Usaha</h5>
                <p class="small text-muted mb-0">Sesuaikan formulir pengajuan dengan jenis tempat usaha yang Anda ajukan</p>
            </div>
        </div>

        <div class="row g-3">
            <!-- Card Penginapan -->
            <div class="col-md-4">
                <a href="{{ route('owner.lodgings.create', ['category' => 'penginapan']) }}" class="text-decoration-none">
                    <div class="card border-2 rounded-4 p-3 text-center category-select-card transition-all {{ $category === 'penginapan' ? 'border-primary bg-primary bg-opacity-10' : 'border-light bg-white' }}">
                        <div class="fs-1 mb-2">🏨</div>
                        <h6 class="fw-bold text-dark mb-1">Penginapan</h6>
                        <small class="text-muted d-block mb-2">Homestay, Hotel, Villa, Kost, Resort</small>
                        @if($category === 'penginapan')
                            <span class="badge bg-primary rounded-pill px-3"><i class="fa-solid fa-check me-1"></i> Kategori Dipilih</span>
                        @else
                            <span class="badge bg-light text-dark rounded-pill px-3">Pilih Penginapan</span>
                        @endif
                    </div>
                </a>
            </div>

            <!-- Card Kafe/Nongkrong -->
            <div class="col-md-4">
                <a href="{{ route('owner.lodgings.create', ['category' => 'nongkrong']) }}" class="text-decoration-none">
                    <div class="card border-2 rounded-4 p-3 text-center category-select-card transition-all {{ $category === 'nongkrong' ? 'border-warning bg-warning bg-opacity-10' : 'border-light bg-white' }}">
                        <div class="fs-1 mb-2">☕</div>
                        <h6 class="fw-bold text-dark mb-1">Kafe & Nongkrong</h6>
                        <small class="text-muted d-block mb-2">Coffee Shop, Resto, Warkop Giras</small>
                        @if($category === 'nongkrong')
                            <span class="badge bg-warning text-dark rounded-pill px-3"><i class="fa-solid fa-check me-1"></i> Kategori Dipilih</span>
                        @else
                            <span class="badge bg-light text-dark rounded-pill px-3">Pilih Kafe/Nongkrong</span>
                        @endif
                    </div>
                </a>
            </div>

            <!-- Card Wisata -->
            <div class="col-md-4">
                <a href="{{ route('owner.lodgings.create', ['category' => 'wisata']) }}" class="text-decoration-none">
                    <div class="card border-2 rounded-4 p-3 text-center category-select-card transition-all {{ $category === 'wisata' ? 'border-info bg-info bg-opacity-10' : 'border-light bg-white' }}">
                        <div class="fs-1 mb-2">🏖️</div>
                        <h6 class="fw-bold text-dark mb-1">Tempat Wisata</h6>
                        <small class="text-muted d-block mb-2">Wisata Alam, Sejarah, Buatan, Religi</small>
                        @if($category === 'wisata')
                            <span class="badge bg-info text-dark rounded-pill px-3"><i class="fa-solid fa-check me-1"></i> Kategori Dipilih</span>
                        @else
                            <span class="badge bg-light text-dark rounded-pill px-3">Pilih Wisata</span>
                        @endif
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Step 2: Form Spesifik Per Kategori -->
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white border-0 pt-4 px-4 px-md-5 pb-0">
        <h5 class="fw-bold text-dark mb-0">
            Formulir Pengajuan: 
            <span class="text-primary text-capitalize">
                @if($category === 'nongkrong')
                    ☕ Kafe / Tempat Nongkrong
                @elseif($category === 'wisata')
                    🏖️ Tempat Wisata
                @else
                    🏨 Penginapan (Hotel / Homestay)
                @endif
            </span>
        </h5>
        <p class="small text-muted">Lengkapi data tempat usaha Anda secara detail untuk diverifikasi oleh Admin</p>
    </div>
    <div class="card-body p-4 p-md-5">
        <form action="{{ route('owner.lodgings.store') }}" method="POST" id="placeSubmitForm">
            @csrf
            <input type="hidden" name="category" value="{{ $category }}">

            @if($category === 'nongkrong')
                @include('owner.lodgings.partials._form_nongkrong')
            @elseif($category === 'wisata')
                @include('owner.lodgings.partials._form_wisata')
            @else
                @include('owner.lodgings.partials._form_penginapan')
            @endif

            <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                <a href="{{ route('owner.lodgings.index') }}" class="btn btn-light rounded-pill px-4">Batal</a>
                <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold">
                    <i class="fa-solid fa-paper-plane me-1"></i> Kirim Pengajuan Usaha
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    async function generateAIDescription() {
        const nameInput = document.getElementById('placeName');
        const districtInput = document.getElementById('placeDistrict');
        const textarea = document.getElementById('descriptionInput');
        const category = "{{ $category }}";

        if (!nameInput || !nameInput.value.trim()) {
            alert('Silakan isi Nama Tempat Usaha terlebih dahulu.');
            if (nameInput) nameInput.focus();
            return;
        }

        const facilities = [];
        document.querySelectorAll('.facility-checkbox:checked').forEach(cb => {
            facilities.push(cb.getAttribute('data-name'));
        });

        textarea.value = "Sedang menyusun deskripsi promosi terbaik menggunakan AI...";
        textarea.disabled = true;

        try {
            const response = await fetch("{{ route('owner.ai.generate-description') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    name: nameInput.value,
                    category: category,
                    district: districtInput ? districtInput.value : '',
                    facilities: facilities
                })
            });

            const data = await response.json();
            if (data.success && data.description) {
                textarea.value = data.description;
            } else {
                textarea.value = "";
                alert('Gagal menghasilkan deskripsi AI. Silakan tulis manual.');
            }
        } catch (err) {
            console.error(err);
            textarea.value = "";
            alert('Terjadi kendala jaringan saat memanggil AI.');
        } finally {
            textarea.disabled = false;
        }
    }
</script>
@endsection
