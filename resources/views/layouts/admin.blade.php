<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard GREX')</title>
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --sidebar-width: 260px;
            --primary-color: #4D3EA3;
            --bg-light: #E1DAFB;
            --brand-dark: #450C3F;
            --brand-accent: #758AD1;
            --brand-highlight: #FFD2F4;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-light);
            min-height: 100vh;
        }

        /* Sidebar Styling */
        .sidebar {
            width: var(--sidebar-width);
            background: #450C3F;
            color: #E1DAFB;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            overflow-y: auto;
            transition: all 0.3s;
        }

        .sidebar-brand {
            padding: 1.5rem 1.25rem;
            font-size: 1.35rem;
            font-weight: 800;
            color: #ffffff;
            border-bottom: 1px solid rgba(255, 210, 244, 0.15);
        }

        .sidebar-menu {
            padding: 1rem 0;
        }

        .menu-header {
            padding: 0.75rem 1.25rem 0.25rem;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #FFD2F4;
        }

        .nav-item-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.25rem;
            color: #E1DAFB;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }

        .nav-item-link:hover, .nav-item-link.active {
            color: #ffffff;
            background: #4D3EA3;
            border-left-color: #FFD2F4;
        }

        .nav-item-link i {
            width: 24px;
            font-size: 1.1rem;
        }

        /* Main Content Wrapper */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .top-navbar {
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 0.75rem 1.5rem;
        }

        .content-body {
            padding: 2rem 1.5rem;
            flex: 1;
        }

        /* Stat Card */
        .stat-card {
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            background: #ffffff;
            padding: 1.25rem;
            transition: all 0.2s;
        }
        .stat-card:hover {
            box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        }

        .icon-box {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
        }

        @media (max-width: 991.98px) {
            .sidebar {
                margin-left: calc(-1 * var(--sidebar-width));
            }
            .main-wrapper {
                margin-left: 0;
            }
            .sidebar.show {
                margin-left: 0;
            }
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand d-flex align-items-center justify-content-between">
            <div><i class="fa-solid fa-compass text-primary me-2"></i>GREX Panel</div>
            <button class="btn btn-sm text-white d-lg-none" onclick="toggleSidebar()"><i class="fa-solid fa-times"></i></button>
        </div>

        <div class="sidebar-menu">
            @if(auth()->user()->isAdmin())
                <div class="menu-header">Utama Admin</div>
                <a href="{{ route('admin.dashboard') }}" class="nav-item-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-line"></i> Dashboard Statistik
                </a>
                <a href="{{ route('admin.verifications.index') }}" class="nav-item-link {{ request()->routeIs('admin.verifications.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-clipboard-check"></i> Verifikasi Penginapan
                </a>

                <div class="menu-header">Kelola Data</div>
                <a href="{{ route('admin.tourist-places.index') }}" class="nav-item-link {{ request()->routeIs('admin.tourist-places.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-mountain-sun"></i> Data Wisata
                </a>
                <a href="{{ route('admin.hangout-places.index') }}" class="nav-item-link {{ request()->routeIs('admin.hangout-places.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-mug-hot"></i> Data Nongkrong
                </a>
                <a href="{{ route('admin.lodgings.index') }}" class="nav-item-link {{ request()->routeIs('admin.lodgings.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-hotel"></i> Data Penginapan
                </a>
                <a href="{{ route('admin.categories.index') }}" class="nav-item-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-layer-group"></i> Master Kategori
                </a>
                <a href="{{ route('admin.facilities.index') }}" class="nav-item-link {{ request()->routeIs('admin.facilities.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-list-check"></i> Master Fasilitas
                </a>
                <a href="{{ route('admin.users.index') }}" class="nav-item-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-users-gear"></i> Kelola User & Owner
                </a>

            @elseif(auth()->user()->isOwner())
                <div class="menu-header">Utama Owner</div>
                <a href="{{ route('owner.dashboard') }}" class="nav-item-link {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-pie"></i> Dashboard Owner
                </a>
                <a href="{{ route('owner.lodgings.index') }}" class="nav-item-link {{ request()->routeIs('owner.lodgings.index') ? 'active' : '' }}">
                    <i class="fa-solid fa-store"></i> Daftar Usaha Saya
                </a>
                <a href="{{ route('owner.lodgings.create') }}" class="nav-item-link {{ request()->routeIs('owner.lodgings.create') ? 'active' : '' }}">
                    <i class="fa-solid fa-plus-circle"></i> Ajukan Usaha Baru
                </a>
                <a href="{{ route('owner.claim.index') }}" class="nav-item-link {{ request()->routeIs('owner.claim.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-hand-holding-hand"></i> Klaim Tempat Usaha
                </a>

                <div class="menu-header">Pengaturan</div>
                <a href="{{ route('owner.profile') }}" class="nav-item-link {{ request()->routeIs('owner.profile') ? 'active' : '' }}">
                    <i class="fa-solid fa-id-card"></i> Profil Usaha
                </a>
            @endif

            <div class="menu-header">Navigasi Publik</div>
            <a href="{{ route('home') }}" class="nav-item-link" target="_blank">
                <i class="fa-solid fa-globe"></i> Halaman Publik
            </a>
        </div>
    </aside>

    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <!-- Top Navbar -->
        <header class="top-navbar d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-light d-lg-none" onclick="toggleSidebar()"><i class="fa-solid fa-bars"></i></button>
                <h5 class="fw-bold mb-0 text-dark">@yield('page-title', 'Dashboard')</h5>
            </div>

            <div class="d-flex align-items-center gap-3">
                <div class="text-end d-none d-sm-block">
                    <div class="fw-bold text-dark mb-0">{{ auth()->user()->name }}</div>
                    <small class="text-muted text-capitalize"><i class="fa-solid fa-shield-halved me-1"></i>{{ auth()->user()->role->label }}</small>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                        <i class="fa-solid fa-power-off me-1"></i> Keluar
                    </button>
                </form>
            </div>
        </header>

        <!-- Content Body -->
        <div class="content-body">
            <!-- Flash Messages -->
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

            @yield('content')
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
        }
    </script>
    @yield('scripts')
</body>
</html>
