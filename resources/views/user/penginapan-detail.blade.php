@extends('layouts.app')

@section('title', $lodging->name . ' - Penginapan GREX')

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

    <div class="row g-4">
        <!-- Kolom Utama -->
        <div class="col-lg-8">
            <div class="detail-card p-4 p-md-5 mb-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="badge-penginapan-lg"><i class="fa-solid fa-hotel me-1"></i> Penginapan</span>
                    <span class="badge bg-success-subtle text-success fs-6 border border-success-subtle rounded-pill px-3 py-2">
                        <i class="fa-solid fa-circle-check me-1"></i> Terverifikasi
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
                    <span class="small text-dark fw-bold">{{ $lodging->manager_name ?? 'Manajemen Hotel' }}</span>
                </div>

                @if($lodging->address)
                    <div class="mb-3">
                        <small class="text-muted d-block fw-semibold">Alamat Lengkap</small>
                        <span class="small text-dark">{{ $lodging->address }}</span>
                    </div>
                @endif

                @if($lodging->phone)
                    <div class="mb-3">
                        <small class="text-muted d-block fw-semibold">Nomor Telepon</small>
                        <span class="small text-dark"><i class="fa-solid fa-phone me-1 text-success"></i> {{ $lodging->phone }}</span>
                    </div>
                @endif

                @if($lodging->email)
                    <div class="mb-3">
                        <small class="text-muted d-block fw-semibold">Email</small>
                        <span class="small text-dark"><i class="fa-solid fa-envelope me-1 text-info"></i> {{ $lodging->email }}</span>
                    </div>
                @endif

                @if($lodging->google_maps)
                    <div class="d-grid mt-4">
                        <a href="{{ $lodging->google_maps }}" id="btnMapTrack" target="_blank" class="btn btn-grex-primary rounded-pill py-2.5 fw-bold" onclick="trackMapClick()">
                            <i class="fa-solid fa-map-pin me-1"></i> Buka di Google Maps
                        </a>
                    </div>
                @endif
            </div>

            <!-- Rekomendasi Penginapan Lainnya -->
            @if(isset($otherLodgings) && $otherLodgings->isNotEmpty())
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                    <h6 class="fw-bold text-dark mb-3">Penginapan Lainnya</h6>
                    <div class="d-flex flex-column gap-3">
                        @foreach($otherLodgings as $other)
                            <a href="{{ route('lodging.detail', $other->id) }}" class="text-decoration-none">
                                <div class="p-3 bg-light rounded-3">
                                    <div class="fw-bold text-dark small">{{ $other->name }}</div>
                                    <small class="text-muted d-block"><i class="fa-solid fa-location-dot text-danger me-1"></i>Kec. {{ $other->district }}</small>
                                    <small class="fw-bold text-primary fs-8">Rp {{ number_format($other->price_start, 0, ',', '.') }} – Rp {{ number_format($other->price_end, 0, ',', '.') }}</small>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        fetch("/api/places/{{ $lodging->id }}/track-view", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ category: "penginapan" })
        }).catch(err => console.error(err));
    });

    function trackMapClick() {
        fetch("/api/places/{{ $lodging->id }}/track-map-click", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ category: "penginapan" })
        }).catch(err => console.error(err));
    }
</script>
@endsection
