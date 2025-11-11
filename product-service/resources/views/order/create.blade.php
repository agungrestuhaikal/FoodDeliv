@extends('layouts.app')
@section('title', 'Pesan Menu')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-9">
        <div class="card border-0 shadow">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">Form Pemesanan</h4>
            </div>
            <div class="card-body p-4">
                @if($menu ?? false)
                    <div class="row mb-4 align-items-center border-bottom pb-3">
                        <div class="col-md-3">
                            @if($menu['image_url'])
                                <img src="{{ $menu['image_url'] }}" class="img-fluid rounded" style="max-height:120px; object-fit:cover;">
                            @endif
                        </div>
                        <div class="col-md-9">
                            <h5>{{ $menu['name'] }}</h5>
                            <p class="text-muted mb-1">{{ $menu['description'] ?? 'Tidak ada deskripsi.' }}</p>
                            <h4 class="text-success">Rp {{ number_format($menu['price'], 0, ',', '.') }}</h4>
                        </div>
                    </div>

                    <form action="{{ route('order.orders.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="menu_id" value="{{ $menu['id'] }}">

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama Pelanggan</label>
                                <input type="text" name="customer_name" class="form-control" 
                                       value="{{ old('customer_name') }}" placeholder="Masukkan nama Anda" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Jumlah</label>
                                <input type="number" name="quantity" class="form-control" 
                                       value="{{ old('quantity', 1) }}" min="1" required>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('order.index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-success">Kirim Pesanan</button>
                        </div>
                    </form>

                    <!-- ULASAN -->
                    <hr class="my-4">
                    <h5>Ulasan untuk {{ $menu['name'] }}</h5>

                    @php
                        $reviews = Illuminate\Support\Facades\Http::get(
                            'http://127.0.0.1:5003/reviews',
                            ['menu_id' => $menu['id']]
                        )->json();
                    @endphp

                    @if(count($reviews) > 0)
                        <div class="list-group">
                            @foreach($reviews as $review)
                                <div class="list-group-item border-0 p-3 shadow-sm mb-2 rounded">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <strong>{{ $review['customer_name'] }}</strong>
                                        <div>
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $review['rating'] ? 'text-warning' : 'text-muted' }}"></i>
                                            @endfor
                                            <small class="text-muted ms-1">({{ $review['rating'] }})</small>
                                        </div>
                                    </div>
                                    <p class="mb-1 text-muted">{{ $review['comment'] ?? '-' }}</p>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($review['created_at'])->format('d/m H:i') }}
                                    </small>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">Belum ada ulasan untuk menu ini.</p>
                    @endif

                @else
                    <hr class="my-4">
                    <h5>Pilih Menu:</h5>
                    <div class="row g-3">
                        @foreach(Illuminate\Support\Facades\Http::get('http://127.0.0.1:5002/menus')->json() as $m)
                            <div class="col-md-6">
                                <div class="border p-3 rounded d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $m['name'] }}</strong><br>
                                        <small class="text-muted">Rp {{ number_format($m['price'], 0, ',', '.') }}</small>
                                    </div>
                                    <a href="{{ route('order.create', ['menu_id' => $m['id']]) }}" 
                                       class="btn btn-sm btn-outline-success">Pilih</a>
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