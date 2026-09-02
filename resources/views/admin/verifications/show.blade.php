@extends('layouts.admin')

@section('title', 'Detail Verifikasi Tempat Usaha - Admin Lokavino')
@section('page-title', 'Detail Pengajuan Tempat Usaha (Verifikasi Tingkat 2)')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.verifications.index', ['type' => $type]) }}" class="btn btn-outline-secondary rounded-pill btn-sm">
        <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Daftar Verifikasi
    </a>
</div>

@php
    $typeLabel = match($type) {
        'wisata'    => '🏖️ Tempat Wisata',
        'nongkrong' => '☕ Kafe & Nongkrong',
        default     => '🏨 Penginapan',
    };
    $cleanPhone = preg_replace('/[^0-9]/', '', $place->phone ?? '');
    if (str_starts_with($cleanPhone, '0')) {
        $cleanPhone = '62' . substr($cleanPhone, 1);
    }
    $waUrl = $cleanPhone ? "https://wa.me/{$cleanPhone}?text=" . urlencode("Halo pengelola {$place->name}, kami dari Tim Admin Disparekrafbudpora Kab. Gresik (Lokavino) ingin mengonfirmasi listing data tempat usaha Anda.") : null;
@endphp

<div class="row g-4">
    <!-- Kolom Kiri: Detail Tempat Usaha & Galeri Foto -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom flex-wrap gap-2">
                <div>
                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle me-2">{{ $typeLabel }}</span>
                    <span class="text-muted small">ID Tempat: #{{ $place->id }}</span>
                </div>
                <div>
                    @if($place->status === 'approved')
                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 fw-bold"><i class="fa-solid fa-circle-check me-1"></i> Status: Approved (Tayang Publik)</span>
                    @elseif($place->status === 'pending')
                        <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-2 fw-bold"><i class="fa-solid fa-clock me-1"></i> Status: Pending Verifikasi</span>
                    @else
                        <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2 fw-bold"><i class="fa-solid fa-times-circle me-1"></i> Status: Rejected</span>
                    @endif
                </div>
            </div>

            <!-- Galeri Foto yang Diunggah Owner -->
            <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-images text-primary me-2"></i>Foto & Galeri Tempat Usaha</h5>
            <div class="row g-3 mb-4">
                <!-- Thumbnail Utama -->
                <div class="col-md-4">
                    <div class="border rounded-3 p-2 bg-light text-center">
                        <small class="fw-bold text-dark d-block mb-1">Foto Thumbnail Utama *</small>
                        <img src="{{ $place->thumbnail_url }}" alt="{{ $place->name }}" class="img-fluid rounded-2 shadow-sm mb-2" style="height: 140px; width: 100%; object-fit: cover;">
                        <a href="{{ $place->thumbnail_url }}" target="_blank" class="btn btn-sm btn-outline-dark rounded-pill px-3 fs-8">Lihat Foto</a>
                    </div>
                </div>

                <!-- Foto 1 -->
                <div class="col-md-4">
                    <div class="border rounded-3 p-2 bg-light text-center">
                        <small class="fw-bold text-dark d-block mb-1">Foto Pendukung #1</small>
                        @if($place->photo_1)
                            <img src="{{ $place->photo_1_url }}" alt="Foto 1" class="img-fluid rounded-2 shadow-sm mb-2" style="height: 140px; width: 100%; object-fit: cover;">
                            <a href="{{ $place->photo_1_url }}" target="_blank" class="btn btn-sm btn-outline-dark rounded-pill px-3 fs-8">Lihat Foto</a>
                        @else
                            <div class="d-flex align-items-center justify-content-center text-muted" style="height: 140px; background: #eaedf2; border-radius: 8px;">
                                <small>Tidak ada foto #1</small>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Foto 2 -->
                <div class="col-md-4">
                    <div class="border rounded-3 p-2 bg-light text-center">
                        <small class="fw-bold text-dark d-block mb-1">Foto Pendukung #2</small>
                        @if($place->photo_2)
                            <img src="{{ $place->photo_2_url }}" alt="Foto 2" class="img-fluid rounded-2 shadow-sm mb-2" style="height: 140px; width: 100%; object-fit: cover;">
                            <a href="{{ $place->photo_2_url }}" target="_blank" class="btn btn-sm btn-outline-dark rounded-pill px-3 fs-8">Lihat Foto</a>
                        @else
                            <div class="d-flex align-items-center justify-content-center text-muted" style="height: 140px; background: #eaedf2; border-radius: 8px;">
                                <small>Tidak ada foto #2</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Detail Informasi Tempat -->
            <h4 class="fw-bold text-dark mb-2">{{ $place->name }}</h4>
            <p class="text-muted small mb-4">
                <i class="fa-solid fa-location-dot text-danger me-1"></i> {{ $place->address ?: '-' }}
                , Kec. {{ $place->district ?? 'Gresik' }} {{ $place->village ? ', Desa ' . $place->village : '' }}, Kabupaten Gresik
            </p>

            <h6 class="fw-bold text-dark mb-2">Deskripsi Tempat:</h6>
            <p class="text-secondary small leading-relaxed mb-4" style="white-space: pre-line;">{{ $place->description }}</p>

            @if(isset($place->facilities) && $place->facilities->isNotEmpty())
                <h6 class="fw-bold text-dark mb-2">Fasilitas yang Tersedia:</h6>
                <div class="row g-2 mb-4">
                    @foreach($place->facilities as $fac)
                        <div class="col-md-4 col-6">
                            <div class="p-2 border rounded-3 bg-light small fw-semibold">
                                <i class="fa-solid fa-circle-check text-primary me-2"></i> {{ $fac->facility_name }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Info Operasional & Tarif -->
            <h6 class="fw-bold text-dark mb-2">Informasi Operasional & Tarif:</h6>
            <div class="row g-3 p-3 bg-light rounded-3 mb-4">
                <div class="col-md-6">
                    <small class="text-muted d-block">Jam Operasional:</small>
                    <span class="fw-bold text-dark">{{ $place->operational_hours }}</span>
                </div>
                @if($type === 'penginapan')
                    <div class="col-md-6">
                        <small class="text-muted d-block">Tarif per Malam:</small>
                        <span class="fw-bold text-primary fs-6">Rp {{ number_format($place->price_start, 0, ',', '.') }} – Rp {{ number_format($place->price_end, 0, ',', '.') }}</span>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted d-block">Check-in:</small>
                        <span class="fw-bold text-dark">{{ $place->check_in ?? '14.00 WIB' }}</span>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted d-block">Check-out:</small>
                        <span class="fw-bold text-dark">{{ $place->check_out ?? '12.00 WIB' }}</span>
                    </div>
                @elseif($type === 'wisata')
                    <div class="col-md-6">
                        <small class="text-muted d-block">Harga Tiket Masuk:</small>
                        <span class="fw-bold text-success fs-6">{{ $place->ticket_price ?? 'Gratis' }}</span>
                    </div>
                @endif
            </div>

            <!-- Keputusan Verifikasi Admin -->
            <div class="p-4 border rounded-4 bg-light">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h6 class="fw-bold text-dark mb-1">Keputusan Verifikasi Listing</h6>
                        <small class="text-muted">Setujui untuk menayangkan tempat usaha ini di web publik atau Tolak jika data belum valid.</small>
                    </div>

                    <div class="d-flex gap-2">
                        <!-- Form Approve -->
                        <form action="{{ route('admin.verifications.approve', $place->id) }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="type" value="{{ $type }}">
                            <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm" onclick="return confirm('Setujui pengajuan tempat usaha ini?')">
                                <i class="fa-solid fa-check me-1"></i> Setujui (Approve)
                            </button>
                        </form>

                        <!-- Tombol Trigger Modal Reject -->
                        <button type="button" class="btn btn-danger rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#rejectPlaceModal">
                            <i class="fa-solid fa-times me-1"></i> Tolak (Reject)
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Histori Verification Logs -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-history text-primary me-2"></i>Histori Log Verifikasi Listing</h6>
            @forelse($verificationLogs as $log)
                <div class="border-start border-3 border-primary ps-3 pb-3 mb-2">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="badge @if($log->status === 'approved') bg-success @else bg-danger @endif">
                            {{ strtoupper($log->status) }}
                        </span>
                        <small class="text-muted">{{ $log->created_at->format('d M Y, H:i') }}</small>
                    </div>
                    <p class="small text-dark mb-0"><strong>Admin:</strong> {{ $log->admin->name ?? 'Administrator' }}</p>
                    @if($log->notes)
                        <p class="small text-muted mb-0"><strong>Catatan / Alasan:</strong> {{ $log->notes }}</p>
                    @endif
                </div>
            @empty
                <p class="text-muted small mb-0">Belum ada catatan log verifikasi untuk listing ini.</p>
            @endforelse
        </div>
    </div>

    <!-- Kolom Kanan: Info Kontak Owner & Google Maps -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
            <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-user-tie text-primary me-2"></i>Kontak Pengelola & WA Bisnis</h5>
            
            <div class="mb-3">
                <small class="text-muted d-block">Nama Pengelola / PIC:</small>
                <span class="fw-bold text-dark">{{ $place->manager_name }}</span>
            </div>

            @if($place->owner)
                <div class="mb-3">
                    <small class="text-muted d-block">Pemilik Akun (Owner):</small>
                    <span class="fw-bold text-dark">{{ $place->owner->user->name ?? 'Owner' }}</span>
                    <small class="text-muted d-block">Badan Usaha: {{ $place->owner->company_name ?: '-' }}</small>
                </div>
            @endif

            @if($place->phone)
                <div class="mb-3">
                    <small class="text-muted d-block">No. WhatsApp Bisnis Tempat Usaha:</small>
                    <span class="fw-bold text-success"><i class="fa-brands fa-whatsapp me-1"></i>{{ $place->phone }}</span>
                </div>
            @endif

            @if($waUrl)
                <a href="{{ $waUrl }}" target="_blank" class="btn btn-outline-success rounded-pill py-2 fw-bold w-100 mb-3">
                    <i class="fa-brands fa-whatsapp me-1"></i> Hubungi WA Pengelola
                </a>
            @endif

            @if($place->google_maps)
                <div class="d-grid mt-2">
                    <a href="{{ $place->google_maps }}" target="_blank" class="btn btn-grex-primary rounded-pill py-2.5 fw-bold">
                        <i class="fa-solid fa-map-pin me-1"></i> Buka Titik Google Maps
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Reject Tempat Usaha -->
<div class="modal fade" id="rejectPlaceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header bg-danger text-white border-0">
                <h5 class="modal-title fw-bold"><i class="fa-solid fa-circle-exclamation me-2"></i>Tolak Pengajuan Tempat Usaha</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.verifications.reject', $place->id) }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="{{ $type }}">
                <div class="modal-body p-4">
                    <p class="text-muted small mb-3">Tuliskan alasan penolakan dengan jelas agar Owner dapat merevisi dan mengunggah kembali data yang sesuai.</p>
                    
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label fw-bold small">Alasan Penolakan Listing *</label>
                        <textarea name="rejection_reason" id="rejection_reason" rows="4" class="form-control" placeholder="Contoh: Foto fasilitas kurang jelas, tarif belum sesuai dengan ketentuan..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold">Tolak Pengajuan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
