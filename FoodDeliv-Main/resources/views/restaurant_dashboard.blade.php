@extends('layouts.app')

@section('title', 'Pesanan Masuk')

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
                <i class="bi bi-receipt me-2"></i>Daftar Pesanan Masuk
            </h3>
            <small class="text-muted">Diperbarui {{ now()->format('d M Y, H:i') }}</small>
        </div>

        @php
            $pendingCount = count(array_filter($orders, fn($o) => $o['status'] === 'pending'));
            $doneCount = count(array_filter($orders, fn($o) => $o['status'] === 'done'));
            $totalCount = count($orders);
        @endphp

        <div class="row mb-4 g-3">
            <div class="col-md-4">
                <div class="card-stat">Pending: {{ $pendingCount }}</div>
            </div>
            <div class="col-md-4">
                <div class="card-stat">Done: {{ $doneCount }}</div>
            </div>
            <div class="col-md-4">
                <div class="card-stat">Total Pesanan: {{ $totalCount }}</div>
            </div>
        </div>

        <div class="content-area mt-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="text-muted">
                        <tr>
                            <th>ID</th>
                            <th>Menu</th>
                            <th>Pelanggan</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-end">Total</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Waktu Order</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td><strong class="text-success">#{{ $order['id'] }}</strong></td>
                                <td>{{ Str::limit($order['menu_name'] ?? 'N/A', 30) }}</td>
                                <td>{{ $order['customer_name'] }}</td>
                                <td class="text-center">{{ $order['quantity'] }}</td>
                                <td class="text-end text-success fw-semibold">
                                    Rp {{ number_format($order['total_price'], 0, ',', '.') }}
                                </td>
                                <td class="text-center">
                                    @php
                                        $statusColor = match($order['status']) {
                                            'pending' => 'warning',
                                            'confirmed' => 'info',
                                            'done' => 'success',
                                            default => 'danger',
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $statusColor }}">
                                        {{ ucfirst($order['status']) }}
                                    </span>
                                </td>
                                <td class="text-center small text-muted">
                                    {{ \Carbon\Carbon::parse($order['created_at'])->format('d M H:i') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <div class="fs-5">🎉 Tidak ada pesanan aktif saat ini</div>
                                    <small>Pesanan baru akan muncul di sini.</small>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
