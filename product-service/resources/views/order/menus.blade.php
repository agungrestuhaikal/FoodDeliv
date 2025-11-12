@extends('layouts.app')
@section('title', 'Daftar Menu - Customer')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Pilih Menu</h2>
    <a href="{{ route('customer.index') }}" class="btn btn-outline-secondary">
        Kembali
    </a>
</div>

<div class="row g-4">
    @forelse($menus as $menu)
        <div class="col-md-4">
            <div class="card h-100">
                @if($menu['image_url'])
                    <img src="{{ $menu['image_url'] }}" class="card-img-top" style="height:180px; object-fit:cover;">
                @endif
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $menu['name'] }}</h5>
                    <p class="card-text text-muted flex-grow-1">{{ Str::limit($menu['description'] ?? '', 60) }}</p>
                    <div class="mt-auto">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="h5 text-success mb-0">Rp {{ number_format($menu['price'], 0, ',', '.') }}</span>
                            <span class="badge bg-info">{{ $menu['category'] }}</span>
                        </div>
                        <div class="btn-group w-100" role="group">
                            <a href="{{ route('customer.show', $menu['id']) }}" 
                               class="btn btn-outline-primary btn-sm">Detail</a>
                            <a href="{{ route('customer.create', ['menu_id' => $menu['id']]) }}" 
                               class="btn btn-success btn-sm">Pesan</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <p class="text-muted">Belum ada menu tersedia.</p>
        </div>
    @endforelse
</div>
@endsection