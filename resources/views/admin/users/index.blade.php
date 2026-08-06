@extends('layouts.admin')

@section('title', 'Kelola Pengguna - Admin GREX')
@section('page-title', 'Kelola User & Owner')

@section('content')
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4 bg-white rounded-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h5 class="fw-bold text-dark mb-1"><i class="fa-solid fa-users-gear text-primary me-2"></i>Daftar Pengguna Sistem</h5>
                <p class="small text-muted mb-0">Kelola akun Administrator, Pemilik Penginapan (Owner), dan Pengguna Umum</p>
            </div>

            <!-- Filter Role -->
            <div class="btn-group rounded-pill p-1 bg-light border" role="group">
                <a href="{{ route('admin.users.index') }}" class="btn btn-sm rounded-pill {{ !request('role') ? 'btn-primary' : 'btn-light' }}">Semua User</a>
                <a href="{{ route('admin.users.index', ['role' => 'admin']) }}" class="btn btn-sm rounded-pill {{ request('role') == 'admin' ? 'btn-primary' : 'btn-light' }}">Admin</a>
                <a href="{{ route('admin.users.index', ['role' => 'owner']) }}" class="btn btn-sm rounded-pill {{ request('role') == 'owner' ? 'btn-primary' : 'btn-light' }}">Owner</a>
                <a href="{{ route('admin.users.index', ['role' => 'user']) }}" class="btn btn-sm rounded-pill {{ request('role') == 'user' ? 'btn-primary' : 'btn-light' }}">User Publik</a>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3">Pengguna</th>
                        <th class="py-3">Role</th>
                        <th class="py-3">Usaha (Khusus Owner)</th>
                        <th class="py-3">No. Telepon</th>
                        <th class="py-3">Tanggal Terdaftar</th>
                        <th class="text-end pe-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $u)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="fa-solid fa-user"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0">{{ $u->name }}</h6>
                                        <small class="text-muted">{{ $u->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($u->role->name === 'admin')
                                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-1">Admin</span>
                                @elseif($u->role->name === 'owner')
                                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1">Owner</span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-1">User</span>
                                @endif
                            </td>
                            <td>{{ $u->owner->company_name ?? '-' }}</td>
                            <td>{{ $u->phone ?? $u->owner->phone ?? '-' }}</td>
                            <td>{{ $u->created_at->format('d M Y') }}</td>
                            <td class="text-end pe-4">
                                @if($u->id !== auth()->id())
                                    <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus pengguna ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-light btn-sm rounded-circle" title="Hapus"><i class="fa-solid fa-trash text-danger"></i></button>
                                    </form>
                                @else
                                    <span class="badge bg-light text-muted">Akun Anda</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Tidak ada pengguna ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4 d-flex justify-content-center">
    {{ $users->links('pagination::bootstrap-5') }}
</div>
@endsection
