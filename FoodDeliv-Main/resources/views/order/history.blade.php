@extends('layouts.app')
@section('title', 'History Pesanan Saya')

@section('content')

{{-- Bootstrap Icons --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style scoped>
/* Hanya berlaku di halaman ini — TIDAK mengganggu style.css global */
.history-page {
    font-family: 'Poppins', sans-serif;
}

/* Navigasi Atas */
.history-page .top-nav {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 0.8rem;
    padding: 1rem 2rem 0;
}

.history-page .btn-nav {
    background-color: #ffffff;
    color: #2e7d32;
    border: 1.8px solid #a5d6a7;
    border-radius: 25px;
    padding: 0.45rem 1.2rem;
    font-weight: 500;
    font-size: 0.95rem;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.history-page .btn-nav:hover {
    background-color: #e8f5e9;
    transform: translateY(-2px);
}

.history-page .btn-nav.active {
    background-color: #2e7d32;
    color: white;
    border-color: #2e7d32;
}

/* Judul */
.history-page h2 {
    color: #2e7d32;
    font-weight: 700;
    border-left: 5px solid #4caf50;
    padding-left: 10px;
}

/* Card History */
.history-page .card-history {
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
    border: none;
}

/* Tabel */
.history-page .table-history thead {
    background-color: #e8f5e9;
}

.history-page .table-history th {
    color: #2e7d32;
    font-weight: 600;
}

/* Tombol Success */
.history-page .btn-success {
    background-color: #2e7d32 !important;
    border: none;
    border-radius: 10px;
    padding: 0.5rem 1.2rem;
    transition: 0.3s ease;
}

.history-page .btn-success:hover {
    background-color: #43a047 !important;
    transform: translateY(-2px);
}

/* Tombol Review */
.history-page .btn-review {
    border-color: #43a047;
    color: #2e7d32;
    font-size: 0.85rem;
    transition: 0.3s ease;
}

.history-page .btn-review:hover {
    background-color: #e8f5e9;
    border-color: #2e7d32;
}

/* Alert */
.history-page .alert {
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

/* Modal */
.history-page .modal-content {
    border-radius: 15px;
    border: none;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}

.history-page .modal-header {
    background: linear-gradient(135deg, #2e7d32 0%, #4caf50 100%);
    color: #fff;
    border: none;
}

.history-page .modal-title {
    font-weight: 600;
}

/* Star Rating */
.history-page .star-rating {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
    gap: 5px;
    font-size: 1.7rem;
}

.history-page .star-rating input {
    display: none;
}

.history-page .star-rating label {
    color: #ddd;
    cursor: pointer;
    transition: color 0.2s;
}

.history-page .star-rating input:checked ~ label,
.history-page .star-rating label:hover,
.history-page .star-rating label:hover ~ label {
    color: #fbc02d;
}

/* Animasi */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.history-page .card-history,
.history-page .alert,
.history-page .modal-content {
    animation: fadeIn 0.4s ease;
}
</style>

<div class="history-page">

    <div class="top-nav">
        <a href="{{ route('order.index') }}" class="btn-nav">
            <i class="bi bi-arrow-left-circle me-1"></i> Kembali ke Menu Awal
        </a>
        <a href="{{ route('order.history') }}" class="btn-nav active">
            <i class="bi bi-clock-history me-1"></i> Riwayat Order & Review
        </a>
    </div>

    <div class="container py-4">
        <h2 class="mb-4"><i class="bi bi-journal-text me-2"></i>History Pesanan Saya</h2>

        @if(session('success'))
            <div class="alert alert-success"><i class="bi bi-check-circle me-1"></i> {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger"><i class="bi bi-x-circle me-1"></i> {{ session('error') }}</div>
        @endif

        <form method="GET" action="{{ route('order.history') }}" class="mb-4">
            <div class="row g-2 align-items-center">
                <div class="col-md-6">
                    <input type="text" name="customer_name" class="form-control" placeholder="Masukkan nama Anda"
                           value="{{ request()->input('customer_name') }}">
                </div>
                <div class="col-md-auto">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-search me-1"></i> Cari
                    </button>
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
                <div class="card-history">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 align-middle table-history">
                                <thead>
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
                                            <td><strong class="text-success">{{ $order['menu_name'] }}</strong></td>
                                            <td>{{ $order['quantity'] }}</td>
                                            <td>Rp {{ number_format($order['total_price'], 0, ',', '.') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($order['created_at'])->format('d M H:i') }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-review shadow-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#reviewModal{{ $order['id'] }}">
                                                    <i class="bi bi-chat-dots"></i> Review
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @foreach($myOrders as $order)
                    <div class="modal fade" id="reviewModal{{ $order['id'] }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">
                                        <i class="bi bi-star me-2"></i>Beri Ulasan: {{ $order['menu_name'] }}
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('order.reviews.store') }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <input type="hidden" name="menu_id" value="{{ $order['menu_id'] }}">
                                        <input type="hidden" name="customer_name" value="{{ $customerName }}">

                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Rating</label>
                                            <div class="star-rating">
                                                @for($i = 5; $i >= 1; $i--)
                                                    <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}-{{ $order['id'] }}" required>
                                                    <label for="star{{ $i }}-{{ $order['id'] }}">★</label>
                                                @endfor
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Komentar (opsional)</label>
                                            <textarea name="comment" class="form-control rounded-3" rows="3"
                                                      placeholder="Tulis pengalaman Anda..."></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-success">Kirim Ulasan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach

            @else
                <div class="alert alert-info text-center mt-4">
                    <i class="bi bi-info-circle me-1"></i> Tidak ada pesanan atas nama <strong>"{{ $customerName }}"</strong>.
                </div>
            @endif
        @else
            <div class="alert alert-warning text-center mt-4">
                <i class="bi bi-exclamation-triangle me-1"></i> Masukkan nama pelanggan untuk melihat history pesanan.
            </div>
        @endif
    </div>
</div>

@endsection