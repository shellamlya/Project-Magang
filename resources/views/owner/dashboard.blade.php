@extends('layouts.admin')

@section('title', 'Dashboard Owner - Lokavino')
@section('page-title', 'Dashboard Owner & Statistik Analitik')

@section('content')
<!-- Stat Cards Ringkasan -->
<div class="row g-3 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="stat-card d-flex align-items-center justify-content-between">
            <div>
                <small class="text-muted d-block fw-semibold mb-1">Total Tempat Usaha</small>
                <h3 class="fw-extrabold text-dark mb-0">{{ $totalPlaces }}</h3>
            </div>
            <div class="icon-box bg-primary bg-opacity-10 text-primary">
                <i class="fa-solid fa-store"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="stat-card d-flex align-items-center justify-content-between">
            <div>
                <small class="text-muted d-block fw-semibold mb-1">Disetujui (Approved)</small>
                <h3 class="fw-extrabold text-success mb-0">{{ $totalApproved }}</h3>
            </div>
            <div class="icon-box bg-success bg-opacity-10 text-success">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="stat-card d-flex align-items-center justify-content-between">
            <div>
                <small class="text-muted d-block fw-semibold mb-1">Total View Pengunjung</small>
                <h3 class="fw-extrabold text-info mb-0">{{ number_format($totalViews) }}</h3>
                <small class="text-muted">Bulan ini</small>
            </div>
            <div class="icon-box bg-info bg-opacity-10 text-info">
                <i class="fa-solid fa-eye"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="stat-card d-flex align-items-center justify-content-between">
            <div>
                <small class="text-muted d-block fw-semibold mb-1">Klik Petunjuk Arah</small>
                <h3 class="fw-extrabold text-danger mb-0">{{ number_format($totalMapClicks) }}</h3>
                <small class="text-muted">Google Maps bulan ini</small>
            </div>
            <div class="icon-box bg-danger bg-opacity-10 text-danger">
                <i class="fa-solid fa-map-location-dot"></i>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Analitik Section -->
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-header bg-white border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
        <div>
            <h5 class="fw-bold text-dark mb-0"><i class="fa-solid fa-chart-line text-primary me-2"></i>Statistik Minat Pengunjung & Akses Lokasi (Bulan Ini)</h5>
            <small class="text-muted">Grafik perbandingan total view detail dan penekanan tombol Google Maps per tempat usaha</small>
        </div>
    </div>
    <div class="card-body p-4">
        <canvas id="ownerAnalyticsChart" height="100"></canvas>
    </div>
</div>

<!-- Modal Category Selection -->
@include('owner.lodgings.partials._category_modal')

<!-- Header Action -->
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3 bg-white rounded-4">
        <div>
            <h5 class="fw-bold text-dark mb-1"><i class="fa-solid fa-list-check text-primary me-2"></i>Kelola Seluruh Tempat Usaha Anda</h5>
            <p class="small text-muted mb-0">Pantau status verifikasi dan analitik performa tempat usaha Anda di Gresik</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('owner.claim.index') }}" class="btn btn-outline-primary rounded-pill px-4 fw-bold">
                <i class="fa-solid fa-hand-holding-hand me-1"></i> Klaim Tempat Usaha
            </a>
            <button type="button" class="btn btn-primary rounded-pill px-4 fw-bold" data-bs-toggle="modal" data-bs-target="#categoryModal">
                <i class="fa-solid fa-plus me-1"></i> Ajukan Usaha Baru
            </button>
        </div>
    </div>
</div>

<!-- Table Places -->
<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3">Tempat Usaha</th>
                        <th class="py-3">Kategori</th>
                        <th class="py-3">Kecamatan</th>
                        <th class="py-3">Statistik Pengunjung</th>
                        <th class="py-3">Status Pengajuan</th>
                        <th class="text-end pe-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($allPlaces as $place)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark">{{ $place->name }}</div>
                                <small class="text-muted">{{ Str::limit($place->address, 45) }}</small>
                            </td>
                            <td>
                                <span class="badge {{ $place->category_badge_class }} rounded-pill px-3 py-1">
                                    {{ $place->category_label }}
                                </span>
                            </td>
                            <td>Kec. {{ $place->district ?? 'Gresik' }}</td>
                            <td>
                                <span class="badge bg-light text-dark border me-1">
                                    <i class="fa-solid fa-eye text-info me-1"></i>{{ number_format($place->views_count ?? 0) }}
                                </span>
                                <span class="badge bg-light text-dark border">
                                    <i class="fa-solid fa-location-dot text-danger me-1"></i>{{ number_format($place->maps_clicks_count ?? 0) }}
                                </span>
                            </td>
                            <td>
                                @if($place->status === 'approved')
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 fw-semibold">
                                        <i class="fa-solid fa-check-circle me-1"></i> Disetujui
                                    </span>
                                @elseif($place->status === 'pending')
                                    <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-2 fw-semibold">
                                        <i class="fa-solid fa-clock me-1"></i> Pending
                                    </span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2 fw-semibold">
                                        <i class="fa-solid fa-times-circle me-1"></i> Ditolak
                                    </span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('owner.lodgings.edit', ['lodging' => $place->id, 'category' => $place->place_category]) }}" class="btn btn-light btn-sm rounded-circle me-1" title="Edit"><i class="fa-solid fa-pen text-primary"></i></a>
                                <form action="{{ route('owner.lodgings.destroy', ['lodging' => $place->id, 'category' => $place->place_category]) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data tempat usaha ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-light btn-sm rounded-circle" title="Hapus"><i class="fa-solid fa-trash text-danger"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-folder-open display-6 d-block mb-2 opacity-25"></i>
                                Anda belum memiliki tempat usaha terdaftar. Klik "Ajukan Usaha Baru" untuk memulai.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('ownerAnalyticsChart').getContext('2d');
        const labels = {!! json_encode($analyticsLabels) !!};
        const viewsData = {!! json_encode($analyticsViewsData) !!};
        const mapsData = {!! json_encode($analyticsMapsData) !!};

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels.length > 0 ? labels : ['Belum ada data'],
                datasets: [
                    {
                        label: 'Total View Pengunjung',
                        data: viewsData.length > 0 ? viewsData : [0],
                        backgroundColor: 'rgba(13, 202, 240, 0.7)',
                        borderColor: '#0dcaf0',
                        borderWidth: 1,
                        borderRadius: 6
                    },
                    {
                        label: 'Klik Petunjuk Arah / Google Maps',
                        data: mapsData.length > 0 ? mapsData : [0],
                        backgroundColor: 'rgba(220, 53, 69, 0.7)',
                        borderColor: '#dc3545',
                        borderWidth: 1,
                        borderRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0 }
                    }
                },
                plugins: {
                    legend: { position: 'top' }
                }
            }
        });
    });
</script>
@endsection
