@extends('layouts.app')
@section('title', 'Pesan Menu')

@section('content')

{{-- Bootstrap Icons --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
    body {
        background-color: #f6f8f5;
        font-family: 'Poppins', sans-serif;
    }

    .card {
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        animation: fadeIn 0.6s ease;
    }

    .card-header {
        background: linear-gradient(135deg, #2e7d32 0%, #4caf50 100%) !important;
        color: #fff !important;
        font-weight: 600;
        letter-spacing: 0.5px;
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .back-btn {
        background-color: #ffffff;
        color: #2e7d32;
        border: none;
        border-radius: 8px;
        font-size: 0.9rem;
        font-weight: 500;
        padding: 0.4rem 0.9rem;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }

    .back-btn:hover {
        background-color: #e8f5e9;
        transform: translateY(-2px);
    }

    .card-body {
        background-color: #ffffff;
        padding: 2rem;
    }

    .menu-preview {
        border-bottom: 1px solid #e0e0e0;
        padding-bottom: 1.2rem;
        margin-bottom: 1.5rem;
    }

    .menu-preview img {
        border-radius: 12px;
        width: 100%;
        height: 120px;
        object-fit: cover;
    }

    .menu-preview h5 {
        font-weight: 600;
        color: #2e7d32;
    }

    .btn-success {
        background-color: #2e7d32 !important;
        border: none;
        border-radius: 10px;
        transition: 0.3s ease;
        font-weight: 500;
        padding: 0.6rem 1.4rem;
    }

    .btn-success:hover {
        background-color: #43a047 !important;
        transform: translateY(-2px);
    }

    .btn-outline-success {
        border-radius: 10px;
        transition: 0.3s ease;
    }

    .btn-outline-success:hover {
        background-color: #e8f5e9;
        border-color: #4caf50;
        transform: translateY(-2px);
    }

    .list-group-item {
        background-color: #f9fff9;
        border-radius: 10px !important;
        border: none !important;
    }

    .list-group-item strong {
        color: #2e7d32;
    }

    .star {
        color: #fbc02d;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .menu-select-card {
        border-radius: 15px;
        background: #fff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }

    .menu-select-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 16px rgba(0,0,0,0.1);
    }
</style>

<div class="row justify-content-center">
    <div class="col-md-9">
        <div class="card border-0 shadow">
            <div class="card-header">
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('order.index') }}" class="back-btn shadow-sm">
                        <i class="bi bi-arrow-left-circle"></i> Kembali
                    </a>
                    <span><i class="bi bi-basket2-fill me-2"></i> Form Pemesanan</span>
                </div>
            </div>

            <div class="card-body p-4">
                @if($menu ?? false)
                    <div class="row mb-4 align-items-center menu-preview">
                        <div class="col-md-3">
                            @if($menu['image_url'])
                                <img src="{{ $menu['image_url'] }}" class="img-fluid rounded">
                            @endif
                        </div>
                        <div class="col-md-9">
                            <h5>{{ $menu['name'] }}</h5>
                            <p class="text-muted mb-1">{{ $menu['description'] ?? 'Tidak ada deskripsi.' }}</p>
                            <h4 class="text-success fw-bold">Rp {{ number_format($menu['price'], 0, ',', '.') }}</h4>
                        </div>
                    </div>

                    <form action="{{ route('order.orders.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="menu_id" value="{{ $menu['id'] }}">

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nama Pelanggan</label>
                                <input type="text" name="customer_name" class="form-control rounded-3"
                                       value="{{ old('customer_name') }}" placeholder="Masukkan nama Anda" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Jumlah</label>
                                <input type="number" name="quantity" class="form-control rounded-3"
                                       value="{{ old('quantity', 1) }}" min="1" required>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('order.index') }}" class="btn btn-outline-success">Batal</a>
                            <button type="submit" class="btn btn-success shadow">Kirim Pesanan</button>
                        </div>
                    </form>

                    <hr class="my-4">
                    <h5 class="text-success"><i class="bi bi-chat-dots-fill me-2"></i>Ulasan untuk {{ $menu['name'] }}</h5>

                    @php
                        $reviews = Illuminate\Support\Facades\Http::get(
                            'http://127.0.0.1:5003/reviews',
                            ['menu_id' => $menu['id']]
                        )->json();
                    @endphp

                    @if(count($reviews) > 0)
                        <div class="list-group mt-3">
                            @foreach($reviews as $review)
                                <div class="list-group-item shadow-sm mb-2 p-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <strong>{{ $review['customer_name'] }}</strong>
                                        <div>
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="bi bi-star-fill {{ $i <= $review['rating'] ? 'star' : 'text-muted' }}"></i>
                                            @endfor
                                            <small class="text-muted ms-1">({{ $review['rating'] }})</small>
                                        </div>
                                    </div>
                                    <p class="mb-1 text-muted">{{ $review['comment'] ?? '-' }}</p>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($review['created_at'])->format('d M H:i') }}
                                    </small>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mt-3">Belum ada ulasan untuk menu ini.</p>
                    @endif

                @else
                    <h5 class="text-success mb-3"><i class="bi bi-list-ul me-2"></i>Pilih Menu:</h5>
                    <div class="row g-3">
                        @foreach(Illuminate\Support\Facades\Http::get('http://127.0.0.1:5002/menus')->json() as $m)
                            <div class="col-md-6">
                                <div class="menu-select-card p-3 rounded d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong class="text-success">{{ $m['name'] }}</strong><br>
                                        <small class="text-muted">Rp {{ number_format($m['price'], 0, ',', '.') }}</small>
                                    </div>
                                    <a href="{{ route('order.create', ['menu_id' => $m['id']]) }}" 
                                       class="btn btn-outline-success btn-sm shadow-sm">
                                       <i class="bi bi-check2-circle me-1"></i>Pilih
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
