@extends('layouts.app')

@section('title', 'Daftar Wisata - Lokavino')

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
        border-left: 8px solid #4D3EA3;
    }
    .card-wisata {
        border: 1px solid rgba(117, 138, 209, 0.2);
        border-radius: 20px;
        background: #ffffff;
        box-shadow: 0 10px 25px rgba(69, 12, 63, 0.04);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .card-wisata:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(77, 62, 163, 0.15);
    }
    .badge-facility {
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
    
    <!-- Header Kategori Wisata -->
    <div class="header-category mb-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <span class="badge-grex-wisata text-uppercase mb-2 d-inline-block">Layanan Wisata</span>
                <h1 class="fw-extrabold text-dark display-6 mb-2">Daftar Destinasi Wisata Gresik</h1>
                <p class="text-muted mb-0">Jelajahi berbagai pilihan objek wisata di Kabupaten Gresik mulai dari wahana air, wisata alam, bukit kapur, hingga situs religi bersejarah.</p>
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
        <form action="{{ route('wisata') }}" method="GET" class="row g-2 align-items-center">
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
                <input type="text" name="search" class="form-control rounded-3" placeholder="Cari nama tempat wisata atau pengelola..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2 d-grid">
                <button type="submit" class="btn btn-grex-primary rounded-3 fw-bold">
                    <i class="fa-solid fa-filter me-1"></i> Filter Wisata
                </button>
            </div>
        </form>
    </div>

    <!-- Grid Card Wisata -->
    @if($places->isEmpty())
        <div class="card border-0 shadow-sm rounded-4 text-center py-5" style="background: #ffffff;">
            <i class="fa-solid fa-mountain-sun fa-3x text-muted opacity-50 mb-3"></i>
            <h5 class="fw-bold text-muted mb-1">Belum Ada Data Wisata</h5>
            <p class="small text-muted mb-0">Belum terdapat data tempat wisata untuk kriteria filter yang dipilih.</p>
        </div>
    @else
        <div class="row g-4">
            @foreach($places as $place)
                <div class="col-md-4">
                    <div class="card card-wisata h-100 p-4 d-flex flex-column">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="badge-grex-wisata"><i class="fa-solid fa-compass me-1"></i> Wisata</span>
                            <small class="text-muted fw-semibold">
                                <i class="fa-solid fa-location-dot me-1 text-danger"></i> {{ $place->district ?? 'Gresik' }}
                            </small>
                        </div>
                        
                        <h4 class="fw-bold text-dark mb-2">{{ $place->name }}</h4>
                        <p class="small text-muted mb-3 flex-grow-1">{{ Str::limit($place->description, 110) }}</p>

                        <!-- Jam Operasional & Pengelola -->
                        <div class="mb-3 p-3 bg-light rounded-3 small">
                            <div class="d-flex align-items-center mb-1 text-secondary">
                                <i class="fa-regular fa-clock me-2 text-danger"></i>
                                <span class="fw-medium">{{ $place->operational_hours }}</span>
                            </div>
                            <div class="d-flex align-items-center text-secondary">
                                <i class="fa-solid fa-user-gear me-2 text-danger"></i>
                                <span>Pengelola: <strong>{{ $place->manager_name }}</strong></span>
                            </div>
                        </div>

                        <!-- Fasilitas Badges -->
                        @if($place->facilities->isNotEmpty())
                            <div class="d-flex flex-wrap gap-1 mb-3">
                                @foreach($place->facilities->take(3) as $fac)
                                    <span class="badge-facility">{{ $fac->facility_name }}</span>
                                @endforeach
                                @if($place->facilities->count() > 3)
                                    <span class="badge-facility">+{{ $place->facilities->count() - 3 }} lainnya</span>
                                @endif
                            </div>
                        @endif

                        <div class="d-flex align-items-center justify-content-between pt-3 border-top mt-auto">
                            <div>
                                <span class="small text-muted d-block fs-7">Tiket Masuk</span>
                                <span class="fw-bold text-success">{{ $place->ticket_price ?? 'Gratis' }}</span>
                            </div>
                            <a href="{{ route('wisata.detail', $place->id) }}" class="btn btn-grex-primary btn-sm px-3 rounded-pill">
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
