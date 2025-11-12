@extends('layouts.app')
@section('title', $menu['name'])

@section('content')
<div class="row">
    <div class="col-md-6">
        @if($menu['image_url'])
            <img src="{{ $menu['image_url'] }}" class="img-fluid rounded shadow" style="max-height:400px; object-fit:cover;">
        @endif
    </div>
    <div class="col-md-6">
        <h2>{{ $menu['name'] }}</h2>
        <p class="lead">{{ $menu['description'] ?? 'Tidak ada deskripsi.' }}</p>
        <h3 class="text-success">Rp {{ number_format($menu['price'], 0, ',', '.') }}</h3>
        <span class="badge bg-info">{{ $menu['category'] }}</span>

        <div class="mt-4">
            <a href="{{ route('order.create', ['menu_id' => $menu['id']]) }}" 
               class="btn btn-success btn-lg w-100 mb-3">Pesan Sekarang</a>
        </div>
        <div class="mt-4 p-4 bg-light rounded">
            <h5>Beri Ulasan</h5>
            <form action="{{ route('order.reviews.store') }}" method="POST">
                @csrf
                <input type="hidden" name="menu_id" value="{{ $menu['id'] }}">
                <div class="row g-2">
                    <div class="col-md-4">
                        <input type="text" name="customer_name" class="form-control form-control-sm" 
                               placeholder="Nama Anda" required>
                    </div>
                    <div class="col-md-3">
                        <select name="rating" class="form-select form-control-sm" required>
                            <option value="">Rating</option>
                            @for($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}">{{ $i }} Bintang</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="comment" class="form-control form-control-sm" placeholder="Komentar">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-outline-primary btn-sm w-100">Kirim</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="mt-3">
            <a href="{{ route('order.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
</div>
@endsection