@extends('layouts.admin')

@section('title', 'Dashboard Admin - Lokavino')
@section('page-title', 'Dashboard Administrator Lokavino')

@section('content')
<!-- Stat Cards (Kotak Statistik Utama - Proporsional) -->
<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-5 g-3 mb-4">
    
    <!-- 1. Owner -->
    <div class="col">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted fw-semibold small">Owner</span>
                <div class="icon-box bg-primary bg-opacity-10 text-primary p-2 rounded-3">
                    <i class="fa-solid fa-user-tie"></i>
                </div>
            </div>
            <h4 class="fw-extrabold text-primary mb-0">{{ $totalOwners ?? 0 }}</h4>
        </div>
    </div>

    <!-- 2. Nongkrong -->
    <div class="col">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted fw-semibold small">Nongkrong</span>
                <div class="icon-box bg-warning bg-opacity-10 text-warning p-2 rounded-3">
                    <i class="fa-solid fa-mug-hot"></i>
                </div>
            </div>
            <h4 class="fw-extrabold text-warning mb-0">{{ $totalHangouts ?? 0 }}</h4>
        </div>
    </div>

    <!-- 3. Penginapan -->
    <div class="col">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted fw-semibold small">Penginapan</span>
                <div class="icon-box bg-info bg-opacity-10 text-info p-2 rounded-3">
                    <i class="fa-solid fa-hotel"></i>
                </div>
            </div>
            <h4 class="fw-extrabold text-info mb-0">{{ $totalLodgings ?? 0 }}</h4>
        </div>
    </div>

    <!-- 4. Wisata -->
    <div class="col">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted fw-semibold small">Wisata</span>
                <div class="icon-box bg-success bg-opacity-10 text-success p-2 rounded-3">
                    <i class="fa-solid fa-compass"></i>
                </div>
            </div>
            <h4 class="fw-extrabold text-success mb-0">{{ $totalTours ?? 0 }}</h4>
        </div>
    </div>

    <!-- 5. Total Viewers -->
    <div class="col">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted fw-semibold small">Total Viewers</span>
                <div class="icon-box bg-danger bg-opacity-10 text-danger p-2 rounded-3">
                    <i class="fa-solid fa-eye"></i>
                </div>
            </div>
            <h4 class="fw-extrabold text-danger mb-0">{{ $totalViews ?? 0 }}</h4>
        </div>
    </div>

</div> <!-- PENTING: Pastikan div penutup ini ADA untuk menutup barisan kotak statistik -->


<!-- ========================================= -->
<!-- KODE GRAFIK KAMU SEHARUSNYA MULAI DI SINI -->
<!-- ========================================= -->
<div class="row">
    <!-- Kode pembungkus Grafik Pengajuan Tempat Usaha Per Bulan ... -->


<!-- Grafik Visualisasi (Bar Chart Bulanan & Doughnut Sebaran Kecamatan) -->
<!-- Bagian Grafik -->
<div class="row mb-4">
    <div class="col-12"> <!-- Ubah dari col-md-8 / col-lg-8 menjadi col-12 agar full melebar -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <h5 class="fw-bold mb-3"><i class="fa-solid fa-chart-bar me-2"></i> Grafik Pengajuan Tempat Usaha Per Bulan</h5>
            
            <!-- Elemen Chart -->
            <div style="position: relative; height: 350px; width: 100%;">
                <canvas id="myChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- 3 Donut Chart Sebaran Kecamatan per Kategori -->
<div class="row g-4 mb-4">
    <!-- Donut 1: Penginapan -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
            <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-hotel text-primary me-2"></i>Sebaran Penginapan per Kecamatan</h6>
            <!-- Batasi tinggi container dan gunakan flex column agar canvas dan legend rapi -->
            <div style="position: relative; height: 280px; width: 100%;">
                <canvas id="lodgingDistrictChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Donut 2: Tempat Nongkrong -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
            <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-mug-hot text-info me-2"></i>Sebaran Nongkrong per Kecamatan</h6>
            <div style="position: relative; height: 280px; width: 100%;">
                <canvas id="hangoutDistrictChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Donut 3: Wisata -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
            <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-mountain-sun text-success me-2"></i>Sebaran Wisata per Kecamatan</h6>
            <div style="position: relative; height: 280px; width: 100%;">
                <canvas id="touristDistrictChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Penginapan Terbaru -->
