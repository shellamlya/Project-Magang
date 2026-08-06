@extends('layouts.admin')

@section('title', 'Master Fasilitas - Admin GREX')
@section('page-title', 'Kelola Master Fasilitas')

@section('content')
<div class="row g-4">
    <!-- Form Tambah Fasilitas -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-plus-circle text-primary me-1"></i> Tambah Fasilitas Baru</h6>

            <form action="{{ route('admin.facilities.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label small fw-bold">Nama Fasilitas *</label>
                    <input type="text" name="name" id="name" class="form-control form-control-sm" placeholder="Contoh: Kolam Renang, AC, WiFi" required>
                </div>

                <div class="mb-3">
                    <label for="icon" class="form-label small fw-bold">Class Icon FontAwesome</label>
                    <input type="text" name="icon" id="icon" class="form-control form-control-sm" placeholder="fa-wifi">
                </div>

                <button type="submit" class="btn btn-primary btn-sm w-100 rounded-3 fw-bold py-2">
                    <i class="fa-solid fa-save me-1"></i> Simpan Fasilitas
                </button>
            </form>
        </div>
    </div>

    <!-- Tabel Daftar Fasilitas -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-white p-3 border-bottom">
                <h6 class="fw-bold text-dark mb-0"><i class="fa-solid fa-list-check text-primary me-2"></i>Daftar Fasilitas Terdaftar</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3">Nama Fasilitas</th>
                                <th class="py-3">Icon</th>
                                <th class="py-3">Digunakan</th>
                                <th class="text-end pe-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($facilities as $f)
                                <tr>
                                    <td class="ps-4 fw-bold text-dark">{{ $f->name }}</td>
                                    <td><i class="fa-solid {{ $f->icon ?? 'fa-check' }} text-primary fs-5"></i> <code class="small ms-1">{{ $f->icon }}</code></td>
                                    <td><span class="fw-bold">{{ $f->lodgings_count }}</span> penginapan</td>
                                    <td class="text-end pe-4">
                                        <form action="{{ route('admin.facilities.destroy', $f->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus fasilitas ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-light btn-sm rounded-circle" title="Hapus"><i class="fa-solid fa-trash text-danger"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">Belum ada fasilitas.</td>
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
