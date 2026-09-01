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

    <div class="row g-4">
        <!-- Kolom Utama -->
        <div class="col-lg-8">
            <div class="detail-card p-4 p-md-5 mb-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="badge-nongkrong-lg"><i class="fa-solid fa-mug-hot me-1"></i> Tempat Nongkrong</span>
                    <span class="badge bg-success-subtle text-success fs-6 border border-success-subtle rounded-pill px-3 py-2">
                        <i class="fa-solid fa-circle-check me-1"></i> Terverifikasi
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
                
                @if($hangoutPlace->address)
                    <div class="mb-3">
                        <small class="text-muted d-block fw-semibold">Alamat Lengkap</small>
                        <span class="small text-dark">{{ $hangoutPlace->address }}</span>
                    </div>
                @endif

                @if($hangoutPlace->phone)
                    <div class="mb-3">
                        <small class="text-muted d-block fw-semibold">Nomor Telepon</small>
                        <span class="small text-dark"><i class="fa-solid fa-phone me-1 text-success"></i> {{ $hangoutPlace->phone }}</span>
                    </div>
                @endif

                @if($hangoutPlace->email)
                    <div class="mb-3">
                        <small class="text-muted d-block fw-semibold">Email</small>
                        <span class="small text-dark"><i class="fa-solid fa-envelope me-1 text-info"></i> {{ $hangoutPlace->email }}</span>
                    </div>
                @endif

                <div class="d-grid mt-4">
                    <a href="{{ $hangoutPlace->google_maps }}" target="_blank" class="btn btn-grex-primary rounded-pill py-2.5 fw-bold" onclick="trackMapClick()">
                        <i class="fa-solid fa-map-pin me-1"></i> Buka di Google Maps
                    </a>
                </div>
            </div>

            <!-- Tempat Nongkrong Lainnya -->
            @if(isset($otherNongkrong) && $otherNongkrong->isNotEmpty())
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                    <h6 class="fw-bold text-dark mb-3">Tempat Nongkrong Lainnya</h6>
                    <div class="d-flex flex-column gap-3">
                        @foreach($otherNongkrong as $other)
                            <a href="{{ route('nongkrong.detail', $other->id) }}" class="text-decoration-none">
                                <div class="p-3 bg-light rounded-3">
                                    <div class="fw-bold text-dark small">{{ $other->name }}</div>
                                    <small class="text-muted d-block"><i class="fa-solid fa-location-dot text-danger me-1"></i>{{ $other->district }}</small>
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
        fetch("/api/places/{{ $hangoutPlace->id }}/track-view", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ category: "nongkrong" })
        }).catch(err => console.error(err));
    });

    function trackMapClick() {
        fetch("/api/places/{{ $hangoutPlace->id }}/track-map-click", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ category: "nongkrong" })
        }).catch(err => console.error(err));
    }
</script>
@endsection
