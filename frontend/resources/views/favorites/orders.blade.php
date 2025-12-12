@extends('layouts.app')
@section('title', 'Repeat Order - Pesanan Favorit')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
    body { background-color: #f6f8f5; font-family: 'Poppins', sans-serif; }
    .favorite-orders-page { padding: 2rem 0; }
    .page-header { background: linear-gradient(135deg, #ff9800 0%, #ffb74d 100%); color: white; padding: 2rem; border-radius: 15px; margin-bottom: 2rem; box-shadow: 0 6px 20px rgba(255, 152, 0, 0.3); }
    .page-header h1 { font-weight: 700; margin: 0; }
    .card-order { border-radius: 15px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1); transition: all 0.3s ease; border: none; margin-bottom: 1.5rem; }
    .card-order:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0,0,0,0.15); }
    .order-image { width: 80px; height: 80px; object-fit: cover; border-radius: 10px; }
    .btn-repeat { background: linear-gradient(135deg, #ff9800 0%, #ffb74d 100%); border: none; color: white; font-weight: 600; }
    .btn-repeat:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(255, 152, 0, 0.4); }
    .btn-favorite { color: #dc3545; font-size: 1.3rem; border: none; background: transparent; cursor: pointer; transition: all 0.3s ease; }
    .btn-favorite:hover { transform: scale(1.2); }
    .empty-state { text-align: center; padding: 4rem 2rem; }
    .empty-state i { font-size: 5rem; color: #ddd; margin-bottom: 1rem; }
</style>

<div class="favorite-orders-page">
    <div class="container">
        <div class="page-header text-center">
            <h1><i class="bi bi-arrow-repeat me-2"></i>Repeat Order</h1>
            <p class="mb-0 mt-2">Pesan ulang pesanan favorit Anda dengan sekali klik</p>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="{{ route('order.history') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Riwayat
            </a>
            <span class="badge bg-warning fs-6">{{ $favoriteOrders->count() }} Pesanan Favorit</span>
        </div>

        @if($favoriteOrders->count() > 0)
            <div class="row">
                @foreach($favoriteOrders as $order)
                    <div class="col-12">
                        <div class="card card-order">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-2">
                                        @if($order->menu && $order->menu->image)
                                            <img src="{{ asset('storage/' . $order->menu->image) }}" class="order-image" alt="{{ $order->menu_name }}">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center order-image">
                                                <i class="bi bi-image text-muted"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <h5 class="fw-bold text-success mb-2">{{ $order->menu_name }}</h5>
                                        <div class="text-muted small">
                                            <div><i class="bi bi-person me-1"></i>{{ $order->customer_name }}</div>
                                            <div><i class="bi bi-geo-alt me-1"></i>{{ Str::limit($order->customer_address, 50) }}</div>
                                            <div><i class="bi bi-calendar me-1"></i>{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y, H:i') }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 text-center">
                                        <div class="mb-2">
                                            <strong class="text-success">Jumlah:</strong>
                                            <div class="h5 mb-0">{{ $order->quantity }}</div>
                                        </div>
                                        <div>
                                            <strong class="text-success">Total:</strong>
                                            <div class="h5 mb-0 text-success">Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 text-center">
                                        <form action="{{ route('favorites.repeat', $order->id) }}" method="POST" class="mb-2">
                                            @csrf
                                            <button type="submit" class="btn btn-repeat w-100 mb-2">
                                                <i class="bi bi-arrow-repeat me-1"></i> Repeat Order
                                            </button>
                                        </form>
                                        <button type="button" 
                                                class="btn-favorite-order btn-favorite" 
                                                data-order-id="{{ $order->id }}"
                                                title="Hapus dari favorit">
                                            <i class="bi bi-heart-fill"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <i class="bi bi-heart"></i>
                <h4 class="text-muted">Belum Ada Pesanan Favorit</h4>
                <p class="text-muted">Tandai pesanan di halaman riwayat untuk mengulang pesanan dengan mudah</p>
                <a href="{{ route('order.history') }}" class="btn btn-warning mt-3">
                    <i class="bi bi-clock-history me-1"></i> Lihat Riwayat Pesanan
                </a>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle favorite order
    document.querySelectorAll('.btn-favorite-order').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const orderId = this.getAttribute('data-order-id');
            const card = this.closest('.col-12');
            
            fetch(`/favorites/order/${orderId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && !data.is_favorite) {
                    // Hapus card dengan animasi
                    card.style.transition = 'all 0.3s ease';
                    card.style.opacity = '0';
                    card.style.transform = 'translateX(-20px)';
                    setTimeout(() => {
                        card.remove();
                        // Reload jika tidak ada order lagi
                        if (document.querySelectorAll('.col-12').length === 0) {
                            location.reload();
                        }
                    }, 300);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
});
</script>

@endsection

