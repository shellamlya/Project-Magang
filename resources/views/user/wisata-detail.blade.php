@extends('layouts.app')

@section('title', $touristPlace->name . ' - Wisata Lokavino')

@section('styles')
<style>
    body {
        background-color: #E1DAFB !important;
    }
    .detail-card {
        background: #ffffff;
        border-radius: 24px;
        border: 1px solid rgba(117, 138, 209, 0.2);
        box-shadow: 0 10px 30px rgba(69, 12, 63, 0.05);
        overflow: hidden;
    }
    .badge-wisata-lg {
        background-color: #4D3EA3;
        color: #ffffff;
        font-weight: 700;
        padding: 0.5rem 1rem;
        border-radius: 50rem;
    }
    .info-box {
        background: #FFD2F4;
        color: #450C3F;
        border-radius: 16px;
        padding: 1.25rem;
        border-left: 4px solid #450C3F;
    }
    .facility-pill {
        background: #E1DAFB;
        color: #4D3EA3;
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
        background: #1b263b;
        position: relative;
    }
    .gallery-img-wrapper {
        position: relative;
        overflow: hidden;
        cursor: pointer;
        background: #0d1b2a;
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
        <a href="{{ route('wisata') }}" class="btn btn-outline-dark btn-sm rounded-pill px-3">
            <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Daftar Wisata
        </a>
    </div>

    <!-- Gallery / Photo Section -->
    @php
        $photo1 = $touristPlace->photo_1_url;
        $photo2 = $touristPlace->photo_2_url;
        $hasSubPhotos = !empty($photo1) || !empty($photo2);

        // Format WA Number
        $rawPhone = $touristPlace->phone ?? ($touristPlace->owner->business_phone ?? ($touristPlace->owner->user->phone ?? ''));
        $cleanPhone = preg_replace('/[^0-9]/', '', $rawPhone);
        if (str_starts_with($cleanPhone, '0')) {
            $cleanPhone = '62' . substr($cleanPhone, 1);
        }
        $waUrl = $cleanPhone ? "https://wa.me/{$cleanPhone}?text=" . urlencode("Halo Pengelola {$touristPlace->name}, saya ingin menanyakan informasi tiket / kunjungan wisata melalui Lokavino.") : null;
    @endphp

    <div class="gallery-container mb-4 shadow-sm">
        @if($hasSubPhotos)
            <div class="row g-2">
                <!-- Foto Utama (Thumbnail) -->
                <div class="col-lg-8 col-md-7">
                    <div class="gallery-img-wrapper gallery-main-h" onclick="openPhotoModal('{{ $touristPlace->thumbnail_url }}', '{{ $touristPlace->name }} - Foto Utama')">
                        <img src="{{ $touristPlace->thumbnail_url }}" alt="{{ $touristPlace->name }}" loading="lazy">
                        <span class="gallery-badge"><i class="fa-solid fa-camera me-1"></i> Foto Utama</span>
                    </div>
                </div>
                <!-- Foto Pendukung #1 & #2 -->
                <div class="col-lg-4 col-md-5 d-flex flex-column gap-2">
                    @if($photo1)
                        <div class="gallery-img-wrapper {{ $photo2 ? 'gallery-sub-h' : 'gallery-main-h' }}" onclick="openPhotoModal('{{ $photo1 }}', '{{ $touristPlace->name }} - Spot / Wahana 1')">
                            <img src="{{ $photo1 }}" alt="{{ $touristPlace->name }} - Foto 1" loading="lazy">
                            <span class="gallery-badge"><i class="fa-solid fa-image me-1"></i> Spot / Wahana Wisata</span>
                        </div>
                    @endif
                    @if($photo2)
                        <div class="gallery-img-wrapper {{ $photo1 ? 'gallery-sub-h' : 'gallery-main-h' }}" onclick="openPhotoModal('{{ $photo2 }}', '{{ $touristPlace->name }} - Suasana / Fasilitas 2')">
                            <img src="{{ $photo2 }}" alt="{{ $touristPlace->name }} - Foto 2" loading="lazy">
                            <span class="gallery-badge"><i class="fa-solid fa-image me-1"></i> Suasana Wisata</span>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <!-- Single Full-Width Thumbnail -->
            <div class="gallery-img-wrapper gallery-main-h" onclick="openPhotoModal('{{ $touristPlace->thumbnail_url }}', '{{ $touristPlace->name }}')">
                <img src="{{ $touristPlace->thumbnail_url }}" alt="{{ $touristPlace->name }}" loading="lazy">
                <span class="gallery-badge"><i class="fa-solid fa-camera me-1"></i> Foto Utama</span>
            </div>
        @endif
    </div>

    <div class="row g-4">
        <!-- Kolom Utama -->
        <div class="col-lg-8">
            <div class="detail-card p-4 p-md-5 mb-4">
                <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                    <span class="badge-wisata-lg"><i class="fa-solid fa-mountain-sun me-1"></i> Destinasi Wisata</span>
                    <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle px-3 py-2 rounded-pill small fw-bold">
                        <i class="fa-solid fa-circle-check me-1"></i> Terverifikasi Resmi
                    </span>
                </div>

                <h1 class="fw-extrabold text-dark display-5 mb-3">{{ $touristPlace->name }}</h1>

                <p class="text-muted fs-6 mb-4">
                    <i class="fa-solid fa-location-dot text-danger me-1"></i> {{ $touristPlace->address ?? ($touristPlace->village . ', Kecamatan ' . $touristPlace->district) }}
                </p>

                <!-- Information Grid Box -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="info-box">
                            <div class="small text-muted mb-1"><i class="fa-regular fa-clock me-1 text-danger"></i> Jam Operasional</div>
                            <div class="fw-bold text-dark fs-6">{{ $touristPlace->operational_hours }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-box">
                            <div class="small text-muted mb-1"><i class="fa-solid fa-ticket me-1 text-danger"></i> Tiket Masuk</div>
                            <div class="fw-bold text-success fs-6">{{ $touristPlace->ticket_price ?? 'Gratis' }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-box">
                            <div class="small text-muted mb-1"><i class="fa-solid fa-user-shield me-1 text-danger"></i> Pengelola / PIC</div>
                            <div class="fw-bold text-dark fs-6">{{ $touristPlace->manager_name }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-box">
                            <div class="small text-muted mb-1"><i class="fa-solid fa-phone me-1 text-danger"></i> No. WA / Kontak</div>
                            <div class="fw-bold text-dark fs-6">{{ $rawPhone ?: 'Tersedia di lokasi' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Deskripsi Wisata -->
                <div class="mb-4">
                    <h4 class="fw-bold text-dark mb-3">Tentang Destinasi Wisata</h4>
                    <p class="text-secondary leading-relaxed fs-6" style="white-space: pre-line;">{{ $touristPlace->description }}</p>
                </div>

                <!-- Fasilitas Wisata -->
                <div class="mb-4">
                    <h4 class="fw-bold text-dark mb-3">Fasilitas yang Tersedia</h4>
                    @if($touristPlace->facilities->isEmpty())
                        <p class="text-muted italic">Fasilitas umum tersedia di area wisata.</p>
                    @else
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($touristPlace->facilities as $fac)
                                <div class="facility-pill">
                                    <i class="fa-solid fa-circle-check text-success"></i> {{ $fac->facility_name }}
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Aksi Kontak & Navigasi -->
                <div class="pt-4 border-top d-flex gap-2 flex-wrap">
                    @if($waUrl)
                        <a href="{{ $waUrl }}" target="_blank" class="btn btn-whatsapp-booking btn-lg rounded-pill px-4 fw-bold shadow-sm">
                            <i class="fa-brands fa-whatsapp me-2 fs-5"></i> Hubungi Pengelola via WA
                        </a>
                    @endif
                    @if($touristPlace->google_maps)
                        <a href="{{ $touristPlace->google_maps }}" target="_blank" class="btn btn-grex-primary btn-lg rounded-pill px-4 fw-bold" onclick="trackMapClick()">
                            <i class="fa-solid fa-map-location-dot me-2"></i> Buka Google Maps
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Kolom Samping (Rekomendasi) -->
        <div class="col-lg-4">
            <!-- Wisata Lainnya -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4" style="background: #ffffff;">
                <h5 class="fw-bold text-dark mb-3">Destinasi Wisata Lainnya</h5>
                <div class="d-flex flex-column gap-3">
                    @foreach($otherWisata as $other)
                        <a href="{{ route('wisata.detail', $other->id) }}" class="text-decoration-none">
                            <div class="p-3 bg-light rounded-3 d-flex gap-3 align-items-center">
                                <img src="{{ $other->thumbnail_url }}" alt="{{ $other->name }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 10px;">
                                <div>
                                    <div class="fw-bold text-dark small mb-1">{{ $other->name }}</div>
                                    <small class="text-muted d-block mb-1"><i class="fa-solid fa-location-dot text-danger me-1"></i> {{ $other->district }}</small>
                                    <small class="text-success fw-bold">{{ $other->ticket_price ?? 'Gratis' }}</small>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Penginapan Terdekat -->
            <div class="card border-0 shadow-sm rounded-4 p-4" style="background: #ffffff;">
                <h5 class="fw-bold text-dark mb-3">Rekomendasi Penginapan</h5>
                <div class="d-flex flex-column gap-3">
                    @foreach($nearbyLodgings as $lodging)
                        <a href="{{ route('lodging.detail', $lodging->id) }}" class="text-decoration-none">
                            <div class="p-3 bg-light rounded-3 d-flex gap-3 align-items-center">
                                <img src="{{ $lodging->thumbnail_url }}" alt="{{ $lodging->name }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 10px;">
                                <div>
                                    <div class="fw-bold text-dark small mb-1">{{ $lodging->name }}</div>
                                    <small class="text-muted d-block mb-1"><i class="fa-solid fa-hotel me-1"></i> Kec. {{ $lodging->district ?? 'Gresik' }}</small>
                                    <small class="text-primary fw-bold">Mulai Rp {{ number_format($lodging->price_start, 0, ',', '.') }}</small>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
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
        fetch("/api/places/{{ $touristPlace->id }}/track-map-click", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ category: "wisata" })
        }).catch(err => console.error(err));
    }
</script>
@endsection
