<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'GREX - Gresik Explore')</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- Custom CSS System GREX -->
    <style>
        :root {
            /* Palette Defaults */
            --page-bg: #E1DAFB;
            --brand-dark: #450C3F;
            --brand-primary: #4D3EA3;
            --brand-accent: #758AD1;
            --brand-highlight: #FFD2F4;
            --card-bg: #ffffff;
            --text-main: #2b2b2b;
            --text-muted: #6c757d;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--page-bg);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: background-color 0.3s ease;
        }

        /* Navbar */
        .navbar-grex {
            background: rgba(255, 255, 255, 0.94);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(77, 62, 163, 0.12);
            box-shadow: 0 4px 20px rgba(69, 12, 63, 0.05);
        }
        .navbar-brand-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #4D3EA3;
            text-decoration: none;
            padding: 0;
        }


        /* Icon Compass */
        .grex-logo-icon {
            font-size: 2rem;
            color: #4D3EA3;
            flex-shrink: 0;
        }


        /* Container teks */
        .grex-logo-text {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            justify-content: center;
            line-height: 1;
        }


        /* GREX */
        .grex-title {
            display: block;
            font-size: 1.4rem;
            font-weight: 800;
            letter-spacing: 0.5px;
            color: #4D3EA3;
        }


        /* Gresik Explore */
        .grex-subtitle {
            display: block;
            margin-top: 4px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.55rem;
            font-weight: 600;
            letter-spacing: 1.2px;
            color: #758AD1;
            white-space: nowrap;
        }
        .nav-link {
            font-weight: 600;
            color: #450C3F;
            padding: 0.5rem 1rem !important;
            border-radius: 8px;
            transition: all 0.2s ease;
        }
        .nav-link:hover, .nav-link.active {
            color: #4D3EA3;
            background: rgba(117, 138, 209, 0.15);
        }

        /* Posisi menu navbar */
        .grex-nav-center {
            display: flex;
            gap: 8px;
            margin-left: 70px !important;
            margin-right: auto !important;
        }

        /* Area tombol kanan */
        .navbar-grex .collapse > .d-flex {
            margin-left: auto;
            flex-shrink: 0;
        }

        /* Common Components */
        .card-grex {
            background: #ffffff;
            border: 1px solid rgba(117, 138, 209, 0.2);
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(69, 12, 63, 0.06);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card-grex:hover {
            transform: translateY(-6px);
            box-shadow: 0 18px 35px rgba(77, 62, 163, 0.15);
        }
        .card-grex-img {
            height: 210px;
            object-fit: cover;
            width: 100%;
        }

        /* Category Badges */
        .badge-grex-penginapan {
            background: #FFD2F4;
            color: #450C3F;
            font-weight: 700;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
        }
        .badge-grex-wisata {
            background: #E1DAFB;
            color: #4D3EA3;
            font-weight: 700;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
        }
        .badge-grex-nongkrong {
            background: #758AD1;
            color: #ffffff;
            font-weight: 700;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
        }

        /* Buttons */
        .btn-grex-primary {
            background-color: #4D3EA3;
            color: #ffffff;
            font-weight: 700;
            border: none;
            border-radius: 30px;
            padding: 0.6rem 1.6rem;
            transition: all 0.2s ease;
        }
        .btn-grex-primary:hover {
            background-color: #450C3F;
            color: #ffffff;
            box-shadow: 0 6px 15px rgba(77, 62, 163, 0.35);
        }

/* Footer */
footer {
    margin-top: auto;
    background: #8d8989ff;
    color: #3F3F3F;
    padding: 3.5rem 0 1.5rem;
}

footer a {
    color: #121212ff;
    text-decoration: none;
    transition: color 0.2s;
}

footer a:hover {
    color: #450C3F;
}

        /* =========================================
   BACKGROUND LANDING PAGE
   ========================================= */

/* Background utama halaman menjadi putih */
body {
    background-color: #ffffff !important;
}

/* Area utama */
main {
    background-color: #ffffff;
}


/* =========================================
   ABOUT GREX
   ========================================= */

.about-grex {
    background-color: #ffffff;
}


/* =========================================
   PANEL / CARD FITUR LAYANAN
   ========================================= */

.feature-card {
    background: #f4f1ff !important;
    border: 1px solid rgba(77, 62, 163, 0.10);
    border-radius: 22px;
    box-shadow: 0 10px 30px rgba(77, 62, 163, 0.08);
    transition: all 0.3s ease;
}

.feature-card:hover {
    transform: translateY(-8px);
    background: #eeeaff !important;
    box-shadow: 0 18px 40px rgba(77, 62, 163, 0.15);
}


/* =========================================
   ICON DI DALAM FEATURE CARD
   ========================================= */

.icon-box-feature {
    border-radius: 16px !important;
}


/* =========================================
   SECTION TITLE
   ========================================= */

.section-title {
    color: #450C3F;
}


/* =========================================
   CARA KERJA GREX
   ========================================= */

.step-card {
    background: #f8f7ff;
    border: 1px solid rgba(77, 62, 163, 0.10);
    border-radius: 18px;
    box-shadow: 0 8px 25px rgba(77, 62, 163, 0.06);
    transition: all 0.3s ease;
}

.step-card:hover {
    transform: translateY(-5px);
    background: #f2efff;
    box-shadow: 0 14px 30px rgba(77, 62, 163, 0.12);
}

    </style>
    @yield('styles')
</head>
<body class="@yield('body-class')">

    <!-- Navbar Sticky -->
    <nav class="navbar navbar-expand-lg sticky-top navbar-grex">
        <div class="container">
           <a class="navbar-brand navbar-brand-logo" href="{{ route('home') }}">
                <i class="fa-solid fa-compass grex-logo-icon"></i>

                <div class="grex-logo-text">
                    <span class="grex-title">GREX</span>
                    <span class="grex-subtitle">Gresik Explore</span>
                </div>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarGrex">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarGrex">
                <ul class="navbar-nav mb-2 mb-lg-0 grex-nav-center">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            <i class="fa-solid fa-house me-1"></i> Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('penginapan*') ? 'active' : '' }}" href="{{ route('penginapan') }}">
                            <i class="fa-solid fa-hotel me-1"></i> Penginapan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('wisata*') ? 'active' : '' }}" href="{{ route('wisata') }}">
                            <i class="fa-solid fa-mountain-sun me-1"></i> Wisata
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('nongkrong*') ? 'active' : '' }}" href="{{ route('nongkrong') }}">
                            <i class="fa-solid fa-mug-hot me-1"></i> Nongkrong
                        </a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-2">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-grex-primary px-3 btn-sm">
                                <i class="fa-solid fa-user-shield me-1"></i> Dashboard Admin
                            </a>
                        @elseif(auth()->user()->isOwner())
                            <a href="{{ route('owner.dashboard') }}" class="btn btn-grex-primary px-3 btn-sm">
                                <i class="fa-solid fa-store me-1"></i> Dashboard Owner
                            </a>
                        @endif
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger rounded-pill px-3 btn-sm fw-semibold">
                                <i class="fa-solid fa-sign-out-alt"></i> Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-dark rounded-pill px-4 fw-bold btn-sm">
                            <i class="fa-solid fa-right-to-bracket me-1"></i> Masuk
                        </a>
                        <a href="{{ route('register.owner') }}" class="btn btn-grex-primary px-4 btn-sm">
                            <i class="fa-solid fa-user-plus me-1"></i> Daftar Owner
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row g-4 mb-4">
                <div class="col-md-5">
                    <h5 class="text-white fw-bold mb-3"><i class="fa-solid fa-compass me-2"></i>GREX (Gresik Explore)</h5>
                    <p class="small text-white-50">
                        Platform pencarian informasi terpadu di Kabupaten Gresik. Mengintegrasikan tiga layanan utama: Penginapan, Wisata, dan Tempat Nongkrong untuk memudahkan perjalanan dan aktivitas Anda.
                    </p>
                </div>
                <div class="col-md-3 ms-auto">
                    <h6 class="text-white fw-bold mb-3">Navigasi Utama</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><a href="{{ route('home') }}"><i class="fa-solid fa-chevron-right me-1 small"></i> Beranda (Pencarian Utama)</a></li>
                        <li class="mb-2"><a href="{{ route('penginapan') }}"><i class="fa-solid fa-chevron-right me-1 small"></i> Penginapan</a></li>
                        <li class="mb-2"><a href="{{ route('wisata') }}"><i class="fa-solid fa-chevron-right me-1 small"></i> Wisata</a></li>
                        <li class="mb-2"><a href="{{ route('nongkrong') }}"><i class="fa-solid fa-chevron-right me-1 small"></i> Nongkrong</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h6 class="text-white fw-bold mb-3">Kontak & Informasi</h6>
                    <p class="small text-white-50 mb-1"><i class="fa-solid fa-location-dot me-2 text-warning"></i> Kab. Gresik, Jawa Timur</p>
                    <p class="small text-white-50 mb-1"><i class="fa-solid fa-envelope me-2 text-warning"></i> info@grex-explore.id</p>
                    <p class="small text-white-50"><i class="fa-solid fa-phone me-2 text-warning"></i> (031) 3981234</p>
                </div>
            </div>
            <hr class="border-secondary opacity-25">
            <div class="text-center small text-white-50">
                &copy; {{ date('Y') }} GREX - Gresik Explore. All rights reserved.
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