<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
    <div class="card-header bg-white p-4 border-bottom d-flex justify-content-between align-items-center">
        <h6 class="fw-bold text-dark mb-0"><i class="fa-solid fa-list-check text-primary me-2"></i>Pengajuan Penginapan Terbaru</h6>
        <a href="{{ route('admin.verifications.index') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">Lihat Semua Verifikasi</a>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3">Nama Penginapan</th>
                        <th class="py-3">Owner</th>
                        <th class="py-3">Kecamatan</th>
                        <th class="py-3">Tanggal Pengajuan</th>
                        <th class="py-3">Status</th>
                        <th class="text-end pe-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentLodgings ?? [] as $item)
                        <tr>
                            <td class="ps-4">
                                <h6 class="fw-bold text-dark mb-0">{{ $item->name }}</h6>
                            </td>
                            <td>{{ $item->owner->company_name ?? ($item->owner->user->name ?? 'Admin System') }}</td>
                            <td>
                                <span class="badge bg-light text-primary border small">Kec. {{ $item->district ?? 'Gresik' }}</span>
                            </td>
                            <td>{{ $item->created_at->format('d M Y, H:i') }}</td>
                            <td>
                                @if($item->status === 'approved')
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1">Disetujui</span>
                                @elseif($item->status === 'pending')
                                    <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-1">Pending</span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-1">Ditolak</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('admin.verifications.show', $item->id) }}" class="btn btn-primary btn-sm rounded-pill px-3 fw-bold">
                                    Verifikasi <i class="fa-solid fa-arrow-right ms-1"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Belum ada pengajuan penginapan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // 1. Grafik Batang Per Bulan (3 Kategori)
    const ctx = document.getElementById('myChart').getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($monthlyLabels) !!},
            datasets: [
                {
                    label: 'Penginapan',
                    data: {!! json_encode($lodgingMonthlyCounts) !!},
                    backgroundColor: '#4e73df'
                },
                {
                    label: 'Tempat Nongkrong',
                    data: {!! json_encode($hangoutMonthlyCounts) !!},
                    backgroundColor: '#36b9cc'
                },
                {
                    label: 'Wisata',
                    data: {!! json_encode($touristMonthlyCounts) !!},
                    backgroundColor: '#f6c23e'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 2
                    }
                }
            }
        }
    });

    const colorPalette = ['#4D3EA3', '#38bdf8', '#facc15', '#f87171', '#34d399', '#a78bfa', '#fb923c'];

    // Konfigurasi umum agar semua Donut Chart ukurannya pas dan konsisten
    const donutOptions = {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '65%', // Mengatur ketebalan lubang tengah donut
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    boxWidth: 12,
                    font: {
                        size: 11
                    }
                }
            }
        }
    };

    // 2. Donut Chart Sebaran Penginapan per Kecamatan
    const ctxLodgingDist = document.getElementById('lodgingDistrictChart');
    if (ctxLodgingDist) {
        new Chart(ctxLodgingDist.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($lodgingDistrictLabels) !!},
                datasets: [{
                    data: {!! json_encode($lodgingDistrictCounts) !!},
                    backgroundColor: colorPalette,
                }]
            },
            options: donutOptions
        });
    }

    // 3. Donut Chart Sebaran Tempat Nongkrong per Kecamatan
    const ctxHangoutDist = document.getElementById('hangoutDistrictChart');
    if (ctxHangoutDist) {
        new Chart(ctxHangoutDist.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($hangoutDistrictLabels) !!},
                datasets: [{
                    data: {!! json_encode($hangoutDistrictCounts) !!},
                    backgroundColor: colorPalette,
                }]
            },
            options: donutOptions
        });
    }

    // 4. Donut Chart Sebaran Wisata per Kecamatan
    const ctxTouristDist = document.getElementById('touristDistrictChart');
    if (ctxTouristDist) {
        new Chart(ctxTouristDist.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($touristDistrictLabels) !!},
                datasets: [{
                    data: {!! json_encode($touristDistrictCounts) !!},
                    backgroundColor: colorPalette,
                }]
            },
            options: donutOptions
        });
    }
</script>
@endsection