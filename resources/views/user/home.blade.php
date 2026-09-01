@extends('layouts.app')

@section('title', 'Lokavino | Pusat Pencarian Penginapan, Wisata & Tempat Nongkrong')

@section('styles')
<style>
    body {
        background-color: #E1DAFB !important;
    }
    
    /* Hero Section */
    .hero-landing {
    position: relative;
    min-height: 720px;
    display: flex;
    align-items: center;
    overflow: hidden;
    background: #ffffffff;
    border-radius: 0 0 35px 35px;
    }

    .hero-landing::before {
        content: "";
        position: absolute;
        inset: -20px;

        background-image: url('/images/hero-lokavino.png');
        background-size: cover;
        background-position: center;

        animation: heroZoom 15s ease-in-out infinite alternate;

        z-index: 0;
    }

    .hero-landing::after {
        content: "";
        position: absolute;
        inset: 0;

        background: rgba(0, 0, 0, 0.15);

        z-index: 1;
    }

    .hero-landing .container {
        position: relative;
        z-index: 2;
    }

    @keyframes heroZoom {
        from {
            transform: scale(1);
        }

        to {
            transform: scale(1.08);
        }
    }
    .search-card {
    background: rgba(255, 255, 255, 0.88);
    backdrop-filter: blur(14px);
    -webkit-backdrop-filter: blur(14px);

    border: 1px solid rgba(255, 255, 255, 0.45);
    border-radius: 20px;

    padding: 1.25rem 1.5rem;

    max-width: 900px;
    margin: 0 auto;

    box-shadow: 0 12px 35px rgba(0, 0, 0, 0.12);

    transition: all 0.3s ease;
    }
    .search-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.18);
    }
    .hero-title {
        color: white;
        font-size: clamp(2.8rem, 6vw, 5rem);
        font-weight: 800;
        letter-spacing: -2px;
        line-height: 1;
    }

    .hero-title span {
        display: block;
        font-size: 0.45em;
        font-weight: 500;
        letter-spacing: 1px;
        margin-top: 12px;
        opacity: 0.9;
    }
    .hero-description {
    color: rgba(255, 255, 255, 0.92);
    font-size: 0.9rem;
    font-weight: 400;
    line-height: 1.6;
    max-width: 750px;
    margin-left: auto;
    margin-right: auto;
    text-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
    }
    /* Feature Cards */
    .feature-card {
        border-radius: 20px;
        border: 1px solid rgba(117, 138, 209, 0.2);
        transition: all 0.3s ease;
        background: #ffffff;
    }
    .feature-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(77, 62, 163, 0.15);
    }
    .icon-box-feature {
        width: 70px;
        height: 70px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin: 0 auto 1.25rem;
    }

    /* Step Card */
    .step-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 1.5rem;
        text-align: center;
        border: 1px solid rgba(117, 138, 209, 0.3);
        position: relative;
    }
    .step-badge {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #4D3EA3;
        color: #ffffff;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
    }

    .section-title {
        color: #450C3F;
        font-weight: 800;
    }

    .nav-pills-grex .nav-link {
        color: #450C3F;
        font-weight: 700;
        border-radius: 30px;
        padding: 0.6rem 1.4rem;
        background: rgba(255,255,255,0.7);
        margin: 0 4px;
    }
    .nav-pills-grex .nav-link.active {
        background: #4D3EA3;
        color: #ffffff;
    }
    .about-grex {
        padding-top: 45px !important;
        padding-bottom: 100px !important;
    }
    .about-heading {
        margin-bottom: 30px;
    }
    .about-label {
        display: inline-block;
        font-size: 0.75rem;
        font-weight: 800;
        letter-spacing: 3px;
        color: var(--brand-primary);
        margin-bottom: 18px;
    }

    .about-title {
        font-size: clamp(2.2rem, 4vw, 3.5rem);
        line-height: 1.08;
        font-weight: 800;
        letter-spacing: -2px;
        color: var(--brand-dark);
        margin: 0 0 25px;
    }
    .about-title span {
        display: block;
        color: var(--brand-primary);
    }
    .about-line {
        width: 70px;
        height: 4px;
        border-radius: 10px;
        background: var(--brand-primary);
    }


    .text-grex {
        color: var(--brand-primary);
    }

   

    .about-point {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 16px;
    }

    .about-point-icon {
        width: 42px;
        height: 42px;
        min-width: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: rgba(77, 62, 163, 0.10);
        color: var(--brand-primary);
    }

    .about-point strong {
        display: block;
        color: var(--brand-dark);
    }

    .about-point p {
        margin: 2px 0 0;
        color: var(--text-muted);
        font-size: 0.85rem;
    }

    .about-image-wrapper {
        position: relative;
        padding: 15px;
    }

    .about-image {
        width: 100%;
        height: 430px;
        object-fit: cover;
        border-radius: 28px;
        box-shadow: 0 25px 50px rgba(69, 12, 63, 0.12);
    }

    .about-floating-card {
        position: absolute;
        left: 0;
        bottom: 35px;
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 18px;
        background: rgba(255,255,255,0.92);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        box-shadow: 0 12px 30px rgba(0,0,0,0.12);
    }

    .about-floating-card > i {
        font-size: 1.5rem;
        color: var(--brand-primary);
    }

    .about-floating-card strong,
    .about-floating-card small {
        display: block;
    }

    .about-floating-card small {
        color: var(--text-muted);
    }
