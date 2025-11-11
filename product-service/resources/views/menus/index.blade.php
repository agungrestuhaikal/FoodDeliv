@extends('layouts.app')

@section('title', 'Daftar Menu & Pesanan')

@section('content')

<!-- =========================
     CARD MENU
============================== -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Daftar Menu</h2>
    <a href="{{ route('menus.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Menu
    </a>
</div>

<div class="row g-4 mb-5">
    @forelse($menus as $menu)
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                @if($menu['image_url'])
                    <img src="{{ $menu['image_url'] }}" class="card-img-top" style="height:180px; object-fit:cover;">
                @endif
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $menu['name'] }}</h5>
                    @if($menu['description'])
                        <p class="card-text text-muted flex-grow-1">
                            {{ Str::limit($menu['description'], 60) }}
                        </p>
                    @endif
                    <div class="mt-auto d-flex justify-content-between align-items-center mb-2">
                        <span class="h6 text-success">Rp {{ number_format($menu['price'],0,',','.') }}</span>
                        <span class="badge bg-info">{{ $menu['category'] }}</span>
                    </div>
                    <div class="btn-group w-100" role="group">
                        <a href="{{ route('menus.show', $menu['id']) }}" class="btn btn-sm btn-outline-info" title="Lihat">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('menus.edit', $menu['id']) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('menus.destroy', $menu['id']) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus"
                                    onclick="return confirm('Hapus menu ini?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <p class="text-muted">Belum ada menu.</p>
        </div>
    @endforelse
</div>

<!-- =========================
     PESANAN MASUK
============================== -->
<hr class="my-5">
<h3>Pesanan Masuk</h3>

@php
    $ordersResponse = Illuminate\Support\Facades\Http::get('http://127.0.0.1:5003/orders');
    $orders = $ordersResponse->successful() ? $ordersResponse->json() : [];
@endphp

<div class="card border-0 shadow">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>ID</th>
                        <th>Menu</th>
                        <th>Pelanggan</th>
                        <th>Jumlah</th>
                        <th>Total</th>
                        <th>Meja</th>
                        <th>Status</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td><strong>#{{ $order['id'] }}</strong></td>
                            <td>{{ $order['menu_name'] }}</td>
                            <td>{{ $order['customer_name'] }}</td>
                            <td>{{ $order['quantity'] }}</td>
                            <td>Rp {{ number_format($order['total_price'], 0, ',', '.') }}</td>
                            <td>{{ $order['table_number'] ?: '-' }}</td>
                            <td>
                                <span class="badge bg-{{ 
                                    $order['status'] == 'pending' ? 'warning' : 
                                    ($order['status'] == 'confirmed' ? 'info' : 
                                    ($order['status'] == 'done' ? 'success' : 'danger')) 
                                }}">
                                    {{ ucfirst($order['status']) }}
                                </span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($order['created_at'])->format('d/m H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                Belum ada pesanan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
