@extends('layouts.admin')

@section('title', 'Master Fasilitas - Admin GREX')
@section('page-title', 'Kelola Master Fasilitas')

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Tab Menu Layanan Fasilitas -->
<ul class="nav nav-pills mb-4 gap-2" id="facilityTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-item-link btn btn-sm rounded-pill px-4 fw-bold {{ $activeTab == 'penginapan' ? 'active btn-primary text-white' : 'btn-light border text-dark' }}" id="penginapan-tab" data-bs-toggle="pill" data-bs-target="#penginapan-facilities" type="button" role="tab">
            <i class="fa-solid fa-hotel me-1"></i> Fasilitas Penginapan <span class="badge bg-white text-dark ms-1">{{ $lodgingFacilities->count() }}</span>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-item-link btn btn-sm rounded-pill px-4 fw-bold {{ $activeTab == 'wisata' ? 'active btn-primary text-white' : 'btn-light border text-dark' }}" id="wisata-tab" data-bs-toggle="pill" data-bs-target="#wisata-facilities" type="button" role="tab">
            <i class="fa-solid fa-mountain-sun me-1"></i> Fasilitas Wisata <span class="badge bg-white text-dark ms-1">{{ $touristFacilities->count() }}</span>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-item-link btn btn-sm rounded-pill px-4 fw-bold {{ $activeTab == 'nongkrong' ? 'active btn-primary text-white' : 'btn-light border text-dark' }}" id="nongkrong-tab" data-bs-toggle="pill" data-bs-target="#nongkrong-facilities" type="button" role="tab">
            <i class="fa-solid fa-mug-hot me-1"></i> Fasilitas Nongkrong <span class="badge bg-white text-dark ms-1">{{ $hangoutFacilities->count() }}</span>
        </button>
    </li>
</ul>