</style>
@endsection

@section('content')

<!-- Hero Section: Pusat Pencarian Utama -->
<section class="hero-landing mb-5">
    <div class="container">
        <div class="row align-items-center justify-content-center text-center">
            <div class="col-lg-10">
                <span class="badge bg-warning text-dark fw-bold px-3 py-2 rounded-pill mb-3 text-uppercase">
                    <i class="fa-solid fa-compass me-1"></i> Platform Resmi Eksplorasi Gresik
                </span>
                <h1 class="hero-title mb-3">
                    Lokavino
                    <span>Lokavino.com</span>
                </h1>
                <p class=" mb-4 hero-description px-lg-5">
                    Temukan akomodasi penginapan terbaik, destinasi wisata menarik, dan tempat nongkrong terfavorit di Seluruh Kabupaten Gresik hanya dalam satu klik.
                </p>

                <!-- Box Pencarian Utama (Global Search) -->
                <div class="search-card text-start text-dark">
                    <form action="{{ route('home') }}" method="GET">
                        <div class="row g-3 align-items-center">
                            <!-- Input Keyword -->
                            <div class="col-md-5">
                                <label for="keyword" class="form-label small fw-bold text-muted">
                                    <i class="fa-solid fa-magnifying-glass text-primary me-1"></i> Cari Tempat / Nama
                                </label>
                                <input type="text" name="keyword" id="keyword" class="form-control form-control-lg rounded-3 fs-6" 
                                       placeholder="Misal: Aston, Sekapuk, Bikin Kopi..." value="{{ $searchKeyword ?? '' }}">
                            </div>

                            <!-- Filter Kategori Service -->
                            <div class="col-md-3">
                                <label for="service" class="form-label small fw-bold text-muted">
                                    <i class="fa-solid fa-layer-group text-primary me-1"></i> Kategori Layanan
                                </label>
                                <select name="service" id="service" class="form-select form-select-lg rounded-3 fs-6">
                                    <option value="">Semua Kategori</option>
                                    <option value="penginapan" {{ ($searchService ?? '') == 'penginapan' ? 'selected' : '' }}>Penginapan</option>
                                    <option value="wisata" {{ ($searchService ?? '') == 'wisata' ? 'selected' : '' }}>Wisata</option>
                                    <option value="nongkrong" {{ ($searchService ?? '') == 'nongkrong' ? 'selected' : '' }}>Nongkrong</option>
                                </select>
                            </div>

                            <!-- Filter Kecamatan -->
                            <div class="col-md-2">
                                <label for="district" class="form-label small fw-bold text-muted">
                                    <i class="fa-solid fa-location-dot text-primary me-1"></i> Kecamatan
                                </label>
                                <select name="district" id="district" class="form-select form-select-lg rounded-3 fs-6">
                                    <option value="">Semua</option>
                                    @foreach($districts as $dist)
                                        <option value="{{ $dist->id }}" {{ ($searchDistrict ?? '') == $dist->id ? 'selected' : '' }}>
                                            {{ $dist->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Tombol Cari -->
                            <div class="col-md-2 d-grid">
                                <label class="form-label d-none d-md-block opacity-0">Action</label>
                                <button type="submit" class="btn btn-grex-primary btn-lg rounded-3 fs-6 fw-bold">
                                    <i class="fa-solid fa-search me-1"></i> Cari
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</section>

<div class="container mb-5">

    <!-- Section Hasil Pencarian jika User melakukan Pencarian -->
    @if(request()->hasAny(['keyword', 'service', 'district']) && $searchResults)
        <div class="card border-0 shadow-lg rounded-4 p-4 mb-5" style="background: #ffffff;">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h4 class="fw-bold text-dark mb-0">
                    <i class="fa-solid fa-list-check text-primary me-2"></i> Hasil Pencarian Global
                    <span class="badge bg-secondary fs-6 rounded-pill ms-2">{{ $searchResults->total() }} Ditemukan</span>
                </h4>
                <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                    <i class="fa-solid fa-rotate-left me-1"></i> Reset
                </a>
            </div>

            @if($searchResults->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="fa-solid fa-folder-open fa-3x mb-3 text-secondary opacity-50"></i>
                    <p class="fs-5 mb-1">Maaf, tempat yang Anda cari tidak ditemukan.</p>
                    <small>Coba gunakan kata kunci atau pilihan kecamatan yang lain.</small>
                </div>
            @else
                <div class="row g-4">
                    @foreach($searchResults as $place)
                        @php
                            $isWisata = $place instanceof \App\Models\TouristPlace;
                            $isHangout = $place instanceof \App\Models\HangoutPlace;
                            $isLodging = $place instanceof \App\Models\Lodging;
                        @endphp
                        <div class="col-md-4">
                            <div class="card card-grex h-100">
                                @if($isLodging && !empty($place->cover_image))
                                    <img src="{{ $place->cover_image }}" class="card-grex-img" alt="{{ $place->name }}">
                                @endif
                                <div class="card-body p-4 d-flex flex-column">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        @if($isWisata)
                                            <span class="badge-grex-wisata">Wisata</span>
                                            <small class="text-muted"><i class="fa-solid fa-location-dot me-1 text-danger"></i> {{ is_string($place->district) ? $place->district : ($place->district->name ?? 'Gresik') }}</small>
                                        @elseif($isHangout)
                                            <span class="badge-grex-nongkrong">Nongkrong</span>
                                            <small class="text-muted"><i class="fa-solid fa-location-dot me-1 text-danger"></i> {{ is_string($place->district) ? $place->district : ($place->district->name ?? 'Gresik') }}</small>
                                        @else
                                            <span class="badge-grex-penginapan">Penginapan</span>
                                            <small class="text-muted"><i class="fa-solid fa-location-dot me-1 text-danger"></i> {{ is_string($place->district) ? $place->district : ($place->district->name ?? 'Gresik') }}</small>
                                        @endif
                                    </div>
                                    <h5 class="fw-bold text-dark mb-2">{{ $place->name }}</h5>
                                    <p class="small text-muted mb-3 flex-grow-1">{{ Str::limit($place->description, 90) }}</p>
                                    <div class="d-flex align-items-center justify-content-between pt-3 border-top mt-auto">
                                        @if($isWisata)
                                            <span class="fw-bold text-success">{{ $place->ticket_price ?? 'Gratis' }}</span>
                                            <a href="{{ route('wisata.detail', $place->id) }}" class="btn btn-grex-primary btn-sm px-3">Lihat Detail</a>
                                        @elseif($isHangout)
                                            <span class="small text-muted">{{ $place->operational_hours ?? 'Setiap Hari' }}</span>
                                            <a href="{{ route('nongkrong.detail', $place->id) }}" class="btn btn-grex-primary btn-sm px-3">Lihat Detail</a>
                                        @else
                                            <span class="fw-bold text-primary">Rp {{ number_format($place->price_start, 0, ',', '.') }}</span>
                                            <a href="{{ route('lodging.detail', $place->id) }}" class="btn btn-grex-primary btn-sm px-3">Lihat Detail</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4">
                    {{ $searchResults->links() }}
                </div>
            @endif
        </div>
    @endif

    <!-- Tentang Lokavino -->
    <section class="about-grex py-5">
        <div class="container">
            <div class="row align-items-center g-5">

                <!-- Text -->
                <div class="col-lg-6">
                    <div class="about-heading">
                        <span class="about-label">TENTANG LOKAVINO</span>
                        <h2 class="about-title">
                            Satu Tempat untuk
                            <span>Menjelajahi Gresik</span>
                        </h2>
                        <div class="about-line"></div>
                    </div>

                    <p class="text-secondary fs-5">
                        <strong>Lokavino</strong> merupakan platform
                        informasi yang membantu masyarakat dan wisatawan menemukan
                        berbagai destinasi menarik di Kabupaten Gresik.
                    </p>

                    <p class="text-secondary">
                        Mulai dari tempat menginap, destinasi wisata, hingga tempat
                        nongkrong dan kuliner, semuanya dikumpulkan dalam satu
                        platform yang mudah digunakan.
                    </p>

                    <div class="about-points mt-4">

                        <div class="about-point">
                            <div class="about-point-icon">
                                <i class="fa-solid fa-hotel"></i>
                            </div>
                            <div>
                                <strong>Penginapan</strong>
                                <p>Hotel, homestay, villa, dan akomodasi lainnya.</p>
                            </div>
                        </div>

                        <div class="about-point">
                            <div class="about-point-icon">
                                <i class="fa-solid fa-mountain-sun"></i>
                            </div>
                            <div>
                                <strong>Wisata</strong>
                                <p>Destinasi alam, religi, sejarah, dan bahari.</p>
                            </div>
                        </div>

                        <div class="about-point">
                            <div class="about-point-icon">
                                <i class="fa-solid fa-mug-hot"></i>
                            </div>
                            <div>
                                <strong>Nongkrong</strong>
                                <p>Cafe, coffee shop, kuliner, dan tempat bersantai.</p>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Image -->
                <div class="col-lg-6">
                    <div class="about-image-wrapper">
                        <img
                            src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=1000&q=80"
                            alt="Eksplorasi Gresik"
                            class="about-image"
                        >

                        <div class="about-floating-card">
                            <i class="fa-solid fa-compass"></i>
                            <div>
                                <strong>Explore Gresik</strong>
                                <small>Temukan tempat favoritmu</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. Fitur-Fitur Lokavino -->
    <div class="mb-5">
        <div class="text-center mb-4">
            <h2 class="section-title display-6">Fitur Layanan Lokavino</h2>
            <p class="text-secondary">Tiga pilar utama untuk memenuhi segala kebutuhan eksplorasi Anda</p>
        </div>
        <div class="row g-4">
            <!-- Card 1: Penginapan -->
            <div class="col-md-4">
                <div class="card feature-card h-100 p-4 text-center">
                    <div class="icon-box-feature" style="background: #FFD2F4; color: #450C3F;">
                        <i class="fa-solid fa-hotel"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-2">Penginapan</h4>
                    <p class="text-muted small mb-4 flex-grow-1">
                        Layanan pencarian akomodasi terlengkap di Gresik. Mulai dari hotel berbintang, guest house, hingga homestay terjangkau.
                    </p>
                    <a href="{{ route('penginapan') }}" class="btn btn-grex-primary w-100">
                        <i class="fa-solid fa-compass me-1"></i> Jelajahi Penginapan
                    </a>
                </div>
            </div>

            <!-- Card 2: Wisata -->
            <div class="col-md-4">
                <div class="card feature-card h-100 p-4 text-center">
                    <div class="icon-box-feature" style="background: #E1DAFB; color: #4D3EA3;">
                        <i class="fa-solid fa-mountain-sun"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-2">Wisata</h4>
                    <p class="text-muted small mb-4 flex-grow-1">
                        Eksplorasi destinasi wisata alam memukau, ziarah religi Walisongo, keindahan pantai bahari, dan tempat bersejarah.
                    </p>
                    <a href="{{ route('wisata') }}" class="btn btn-grex-primary w-100">
                        <i class="fa-solid fa-compass me-1"></i> Jelajahi Wisata
                    </a>
                </div>
            </div>

            <!-- Card 3: Nongkrong -->
            <div class="col-md-4">
                <div class="card feature-card h-100 p-4 text-center">
                    <div class="icon-box-feature" style="background: #758AD1; color: #ffffff;">
                        <i class="fa-solid fa-mug-hot"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-2">Nongkrong</h4>
                    <p class="text-muted small mb-4 flex-grow-1">
                        Rekomendasi tempat bersantai, coffee shop aesthetic, warung kopi khas Gresik, dan lokasi kulineran favorit keluarga.
                    </p>
                    <a href="{{ route('nongkrong') }}" class="btn btn-grex-primary w-100">
                        <i class="fa-solid fa-compass me-1"></i> Jelajahi Nongkrong
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Cara Kerja -->
    <div class="mb-5">
        <div class="text-center mb-4">
            <h2 class="section-title display-6">Cara Kerja Lokavino</h2>
            <p class="text-secondary">Alur sistem yang cepat, transparan, dan terverifikasi</p>
        </div>
        <div class="row g-3">
            <div class="col">
                <div class="step-card h-100">
                    <div class="step-badge">1</div>
                    <i class="fa-solid fa-user-plus fa-2x text-primary mb-2"></i>
                    <h6 class="fw-bold mb-1">Owner Registrasi</h6>
                    <p class="small text-muted mb-0">Pemilik usaha mendaftar akun owner sederhana.</p>
                </div>
            </div>
            <div class="col">
                <div class="step-card h-100">
                    <div class="step-badge">2</div>
                    <i class="fa-solid fa-plus-circle fa-2x text-primary mb-2"></i>
                    <h6 class="fw-bold mb-1">Tambah Data</h6>
                    <p class="small text-muted mb-0">Owner memasukkan informasi lengkap tempat.</p>
                </div>
            </div>
            <div class="col">
                <div class="step-card h-100">
                    <div class="step-badge">3</div>
                    <i class="fa-solid fa-clipboard-check fa-2x text-primary mb-2"></i>
                    <h6 class="fw-bold mb-1">Verifikasi Admin</h6>
                    <p class="small text-muted mb-0">Admin memeriksa keabsahan data tempat.</p>
                </div>
            </div>
            <div class="col">
                <div class="step-card h-100">
                    <div class="step-badge">4</div>
                    <i class="fa-solid fa-globe fa-2x text-primary mb-2"></i>
                    <h6 class="fw-bold mb-1">Data Tayang</h6>
                    <p class="small text-muted mb-0">Tempat yang disetujui otomatis aktif di Lokavino.</p>
                </div>
            </div>
            <div class="col">
                <div class="step-card h-100">
                    <div class="step-badge">5</div>
                    <i class="fa-solid fa-search-location fa-2x text-primary mb-2"></i>
                    <h6 class="fw-bold mb-1">Pengguna Mencari</h6>
                    <p class="small text-muted mb-0">Pengunjung dengan mudah menemukan lokasi tujuan.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. Rekomendasi Tempat Terbaik -->
    <div class="mb-5">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h2 class="section-title display-6 mb-1">Rekomendasi Populer</h2>
                <p class="text-secondary mb-0">Pilihan favorit pengunjung dari masing-masing kategori</p>
            </div>
            <ul class="nav nav-pills nav-pills-grex" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pills-penginapan-tab" data-bs-toggle="pill" data-bs-target="#pills-penginapan" type="button" role="tab">Penginapan</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-wisata-tab" data-bs-toggle="pill" data-bs-target="#pills-wisata" type="button" role="tab">Wisata</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-nongkrong-tab" data-bs-toggle="pill" data-bs-target="#pills-nongkrong" type="button" role="tab">Nongkrong</button>
                </li>
            </ul>
        </div>

        <div class="tab-content" id="pills-tabContent">
            <!-- Tab 1: 4 Penginapan Populer -->
            <div class="tab-pane fade show active" id="pills-penginapan" role="tabpanel">
                <div class="row g-4">
                    @foreach($popularPenginapan as $item)
                        <div class="col-md-3">
                            <div class="card card-grex h-100">
                                <div class="card-body p-4 d-flex flex-column">
                                    <span class="badge-grex-penginapan w-auto me-auto mb-2"><i class="fa-solid fa-hotel me-1"></i> Penginapan</span>
                                    <h6 class="fw-bold text-dark mb-1">{{ $item->name }}</h6>
                                    <p class="small text-muted mb-2"><i class="fa-solid fa-location-dot text-danger me-1"></i> Kec. {{ $item->district ?? 'Gresik' }}</p>
                                    <p class="small text-muted mb-3 flex-grow-1">{{ Str::limit($item->description, 80) }}</p>
                                    <div class="mt-auto pt-2 border-top d-flex align-items-center justify-content-between">
                                        <span class="small fw-bold text-primary fs-8">Rp {{ number_format($item->price_start, 0, ',', '.') }}</span>
                                        <a href="{{ route('lodging.detail', $item->id) }}" class="btn btn-outline-dark btn-sm rounded-pill px-3 fs-7">Detail</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Tab 2: 4 Wisata Populer -->
            <div class="tab-pane fade" id="pills-wisata" role="tabpanel">
                <div class="row g-4">
                    @foreach($popularWisata as $item)
                        <div class="col-md-3">
                            <div class="card card-grex h-100 p-3">
                                <div class="card-body p-0 d-flex flex-column h-100">
                                    <span class="badge-grex-wisata w-auto me-auto mb-2"><i class="fa-solid fa-mountain-sun me-1"></i> Wisata</span>
                                    <h6 class="fw-bold text-dark mb-1">{{ $item->name }}</h6>
                                    <p class="small text-muted mb-2"><i class="fa-solid fa-location-dot text-danger me-1"></i> {{ $item->district ?? 'Gresik' }}</p>
                                    <p class="small text-secondary mb-3 flex-grow-1">{{ Str::limit($item->description, 70) }}</p>
                                    <div class="mt-auto pt-2 border-top d-flex align-items-center justify-content-between">
                                        <span class="small fw-bold text-success">{{ $item->ticket_price ?? 'Gratis' }}</span>
                                        <a href="{{ route('wisata.detail', $item->id) }}" class="btn btn-outline-dark btn-sm rounded-pill px-3 fs-7">Detail</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Tab 3: 4 Nongkrong Populer -->
            <div class="tab-pane fade" id="pills-nongkrong" role="tabpanel">
                <div class="row g-4">
                    @foreach($popularNongkrong as $item)
                        <div class="col-md-3">
                            <div class="card card-grex h-100">
                                <div class="card-body p-4 d-flex flex-column">
                                    <span class="badge-grex-nongkrong w-auto me-auto mb-2"><i class="fa-solid fa-mug-hot me-1"></i> Nongkrong</span>
                                    <h6 class="fw-bold text-dark mb-1">{{ $item->name }}</h6>
                                    <p class="small text-muted mb-2"><i class="fa-solid fa-location-dot text-danger me-1"></i> Kec. {{ $item->district ?? 'Gresik' }}</p>
                                    <p class="small text-muted mb-3 flex-grow-1">{{ Str::limit($item->description, 80) }}</p>
                                    <div class="mt-auto pt-2 border-top d-flex align-items-center justify-content-between">
                                        <span class="small fw-bold text-dark fs-8">{{ $item->operational_hours }}</span>
                                        <a href="{{ route('nongkrong.detail', $item->id) }}" class="btn btn-outline-dark btn-sm rounded-pill px-3 fs-7">Detail</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
