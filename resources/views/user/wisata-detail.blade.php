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

    <div class="row g-4">
        <!-- Kolom Utama -->
        <div class="col-lg-8">
            <div class="detail-card p-4 p-md-5 mb-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="badge-wisata-lg"><i class="fa-solid fa-mountain-sun me-1"></i> Fitur Wisata</span>
                    <span class="badge bg-success-subtle text-success fs-6 border border-success-subtle rounded-pill px-3 py-2">
                        <i class="fa-solid fa-circle-check me-1"></i> Terverifikasi
                    </span>
                </div>

                <h1 class="fw-extrabold text-dark display-5 mb-3">{{ $touristPlace->name }}</h1>

                <p class="text-muted fs-6 mb-4">
                    <i class="fa-solid fa-location-dot me-1 text-danger"></i> {{ $touristPlace->address ?? ($touristPlace->village . ', Kecamatan ' . $touristPlace->district . ', Kabupaten Gresik') }}
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
                            <div class="small text-muted mb-1"><i class="fa-solid fa-phone me-1 text-danger"></i> Kontak Pengelola</div>
                            <div class="fw-bold text-dark fs-6">{{ $touristPlace->phone ?? 'Tidak tersedia' }}</div>
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
                        <p class="text-muted italic">Fasilitas belum dikatalogkan secara khusus.</p>
                    @else
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($touristPlace->facilities as $fac)
                                <div class="facility-pill">
                                    <i class="fa-solid fa-circle-check text-danger"></i> {{ $fac->facility_name }}
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Google Maps Button -->
                <div class="pt-3 border-top">
                    <h5 class="fw-bold text-dark mb-2">Lokasi & Peta</h5>
                    <a href="{{ $touristPlace->google_maps }}" target="_blank" class="btn btn-danger btn-lg rounded-pill px-4 fw-bold" onclick="trackMapClick()">
                        <i class="fa-solid fa-map-location-dot me-2"></i> Buka Google Maps
                    </a>
                </div>
            </div>
        </div>

        <!-- Kolom Samping (Rekomendasi) -->
        <div class="col-lg-4">
            <!-- Wisata Lainnya -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4" style="background: #ffffff;">
                <h5 class="fw-bold text-dark mb-3">Wisata Lainnya di Gresik</h5>
                <div class="d-flex flex-column gap-3">
                    @foreach($otherWisata as $other)
                        <div class="p-3 bg-light rounded-3">
                            <h6 class="fw-bold mb-1"><a href="{{ route('wisata.detail', $other->id) }}" class="text-decoration-none text-dark">{{ $other->name }}</a></h6>
                            <small class="text-muted d-block mb-1"><i class="fa-solid fa-location-dot text-danger me-1"></i> {{ $other->district }}</small>
                            <small class="text-success fw-bold">{{ $other->ticket_price ?? 'Gratis' }}</small>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Penginapan Terdekat -->
            <div class="card border-0 shadow-sm rounded-4 p-4" style="background: #ffffff;">
                <h5 class="fw-bold text-dark mb-3">Rekomendasi Penginapan</h5>
                <div class="d-flex flex-column gap-3">
                    @foreach($nearbyLodgings as $lodging)
                        <div class="p-3 bg-light rounded-3">
                            <h6 class="fw-bold mb-1"><a href="{{ route('lodging.detail', $lodging->id) }}" class="text-decoration-none text-dark">{{ $lodging->name }}</a></h6>
                            <small class="text-muted d-block mb-1"><i class="fa-solid fa-hotel me-1"></i> Kec. {{ $lodging->district ?? 'Gresik' }}</small>
                            <small class="text-primary fw-bold">Mulai Rp {{ number_format($lodging->price_start, 0, ',', '.') }}</small>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        fetch("/api/places/{{ $touristPlace->id }}/track-view", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ category: "wisata" })
        }).catch(err => console.error(err));
    });

    function trackMapClick() {
        fetch("/api/places/{{ $touristPlace->id }}/track-map-click", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ category: "wisata" })
        }).catch(err => console.error(err));
    }
</script>
@endsection
