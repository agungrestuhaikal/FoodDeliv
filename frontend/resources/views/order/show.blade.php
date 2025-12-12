@extends('layouts.app')
@section('title', $menu->name)

@section('content')
<div class="container py-4">
    <div class="row">
        {{-- Kolom Gambar --}}
        <div class="col-md-6 mb-4">
            @if($menu->image)
                <img src="{{ asset('storage/' . $menu->image) }}" class="img-fluid rounded-4 shadow w-100" style="max-height: 450px; object-fit: cover;">
            @else
                <div class="bg-light rounded-4 d-flex align-items-center justify-content-center text-muted shadow-sm" style="height: 400px;">
                    <i class="bi bi-image fs-1"></i>
                    <p class="ms-2 mb-0">Tidak ada gambar</p>
                </div>
            @endif
        </div>

        {{-- Kolom Detail --}}
        <div class="col-md-6">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <h1 class="fw-bold text-success">{{ $menu->name }}</h1>
                @php
                    $isFavorite = Auth::check() && \App\Models\Favorite::where('user_id', Auth::id())->where('menu_id', $menu->id)->exists();
                @endphp
                <button type="button" 
                        class="btn-favorite-menu btn btn-sm p-2 border-0 bg-transparent" 
                        data-menu-id="{{ $menu->id }}"
                        style="color: {{ $isFavorite ? '#dc3545' : '#6c757d' }}; font-size: 1.5rem;">
                    <i class="bi bi-heart{{ $isFavorite ? '-fill' : '' }}"></i>
                </button>
            </div>
            <div class="d-flex align-items-center gap-2 mb-3">
                <span class="badge bg-info bg-opacity-10 text-info border border-info px-3 py-2 rounded-pill">
                    {{ $menu->category ?? 'Umum' }}
                </span>
                <span class="text-muted">
                    <i class="bi bi-star-fill text-warning"></i> 
                    {{ $menu->reviews->avg('rating') ? number_format($menu->reviews->avg('rating'), 1) : 'Belum ada rating' }}
                </span>
            </div>

            <h2 class="text-success fw-bold mb-3">Rp {{ number_format($menu->price, 0, ',', '.') }}</h2>
            <p class="lead text-secondary">{{ $menu->description ?? 'Tidak ada deskripsi untuk menu ini.' }}</p>

            <hr class="my-4">

            {{-- Tombol Pesan --}}
            <a href="{{ route('order.create', ['menu_id' => $menu->id]) }}" 
               class="btn btn-success btn-lg w-100 py-3 rounded-3 shadow-sm mb-4 fw-bold">
               <i class="bi bi-cart-plus me-2"></i> Pesan Sekarang
            </a>

            {{-- Form Review (Versi Otomatis Nama) --}}
            <div class="p-4 bg-light rounded-4 border">
                <h5 class="fw-bold mb-3"><i class="bi bi-chat-quote me-2"></i>Beri Ulasan</h5>
                
                <form action="{{ route('order.reviews.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                    
                    {{-- Input Nama DIHAPUS (Sudah otomatis pakai Auth) --}}

                    <div class="row g-2">
                        <div class="col-md-4">
                            <select name="rating" class="form-select" required>
                                <option value="">★ Rating</option>
                                @for($i = 5; $i >= 1; $i--)
                                    <option value="{{ $i }}">{{ $i }} Bintang</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="comment" class="form-control" placeholder="Tulis komentar...">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-dark w-100">Kirim</button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Daftar Review Sebelumnya (Bonus) --}}
            @if($menu->reviews->count() > 0)
                <div class="mt-4">
                    <h6 class="fw-bold mb-3">Apa kata mereka?</h6>
                    <div class="list-group list-group-flush">
                        @foreach($menu->reviews->take(3) as $review)
                            <div class="list-group-item bg-transparent px-0">
                                <div class="d-flex justify-content-between">
                                    <strong class="text-dark">{{ $review->customer_name }}</strong>
                                    <small class="text-warning">
                                        @for($j=0; $j < $review->rating; $j++) ★ @endfor
                                    </small>
                                </div>
                                <p class="mb-0 small text-muted">{{ $review->comment }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="mt-4">
                <a href="{{ route('order.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Menu
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle favorite menu
    const favoriteBtn = document.querySelector('.btn-favorite-menu');
    if (favoriteBtn) {
        favoriteBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const menuId = this.getAttribute('data-menu-id');
            const icon = this.querySelector('i');
            
            fetch(`/favorites/menu/${menuId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.is_favorite) {
                        icon.classList.remove('bi-heart');
                        icon.classList.add('bi-heart-fill');
                        this.style.color = '#dc3545';
                    } else {
                        icon.classList.remove('bi-heart-fill');
                        icon.classList.add('bi-heart');
                        this.style.color = '#6c757d';
                    }
                }
            })
            .catch(error => console.error('Error:', error));
        });
    }
});
</script>
@endsection