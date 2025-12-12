@extends('layouts.app')

@section('title', 'Pesanan Masuk')

@section('content')

{{-- Pastikan style.css ter-load --}}
<link rel="stylesheet" href="{{ asset('css/style.css') }}">

<div class="layout-wrapper">
    {{-- SIDEBAR --}}
    <div class="sidebar">
        <div>
            <h4 class="mb-4">
                <a href="{{ route('restaurant.dashboard') }}" class="text-decoration-none text-white fw-bold">
                    <i class="bi bi-egg-fried me-2"></i> Food Delivery
                </a>
            </h4>
            
            <a href="{{ route('restaurant.dashboard') }}" 
               class="{{ request()->routeIs('restaurant.dashboard') ? 'active' : '' }}">
               <i class="bi bi-receipt-cutoff me-2"></i> Pesanan Masuk
            </a>
            
            {{-- Menu Kelola Makanan --}}
            <a href="{{ route('menus.index') }}" 
               class="{{ request()->routeIs('menus.index') ? 'active' : '' }}">
               <i class="bi bi-list-ul me-2"></i> Kelola Menu
            </a>
        </div>

        {{-- PERBAIKAN PENTING DI SINI: route('logout') bukan 'logout.custom' --}}
        <form action="{{ route('logout') }}" method="POST" class="mt-auto">
            @csrf
            <button type="submit" class="logout-btn w-100 text-start">
                <i class="bi bi-box-arrow-right me-2"></i> Logout
            </button>
        </form>
    </div>

    {{-- KONTEN UTAMA --}}
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-semibold text-success">
                <i class="bi bi-receipt me-2"></i>Daftar Pesanan Masuk
            </h3>
            <small class="text-muted">Diperbarui {{ now()->format('d M Y, H:i') }}</small>
        </div>

        {{-- Statistik Ringkas --}}
        @php
            // Menghitung status dari Collection
            $pendingCount = $orders->where('status', 'pending')->count();
            $doneCount = $orders->where('status', 'done')->count();
            $totalCount = $orders->count();
        @endphp

        <div class="row mb-4 g-3">
            <div class="col-md-4">
                <div class="card-stat bg-white p-3 rounded shadow-sm border-start border-4 border-warning">
                    <small class="text-muted fw-bold">PENDING</small>
                    <div class="fs-4 fw-bold text-warning">{{ $pendingCount }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-stat bg-white p-3 rounded shadow-sm border-start border-4 border-success">
                    <small class="text-muted fw-bold">SELESAI</small>
                    <div class="fs-4 fw-bold text-success">{{ $doneCount }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-stat bg-white p-3 rounded shadow-sm border-start border-4 border-primary">
                    <small class="text-muted fw-bold">TOTAL PESANAN</small>
                    <div class="fs-4 fw-bold text-primary">{{ $totalCount }}</div>
                </div>
            </div>
        </div>

        {{-- Tabel Pesanan --}}
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted">
                            <tr>
                                <th class="ps-4">ID</th>
                                <th>Menu</th>
                                <th>Pelanggan</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-end">Total</th>
                                <th class="text-center">Status</th>
                                <th class="text-center pe-4">Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    {{-- Menggunakan Object Access ($order->id) --}}
                                    <td class="ps-4"><strong class="text-success">#{{ $order->id }}</strong></td>
                                    
                                    <td>
                                        <div class="fw-bold text-dark">{{ Str::limit($order->menu_name ?? 'Menu Dihapus', 30) }}</div>
                                    </td>
                                    
                                    <td>
                                        <div class="fw-semibold">{{ $order->customer_name }}</div>
                                        <small class="text-muted" style="font-size: 0.75rem;">{{ Str::limit($order->customer_address, 20) }}</small>
                                    </td>
                                    
                                    <td class="text-center">{{ $order->quantity }}</td>
                                    
                                    <td class="text-end text-success fw-bold">
                                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                    </td>
                                    
                                    <td class="text-center">
                                        @php
                                            $badgeClass = match($order->status) {
                                                'pending' => 'bg-warning text-dark',
                                                'confirmed' => 'bg-info text-white',
                                                'done', 'completed' => 'bg-success text-white',
                                                default => 'bg-secondary text-white',
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }} rounded-pill px-3">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    
                                    <td class="text-center pe-4 small text-muted">
                                        {{ $order->created_at->format('d M H:i') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2 opacity-25"></i>
                                        <div>Belum ada pesanan masuk.</div>
                                    </td>
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