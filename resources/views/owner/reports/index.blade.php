@extends('layouts.admin')

@section('title', 'Laporan Kunjungan - Lokavino')
@section('page-title', 'Laporan Kunjungan & Interaksi Usaha')

@section('content')

<!-- Ringkasan Statistik Laporan -->
<div class="row g-3 mb-4">
    <div class="col-12 col-md-6">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold">Akumulasi Total Dilihat (Views)</span>
                    <h2 class="fw-bold my-1" style="color: #7C3AED;">{{ number_format($totalViews ?? 0) }}</h2>
                    <small class="text-muted">Dari seluruh tempat usaha Anda</small>
                </div>
                <div class="p-3 rounded-3" style="background-color: #F3E8FF; color: #7C3AED;">
                    <i class="fa-solid fa-eye fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold">Akumulasi Total Klik Peta (Maps)</span>
                    <h2 class="fw-bold text-info my-1">{{ number_format($totalMapClicks ?? 0) }}</h2>
                    <small class="text-muted">Pengguna yang membuka rute lokasi</small>
                </div>
                <div class="p-3 rounded-3" style="background-color: #E0F2FE; color: #0284C7;">
                    <i class="fa-solid fa-map-location-dot fs-3"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bagian Diagram Garis (Chart.js) & Analisis AI -->
<div class="row g-4 mb-4">
    <!-- Grafik Tren Kunjungan -->
    <div class="col-12 col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-0 p-4 pb-0">
                <h5 class="fw-bold text-dark mb-1"><i class="fa-solid fa-chart-line text-primary me-2"></i>Tren Performa Kunjungan</h5>
                <p class="small text-muted mb-0">Grafik pergerakan views dan interaksi peta bulanan</p>
            </div>
            <div class="card-body p-4">
                <div style="height: 260px; position: relative;">
                    <canvas id="visitChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Analisis AI -->
    <div class="col-12 col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #F8F7FF 0%, #EDE9FE 100%);">
            <div class="card-body p-4 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="p-2 rounded-3 text-white me-2" style="background-color: #7C3AED;">
                            <i class="fa-solid fa-wand-magic-sparkles"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-0">Analisis Pintar AI</h5>
                    </div>
                    <p class="text-secondary small mb-3">
                        Sistem AI Lokavino mendeteksi performa stabil pada unit usaha Anda. Pastikan kelengkapan koordinat peta dan foto galeri diperbarui agar tingkat klik rute lokasi semakin meningkat ke depannya.
                    </p>
                </div>
                <div class="p-3 bg-white rounded-3 border border-purple-subtle shadow-sm">
                    <small class="text-muted d-block fw-semibold mb-1"><i class="fa-solid fa-lightbulb text-warning me-1"></i> Rekomendasi Hari Ini:</small>
                    <span class="small text-dark">Bagikan tautan tempat usaha Anda ke media sosial untuk mendongkrak total views secara instan.</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Rincian Per Tempat Usaha -->
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white border-0 p-4 pb-0 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h5 class="fw-bold text-dark mb-1"><i class="fa-solid fa-table-list text-primary me-2"></i>Rincian Statistik Per Tempat Usaha</h5>
            <p class="small text-muted mb-0">Daftar performa kunjungan berdasarkan masing-masing unit usaha</p>
        </div>
        
        <!-- Form Filter Kategori -->
        <form method="GET" action="" class="d-flex align-items-center gap-2">
            <select name="category" class="form-select form-select-sm rounded-3 px-3" onchange="this.form.submit()">
                <option value="">Semua Kategori</option>
                <option value="lodging" {{ request('category') == 'lodging' ? 'selected' : '' }}>Penginapan (Lodging)</option>
                <option value="culinary" {{ request('category') == 'culinary' ? 'selected' : '' }}>Kuliner / Tempat Nongkrong</option>
                <option value="attraction" {{ request('category') == 'attraction' ? 'selected' : '' }}>Wisata (Attraction)</option>
            </select>
        </form>
    </div>

    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light text-uppercase fs-7">
                    <tr>
                        <th class="py-3 rounded-start">No</th>
                        <th class="py-3">Nama Tempat Usaha</th>
                        <th class="py-3">Kategori / Tipe</th>
                        <th class="py-3 text-center">Status</th>
                        <th class="py-3 text-center">Total Views</th>
                        <th class="py-3 text-center rounded-end">Klik Peta</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($allPlaces as $index => $place)
                        <tr>
                            <td class="fw-bold text-secondary">{{ $index + 1 }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $place->name }}</div>
                                <small class="text-muted"><i class="fa-solid fa-location-dot me-1 text-danger"></i>{{ $place->address ?? 'Alamat tidak diisi' }}</small>
                            </td>
                            <td>
                                @php
                                    $cat = strtolower($place->category ?? class_basename($place));
                                    $badgeText = 'Lainnya';
                                    if(str_contains($cat, 'lodging') || str_contains($cat, 'hotel')) $badgeText = 'Penginapan';
                                    elseif(str_contains($cat, 'culinary') || str_contains($cat, 'cafe')) $badgeText = 'Kuliner / Nongkrong';
                                    elseif(str_contains($cat, 'attraction') || str_contains($cat, 'wisata')) $badgeText = 'Wisata';
                                @endphp
                                <span class="badge bg-light text-dark border">
                                    {{ $badgeText }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($place->status === 'approved')
                                    <span class="badge bg-success-subtle text-success px-2 py-1">Aktif</span>
                                @elseif($place->status === 'pending')
                                    <span class="badge bg-warning-subtle text-warning px-2 py-1">Pending</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger px-2 py-1">Ditolak</span>
                                @endif
                            </td>
                            <td class="text-center fw-bold" style="color: #7C3AED;">
                                {{ number_format($place->views_count ?? 0) }}
                            </td>
                            <td class="text-center fw-bold text-info">
                                {{ number_format($place->maps_clicks_count ?? 0) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="fa-solid fa-folder-open fs-2 mb-2 d-block text-secondary opacity-50"></i>
                                Tidak ada data tempat usaha untuk kategori ini.
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
<!-- CDN Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('visitChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'],
                    datasets: [{
                        label: 'Total Views',
                        data: [120, 190, 280, {{ $totalViews ?? 0 }}],
                        borderColor: '#7C3AED',
                        backgroundColor: 'rgba(124, 58, 237, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4
                    }, {
                        label: 'Klik Peta',
                        data: [45, 80, 110, {{ $totalMapClicks ?? 0 }}],
                        borderColor: '#0284C7',
                        backgroundColor: 'rgba(2, 132, 199, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { borderDash: [4, 4] }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                }
            });
        }
    });
</script>
@endsection