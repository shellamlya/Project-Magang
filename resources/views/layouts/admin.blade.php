<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Lokavino')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        :root {
            --sidebar-width: 260px;
            --primary-purple: #7C3AED;
            --dark-purple: #1E1B4B;
            --light-purple: #F3E8FF;
            --border-purple: #E9D5FF;
            --bg-light: #F8FAFC;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-light);
            min-height: 100vh;
        }

        .sidebar {
            width: var(--sidebar-width);
            background: #FFFFFF;
            color: var(--dark-purple);
            position: fixed;
            top: 0; bottom: 0; left: 0;
            z-index: 100;
            overflow-y: auto;
            border-right: 1px solid #E2E8F0;
            transition: all 0.3s ease;
        }

        .sidebar-brand {
            padding: 1.25rem;
            border-bottom: 1px solid #F1F5F9;
        }

        .sidebar-menu { padding: 1rem 0.75rem; }

        .menu-header {
            padding: 0.75rem 0.75rem 0.35rem;
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #94A3B8;
        }

        .nav-item-link {
            display: flex;
            align-items: center;
            padding: 0.65rem 0.85rem;
            color: var(--dark-purple);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.88rem;
            border-radius: 10px;
            margin-bottom: 0.25rem;
            transition: all 0.2s ease-in-out;
        }

        .nav-item-link i {
            width: 24px;
            font-size: 1.1rem;
            color: var(--dark-purple);
        }

        .nav-item-link:hover, .nav-item-link.active {
            color: var(--primary-purple) !important;
            background-color: var(--light-purple) !important;
        }

        .nav-item-link:hover i, .nav-item-link.active i {
            color: var(--primary-purple) !important;
        }

        .main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .top-navbar {
            background: #ffffff;
            border-bottom: 1px solid #E2E8F0;
            padding: 0.85rem 1.5rem;
        }

        .content-body {
            padding: 2rem 1.5rem;
            flex: 1;
        }

        @media (max-width: 991.98px) {
            .sidebar { margin-left: calc(-1 * var(--sidebar-width)); }
            .main-wrapper { margin-left: 0; }
            .sidebar.show { margin-left: 0; }
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Sidebar Wrapper -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand d-flex align-items-center justify-content-between">
            <a href="{{ route('home') }}" class="d-flex align-items-center text-decoration-none">
                <img src="{{ asset('images/logo-lokavino.png') }}" alt="Lokavino Logo" style="height: 32px; width: auto;" class="me-2">
                <span class="badge rounded-pill" style="background-color: var(--light-purple); color: var(--primary-purple); border: 1px solid var(--border-purple); font-size: 0.7rem;">
                    {{ auth()->user()->isAdmin() ? 'Admin Panel' : 'Owner Panel' }}
                </span>
            </a>
            <button class="btn btn-sm text-secondary d-lg-none" onclick="toggleSidebar()"><i class="fa-solid fa-xmark fs-5"></i></button>
        </div>

        <div class="sidebar-menu">
            @if(auth()->user()->isAdmin())
                <div class="menu-header">Utama Admin</div>
                <a href="{{ route('admin.dashboard') }}" class="nav-item-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-line me-2"></i> Dashboard Statistik
                </a>

                <div class="menu-header">Verifikasi & Pengawasan</div>
                <a href="{{ route('admin.owner-verifications.index') }}" class="nav-item-link {{ request()->routeIs('admin.owner-verifications.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-id-card me-2"></i> Verifikasi Akun Owner
                </a>
                <a href="{{ route('admin.verifications.index') }}" class="nav-item-link {{ request()->routeIs('admin.verifications.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-clipboard-check me-2"></i> Verifikasi Tempat Usaha
                </a>

                <div class="menu-header">Kelola Data</div>
                <a href="{{ route('admin.tourist-places.index') }}" class="nav-item-link {{ request()->routeIs('admin.tourist-places.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-mountain-sun me-2"></i> Data Wisata
                </a>
                <a href="{{ route('admin.hangout-places.index') }}" class="nav-item-link {{ request()->routeIs('admin.hangout-places.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-mug-hot me-2"></i> Data Nongkrong
                </a>
                <a href="{{ route('admin.lodgings.index') }}" class="nav-item-link {{ request()->routeIs('admin.lodgings.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-hotel me-2"></i> Data Penginapan
                </a>
            @elseif(auth()->user()->isOwner())
                <div class="menu-header">Menu Utama</div>
                <a href="{{ route('owner.dashboard') }}" class="nav-item-link {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-line me-2"></i> Dashboard & Analitik
                </a>
                <a href="{{ route('owner.lodgings.index') }}" class="nav-item-link {{ request()->routeIs('owner.lodgings.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-store me-2"></i> Kelola Usaha
                </a>
                <a href="{{ route('owner.reports.index') }}" class="nav-item-link {{ request()->routeIs('owner.reports.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-pie me-2"></i> Laporan Kunjungan
                </a>
                <div class="menu-header">Sistem</div>
                <a href="{{ route('owner.profile') }}" class="nav-item-link {{ request()->routeIs('owner.profile') ? 'active' : '' }}">
                    <i class="fa-solid fa-gear me-2"></i> Pengaturan
                </a>
            @endif

            <div class="menu-header">Lainnya</div>
            <a href="{{ route('home') }}" class="nav-item-link" target="_blank">
                <i class="fa-solid fa-globe me-2"></i> Halaman Publik
            </a>
        </div>
    </aside>

    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <header class="top-navbar d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-light d-lg-none" onclick="toggleSidebar()"><i class="fa-solid fa-bars"></i></button>
                <h5 class="fw-bold mb-0 text-dark">@yield('page-title', 'Dashboard')</h5>
            </div>

            <div class="d-flex align-items-center gap-3">
                <div class="text-end d-none d-sm-block">
                    <div class="fw-bold text-dark mb-0">{{ auth()->user()->name }}</div>
                    <small class="text-muted text-capitalize"><i class="fa-solid fa-shield-halved me-1"></i>{{ auth()->user()->role->label ?? 'User' }}</small>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                        <i class="fa-solid fa-power-off me-1"></i> Keluar
                    </button>
                </form>
            </div>
        </header>

        <div class="content-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
                    <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
                    <i class="fa-solid fa-circle-exclamation me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Isi konten spesifik per halaman akan masuk ke sini -->
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
        }
    </script>
    @yield('scripts')
</body>
</html>