<div class="row g-4">
    <!-- Form Tambah Fasilitas -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-plus-circle text-primary me-1"></i> Tambah Fasilitas Baru</h6>

            <form action="{{ route('admin.facilities.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="facility_name" class="form-label small fw-bold">Nama Fasilitas *</label>
                    <input type="text" name="facility_name" id="facility_name" class="form-control form-control-sm" placeholder="Contoh: WiFi, AC, Kolam Renang" required>
                </div>

                <div class="mb-3">
                    <label for="service_type" class="form-label small fw-bold">Terapkan Pada Fitur *</label>
                    <select name="service_type" id="service_type" class="form-select form-select-sm" required>
                        <option value="penginapan">Penginapan Sahaja</option>
                        <option value="wisata">Wisata Sahaja</option>
                        <option value="nongkrong">Nongkrong Sahaja</option>
                        <option value="all" selected>Semua Fitur (Penginapan + Wisata + Nongkrong)</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="icon" class="form-label small fw-bold">Class Icon FontAwesome (Opsional)</label>
                    <input type="text" name="icon" id="icon" class="form-control form-control-sm" placeholder="fa-wifi">
                </div>

                <button type="submit" class="btn btn-primary btn-sm w-100 rounded-3 fw-bold py-2">
                    <i class="fa-solid fa-save me-1"></i> Simpan Fasilitas
                </button>
            </form>
        </div>
    </div>

    <!-- Tabel Daftar Fasilitas berdasarkan Tab -->
    <div class="col-lg-8">
        <div class="tab-content" id="facilityTabsContent">
            
            <!-- Tab 1: Fasilitas Penginapan -->
            <div class="tab-pane fade {{ $activeTab == 'penginapan' ? 'show active' : '' }}" id="penginapan-facilities" role="tabpanel">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white p-3 border-bottom d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold text-dark mb-0"><i class="fa-solid fa-hotel text-primary me-2"></i>Master Fasilitas Penginapan</h6>
                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-1 fw-bold">{{ $lodgingFacilities->count() }} Fasilitas</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4 py-3">Nama Fasilitas</th>
                                        <th class="py-3">Fitur Terkait</th>
                                        <th class="text-end pe-4 py-3">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($lodgingFacilities as $f)
                                        <tr>
                                            <td class="ps-4 fw-bold text-dark"><i class="fa-solid fa-check text-success me-2"></i>{{ $f->facility_name }}</td>
                                            <td><span class="badge bg-primary bg-opacity-10 text-primary px-3 py-1"><i class="fa-solid fa-hotel me-1"></i> Penginapan</span></td>
                                            <td class="text-end pe-4">
                                                <button type="button" class="btn btn-light btn-sm rounded-circle me-1" data-bs-toggle="modal" data-bs-target="#editLodgingFacModal{{ $f->id }}" title="Edit">
                                                    <i class="fa-solid fa-pen-to-square text-warning"></i>
                                                </button>
                                                <form action="{{ route('admin.facilities.destroy', $f->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus fasilitas penginapan ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" name="facility_type" value="penginapan">
                                                    <button type="submit" class="btn btn-light btn-sm rounded-circle" title="Hapus"><i class="fa-solid fa-trash text-danger"></i></button>
                                                </form>

                                                <!-- Modal Edit -->
                                                <div class="modal fade text-start" id="editLodgingFacModal{{ $f->id }}" tabindex="-1">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content rounded-4 border-0">
                                                            <div class="modal-header border-bottom">
                                                                <h6 class="modal-title fw-bold"><i class="fa-solid fa-pen-to-square text-primary me-2"></i>Edit Fasilitas Penginapan</h6>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <form action="{{ route('admin.facilities.update', $f->id) }}" method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <input type="hidden" name="facility_type" value="penginapan">
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label class="form-label small fw-bold">Nama Fasilitas *</label>
                                                                        <input type="text" name="facility_name" class="form-control form-control-sm" value="{{ $f->facility_name }}" required>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer border-top">
                                                                    <button type="button" class="btn btn-light btn-sm rounded-3" data-bs-dismiss="modal">Batal</button>
                                                                    <button type="submit" class="btn btn-primary btn-sm rounded-3 fw-bold">Simpan Perubahan</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="text-center py-4 text-muted">Belum ada fasilitas penginapan.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 2: Fasilitas Wisata -->
            <div class="tab-pane fade {{ $activeTab == 'wisata' ? 'show active' : '' }}" id="wisata-facilities" role="tabpanel">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white p-3 border-bottom d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold text-dark mb-0"><i class="fa-solid fa-mountain-sun text-success me-2"></i>Master Fasilitas Wisata</h6>
                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-1 fw-bold">{{ $touristFacilities->count() }} Fasilitas</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4 py-3">Nama Fasilitas</th>
                                        <th class="py-3">Fitur Terkait</th>
                                        <th class="text-end pe-4 py-3">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($touristFacilities as $f)
                                        <tr>
                                            <td class="ps-4 fw-bold text-dark"><i class="fa-solid fa-check text-success me-2"></i>{{ $f->facility_name }}</td>
                                            <td><span class="badge bg-success bg-opacity-10 text-success px-3 py-1"><i class="fa-solid fa-mountain-sun me-1"></i> Wisata</span></td>
                                            <td class="text-end pe-4">
                                                <button type="button" class="btn btn-light btn-sm rounded-circle me-1" data-bs-toggle="modal" data-bs-target="#editTouristFacModal{{ $f->id }}" title="Edit">
                                                    <i class="fa-solid fa-pen-to-square text-warning"></i>
                                                </button>
                                                <form action="{{ route('admin.facilities.destroy', $f->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus fasilitas wisata ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" name="facility_type" value="wisata">
                                                    <button type="submit" class="btn btn-light btn-sm rounded-circle" title="Hapus"><i class="fa-solid fa-trash text-danger"></i></button>
                                                </form>

                                                <!-- Modal Edit -->
                                                <div class="modal fade text-start" id="editTouristFacModal{{ $f->id }}" tabindex="-1">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content rounded-4 border-0">
                                                            <div class="modal-header border-bottom">
                                                                <h6 class="modal-title fw-bold"><i class="fa-solid fa-pen-to-square text-primary me-2"></i>Edit Fasilitas Wisata</h6>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <form action="{{ route('admin.facilities.update', $f->id) }}" method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <input type="hidden" name="facility_type" value="wisata">
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label class="form-label small fw-bold">Nama Fasilitas *</label>
                                                                        <input type="text" name="facility_name" class="form-control form-control-sm" value="{{ $f->facility_name }}" required>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer border-top">
                                                                    <button type="button" class="btn btn-light btn-sm rounded-3" data-bs-dismiss="modal">Batal</button>
                                                                    <button type="submit" class="btn btn-primary btn-sm rounded-3 fw-bold">Simpan Perubahan</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="text-center py-4 text-muted">Belum ada fasilitas wisata.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 3: Fasilitas Nongkrong -->
            <div class="tab-pane fade {{ $activeTab == 'nongkrong' ? 'show active' : '' }}" id="nongkrong-facilities" role="tabpanel">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white p-3 border-bottom d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold text-dark mb-0"><i class="fa-solid fa-mug-hot text-warning me-2"></i>Master Fasilitas Nongkrong</h6>
                        <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-1 fw-bold">{{ $hangoutFacilities->count() }} Fasilitas</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4 py-3">Nama Fasilitas</th>
                                        <th class="py-3">Fitur Terkait</th>
                                        <th class="text-end pe-4 py-3">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($hangoutFacilities as $f)
                                        <tr>
                                            <td class="ps-4 fw-bold text-dark"><i class="fa-solid fa-check text-success me-2"></i>{{ $f->facility_name }}</td>
                                            <td><span class="badge bg-warning bg-opacity-10 text-warning px-3 py-1"><i class="fa-solid fa-mug-hot me-1"></i> Nongkrong</span></td>
                                            <td class="text-end pe-4">
                                                <button type="button" class="btn btn-light btn-sm rounded-circle me-1" data-bs-toggle="modal" data-bs-target="#editHangoutFacModal{{ $f->id }}" title="Edit">
                                                    <i class="fa-solid fa-pen-to-square text-warning"></i>
                                                </button>
                                                <form action="{{ route('admin.facilities.destroy', $f->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus fasilitas nongkrong ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" name="facility_type" value="nongkrong">
                                                    <button type="submit" class="btn btn-light btn-sm rounded-circle" title="Hapus"><i class="fa-solid fa-trash text-danger"></i></button>
                                                </form>

                                                <!-- Modal Edit -->
                                                <div class="modal fade text-start" id="editHangoutFacModal{{ $f->id }}" tabindex="-1">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content rounded-4 border-0">
                                                            <div class="modal-header border-bottom">
                                                                <h6 class="modal-title fw-bold"><i class="fa-solid fa-pen-to-square text-primary me-2"></i>Edit Fasilitas Nongkrong</h6>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <form action="{{ route('admin.facilities.update', $f->id) }}" method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <input type="hidden" name="facility_type" value="nongkrong">
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label class="form-label small fw-bold">Nama Fasilitas *</label>
                                                                        <input type="text" name="facility_name" class="form-control form-control-sm" value="{{ $f->facility_name }}" required>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer border-top">
                                                                    <button type="button" class="btn btn-light btn-sm rounded-3" data-bs-dismiss="modal">Batal</button>
                                                                    <button type="submit" class="btn btn-primary btn-sm rounded-3 fw-bold">Simpan Perubahan</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="text-center py-4 text-muted">Belum ada fasilitas nongkrong.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
