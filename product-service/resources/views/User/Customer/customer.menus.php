@extends('customer.app')
@section('title', 'Daftar Menu')

@section('content')
<h2 class="mb-4">D10aftar Menu</h2>
<div class="row">
    @forelse($menus as $menu)
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                @if($menu['image_url'])
                    <img src="{{ $menu['image_url'] }}" class="card-img-top" style="height:200px; object-fit:cover;">
                @endif
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $menu['name'] }}</h5>
                    <p class="card-text text-muted flex-grow-1">{{ $menu['description'] ?: 'Tidak ada deskripsi.' }}</p>
                    <div class="d-flex justify-content-between align-items-center mt-auto">
                        <span class="h5 text-primary mb-0">Rp {{ number_format($menu['price'], 0, ',', '.') }}</span>
                        <a href="{{ route('customer.menu.show', $menu['id']) }}" class="btn btn-outline-primary btn-sm">Lihat</a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center">
            <p>Belum ada menu.</p>
        </div>
    @endforelse
</div>
@endsection