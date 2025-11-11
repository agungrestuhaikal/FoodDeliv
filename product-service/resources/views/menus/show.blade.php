@extends('layouts.app')
@section('title', $menu['name'])

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-body p-5 text-center">
                @if($menu['image_url'])
                    <img src="{{ $menu['image_url'] }}" class="img-fluid rounded shadow-sm mb-4" style="max-height: 300px;">
                @endif
                <h2 class="mb-3">{{ $menu['name'] }}</h2>
                <p class="text-muted">{{ $menu['description'] ?: 'Tidak ada deskripsi.' }}</p>
                <h4 class="text-primary">Rp {{ number_format($menu['price'], 0, ',', '.') }}</h4>
                <span class="badge bg-info fs-6">{{ $menu['category'] }}</span>

                <div class="mt-4">
                    <a href="{{ route('menus.edit', $menu['id']) }}" class="btn btn-warning">Edit</a>
                    <a href="{{ route('menus.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection