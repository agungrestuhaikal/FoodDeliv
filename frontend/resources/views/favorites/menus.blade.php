@extends('layouts.app')
@section('title', 'Menu Favorit Saya')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
    body { background-color: #f6f8f5; font-family: 'Poppins', sans-serif; }
    .favorite-page { padding: 2rem 0; }
    .page-header { background: linear-gradient(135deg, #dc3545 0%, #ff6b7a 100%); color: white; padding: 2rem; border-radius: 15px; margin-bottom: 2rem; box-shadow: 0 6px 20px rgba(220, 53, 69, 0.3); }
    .page-header h1 { font-weight: 700; margin: 0; }
    .card-menu { border-radius: 15px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1); transition: all 0.3s ease; border: none; }
    .card-menu:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(0,0,0,0.15); }
    .card-img-top { height: 200px; object-fit: cover; }
    .btn-favorite { color: #dc3545; font-size: 1.5rem; border: none; background: transparent; cursor: pointer; transition: all 0.3s ease; }
    .btn-favorite:hover { transform: scale(1.2); }
    .empty-state { text-align: center; padding: 4rem 2rem; }
    .empty-state i { font-size: 5rem; color: #ddd; margin-bottom: 1rem; }
</style>

<div class="favorite-page">
    <div class="container">
        <div class="page-header text-center">
            <h1><i class="bi bi-heart-fill me-2"></i>Menu Favorit Saya</h1>
            <p class="mb-0 mt-2">Koleksi menu yang sudah Anda tandai sebagai favorit</p>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="{{ route('order.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Menu
            </a>
            <span class="badge bg-danger fs-6">{{ $favoriteMenus->count() }} Menu Favorit</span>
        </div>

        @if($favoriteMenus->count() > 0)
            <div class="row g-4">
                @foreach($favoriteMenus as $menu)
                    @php
                        $isFavorite = true; // Sudah di halaman favorite, pasti true
                    @endphp
                    <div class="col-md-4">
                        <div class="card card-menu h-100">
                            @if($menu->image)
                                <img src="{{ asset('storage/' . $menu->image) }}" class="card-img-top" alt="{{ $menu->name }}">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                                </div>
                            @endif
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title fw-bold text-success">{{ $menu->name }}</h5>
                                    <button type="button" 
                                            class="btn-favorite-menu btn-favorite" 
                                            data-menu-id="{{ $menu->id }}">
                                        <i class="bi bi-heart-fill"></i>
                                    </button>
                                </div>
                                <p class="card-text text-muted flex-grow-1">
                                    {{ Str::limit($menu->description ?? 'Tidak ada deskripsi', 80) }}
                                </p>
                                <div class="d-flex justify-content-between align-items-center mt-auto">
                                    <span class="h5 text-success mb-0 fw-bold">
                                        Rp {{ number_format($menu->price, 0, ',', '.') }}
                                    </span>
                                    <span class="badge bg-info">{{ $menu->category ?? 'Umum' }}</span>
                                </div>
                                <div class="mt-3">
                                    <a href="{{ route('order.show', $menu->id) }}" class="btn btn-success w-100">
                                        <i class="bi bi-eye me-1"></i> Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <i class="bi bi-heart"></i>
                <h4 class="text-muted">Belum Ada Menu Favorit</h4>
                <p class="text-muted">Klik ikon ❤️ pada menu untuk menambahkannya ke favorit</p>
                <a href="{{ route('order.create') }}" class="btn btn-success mt-3">
                    <i class="bi bi-basket me-1"></i> Lihat Menu
                </a>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle favorite menu
    document.querySelectorAll('.btn-favorite-menu').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const menuId = this.getAttribute('data-menu-id');
            const card = this.closest('.col-md-4');
            
            fetch(`/favorites/menu/${menuId}`, {
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
                    card.style.transform = 'scale(0.8)';
                    setTimeout(() => {
                        card.remove();
                        // Reload jika tidak ada menu lagi
                        if (document.querySelectorAll('.col-md-4').length === 0) {
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

