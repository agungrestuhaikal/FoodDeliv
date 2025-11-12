@extends('layouts.app')
@section('title', 'Customer - Selamat Datang')

@section('content')

{{-- Bootstrap Icons --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
    body {
        background-color: #f6f8f5;
        font-family: 'Poppins', sans-serif;
        position: relative;
    }

    .logout-wrapper {
        position: absolute;
        top: 20px;
        right: 25px;
        z-index: 1000;
    }

    .btn-logout {
        background-color: transparent;
        color: #2e7d32;
        border: 1.8px solid #2e7d32;
        font-size: 0.9rem;
        font-weight: 500;
        border-radius: 25px;
        padding: 0.4rem 1rem;
        transition: all 0.3s ease;
    }

    .btn-logout:hover {
        background-color: #e8f5e9;
        transform: translateY(-2px);
    }

    .hero-section {
        text-align: center;
        padding: 6rem 1rem 4rem;
        background: linear-gradient(135deg, #2e7d32 0%, #4caf50 100%);
        color: white;
        border-radius: 0 0 50px 50px;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }

    .hero-title {
        font-weight: 700;
        font-size: 2.8rem;
        margin-bottom: 1rem;
        letter-spacing: 1px;
    }

    .hero-subtitle {
        font-size: 1.2rem;
        font-weight: 400;
        margin-bottom: 2rem;
        color: #e8f5e9;
    }

    .btn-main {
        background-color: #ffffff;
        color: #2e7d32;
        font-weight: 600;
        padding: 0.9rem 2.5rem;
        border-radius: 30px;
        transition: all 0.3s ease;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        text-decoration: none;
    }

    .btn-main:hover {
        background-color: #e8f5e9;
        transform: translateY(-2px);
    }

    .features {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 2rem;
        margin-top: 4rem;
        text-align: center;
    }

    .feature-card {
        background-color: white;
        border-radius: 20px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        padding: 2rem;
        width: 260px;
        transition: 0.3s ease;
    }

    .feature-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 8px 18px rgba(0,0,0,0.1);
    }

    .feature-icon {
        font-size: 2.5rem;
        color: #2e7d32;
        margin-bottom: 1rem;
    }

    .feature-title {
        font-weight: 600;
        color: #2e7d32;
        margin-bottom: 0.5rem;
    }

    nav.menu-nav {
        margin-top: 2rem;
        display: flex;
        justify-content: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    nav.menu-nav a {
        font-weight: 500;
        border-radius: 30px;
        padding: 0.6rem 1.4rem;
        transition: 0.3s;
    }

    nav.menu-nav a:hover {
        transform: translateY(-2px);
    }
</style>

<div class="logout-wrapper">
    <form action="/" method="GET">
        <button type="submit" class="btn-logout">
            <i class="bi bi-box-arrow-right me-1"></i> Logout
        </button>
    </form>
</div>

<nav class="menu-nav">
    <a href="{{ route('order.index') }}" class="btn btn-success shadow-sm">
        <i class="bi bi-basket-fill me-1"></i> Pesan Sekarang (Menu)
    </a>
    <a href="{{ route('order.history') }}" class="btn btn-outline-success shadow-sm">
        <i class="bi bi-clock-history me-1"></i> Riwayat Order & Review
    </a>
</nav>

<section class="hero-section mt-4">
    <h1 class="hero-title"><i class="bi bi-emoji-smile me-2"></i>Selamat Datang, Pelanggan!</h1>
    <p class="hero-subtitle">Pesan menu favorit Anda dengan mudah dan nikmati pengalaman kuliner terbaik 🍽️</p>
    <a href="{{ route('order.create') }}" class="btn btn-main">
        <i class="bi bi-shop-window me-2"></i> Lihat Menu & Pesan
    </a>
</section>

<div class="container mt-5">
    <div class="features">
        <div class="feature-card">
            <div class="feature-icon"><i class="bi bi-lightning-charge-fill"></i></div>
            <h5 class="feature-title">Proses Cepat</h5>
            <p class="text-muted small">Pesan makanan favorit Anda hanya dalam beberapa klik tanpa ribet!</p>
        </div>

        <div class="feature-card">
            <div class="feature-icon"><i class="bi bi-bag-check-fill"></i></div>
            <h5 class="feature-title">Pilihan Lengkap</h5>
            <p class="text-muted small">Dari makanan berat hingga dessert — semua tersedia untuk Anda.</p>
        </div>

        <div class="feature-card">
            <div class="feature-icon"><i class="bi bi-cup-hot-fill"></i></div>
            <h5 class="feature-title">Kualitas Terbaik</h5>
            <p class="text-muted small">Kami hanya menggunakan bahan segar agar rasa selalu sempurna.</p>
        </div>

        <div class="feature-card">
            <div class="feature-icon"><i class="bi bi-star-fill"></i></div>
            <h5 class="feature-title">Ulasan Pelanggan</h5>
            <p class="text-muted small">Baca dan bagikan pengalaman kulinermu dengan pengguna lainnya!</p>
        </div>
    </div>
</div>
@endsection
