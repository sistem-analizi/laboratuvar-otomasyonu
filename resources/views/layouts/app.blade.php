<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'LabSistem Otomasyonu')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            overflow-x: hidden;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
        }

        .site-wrapper {
            width: 100%;
            min-height: 100vh;
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table-responsive table {
            min-width: 800px;
        }

        .table-responsive th,
        .table-responsive td {
            white-space: nowrap;
            vertical-align: middle;
        }

        .table-responsive thead th,
        .table thead th {
            z-index: 1 !important;
        }

        .lab-navbar {
            background: linear-gradient(135deg, #264371 0%, #1e293b 100%);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            padding: 12px 0;
            z-index: 5000 !important;
        }

        .lab-navbar .container-fluid {
            max-width: none;
        }

        .lab-navbar .navbar-brand {
            font-weight: 800;
            letter-spacing: 0.3px;
            font-size: 1.15rem;
            white-space: nowrap;
        }

        .brand-icon {
            width: 40px !important;
            height: 40px !important;
            min-width: 40px !important;
        }

        .lab-nav-link {
            color: #94a3b8 !important;
            font-weight: 500;
            padding: 8px 10px !important;
            border-radius: 12px;
            transition: all 0.25s ease;
            display: flex;
            align-items: center;
            gap: 6px;
            margin: 0 2px;
            white-space: nowrap;
            font-size: 0.86rem;
        }

        .lab-nav-link:hover {
            background-color: rgba(255, 255, 255, 0.06);
            color: #ffffff !important;
        }

        .lab-nav-link.active {
            background-color: #3b82f6;
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        }

        .lab-dropdown-menu {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            padding: 12px;
            margin-top: 12px;
        }

        .lab-dropdown-item {
            border-radius: 8px;
            padding: 10px 15px;
            font-weight: 500;
            color: #eae1e1;
            transition: all 0.2s ease;
        }

        .lab-dropdown-item:hover {
            background-color: #f8fafc;
            color: #2563eb;
        }

        .navbar-profile {
            white-space: nowrap;
        }

        .profile-text {
            display: none !important;
        }

        /*
            ÖNEMLİ:
            navbar-expand-xxl kullanıyorsan Bootstrap 1400px altında menüyü mobil yapar.
            O yüzden bizim mobil CSS de 1399.98px altında çalışmalı.
        */
        @media (max-width: 1399.98px) {
            .navbar-collapse {
                margin-top: 15px;
                padding-top: 15px;
                border-top: 1px solid rgba(255, 255, 255, 0.15);
                position: relative;
                z-index: 5001;
            }

            .navbar-nav {
                width: 100%;
            }

            .lab-nav-link {
                width: 100%;
                margin: 4px 0;
                padding: 12px 14px !important;
                font-size: 0.95rem;
                justify-content: flex-start;
            }

            .navbar .dropdown-menu {
                position: static !important;
                float: none !important;
                transform: none !important;
                inset: auto !important;
                width: 100%;
                margin-top: 8px;
                box-shadow: none;
                border: none;
            }

            .navbar .dropdown-menu-end {
                right: auto !important;
                left: auto !important;
            }

            .navbar-profile {
                width: 100%;
                margin-top: 12px;
                padding-top: 12px;
                border-top: 1px solid rgba(255, 255, 255, 0.15);
            }

            .navbar-profile > .dropdown {
                width: 100%;
            }

            .navbar-profile > .dropdown > a {
                width: 100%;
                justify-content: flex-start;
            }
        }

        @media (min-width: 1400px) {
            .navbar .dropdown-menu {
                position: absolute;
            }
        }
    </style>

    @yield('styles')
</head>
<body>

<div class="site-wrapper">

    <nav class="navbar navbar-expand-xxl lab-navbar sticky-top" data-bs-theme="dark">
        <div class="container-fluid px-3 px-xl-4">

            <a class="navbar-brand text-white d-flex align-items-center gap-3" href="{{ url('/') }}">
                <div
                    class="brand-icon bg-primary text-white rounded-3 d-flex align-items-center justify-content-center shadow-sm">
                    <i class="fa-solid fa-microscope fa-lg"></i>
                </div>
                LabSistem
            </a>
            <button class="navbar-toggler border-0 shadow-none" type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#anaMenu"
                    aria-controls="anaMenu"
                    aria-expanded="false"
                    aria-label="Menüyü Aç/Kapat">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="anaMenu">
                <ul class="navbar-nav me-xxl-auto mb-2 mb-xxl-0">
                    <li class="nav-item">
                        <a class="nav-link lab-nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="fa-solid fa-book-open"></i> Katalog
                        </a>
                    </li>

                    @if(session('rol_id') == 2)
                        <li class="nav-item"><a
                                class="nav-link lab-nav-link {{ request()->is('urun-tanit*') ? 'active' : '' }}"
                                href="{{ url('urun-tanit') }}"><i class="fa-solid fa-plus-circle"></i> Yeni Cihaz</a></li>
                        <li class="nav-item"><a
                                class="nav-link lab-nav-link {{ request()->is('stok-giris*') ? 'active' : '' }}"
                                href="{{ url('stok-giris') }}"><i class="fa-solid fa-boxes-stacked"></i> Stok Girişi</a></li>
                        <li class="nav-item dropdown">
                            <a class="nav-link lab-nav-link dropdown-toggle {{ request()->is('odunc-ver*') || request()->is('kalici-zimmet-ver*') ? 'active' : '' }}"
                               href="#" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-handshake"></i> Zimmet İşlemleri
                            </a>
                            <ul class="dropdown-menu lab-dropdown-menu">
                                <li><a class="dropdown-item lab-dropdown-item" href="{{ url('odunc-ver') }}"><i
                                            class="fa-solid fa-clock-rotate-left me-2"></i> Süreli Ödünç</a></li>
                                <li><a class="dropdown-item lab-dropdown-item" href="{{ url('kalici-zimmet-ver') }}"><i
                                            class="fa-solid fa-shield-halved me-2"></i> Kalıcı Zimmetler</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link lab-nav-link {{ request()->is('geciken-teslimatlar*') ? 'active' : '' }}"
                               href="{{ url('geciken-teslimatlar') }}">
                                <i class="fa-solid fa-circle-exclamation"></i> Geciken Teslimatlar
                            </a>
                        </li>
                        <li class="nav-item"><a
                                class="nav-link lab-nav-link {{ request()->is('kullanicilar*') ? 'active' : '' }}"
                                href="{{ url('kullanicilar') }}"><i class="fa-solid fa-users"></i> Kullanıcılar</a></li>
                        <li class="nav-item"><a
                                class="nav-link lab-nav-link {{ request()->is('ayarlar*') ? 'active' : '' }}"
                                href="{{ url('ayarlar') }}"><i class="fa-solid fa-gear"></i> Sistem Ayarları</a></li>
                    @endif
                </ul>

                <div class="navbar-profile dropdown ms-xxl-auto mt-3 mt-xxl-0">
                    <div class="dropdown">
                        <a href="#"
                           class="d-flex align-items-center text-white text-decoration-none dropdown-toggle gap-3"
                           data-bs-toggle="dropdown">
                            <div class="profile-text text-end d-none d-xxl-block">
                        <span class="d-block fw-bold" style="font-size: 0.95rem;">
                                 {{ session('ad_soyad') ?? 'Kullanıcı' }}</span>
                                <small class="text-info" style="font-size: 0.8rem;">
                                    {{ session('rol_id') == 2 ? 'Yönetici Paneli' : (session('rol_id') == 3 ? 'Personel Paneli' : 'Öğrenci Paneli') }}
                                </small>
                            </div>
                            <img
                                src="https://ui-avatars.com/api/?name={{ urlencode(session('ad_soyad') ?? 'K') }}&background=3b82f6&color=fff&rounded=true"
                                alt="Profil" width="45" height="45"
                                class="rounded-circle border border-2 border-primary shadow-sm">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end lab-dropdown-menu shadow-lg"
                            style="min-width: 200px;">
                            <li><a class="dropdown-item lab-dropdown-item" href="{{ url('profil') }}"><i
                                        class="fa-regular fa-user me-2"></i> Profilim</a></li>
                            <li>
                                <hr class="dropdown-divider opacity-25">
                            </li>
                            <li><a class="dropdown-item lab-dropdown-item text-danger fw-bold" href="{{ url('cikis') }}"><i
                                        class="fa-solid fa-power-off me-2"></i> Çıkış Yap</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="py-4">
        @yield('content')
    </main>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>
