@extends('layouts.admin')

@section('title', 'Dashboard Owner - Lokavino')
@section('page-title', 'Dashboard & Ringkasan Analitik')

@section('content')

<!-- Status Banner Verifikasi Akun Owner -->
@if(optional($owner)->isAccountPending())
    <div class="alert border-0 rounded-4 shadow-sm p-3 mb-4 d-flex align-items-center gap-3" style="background-color: #FEF3C7; color: #92400E;">
        <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="background-color: #FDE68A; width: 44px; height: 44px;">
            <i class="fa-solid fa-clock-rotate-left fs-5 text-warning"></i>
        </div>
        <div>
            <h6 class="fw-bold mb-0">Akun Anda Menunggu Verifikasi Admin</h6>
            <small class="opacity-75">Foto KTP dan data akun Anda sedang ditinjau oleh Tim Admin.</small>
        </div>
    </div>
@elseif(optional($owner)->isAccountRejected())
    <div class="alert border-0 rounded-4 shadow-sm p-3 mb-4 d-flex align-items-center gap-3" style="background-color: #FEF2F2; color: #991B1B;">
        <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="background-color: #FCA5A5; width: 44px; height: 44px;">
            <i class="fa-solid fa-circle-exclamation fs-5 text-danger"></i>
        </div>
        <div>
            <h6 class="fw-bold mb-0">Verifikasi Akun Owner Ditolak</h6>
            <small class="d-block opacity-75">Alasan: <strong>{{ $owner->account_rejection_reason ?? 'Data KTP tidak valid.' }}</strong></small>
            <small><a href="{{ route('owner.rejected-verification') }}" class="text-danger fw-bold text-decoration-underline"><i class="fa-solid fa-upload me-1"></i>Unggah Ulang KTP & Perbaiki Data</a></small>
        </div>
    </div>
@else
    <div class="alert border-0 rounded-4 shadow-sm p-3 mb-4 d-flex align-items-center justify-content-between flex-wrap gap-2" style="background-color: #ECFDF5; color: #065F46;">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="background-color: #A7F3D0; width: 44px; height: 44px;">
                <i class="fa-solid fa-certificate fs-5 text-success"></i>
            </div>
            <div>
                <h6 class="fw-bold mb-0">Akun Mitra Owner Terverifikasi Resmi</h6>
                <small class="opacity-75">Akun Anda aktif. Anda dapat mengelola unit usaha secara penuh.</small>
            </div>
        </div>
        <span class="badge rounded-pill px-3 py-2 text-white" style="background-color: #10B981;"><i class="fa-solid fa-circle-check me-1"></i> Verified</span>
    </div>
@endif

<!-- Ringkasan Statistik Status Tempat Usaha -->
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold">Total Seluruh Usaha</span>
                    <h3 class="fw-bold text-dark my-1">{{ $totalPlaces }}</h3>
                    <small class="text-muted">Terdaftar di Lokavino</small>
                </div>
                <div class="p-3 rounded-3" style="background-color: #F3E8FF; color: #7C3AED;">
                    <i class="fa-solid fa-store fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold">Usaha Disetujui</span>
                    <h3 class="fw-bold text-success my-1">{{ $totalApproved }}</h3>
                    <small class="text-success fw-medium"><i class="fa-solid fa-circle-check me-1"></i> Aktif & Tayang</small>
                </div>
                <div class="p-3 rounded-3" style="background-color: #ECFDF5; color: #10B981;">
                    <i class="fa-solid fa-square-check fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold">Pending Pengajuan</span>
                    <h3 class="fw-bold text-warning my-1">{{ $totalPending }}</h3>
                    <small class="text-warning fw-medium"><i class="fa-solid fa-clock me-1"></i> Menunggu Admin</small>
                </div>
                <div class="p-3 rounded-3" style="background-color: #FEF3C7; color: #D97706;">
                    <i class="fa-solid fa-hourglass-half fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold">Pengajuan Ditolak</span>
                    <h3 class="fw-bold text-danger my-1">{{ $totalRejected }}</h3>
                    <small class="text-danger fw-medium"><i class="fa-solid fa-triangle-exclamation me-1"></i> Perlu Perbaikan</small>
                </div>
                <div class="p-3 rounded-3" style="background-color: #FEF2F2; color: #EF4444;">
                    <i class="fa-solid fa-circle-xmark fs-4"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Ringkasan Interaksi & Grafik Analitik Per Tempat Usaha -->
<div class="row g-4 mb-4">
    <div class="col-12 col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h6 class="fw-bold text-dark mb-0"><i class="fa-solid fa-chart-bar me-2 text-primary"></i>Perbandingan Analitik Per Tempat Usaha</h6>
                <span class="badge bg-light text-secondary">Views vs Klik Map</span>
            </div>
            <div style="height: 300px; position: relative;">
                <canvas id="placesAnalyticsChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100 d-flex flex-column justify-content-between">
            <div>
                <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-eye me-2 text-purple" style="color: #7C3AED;"></i>Total Interaksi Pengunjung</h6>
                
                <div class="p-3 rounded-4 mb-3" style="background-color: #F3E8FF;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <small class="text-muted d-block">Total Dilihat (Views)</small>
                            <h4 class="fw-bold mb-0" style="color: #7C3AED;">{{ number_format($totalViews) }}</h4>
                        </div>
                        <i class="fa-solid fa-eye fs-3 opacity-50" style="color: #7C3AED;"></i>
                    </div>
                </div>

                <div class="p-3 rounded-4 mb-3" style="background-color: #E0F2FE;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <small class="text-muted d-block">Total Klik Peta (Maps)</small>
                            <h4 class="fw-bold mb-0 text-info">{{ number_format($totalMapClicks) }}</h4>
                        </div>
                        <i class="fa-solid fa-map-location-dot fs-3 text-info opacity-50"></i>
                    </div>
                </div>
            </div>

            <div class="p-3 rounded-3" style="background-color: #F8FAFC; border-left: 4px solid #7C3AED;">
                <p class="small mb-0 text-muted">
                    <i class="fa-solid fa-lightbulb me-1 text-warning"></i>
                    <strong>Tips:</strong> Tempat usaha dengan koordinat Peta akurat dan deskripsi jelas cenderung mendapat lebih banyak klik lokasi.
                </p>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('placesAnalyticsChart');
        if (ctx) {
            const labels = @json($analyticsLabels);
            const viewsData = @json($analyticsViewsData);
            const mapsData = @json($analyticsMapsData);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels.length > 0 ? labels : ['Belum Ada Data Tempat'],
                    datasets: [
                        {
                            label: 'Jumlah Dilihat (Views)',
                            data: viewsData.length > 0 ? viewsData : [0],
                            backgroundColor: '#7C3AED',
                            borderRadius: 8,
                        },
                        {
                            label: 'Klik Peta (Maps)',
                            data: mapsData.length > 0 ? mapsData : [0],
                            backgroundColor: '#0EA5E9',
                            borderRadius: 8,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endsection