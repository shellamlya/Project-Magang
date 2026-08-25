@extends('layouts.admin')

@section('title', 'Klaim Tempat Usaha - Owner GREX')
@section('page-title', 'Klaim Tempat Usaha')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
    <a href="{{ route('owner.dashboard') }}" class="btn btn-outline-secondary rounded-pill btn-sm">
        <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Dashboard
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4">
        <h5 class="fw-bold text-dark mb-1"><i class="fa-solid fa-hand-holding-hand text-primary me-2"></i>Klaim Tempat Usaha Anda dari Data Resmi Gresik</h5>
        <p class="small text-muted mb-3">Apakah tempat usaha Anda (Hotel, Resto/Warkop, atau Tempat Wisata) sudah terdaftar dalam data awal Disparekrafbudpora Gresik? Cari nama usaha Anda di bawah dan ajukan klaim kepemilikan.</p>

        <form action="{{ route('owner.claim.index') }}" method="GET" class="row g-2">
            <div class="col-md-9">
                <input type="text" name="search" class="form-control rounded-pill px-4" value="{{ $search }}" placeholder="Cari nama usaha atau lokasi (misal: Sunan Giri, Warkop Giras, KHAS Hotel)...">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary rounded-pill w-100 fw-bold">
                    <i class="fa-solid fa-magnifying-glass me-1"></i> Cari Usaha
                </button>
            </div>
        </form>
    </div>
</div>

<div class="row g-3">
    @forelse($unclaimedPlaces as $place)
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1">
                                {{ $place->category_label }}
                            </span>
                            @if($place->is_verified_official)
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1 small">
                                    <i class="fa-solid fa-check-double me-1"></i> Resmi Dinas
                                </span>
                            @endif
                        </div>
                        <h5 class="fw-bold text-dark mb-1">{{ $place->name }}</h5>
                        <p class="small text-muted mb-2"><i class="fa-solid fa-location-dot text-danger me-1"></i>Kec. {{ $place->district ?? 'Gresik' }}</p>
                        <p class="small text-secondary mb-3">{{ Str::limit($place->description, 100) }}</p>
                    </div>

                    <form action="{{ route('owner.claim.submit', ['category' => $place->place_category, 'id' => $place->id]) }}" method="POST" onsubmit="return confirm('Klaim kepemilikan untuk usaha ini?')">
                        @csrf
                        <button type="submit" class="btn btn-outline-primary btn-sm rounded-pill w-100 fw-bold">
                            <i class="fa-solid fa-hand-pointer me-1"></i> Klaim Kepemilikan Usaha Ini
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5 bg-white rounded-4 shadow-sm">
            <i class="fa-solid fa-building-circle-check display-5 text-muted opacity-25 d-block mb-3"></i>
            <h6 class="fw-bold text-dark">Tidak Ada Tempat Usaha Unclaimed Didapatkan</h6>
            <p class="small text-muted">Semua tempat usaha dari data awal telah diklaim atau pencarian Anda tidak menemukan hasil.</p>
        </div>
    @endforelse
</div>
@endsection
