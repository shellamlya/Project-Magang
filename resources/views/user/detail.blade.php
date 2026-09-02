@extends('layouts.app')

@php
    $serviceType = $place->category->service_type ?? 'penginapan';
    $bgColor = '#E1DAFB';
    $badgeClass = $serviceType == 'penginapan' ? 'badge-grex-penginapan' : ($serviceType == 'wisata' ? 'badge-grex-wisata' : 'badge-grex-nongkrong');
@endphp

@section('title', $place->name . ' - Lokavino')

@section('styles')
<style>
    body {
        background-color: {{ $bgColor }} !important;
    }
    .detail-card {
        background: #ffffff;
        border-radius: 24px;
        border: 1px solid rgba(117, 138, 209, 0.2);
        box-shadow: 0 15px 35px rgba(69, 12, 63, 0.06);
        overflow: hidden;
    }
    .detail-img {
        width: 100%;
        max-height: 420px;
        object-fit: cover;
    }
    .recommendation-panel {
        background: #ffffff;
        border-radius: 24px;
        border: 1px solid rgba(117, 138, 209, 0.2);
        padding: 1.5rem;
        box-shadow: 0 10px 30px rgba(69, 12, 63, 0.05);
    }
    .small-card-recommendation {
        border-radius: 16px;
        border: 1px solid rgba(117, 138, 209, 0.2);
        transition: all 0.2s ease;
        background: #fafafa;
    }
    .small-card-recommendation:hover {
        background: #ffffff;
        box-shadow: 0 8px 20px rgba(77, 62, 163, 0.12);
        transform: translateY(-3px);
    }
    .small-card-img {
        width: 90px;
        height: 90px;
        object-fit: cover;
        border-radius: 12px;
    }
    .owner-info-box {
        background: #f8f9fa;
        border-left: 4px solid #4D3EA3;
        border-radius: 12px;
        padding: 1.25rem;
    }
</style>
@endsection

