@extends('customer.app')
@section('title', $menu['name'])

@section('content')
<div class="row">
    <div class="col-md-6">
        @if($menu['image_url'])
            <img src="{{ $menu['image_url'] }}" class="img-fluid rounded shadow" style="max-height:400px;">
        @endif
    </div>
    <div class="col-md-6">
        <h2>{{ $menu['name'] }}</h2>
        <p class="lead">{{ $menu['description'] }}</p>
        <h3 class="text-primary">Rp {{ number_format($menu['price'], 0, ',', '.') }}</h3>
        <span class="badge bg-info">{{ $menu['category'] }}</span>

        <!-- PESAN -->
        <div class="mt-4 p-4 bg-white rounded text-dark">
            <h5>Pesan Sekarang</h5>
            <form action="{{ route('customer.orders.store') }}" method="POST">
                @csrf
                <input type="hidden" name="menu_id" value="{{ $menu['id'] }}">
                <div class="row g-2">
                    <div class="col-md-4"><input type="text" name="customer_name" class="form-control" placeholder="Nama" required></div>
                    <div class="col-md-3"><input type="number" name="quantity" value="1" min="1" class="form-control" required></div>
                    <div class="col-md-3"><input type="text" name="table_number" class="form-control" placeholder="Meja"></div>
                    <div class="col-md-2"><button type="submit" class="btn btn-success w-100">Pesan</button></div>
                </div>
            </form>
        </div>

        <!-- REVIEW -->
        <div class="mt-4 p-4 bg-light rounded">
            <h5>Beri Ulasan</h5>
            <form action="{{ route('customer.reviews.store') }}" method="POST">
                @csrf
                <input type="hidden" name="menu_id" value="{{ $menu['id'] }}">
                <div class="row g-2">
                    <div class="col-md-4"><input type="text" name="customer_name" class="form-control form-control-sm" placeholder="Nama" required></div>
                    <div class="col-md-3">
                        <select name="rating" class="form-select form-control-sm" required>
                            <option value="">Rating</option>
                            @for($i=1;$i<=5;$i++)<option value="{{ $i }}">{{ $i }} Bintang</option>@endfor
                        </select>
                    </div>
                    <div class="col-md-3"><input type="text" name="comment" class="form-control form-control-sm" placeholder="Komentar"></div>
                    <div class="col-md-2"><button type="submit" class="btn btn-outline-primary btn-sm w-100">Kirim</button></div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection