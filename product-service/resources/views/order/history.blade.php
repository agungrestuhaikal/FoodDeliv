@extends('layouts.app')
@section('title', 'History Pesanan Saya')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">History Pesanan Saya</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- FORM FILTER NAMA PELANGGAN -->
    <form method="GET" action="{{ route('order.history') }}" class="mb-4">
        <div class="row g-2 align-items-center">
            <div class="col-md-6">
                <input type="text" name="customer_name" class="form-control" placeholder="Masukkan nama Anda"
                       value="{{ request()->input('customer_name') }}">
            </div>
            <div class="col-md-auto">
                <button type="submit" class="btn btn-success">Cari</button>
            </div>
        </div>
    </form>

    @php
        $allOrders = Illuminate\Support\Facades\Http::get('http://127.0.0.1:5003/orders')->successful()
                     ? Illuminate\Support\Facades\Http::get('http://127.0.0.1:5003/orders')->json()
                     : [];

        $customerName = request()->input('customer_name') ?? '';
        $myOrders = $customerName ? array_filter($allOrders, fn($o) => $o['customer_name'] === $customerName) : [];
    @endphp

    @if($customerName)
        @if(count($myOrders) > 0)
            <div class="card border-0 shadow">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Menu</th>
                                    <th>Jumlah</th>
                                    <th>Total</th>
                                    <th>Waktu</th>
                                    <th>Review</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($myOrders as $order)
                                    <tr>
                                        <td>{{ $order['menu_name'] }}</td>
                                        <td>{{ $order['quantity'] }}</td>
                                        <td>Rp {{ number_format($order['total_price'], 0, ',', '.') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($order['created_at'])->format('d/m H:i') }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#reviewModal{{ $order['id'] }}">
                                                Review
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Modal Review -->
                                    <div class="modal fade" id="reviewModal{{ $order['id'] }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Beri Ulasan: {{ $order['menu_name'] }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('order.reviews.store') }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <input type="hidden" name="menu_id" value="{{ $order['menu_id'] }}">
                                                        <input type="hidden" name="customer_name" value="{{ $customerName }}">

                                                        <div class="mb-3">
                                                            <label>Rating</label>
                                                            <div class="star-rating">
                                                                @for($i = 5; $i >= 1; $i--)
                                                                    <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}-{{ $order['id'] }}" required>
                                                                    <label for="star{{ $i }}-{{ $order['id'] }}" title="{{ $i }} bintang">★</label>
                                                                @endfor
                                                            </div>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label>Komentar (opsional)</label>
                                                            <textarea name="comment" class="form-control" rows="3"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-success">Kirim Ulasan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-info">Tidak ada pesanan atas nama "{{ $customerName }}"</div>
        @endif
    @else
        <div class="alert alert-warning">Masukkan nama pelanggan untuk melihat history pesanan.</div>
    @endif
</div>

<style>
.star-rating {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
    gap: 5px;
    font-size: 1.5rem;
}
.star-rating input { display: none; }
.star-rating label {
    color: #ddd;
    cursor: pointer;
    transition: color 0.2s;
}
.star-rating input:checked ~ label,
.star-rating label:hover,
.star-rating label:hover ~ label {
    color: #f5c518;
}
</style>
@endsection
