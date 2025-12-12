@extends('layouts.app')
@section('title', 'Pesan Menu')

@section('content')

{{-- Bootstrap Icons --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
    body { background-color: #f6f8f5; font-family: 'Poppins', sans-serif; }
    .card { border-radius: 18px; overflow: hidden; box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08); animation: fadeIn 0.6s ease; }
    .card-header { background: linear-gradient(135deg, #2e7d32 0%, #4caf50 100%) !important; color: #fff !important; font-weight: 600; letter-spacing: 0.5px; padding: 1rem 1.5rem; display: flex; align-items: center; justify-content: space-between; }
    .back-btn { background-color: #ffffff; color: #2e7d32; border: none; border-radius: 8px; font-size: 0.9rem; font-weight: 500; padding: 0.4rem 0.9rem; transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 0.3rem; }
    .back-btn:hover { background-color: #e8f5e9; transform: translateY(-2px); }
    .card-body { background-color: #ffffff; padding: 2rem; }
    .menu-preview { border-bottom: 1px solid #e0e0e0; padding-bottom: 1.2rem; margin-bottom: 1.5rem; }
    .menu-preview img { border-radius: 12px; width: 100%; height: 120px; object-fit: cover; }
    .menu-preview h5 { font-weight: 600; color: #2e7d32; }
    .btn-success { background-color: #2e7d32 !important; border: none; border-radius: 10px; transition: 0.3s ease; font-weight: 500; padding: 0.6rem 1.4rem; }
    .btn-success:hover { background-color: #43a047 !important; transform: translateY(-2px); }
    .btn-outline-success { border-radius: 10px; transition: 0.3s ease; }
    .btn-outline-success:hover { background-color: #e8f5e9; border-color: #4caf50; transform: translateY(-2px); }
    .list-group-item { background-color: #f9fff9; border-radius: 10px !important; border: none !important; }
    .list-group-item strong { color: #2e7d32; }
    .star { color: #fbc02d; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .menu-select-card { border-radius: 15px; background: #fff; box-shadow: 0 4px 12px rgba(0,0,0,0.05); transition: all 0.3s ease; }
    .menu-select-card:hover { transform: translateY(-5px); box-shadow: 0 6px 16px rgba(0,0,0,0.1); }
    .btn-favorite-menu { transition: all 0.3s ease; cursor: pointer; }
    .btn-favorite-menu:hover { transform: scale(1.2); }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle favorite menu
    document.querySelectorAll('.btn-favorite-menu').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
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
    });
});
</script>

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
                    {{-- TAMPILAN JIKA MENU SUDAH DIPILIH --}}
                    <div class="row mb-4 align-items-center menu-preview">
                        <div class="col-md-3">
                            @if($menu->image)
                                <img src="{{ asset('storage/' . $menu->image) }}" class="img-fluid rounded shadow-sm">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted" style="height: 120px; width: 100%;">
                                    <i class="bi bi-image fs-1"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-9">
                            <h5>{{ $menu->name }}</h5>
                            <p class="text-muted mb-1">{{ $menu->description ?? 'Tidak ada deskripsi.' }}</p>
                            <h4 class="text-success fw-bold">Rp {{ number_format($menu->price, 0, ',', '.') }}</h4>
                        </div>
                    </div>

                    <form action="{{ route('order.orders.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="menu_id" value="{{ $menu->id }}">

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nama Pelanggan</label>
                                {{-- UPDATE PENTING: Value otomatis dari Auth user dan Readonly --}}
                                <input type="text" name="customer_name" class="form-control rounded-3 bg-light"
                                       value="{{ Auth::user()->name }}" readonly required>
                                <small class="text-muted" style="font-size: 0.8em;">*Sesuai nama akun Anda</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Jumlah</label>
                                <input type="number" name="quantity" class="form-control rounded-3"
                                       value="{{ old('quantity', 1) }}" min="1" required>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Alamat Pengiriman</label>
                                <textarea name="customer_address" class="form-control rounded-3" rows="2" placeholder="Alamat lengkap..." required>{{ old('customer_address') }}</textarea>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('order.create') }}" class="btn btn-outline-success">Ganti Menu</a>
                            <button type="submit" class="btn btn-success shadow">Kirim Pesanan</button>
                        </div>
                    </form>

                    <hr class="my-4">
                    <h5 class="text-success"><i class="bi bi-chat-dots-fill me-2"></i>Ulasan untuk {{ $menu->name }}</h5>

                    {{-- Logic Review menggunakan Eloquent --}}
                    @php
                        $reviews = $menu->reviews()->latest()->get();
                    @endphp

                    @if($reviews->count() > 0)
                        <div class="list-group mt-3">
                            @foreach($reviews as $review)
                                <div class="list-group-item shadow-sm mb-2 p-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <strong>{{ $review->customer_name }}</strong>
                                        <div>
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="bi bi-star-fill {{ $i <= $review->rating ? 'star' : 'text-muted' }}"></i>
                                            @endfor
                                            <small class="text-muted ms-1">({{ $review->rating }})</small>
                                        </div>
                                    </div>
                                    <p class="mb-1 text-muted">{{ $review->comment ?? '-' }}</p>
                                    <small class="text-muted">
                                        {{ $review->created_at->format('d M H:i') }}
                                    </small>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-light text-center border mt-3">
                            <i class="bi bi-chat-square-text text-muted mb-2 fs-3 d-block"></i>
                            <p class="text-muted mb-0">Belum ada ulasan untuk menu ini.</p>
                        </div>
                    @endif

                @else
                    {{-- TAMPILAN LIST MENU (JIKA BELUM PILIH MENU) --}}
                    <h5 class="text-success mb-3"><i class="bi bi-list-ul me-2"></i>Pilih Menu:</h5>
                    <div class="row g-3">
                        @foreach(\App\Models\Menu::all() as $m)
                            @php
                                $isFavorite = Auth::check() && \App\Models\Favorite::where('user_id', Auth::id())->where('menu_id', $m->id)->exists();
                            @endphp
                            <div class="col-md-6">
                                <div class="menu-select-card p-3 rounded d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center gap-3">
                                        @if($m->image)
                                            <img src="{{ asset('storage/' . $m->image) }}" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted" style="width: 50px; height: 50px;">
                                                <i class="bi bi-image"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <strong class="text-success d-block">{{ $m->name }}</strong>
                                            <small class="text-muted">Rp {{ number_format($m->price, 0, ',', '.') }}</small>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <button type="button" 
                                                class="btn-favorite-menu btn btn-sm p-1 border-0 bg-transparent" 
                                                data-menu-id="{{ $m->id }}"
                                                style="color: {{ $isFavorite ? '#dc3545' : '#6c757d' }}; font-size: 1.2rem;">
                                            <i class="bi bi-heart{{ $isFavorite ? '-fill' : '' }}"></i>
                                        </button>
                                        <a href="{{ route('order.create', ['menu_id' => $m->id]) }}" 
                                           class="btn btn-outline-success btn-sm shadow-sm">
                                           <i class="bi bi-check2-circle me-1"></i>Pilih
                                        </a>
                                    </div>
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