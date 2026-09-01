@extends('layouts.admin')

@section('title', 'Dashboard Admin - Lokavino')
@section('page-title', 'Dashboard Administrator Lokavino')

@section('content')
<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-2-4 col-sm-6">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <small class="text-muted fw-semibold">Jumlah Owner</small>
                <div class="icon-box bg-primary bg-opacity-10 text-primary"><i class="fa-solid fa-store"></i></div>
            </div>
            <h3 class="fw-extrabold text-dark mb-0">{{ $totalOwners }}</h3>
        </div>
    </div>

    <div class="col-md-2-4 col-sm-6">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <small class="text-muted fw-semibold">Total Penginapan</small>
                <div class="icon-box bg-info bg-opacity-10 text-info"><i class="fa-solid fa-hotel"></i></div>
            </div>
            <h3 class="fw-extrabold text-dark mb-0">{{ $totalLodgings }}</h3>
        </div>
    </div>

    <div class="col-md-2-4 col-sm-6">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <small class="text-muted fw-semibold">Pending (Verifikasi)</small>
                <div class="icon-box bg-warning bg-opacity-10 text-warning"><i class="fa-solid fa-clock"></i></div>
            </div>
            <h3 class="fw-extrabold text-warning mb-0">{{ $totalPending }}</h3>
        </div>
    </div>

    <div class="col-md-2-4 col-sm-6">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <small class="text-muted fw-semibold">Approved (Disetujui)</small>
                <div class="icon-box bg-success bg-opacity-10 text-success"><i class="fa-solid fa-circle-check"></i></div>
            </div>
            <h3 class="fw-extrabold text-success mb-0">{{ $totalApproved }}</h3>
        </div>
    </div>

    <div class="col-md-2-4 col-sm-6">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <small class="text-muted fw-semibold">Rejected (Ditolak)</small>
                <div class="icon-box bg-danger bg-opacity-10 text-danger"><i class="fa-solid fa-circle-xmark"></i></div>
            </div>
            <h3 class="fw-extrabold text-danger mb-0">{{ $totalRejected }}</h3>
        </div>
    </div>
</div>

<!-- Chart Visualizations -->
<div class="row g-4 mb-4">
    <!-- Bar Chart Pengajuan per Bulan -->
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold text-dark mb-0"><i class="fa-solid fa-chart-bar text-primary me-2"></i>Grafik Pengajuan Penginapan Per Bulan</h6>
            </div>
            <div style="position: relative; height: 280px;">
                <canvas id="monthlyChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Doughnut Chart Kecamatan -->
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold text-dark mb-0"><i class="fa-solid fa-chart-pie text-primary me-2"></i>Sebaran Penginapan per Kecamatan</h6>
            </div>
            <div style="position: relative; height: 280px;" class="d-flex justify-content-center">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Penginapan Terbaru -->
<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
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
                    @forelse($recentLodgings as $item)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <h6 class="fw-bold text-dark mb-0">{{ $item->name }}</h6>
                                </div>
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
<script>
    // 1. Bar Chart Pengajuan per Bulan
    const ctxMonthly = document.getElementById('monthlyChart').getContext('2d');
    new Chart(ctxMonthly, {
        type: 'bar',
        data: {
            labels: {!! json_encode($monthlyLabels) !!},
            datasets: [{
                label: 'Jumlah Pengajuan',
                data: {!! json_encode($monthlyCounts) !!},
                backgroundColor: '#4D3EA3',
                borderRadius: 8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } }
            }
        }
    });

    // 2. Doughnut Chart Kategori / Kecamatan
    const ctxCategory = document.getElementById('categoryChart').getContext('2d');
    new Chart(ctxCategory, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($categoryLabels) !!},
            datasets: [{
                data: {!! json_encode($categoryCounts) !!},
                backgroundColor: ['#4D3EA3', '#758AD1', '#FFD2F4', '#E1DAFB', '#450C3F', '#9580FF'],
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
</script>
@endsection
