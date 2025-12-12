<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Food Delivery')</title>

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    
    {{-- Global CSS (Pastikan file ini tidak menyembunyikan navbar) --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>
        body {
            background-color: #f6f8f5;
            font-family: 'Poppins', sans-serif;
            /* Pastikan body tidak terkunci */
            overflow-x: hidden; 
        }

        /* --- PERBAIKAN NAVBAR --- */
        .my-navbar {
            background: #2e7d32; /* Hijau Solid */
            position: relative; /* Jangan fixed/sticky dulu */
            z-index: 1050; /* Pastikan di atas */
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .nav-link {
            color: rgba(255,255,255,0.9) !important;
            font-weight: 500;
        }
        .nav-link:hover {
            color: #ffffff !important;
        }
        
        /* Tombol Logout */
        .btn-logout {
            border: 1px solid rgba(255,255,255,0.5);
            color: white;
            background: transparent;
        }
        .btn-logout:hover {
            background: #dc3545;
            border-color: #dc3545;
        }
    </style>
</head>
<body>

    {{-- NAVBAR --}}
    {{-- Saya ganti class 'navbar' jadi 'navbar my-navbar' biar tidak bentrok sama style.css lama --}}
    <nav class="navbar navbar-expand-lg navbar-dark my-navbar mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ url('/') }}">
                <i class="bi bi-basket2-fill me-2"></i>Food Delivery
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav ms-auto align-items-center gap-2">
                    
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Masuk</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-light text-success fw-bold rounded-pill px-4 ms-2" href="{{ route('register') }}">Daftar</a>
                        </li>
                    @else
                        {{-- Menu Customer --}}
                        @if(Auth::user()->role == 'customer')
                            <li class="nav-item"><a class="nav-link" href="{{ route('order.index') }}">Menu</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('order.history') }}">Riwayat</a></li>
                        @endif

                        {{-- Menu Restoran --}}
                        @if(Auth::user()->role == 'restaurant')
                            <li class="nav-item"><a class="nav-link" href="{{ route('restaurant.dashboard') }}">Dashboard</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('menus.index') }}">Kelola Menu</a></li>
                        @endif

                        <li class="nav-item ms-3 text-white small d-none d-lg-block">
                            Halo, {{ Auth::user()->name }}
                        </li>

                        {{-- Tombol Logout --}}
                        <li class="nav-item ms-2">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-logout rounded-pill px-3 py-1">
                                    Logout <i class="bi bi-box-arrow-right ms-1"></i>
                                </button>
                            </form>
                        </li>
                    @endguest

                </ul>
            </div>
        </div>
    </nav>
    {{-- AKHIR NAVBAR --}}

    {{-- KONTEN UTAMA --}}
    <div class="container" style="position: relative; z-index: 1;">
        {{-- Flash Message --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Yield Content --}}
        @yield('content')
    </div>

    <footer class="text-center py-4 mt-5 text-muted small bg-white border-top">
        <medium>&copy; {{ date('Y') }} Food Delivery System</medium>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>