@section('content')
<div class="container py-5">
    
    <!-- Tombol Kembali -->
    <div class="mb-4">
        <a href="{{ url()->previous() != url()->current() ? url()->previous() : route('home') }}" class="btn btn-outline-dark rounded-pill px-4 btn-sm fw-semibold">
            <i class="fa-solid fa-arrow-left me-2"></i> Kembali
        </a>
    </div>

    <div class="row g-4">
        
        <!-- ==========================================
             BAGIAN KIRI: Informasi Lengkap Tempat & Pemilik Usaha
             ========================================== -->
        <div class="col-lg-8">
            <div class="detail-card">
                <!-- Cover Photo -->
                <img src="{{ $place->thumbnail_url ?? ($place->cover_image ?? 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=800&q=80') }}" class="detail-img" alt="{{ $place->name }}">
                
                <div class="p-4 p-md-5">
                    <!-- Category & Badges -->
                    <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                        <span class="{{ $badgeClass }} fs-6 px-3 py-2">{{ $place->category->name }}</span>
                        <span class="badge bg-secondary px-3 py-2 rounded-pill fs-7">
                            <i class="fa-solid fa-location-dot me-1 text-warning"></i> Kec. {{ $place->district->name }}
                        </span>
                    </div>

                    <!-- Place Name -->
                    <h1 class="fw-extrabold text-dark mb-3">{{ $place->name }}</h1>

                    <!-- Core Info Meta Grid -->
                    <div class="row g-3 p-3 rounded-3 mb-4" style="background: #f8f9fa;">
                        <div class="col-sm-6">
                            <small class="text-muted d-block fw-semibold mb-1"><i class="fa-solid fa-map-marker-alt text-danger me-1"></i> Alamat Lengkap</small>
                            <span class="fw-bold text-dark small">{{ $place->address }} {{ $place->village ? ', Desa ' . $place->village->name : '' }}</span>
                        </div>
                        
                        @if($serviceType == 'penginapan')
                            <div class="col-sm-6">
                                <small class="text-muted d-block fw-semibold mb-1"><i class="fa-solid fa-tag text-primary me-1"></i> Kisaran Harga</small>
                                <span class="fw-bold text-primary fs-6">
                                    Rp {{ number_format($place->price_min, 0, ',', '.') }} - Rp {{ number_format($place->price_max, 0, ',', '.') }}
                                </span>
                            </div>
                        @else
                            <div class="col-sm-6">
                                <small class="text-muted d-block fw-semibold mb-1"><i class="fa-solid fa-clock text-danger me-1"></i> Jam Operasional</small>
                                <span class="fw-bold text-dark small">{{ $place->operating_hours ?? 'Setiap Hari' }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-align-left text-primary me-2"></i> Deskripsi Tempat</h5>
                        <p class="text-secondary leading-relaxed mb-0" style="white-space: pre-line;">{{ $place->description }}</p>
                    </div>

                    <!-- Facilities -->
                    @if($place->facilities->isNotEmpty())
                        <div class="mb-4">
                            <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-concierge-bell text-primary me-2"></i> Fasilitas</h5>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($place->facilities as $fac)
                                    <span class="badge bg-light text-dark border px-3 py-2 rounded-pill fw-semibold fs-7">
                                        <i class="fa-solid {{ $fac->icon ?? 'fa-check' }} text-success me-1"></i> {{ $fac->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Informasi Pemilik Usaha (Digabung menjadi satu section dengan deskripsi tempat) -->
                    <div class="owner-info-box mt-4">
                        <h6 class="fw-bold text-dark mb-2">
                            <i class="fa-solid fa-user-tie text-primary me-2"></i> Informasi Pemilik & Kontak Usaha
                        </h6>
                        <div class="row g-2 small text-secondary">
                            <div class="col-md-6">
                                <strong>Pemilik / Pengelola:</strong> {{ $place->owner->user->name ?? ($place->owner->company_name ?? 'Mitra Owner Lokavino') }}
                            </div>
                            @if($place->phone || $place->whatsapp)
                                <div class="col-md-6">
                                    <strong>Telepon / WhatsApp:</strong> {{ $place->whatsapp ?? $place->phone }}
                                </div>
                            @endif
                            @if($place->email)
                                <div class="col-md-6">
                                    <strong>Email:</strong> {{ $place->email }}
                                </div>
                            @endif
                            @if($place->gmaps_link)
                                <div class="col-md-6">
                                    <strong>Peta Lokasi:</strong> 
                                    <a href="{{ $place->gmaps_link }}" target="_blank" class="text-primary text-decoration-none fw-bold">
                                        <i class="fa-solid fa-arrow-up-right-from-square ms-1"></i> Buka Google Maps
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- ==========================================
             BAGIAN KANAN: Panel Rekomendasi Tempat Terdekat (Kategori Lain)
             ========================================== -->
        <div class="col-lg-4">
            <div class="recommendation-panel sticky-top" style="top: 90px;">
                <h5 class="fw-bold text-dark mb-1">
                    <i class="fa-solid fa-map-location-dot text-primary me-2"></i> Rekomendasi Terdekat
                </h5>
                <p class="small text-muted mb-4">Tempat menarik terdekat di Kecamatan {{ $place->district->name }}</p>

                <!-- Group 1 Rekomendasi -->
                <div class="mb-4">
                    <h6 class="fw-bold text-dark mb-3 border-bottom pb-2">
                        <i class="fa-solid fa-compass text-danger me-1"></i> {{ $group1Title }}
                    </h6>
                    @if($recommendationsGroup1->isEmpty())
                        <p class="small text-muted italic">Tidak ada rekomendasi terdekat.</p>
                    @else
                        <div class="d-flex flex-column gap-3">
                            @foreach($recommendationsGroup1 as $rec)
                                <div class="small-card-recommendation p-3 d-flex align-items-center gap-3">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h6 class="fw-bold text-dark mb-1 text-truncate fs-7" title="{{ $rec->name }}">{{ $rec->name }}</h6>
                                        <small class="text-muted d-block mb-2 fs-8">
                                            <i class="fa-solid fa-location-dot text-danger me-1"></i> Kec. {{ is_string($rec->district) ? $rec->district : ($rec->district->name ?? 'Gresik') }}
                                        </small>
                                        <a href="{{ route('wisata.detail', $rec->id) }}" class="btn btn-grex-primary btn-sm py-1 px-2 fs-8 rounded-pill">
                                            Lihat Detail
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Group 2 Rekomendasi -->
                <div>
                    <h6 class="fw-bold text-dark mb-3 border-bottom pb-2">
                        <i class="fa-solid fa-compass text-danger me-1"></i> {{ $group2Title }}
                    </h6>
                    @if($recommendationsGroup2->isEmpty())
                        <p class="small text-muted italic">Tidak ada rekomendasi terdekat.</p>
                    @else
                        <div class="d-flex flex-column gap-3">
                            @foreach($recommendationsGroup2 as $rec)
                                <div class="small-card-recommendation p-3 d-flex align-items-center gap-3">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h6 class="fw-bold text-dark mb-1 text-truncate fs-7" title="{{ $rec->name }}">{{ $rec->name }}</h6>
                                        <small class="text-muted d-block mb-2 fs-8">
                                            <i class="fa-solid fa-location-dot text-danger me-1"></i> Kec. {{ is_string($rec->district) ? $rec->district : ($rec->district->name ?? 'Gresik') }}
                                        </small>
                                        <a href="{{ route('nongkrong.detail', $rec->id) }}" class="btn btn-grex-primary btn-sm py-1 px-2 fs-8 rounded-pill">
                                            Lihat Detail
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
