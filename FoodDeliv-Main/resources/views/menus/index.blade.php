@extends('layouts.app')

@section('title', 'Kelola Menu')

@section('content')

<link rel="stylesheet" href="{{ asset('css/style.css') }}">

<div class="layout-wrapper">
    <div class="sidebar">
        <div>
            <h4>
                <a href="{{ route('restaurant.dashboard') }}">
                    <i class="bi bi-egg-fried me-2"></i> Food Delivery
                </a>
            </h4>
            <a href="{{ route('restaurant.dashboard') }}" 
               class="{{ request()->routeIs('restaurant.dashboard') ? 'active' : '' }}">
               <i class="bi bi-receipt-cutoff me-2"></i> Pesanan Masuk
            </a>
            <a href="{{ route('menus.index') }}" 
               class="{{ request()->routeIs('menus.index') ? 'active' : '' }}">
               <i class="bi bi-list-ul me-2"></i> Kelola Menu
            </a>
        </div>

        <form action="/" method="GET" class="mt-auto">
            <button type="submit" class="logout-btn">
                <i class="bi bi-box-arrow-right me-2"></i> Logout
            </button>
        </form>
    </div>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-semibold text-success">
                <i class="bi bi-list-ul me-2"></i>Daftar Menu
            </h3>
            <a href="{{ route('menus.create') }}" class="btn btn-success shadow-sm">
                <i class="bi bi-plus-lg"></i> Tambah Menu
            </a>
        </div>

        <div class="row g-4">
            @forelse($menus as $menu)
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 rounded-4 h-100">
                        @if($menu['image_url'])
                            <img src="{{ $menu['image_url'] }}" class="card-img-top rounded-top-4" style="height: 180px; object-fit: cover;">
                        @endif
                        <div class="card-body d-flex flex-column">
                            <h5 class="fw-semibold">{{ $menu['name'] }}</h5>
                            @if($menu['description'])
                                <p class="text-muted small flex-grow-1">
                                    {{ Str::limit($menu['description'], 60) }}
                                </p>
                            @endif
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-success fw-semibold">
                                    Rp {{ number_format($menu['price'], 0, ',', '.') }}
                                </span>
                                <span class="badge bg-info text-dark">{{ $menu['category'] }}</span>
                            </div>
                            <div class="btn-group w-100" role="group">
                                <a href="{{ route('menus.show', $menu['id']) }}" class="btn btn-outline-success btn-sm">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('menus.edit', $menu['id']) }}" class="btn btn-outline-warning btn-sm">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('menus.destroy', $menu['id']) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm"
                                            onclick="return confirm('Hapus menu ini?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted">Belum ada menu yang tersedia.</p>
                    <a href="{{ route('menus.create') }}" class="btn btn-success">
                        <i class="bi bi-plus-lg"></i> Tambahkan Menu Pertama
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</div>

@endsection
