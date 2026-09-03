@extends('layouts.app')

@section('title', $hangoutPlace->name . ' - Nongkrong Lokavino')

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
    .badge-nongkrong-lg {
        background-color: #758AD1;
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
        background: #2b1f1d;
        position: relative;
    }
    .gallery-img-wrapper {
        position: relative;
        overflow: hidden;
        cursor: pointer;
        background: #1b1210;
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
        <a href="{{ route('nongkrong') }}" class="btn btn-outline-dark btn-sm rounded-pill px-3">
            <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Daftar Nongkrong
        </a>
    </div>

    <!-- Gallery / Photo Section -->
    @php
        $photo1 = $hangoutPlace->photo_1_url;
        $photo2 = $hangoutPlace->photo_2_url;
        $hasSubPhotos = !empty($photo1) || !empty($photo2);

        // Format WA Number
        $rawPhone = $hangoutPlace->phone ?? ($hangoutPlace->owner->business_phone ?? ($hangoutPlace->owner->user->phone ?? ''));
        $cleanPhone = preg_replace('/[^0-9]/', '', $rawPhone);
        if (str_starts_with($cleanPhone, '0')) {
            $cleanPhone = '62' . substr($cleanPhone, 1);
        }
        $waUrl = $cleanPhone ? "https://wa.me/{$cleanPhone}?text=" . urlencode("Halo {$hangoutPlace->name}, saya melihat kafe/tempat Anda di Lokavino dan ingin bertanya menu / reservasi meja.") : null;
    @endphp

    <div class="gallery-container mb-4 shadow-sm">
        @if($hasSubPhotos)
            <div class="row g-2">
                <!-- Foto Utama (Thumbnail) -->
                <div class="col-lg-8 col-md-7">
                    <div class="gallery-img-wrapper gallery-main-h" onclick="openPhotoModal('{{ $hangoutPlace->thumbnail_url }}', '{{ $hangoutPlace->name }} - Foto Utama')">
                        <img src="{{ $hangoutPlace->thumbnail_url }}" alt="{{ $hangoutPlace->name }}" loading="lazy">
                        <span class="gallery-badge"><i class="fa-solid fa-camera me-1"></i> Foto Utama</span>
                    </div>
                </div>
                <!-- Foto Pendukung #1 & #2 -->
                <div class="col-lg-4 col-md-5 d-flex flex-column gap-2">
                    @if($photo1)
                        <div class="gallery-img-wrapper {{ $photo2 ? 'gallery-sub-h' : 'gallery-main-h' }}" onclick="openPhotoModal('{{ $photo1 }}', '{{ $hangoutPlace->name }} - Foto Menu / Suasana 1')">
                            <img src="{{ $photo1 }}" alt="{{ $hangoutPlace->name }} - Foto 1" loading="lazy">
                            <span class="gallery-badge"><i class="fa-solid fa-utensils me-1"></i> Foto Menu / Suasana</span>
                        </div>
                    @endif
                    @if($photo2)
                        <div class="gallery-img-wrapper {{ $photo1 ? 'gallery-sub-h' : 'gallery-main-h' }}" onclick="openPhotoModal('{{ $photo2 }}', '{{ $hangoutPlace->name }} - Suasana / Fasilitas 2')">
                            <img src="{{ $photo2 }}" alt="{{ $hangoutPlace->name }} - Foto 2" loading="lazy">
                            <span class="gallery-badge"><i class="fa-solid fa-image me-1"></i> Suasana Nongkrong</span>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <!-- Single Full-Width Thumbnail -->
            <div class="gallery-img-wrapper gallery-main-h" onclick="openPhotoModal('{{ $hangoutPlace->thumbnail_url }}', '{{ $hangoutPlace->name }}')">
                <img src="{{ $hangoutPlace->thumbnail_url }}" alt="{{ $hangoutPlace->name }}" loading="lazy">
                <span class="gallery-badge"><i class="fa-solid fa-camera me-1"></i> Foto Utama</span>
            </div>
        @endif
    </div>

    <div class="row g-4">
        <!-- Kolom Utama -->
        <div class="col-lg-8">
            <div class="detail-card p-4 p-md-5 mb-4">
                <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                    <span class="badge-nongkrong-lg"><i class="fa-solid fa-mug-hot me-1"></i> Tempat Nongkrong & Kafe</span>
                    <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle px-3 py-2 rounded-pill small fw-bold">
                        <i class="fa-solid fa-circle-check me-1"></i> Terverifikasi Resmi
                    </span>
                </div>

                <h1 class="display-5 fw-extrabold text-dark mb-2">{{ $hangoutPlace->name }}</h1>
                <p class="text-muted mb-4 fs-5"><i class="fa-solid fa-location-dot text-danger me-1"></i> Kec. {{ $hangoutPlace->district ?? 'Gresik' }} {{ $hangoutPlace->village ? ', Desa ' . $hangoutPlace->village : '' }}</p>

                <!-- Info Box (Jam Operasional & Pengelola) -->
                <div class="info-box mb-4">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="fw-bold mb-1"><i class="fa-regular fa-clock me-2"></i>Jam Operasional</div>
                            <div class="fs-6">{{ $hangoutPlace->operational_hours }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="fw-bold mb-1"><i class="fa-solid fa-user-tie me-2"></i>Pengelola / Brand</div>
                            <div class="fs-6">{{ $hangoutPlace->manager_name }}</div>
                        </div>
                    </div>
                </div>

                <!-- Deskripsi Tempat Nongkrong -->
                <div class="mb-5">
                    <h4 class="fw-bold text-dark mb-3">Deskripsi & Suasana</h4>
                    <p class="text-secondary leading-relaxed fs-6" style="white-space: pre-line;">{{ $hangoutPlace->description }}</p>
                </div>

                <!-- Fasilitas Tempat Nongkrong -->
                @if($hangoutPlace->facilities->isNotEmpty())
                    <div class="mb-4">
                        <h4 class="fw-bold text-dark mb-3">Fasilitas yang Tersedia</h4>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($hangoutPlace->facilities as $fac)
                                <div class="facility-pill">
                                    <i class="fa-solid fa-check text-success"></i> {{ $fac->facility_name }}
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
                    <span class="small text-dark fw-bold">{{ $hangoutPlace->manager_name ?? 'Manajemen Kafe' }}</span>
                </div>

                @if($hangoutPlace->address)
                    <div class="mb-3">
                        <small class="text-muted d-block fw-semibold">Alamat Lengkap</small>
                        <span class="small text-dark">{{ $hangoutPlace->address }}</span>
                    </div>
                @endif

                @if($rawPhone)
                    <div class="mb-3">
                        <small class="text-muted d-block fw-semibold">No. WhatsApp / Telepon Kafe</small>
                        <span class="small text-dark"><i class="fa-solid fa-phone me-1 text-success"></i> {{ $rawPhone }}</span>
                    </div>
                @endif

                @if($hangoutPlace->email)
                    <div class="mb-3">
                        <small class="text-muted d-block fw-semibold">Email</small>
                        <span class="small text-dark"><i class="fa-solid fa-envelope me-1 text-info"></i> {{ $hangoutPlace->email }}</span>
                    </div>
                @endif

                <!-- Tombol Direct WhatsApp / Reservasi -->
                @if($waUrl)
                    <div class="d-grid mt-4 mb-2">
                        <a href="{{ $waUrl }}" target="_blank" class="btn btn-whatsapp-booking rounded-pill py-2.5 fw-bold shadow-sm text-center">
                            <i class="fa-brands fa-whatsapp me-1 fs-5"></i> Hubungi & Reservasi via WA
                        </a>
                    </div>
                @endif

                @if($hangoutPlace->google_maps)
                    <div class="d-grid mt-2">
                        <a href="{{ $hangoutPlace->google_maps }}" target="_blank" class="btn btn-grex-primary rounded-pill py-2.5 fw-bold" onclick="trackMapClick()">
                            <i class="fa-solid fa-map-pin me-1"></i> Buka di Google Maps
                        </a>
                    </div>
                @endif
            </div>

            <!-- Tempat Nongkrong Lainnya -->
            @if(isset($otherNongkrong) && $otherNongkrong->isNotEmpty())
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                    <h6 class="fw-bold text-dark mb-3">Tempat Nongkrong Lainnya</h6>
                    <div class="d-flex flex-column gap-3">
                        @foreach($otherNongkrong as $other)
                            <a href="{{ route('nongkrong.detail', $other->id) }}" class="text-decoration-none">
                                <div class="p-3 bg-light rounded-3 d-flex gap-3 align-items-center">
                                    <img src="{{ $other->thumbnail_url }}" alt="{{ $other->name }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 10px;">
                                    <div>
                                        <div class="fw-bold text-dark small mb-1">{{ $other->name }}</div>
                                        <small class="text-muted d-block"><i class="fa-solid fa-location-dot text-danger me-1"></i>{{ $other->district }}</small>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Rekomendasi Penginapan Terdekat -->
            @if(isset($nearbyLodgings) && $nearbyLodgings->isNotEmpty())
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                    <h6 class="fw-bold text-dark mb-3">Rekomendasi Penginapan Terdekat</h6>
                    <div class="d-flex flex-column gap-3">
                        @foreach($nearbyLodgings as $lodging)
                            <a href="{{ route('lodging.detail', $lodging->id) }}" class="text-decoration-none">
                                <div class="p-3 bg-light rounded-3 d-flex gap-3 align-items-center">
                                    <img src="{{ $lodging->thumbnail_url }}" alt="{{ $lodging->name }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 10px;">
                                    <div>
                                        <div class="fw-bold text-dark small mb-1">{{ $lodging->name }}</div>
                                        <small class="text-muted d-block"><i class="fa-solid fa-location-dot text-danger me-1"></i>Kec. {{ $lodging->district }}</small>
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
        fetch("/api/places/{{ $hangoutPlace->id }}/track-map-click", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ category: "nongkrong" })
        }).catch(err => console.error(err));
    }
</script>
@endsection
