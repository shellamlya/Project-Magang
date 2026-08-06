@extends('layouts.app')

@section('title', 'Daftar Tempat Nongkrong (Kunti) - GREX Gresik Explore')

@section('styles')
<style>
    body {
        background-color: #E1DAFB !important;
    }
    .header-category {
        background: #ffffff;
        border-radius: 24px;
        padding: 2.5rem 2rem;
        box-shadow: 0 10px 25px rgba(69, 12, 63, 0.05);
        border-left: 8px solid #758AD1;
    }
    .card-hangout {
        border: 1px solid rgba(117, 138, 209, 0.2);
        border-radius: 20px;
        background: #ffffff;
        box-shadow: 0 10px 25px rgba(69, 12, 63, 0.04);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .card-hangout:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(117, 138, 209, 0.2);
    }
    .badge-facility-hangout {
        background-color: #FFD2F4;
        color: #450C3F;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.35rem 0.65rem;
        border-radius: 12px;
    }
</style>
@endsection

@section('content')
<div class="container py-5">
    
    <!-- Header Kategori Nongkrong -->
    <div class="header-category mb-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <span class="badge-grex-kunti text-uppercase mb-2 d-inline-block">Layanan Nongkrong (Kunti)</span>
                <h1 class="fw-extrabold text-dark display-6 mb-2">Daftar Tempat Nongkrong Gresik</h1>
                <p class="text-muted mb-0">Temukan 20 pilihan coffee shop, café aesthetic, tempat bersantai, resto keluarga, dan tempat nongkrong terfavorit di Kabupaten Gresik.</p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <a href="{{ route('home') }}" class="btn btn-outline-dark rounded-pill px-4 fw-semibold btn-sm">
                    <i class="fa-solid fa-search me-1"></i> Ke Pusat Pencarian
                </a>
            </div>
        </div>
    </div>

    <!-- Filter Kecamatan & Pencarian -->
    <div class="card border-0 shadow-sm rounded-4 p-3 mb-4" style="background: #ffffff;">
        <form action="{{ route('nongkrong') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-5">
                <select name="district" class="form-select rounded-3">
                    <option value="">-- Semua Kecamatan --</option>
                    @foreach($districts as $dist)
                        <option value="{{ $dist->name }}" {{ request('district') == $dist->name ? 'selected' : '' }}>
                            Kecamatan {{ $dist->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-5">
                <input type="text" name="search" class="form-control rounded-3" placeholder="Cari nama tempat nongkrong atau pengelola..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2 d-grid">
                <button type="submit" class="btn btn-grex-primary rounded-3 fw-bold">
                    <i class="fa-solid fa-filter me-1"></i> Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Grid Card Nongkrong -->
    @if($places->isEmpty())
        <div class="card border-0 shadow-sm rounded-4 text-center py-5" style="background: #ffffff;">
            <i class="fa-solid fa-mug-hot fa-3x text-muted opacity-50 mb-3"></i>
            <h5 class="fw-bold text-muted mb-1">Belum Ada Data Tempat Nongkrong</h5>
            <p class="small text-muted mb-0">Belum terdapat data tempat nongkrong untuk kriteria filter yang dipilih.</p>
        </div>
    @else
        <div class="row g-4">
            @foreach($places as $place)
                <div class="col-md-4">
                    <div class="card card-hangout h-100 p-4 d-flex flex-column">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="badge-grex-kunti"><i class="fa-solid fa-mug-hot me-1"></i> Nongkrong</span>
                            <small class="text-muted fw-semibold">
                                <i class="fa-solid fa-location-dot me-1 text-danger"></i> {{ $place->district ?? 'Gresik' }}
                            </small>
                        </div>
                        
                        <h4 class="fw-bold text-dark mb-2">{{ $place->name }}</h4>
                        <p class="small text-muted mb-3 flex-grow-1">{{ Str::limit($place->description, 110) }}</p>

                        <!-- Jam Operasional & Pengelola -->
                        <div class="mb-3 p-3 bg-light rounded-3 small">
                            <div class="d-flex align-items-center mb-1 text-secondary">
                                <i class="fa-regular fa-clock me-2 text-primary"></i>
                                <span class="fw-medium">{{ $place->operational_hours }}</span>
                            </div>
                            <div class="d-flex align-items-center text-secondary">
                                <i class="fa-solid fa-user-gear me-2 text-primary"></i>
                                <span>Pengelola: <strong>{{ $place->manager_name }}</strong></span>
                            </div>
                        </div>

                        <!-- Fasilitas Badges -->
                        @if($place->facilities->isNotEmpty())
                            <div class="d-flex flex-wrap gap-1 mb-3">
                                @foreach($place->facilities->take(3) as $fac)
                                    <span class="badge-facility-hangout">{{ $fac->facility_name }}</span>
                                @endforeach
                                @if($place->facilities->count() > 3)
                                    <span class="badge-facility-hangout">+{{ $place->facilities->count() - 3 }} lainnya</span>
                                @endif
                            </div>
                        @endif

                        <div class="d-flex align-items-center justify-content-between pt-3 border-top mt-auto">
                            <div>
                                <small class="text-muted d-block fs-7">Telepon</small>
                                <span class="fw-bold text-dark small">{{ $place->phone ?? 'Tidak Ada' }}</span>
                            </div>
                            <a href="{{ route('nongkrong.detail', $place->id) }}" class="btn btn-grex-primary btn-sm px-3 rounded-pill">
                                Lihat Detail <i class="fa-solid fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-5 d-flex justify-content-center">
            {{ $places->links() }}
        </div>
    @endif

</div>
@endsection
