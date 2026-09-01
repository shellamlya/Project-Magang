@extends('layouts.admin')

@section('title', 'Master Kategori - Admin Lokavino')
@section('page-title', 'Kelola Master Kategori')

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Filter Service Type Pills -->
<div class="d-flex flex-wrap align-items-center gap-2 mb-4">
    <a href="{{ route('admin.categories.index') }}" class="btn btn-sm rounded-pill px-3 fw-bold {{ empty($serviceType) ? 'btn-primary' : 'btn-light border' }}">
        <i class="fa-solid fa-border-all me-1"></i> Semua Kategori
    </a>
    <a href="{{ route('admin.categories.index', ['service_type' => 'penginapan']) }}" class="btn btn-sm rounded-pill px-3 fw-bold {{ $serviceType == 'penginapan' ? 'btn-primary' : 'btn-light border' }}">
        <i class="fa-solid fa-hotel me-1"></i> Penginapan <span class="badge bg-white text-dark ms-1">{{ $countPenginapan }}</span>
    </a>
    <a href="{{ route('admin.categories.index', ['service_type' => 'wisata']) }}" class="btn btn-sm rounded-pill px-3 fw-bold {{ $serviceType == 'wisata' ? 'btn-primary' : 'btn-light border' }}">
        <i class="fa-solid fa-mountain-sun me-1"></i> Wisata <span class="badge bg-white text-dark ms-1">{{ $countWisata }}</span>
    </a>
    <a href="{{ route('admin.categories.index', ['service_type' => 'nongkrong']) }}" class="btn btn-sm rounded-pill px-3 fw-bold {{ $serviceType == 'nongkrong' ? 'btn-primary' : 'btn-light border' }}">
        <i class="fa-solid fa-mug-hot me-1"></i> Nongkrong <span class="badge bg-white text-dark ms-1">{{ $countNongkrong }}</span>
    </a>
</div>

<div class="row g-4">
    <!-- Form Tambah Kategori Baru -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-plus-circle text-primary me-1"></i> Tambah Kategori Baru</h6>

            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label small fw-bold">Nama Kategori *</label>
                    <input type="text" name="name" id="name" class="form-control form-control-sm" placeholder="Contoh: Hotel, Wisata Alam, Cafe" required>
                </div>

                <div class="mb-3">
                    <label for="service_type" class="form-label small fw-bold">Layanan Lokavino *</label>
                    <select name="service_type" id="service_type" class="form-select form-select-sm" required>
                        <option value="penginapan" {{ $serviceType == 'penginapan' ? 'selected' : '' }}>Penginapan</option>
                        <option value="wisata" {{ $serviceType == 'wisata' ? 'selected' : '' }}>Wisata</option>
                        <option value="nongkrong" {{ $serviceType == 'nongkrong' ? 'selected' : '' }}>Nongkrong</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="icon" class="form-label small fw-bold">Class Icon FontAwesome</label>
                    <input type="text" name="icon" id="icon" class="form-control form-control-sm" placeholder="fa-hotel">
                </div>

                <button type="submit" class="btn btn-primary btn-sm w-100 rounded-3 fw-bold py-2">
                    <i class="fa-solid fa-save me-1"></i> Simpan Kategori
                </button>
            </form>
        </div>
    </div>

    <!-- Tabel Daftar Kategori -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-white p-3 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="fw-bold text-dark mb-0"><i class="fa-solid fa-layer-group text-primary me-2"></i>Daftar Kategori Terdaftar</h6>
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-1 fw-bold">{{ $categories->count() }} Kategori</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3">Nama Kategori</th>
                                <th class="py-3">Layanan Lokavino</th>
                                <th class="py-3">Status</th>
                                <th class="text-end pe-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $c)
                                <tr>
                                    <td class="ps-4 fw-bold text-dark">
                                        <i class="fa-solid {{ $c->icon ?? 'fa-building' }} text-primary me-2"></i>{{ $c->name }}
                                    </td>
                                    <td>
                                        @if($c->service_type == 'penginapan' || $c->service_type == 'shella')
                                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-1"><i class="fa-solid fa-hotel me-1"></i> PENGINAPAN</span>
                                        @elseif($c->service_type == 'wisata')
                                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-1"><i class="fa-solid fa-mountain-sun me-1"></i> WISATA</span>
                                        @else
                                            <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-1"><i class="fa-solid fa-mug-hot me-1"></i> NONGKRONG</span>
                                        @endif
                                    </td>
                                    <td><span class="badge bg-success bg-opacity-10 text-success px-3 py-1">Aktif</span></td>
                                    <td class="text-end pe-4">
                                        <!-- Tombol Edit Modal -->
                                        <button type="button" class="btn btn-light btn-sm rounded-circle me-1" data-bs-toggle="modal" data-bs-target="#editCategoryModal{{ $c->id }}" title="Edit Kategori">
                                            <i class="fa-solid fa-pen-to-square text-warning"></i>
                                        </button>

                                        <!-- Form Delete -->
                                        <form action="{{ route('admin.categories.destroy', $c->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-light btn-sm rounded-circle" title="Hapus"><i class="fa-solid fa-trash text-danger"></i></button>
                                        </form>

                                        <!-- Modal Edit Kategori -->
                                        <div class="modal fade" id="editCategoryModal{{ $c->id }}" tabindex="-1" aria-labelledby="editCategoryModalLabel{{ $c->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content rounded-4 border-0 text-start">
                                                    <div class="modal-header border-bottom">
                                                        <h6 class="modal-title fw-bold" id="editCategoryModalLabel{{ $c->id }}">
                                                            <i class="fa-solid fa-pen-to-square text-primary me-2"></i>Edit Kategori
                                                        </h6>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('admin.categories.update', $c->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label small fw-bold">Nama Kategori *</label>
                                                                <input type="text" name="name" class="form-control form-control-sm" value="{{ $c->name }}" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label small fw-bold">Layanan Lokavino *</label>
                                                                <select name="service_type" class="form-select form-select-sm" required>
                                                                    <option value="penginapan" {{ ($c->service_type == 'penginapan' || $c->service_type == 'shella') ? 'selected' : '' }}>Penginapan</option>
                                                                    <option value="wisata" {{ $c->service_type == 'wisata' ? 'selected' : '' }}>Wisata</option>
                                                                    <option value="nongkrong" {{ $c->service_type == 'nongkrong' ? 'selected' : '' }}>Nongkrong</option>
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label small fw-bold">Class Icon FontAwesome</label>
                                                                <input type="text" name="icon" class="form-control form-control-sm" value="{{ $c->icon }}">
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
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">Belum ada kategori terdaftar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
