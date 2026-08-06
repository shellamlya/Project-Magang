@extends('layouts.admin')

@section('title', 'Master Kategori - Admin GREX')
@section('page-title', 'Kelola Master Kategori')

@section('content')
<div class="row g-4">
    <!-- Form Tambah Kategori Baru -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-plus-circle text-primary me-1"></i> Tambah Kategori Baru</h6>

            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label small fw-bold">Nama Kategori *</label>
                    <input type="text" name="name" id="name" class="form-control form-control-sm" placeholder="Contoh: Hotel, Villa" required>
                </div>

                <div class="mb-3">
                    <label for="service_type" class="form-label small fw-bold">Layanan GREX *</label>
                    <select name="service_type" id="service_type" class="form-select form-select-sm" required>
                        <option value="shella" selected>Shella (Penginapan)</option>
                        <option value="nyimas">Nyimas (Wisata - Future)</option>
                        <option value="kunti">Kunti (Nongkrong - Future)</option>
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
            <div class="card-header bg-white p-3 border-bottom">
                <h6 class="fw-bold text-dark mb-0"><i class="fa-solid fa-layer-group text-primary me-2"></i>Daftar Kategori Terdaftar</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3">Nama Kategori</th>
                                <th class="py-3">Service Type</th>
                                <th class="py-3">Jumlah Penginapan</th>
                                <th class="text-end pe-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $c)
                                <tr>
                                    <td class="ps-4 fw-bold text-dark">
                                        <i class="fa-solid {{ $c->icon ?? 'fa-building' }} text-primary me-2"></i>{{ $c->name }}
                                    </td>
                                    <td><span class="badge bg-info bg-opacity-10 text-info px-3 py-1">{{ strtoupper($c->service_type) }}</span></td>
                                    <td><span class="fw-bold">{{ $c->lodgings_count }}</span> data</td>
                                    <td class="text-end pe-4">
                                        <form action="{{ route('admin.categories.destroy', $c->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kategori ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-light btn-sm rounded-circle" title="Hapus"><i class="fa-solid fa-trash text-danger"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">Belum ada kategori.</td>
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
