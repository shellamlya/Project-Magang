@extends('layouts.app')

@section('title', $lodging->name . ' - Penginapan Lokavino')

@section('styles')
<style>
    body {
        background-color: #E1DAFB !important;
    }
    .detail-card {
        background: #ffffff;
        border-radius: 24px;
        border: 1px solid rgba(77, 62, 163, 0.2);
        box-shadow: 0 10px 30px rgba(69, 12, 63, 0.05);
        overflow: hidden;
    }
    .badge-penginapan-lg {
        background-color: #4D3EA3;
        color: #ffffff;
        font-weight: 700;
        padding: 0.5rem 1rem;
        border-radius: 50rem;
    }
    .price-box {
        background: #E1DAFB;
        color: #450C3F;
        border-radius: 16px;
        padding: 1.25rem;
        border-left: 4px solid #4D3EA3;
    }
    .facility-pill {
        background: #FFD2F4;
        color: #450C3F;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 0.5rem 1rem;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    /* Modern Photo Gallery Styles */
    .gallery-container {
        border-radius: 20px;
        overflow: hidden;
        background: #2a1b3d;
        position: relative;
    }
    .gallery-img-wrapper {
        position: relative;
        overflow: hidden;
        cursor: pointer;
        background: #1e112a;
    }
    .gallery-img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }
    .gallery-img-wrapper:hover img {
        transform: scale(1.05);
    }
    .gallery-main-h {
        height: 380px;
    }
    .gallery-sub-h {
        height: 185px;
    }
    .gallery-badge {
        position: absolute;
        bottom: 12px;
        left: 12px;
        background: rgba(0, 0, 0, 0.65);
        backdrop-filter: blur(4px);
        color: #ffffff;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 20px;
        pointer-events: none;
    }
    .btn-whatsapp-booking {
        background: #25D366;
        color: #ffffff;
        border: none;
        transition: all 0.2s ease;
    }
    .btn-whatsapp-booking:hover {
        background: #1ebc59;
        color: #ffffff;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(37, 211, 102, 0.3);
    }
</style>
@endsection

@section('content')
<div class="container py-5">
    
    <!-- Tombol Kembali -->
    <div class="mb-4">
        <a href="{{ route('penginapan') }}" class="btn btn-outline-dark btn-sm rounded-pill px-3">
            <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Daftar Penginapan
        </a>
    </div>

    <!-- Gallery / Photo Section -->
    @php
        $photo1 = $lodging->photo_1_url;
        $photo2 = $lodging->photo_2_url;
        $hasSubPhotos = !empty($photo1) || !empty($photo2);
        
        // Format WA Number
        $rawPhone = $lodging->phone ?? ($lodging->owner->business_phone ?? ($lodging->owner->user->phone ?? ''));
        $cleanPhone = preg_replace('/[^0-9]/', '', $rawPhone);
        if (str_starts_with($cleanPhone, '0')) {
            $cleanPhone = '62' . substr($cleanPhone, 1);
        }
        $waUrl = $cleanPhone ? "https://wa.me/{$cleanPhone}?text=" . urlencode("Halo {$lodging->name}, saya tertarik dengan informasi penginapan di Lokavino dan ingin bertanya ketersediaan kamar / reservasi.") : null;
    @endphp

    <div class="gallery-container mb-4 shadow-sm">
        @if($hasSubPhotos)
            <div class="row g-2">
                <!-- Foto Utama (Thumbnail) -->
                <div class="col-lg-8 col-md-7">
                    <div class="gallery-img-wrapper gallery-main-h" onclick="openPhotoModal('{{ $lodging->thumbnail_url }}', '{{ $lodging->name }} - Foto Utama')">
                        <img src="{{ $lodging->thumbnail_url }}" alt="{{ $lodging->name }}" loading="lazy">
                        <span class="gallery-badge"><i class="fa-solid fa-camera me-1"></i> Foto Utama</span>
                    </div>
                </div>
                <!-- Foto Pendukung #1 & #2 -->
                <div class="col-lg-4 col-md-5 d-flex flex-column gap-2">
                    @if($photo1)
                        <div class="gallery-img-wrapper {{ $photo2 ? 'gallery-sub-h' : 'gallery-main-h' }}" onclick="openPhotoModal('{{ $photo1 }}', '{{ $lodging->name }} - Fasilitas / Kamar 1')">
                            <img src="{{ $photo1 }}" alt="{{ $lodging->name }} - Foto 1" loading="lazy">
                            <span class="gallery-badge"><i class="fa-solid fa-image me-1"></i> Foto Fasilitas / Kamar</span>
                        </div>
                    @endif
                    @if($photo2)
                        <div class="gallery-img-wrapper {{ $photo1 ? 'gallery-sub-h' : 'gallery-main-h' }}" onclick="openPhotoModal('{{ $photo2 }}', '{{ $lodging->name }} - Fasilitas / Kamar 2')">
                            <img src="{{ $photo2 }}" alt="{{ $lodging->name }} - Foto 2" loading="lazy">
                            <span class="gallery-badge"><i class="fa-solid fa-image me-1"></i> Suasana / Kamar</span>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <!-- Single Full-Width Thumbnail -->
            <div class="gallery-img-wrapper gallery-main-h" onclick="openPhotoModal('{{ $lodging->thumbnail_url }}', '{{ $lodging->name }}')">
                <img src="{{ $lodging->thumbnail_url }}" alt="{{ $lodging->name }}" loading="lazy">
                <span class="gallery-badge"><i class="fa-solid fa-camera me-1"></i> Foto Utama</span>
            </div>
        @endif
    </div>

    <div class="row g-4">
        <!-- Kolom Utama -->
        <div class="col-lg-8">
            <div class="detail-card p-4 p-md-5 mb-4">
                <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                    <span class="badge-penginapan-lg"><i class="fa-solid fa-hotel me-1"></i> Penginapan</span>
                    <span class="badge bg-success-subtle text-success fs-6 border border-success-subtle rounded-pill px-3 py-2">
                        <i class="fa-solid fa-circle-check me-1"></i> Terverifikasi Disparekrafbudpora
                    </span>
                </div>

                <h1 class="display-5 fw-extrabold text-dark mb-2">{{ $lodging->name }}</h1>
                <p class="text-muted mb-4 fs-5"><i class="fa-solid fa-location-dot text-danger me-1"></i> Kec. {{ $lodging->district ?? 'Gresik' }} {{ $lodging->village ? ', Desa ' . $lodging->village : '' }}</p>

                <!-- Tariff & Operational Info Box -->
                <div class="price-box mb-4">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-6">
                            <div class="small fw-bold text-uppercase text-muted">Tarif Sewa per Malam</div>
                            <div class="fs-4 fw-extrabold text-primary">
                                Rp {{ number_format($lodging->price_start, 0, ',', '.') }} – Rp {{ number_format($lodging->price_end, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="fw-bold small"><i class="fa-regular fa-clock me-1 text-primary"></i>Check-in</div>
                            <div class="small text-dark">{{ $lodging->check_in ?? '14.00 WIB' }}</div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="fw-bold small"><i class="fa-regular fa-clock me-1 text-danger"></i>Check-out</div>
                            <div class="small text-dark">{{ $lodging->check_out ?? '12.00 WIB' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Deskripsi Penginapan -->
                <div class="mb-5">
                    <h4 class="fw-bold text-dark mb-3">Deskripsi & Layanan</h4>
                    <p class="text-secondary leading-relaxed fs-6" style="white-space: pre-line;">{{ $lodging->description }}</p>
                </div>

                <!-- Fasilitas Penginapan -->
                @if($lodging->facilities->isNotEmpty())
                    <div class="mb-4">
                        <h4 class="fw-bold text-dark mb-3">Fasilitas Unggulan</h4>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($lodging->facilities as $fac)
                                <div class="facility-pill">
                                    <i class="fa-solid fa-circle-check text-success"></i> {{ $fac->facility_name }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Kolom Samping (Lokasi & Kontak) -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-map-location-dot me-2 text-primary"></i>Lokasi & Kontak</h5>
                
                <div class="mb-3">
                    <small class="text-muted d-block fw-semibold">Pengelola / Brand</small>
                    <span class="small text-dark fw-bold">{{ $lodging->manager_name ?? 'Manajemen Penginapan' }}</span>
                </div>

                @if($lodging->address)
                    <div class="mb-3">
                        <small class="text-muted d-block fw-semibold">Alamat Lengkap</small>
                        <span class="small text-dark">{{ $lodging->address }}</span>
                    </div>
                @endif

                @if($rawPhone)
                    <div class="mb-3">
                        <small class="text-muted d-block fw-semibold">No. WhatsApp / Telepon Bisnis</small>
                        <span class="small text-dark"><i class="fa-solid fa-phone me-1 text-success"></i> {{ $rawPhone }}</span>
                    </div>
                @endif

                @if($lodging->email)
                    <div class="mb-3">
                        <small class="text-muted d-block fw-semibold">Email</small>
                        <span class="small text-dark"><i class="fa-solid fa-envelope me-1 text-info"></i> {{ $lodging->email }}</span>
                    </div>
                @endif

                <!-- Tombol Direct WhatsApp Booking / Reservasi -->
                @if($waUrl)
                    <div class="d-grid mt-4 mb-2">
                        <a href="{{ $waUrl }}" target="_blank" class="btn btn-whatsapp-booking rounded-pill py-2.5 fw-bold shadow-sm text-center">
                            <i class="fa-brands fa-whatsapp me-1 fs-5"></i> Hubungi & Booking via WA
                        </a>
                    </div>
                @endif

                @if($lodging->google_maps)
                    <div class="d-grid mt-2">
                        <a href="{{ $lodging->google_maps }}" id="btnMapTrack" target="_blank" class="btn btn-grex-primary rounded-pill py-2.5 fw-bold" onclick="trackMapClick()">
                            <i class="fa-solid fa-map-pin me-1"></i> Buka di Google Maps
                        </a>
                    </div>
                @endif
            </div>

            <!-- Rekomendasi Penginapan Lainnya -->
            @if(isset($otherLodgings) && $otherLodgings->isNotEmpty())
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                    <h6 class="fw-bold text-dark mb-3">Penginapan Lainnya</h6>
                    <div class="d-flex flex-column gap-3">
                        @foreach($otherLodgings as $other)
                            <a href="{{ route('lodging.detail', $other->id) }}" class="text-decoration-none">
                                <div class="p-3 bg-light rounded-3 d-flex gap-3 align-items-center">
                                    <img src="{{ $other->thumbnail_url }}" alt="{{ $other->name }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 10px;">
                                    <div>
                                        <div class="fw-bold text-dark small mb-1">{{ $other->name }}</div>
                                        <small class="text-muted d-block"><i class="fa-solid fa-location-dot text-danger me-1"></i>Kec. {{ $other->district }}</small>
                                        <small class="fw-bold text-primary">Rp {{ number_format($other->price_start, 0, ',', '.') }}</small>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Rekomendasi Wisata Terdekat -->
            @if(isset($nearbyWisata) && $nearbyWisata->isNotEmpty())
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                    <h6 class="fw-bold text-dark mb-3">Wisata Terdekat di Gresik</h6>
                    <div class="d-flex flex-column gap-3">
                        @foreach($nearbyWisata as $wisata)
                            <a href="{{ route('wisata.detail', $wisata->id) }}" class="text-decoration-none">
                                <div class="p-3 bg-light rounded-3 d-flex gap-3 align-items-center">
                                    <img src="{{ $wisata->thumbnail_url }}" alt="{{ $wisata->name }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 10px;">
                                    <div>
                                        <div class="fw-bold text-dark small mb-1">{{ $wisata->name }}</div>
                                        <small class="text-muted d-block"><i class="fa-solid fa-location-dot text-danger me-1"></i>Kec. {{ $wisata->district }}</small>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

</div>

<!-- Modal Zoom Foto -->
<div class="modal fade" id="photoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-header border-0 pb-0 justify-content-end">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-0">
                <img id="modalPhotoImg" src="" alt="Preview Foto" class="img-fluid rounded-4 shadow-lg" style="max-height: 80vh; object-fit: contain;">
                <p id="modalPhotoCaption" class="text-white mt-2 small"></p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openPhotoModal(url, caption) {
        document.getElementById('modalPhotoImg').src = url;
        document.getElementById('modalPhotoCaption').innerText = caption;
        const modal = new bootstrap.Modal(document.getElementById('photoModal'));
        modal.show();
    }

    function trackMapClick() {
        fetch("/api/places/{{ $lodging->id }}/track-map-click", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ category: "penginapan" })
        }).catch(err => console.error(err));
    }
</script>
@endsection
