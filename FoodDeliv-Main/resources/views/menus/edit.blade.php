@extends('layouts.app')
@section('title', 'Edit Menu')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">Edit Menu</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('menus.update', $menu['id']) }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama</label>
                        <input type="text" name="name" class="form-control" value="{{ $menu['name'] }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Deskripsi</label>
                        <textarea name="description" rows="3" class="form-control">{{ $menu['description'] }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Harga</label>
                        <input type="number" name="price" step="500" class="form-control" value="{{ $menu['price'] }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Kategori</label>
                        <select name="category" class="form-select" required>
                            <option value="Makanan" {{ $menu['category']=='Makanan' ? 'selected':'' }}>Makanan</option>
                            <option value="Minuman" {{ $menu['category']=='Minuman' ? 'selected':'' }}>Minuman</option>
                            <option value="Cemilan" {{ $menu['category']=='Cemilan' ? 'selected':'' }}>Cemilan</option>
                            <option value="Dessert" {{ $menu['category']=='Dessert' ? 'selected':'' }}>Dessert</option>
                        </select>
                    </div>

                    @if($menu['image_url'])
                        <div class="mb-3">
                            <label class="form-label fw-bold">Gambar Saat Ini</label>
                            <img src="{{ $menu['image_url'] }}" class="img-fluid rounded" style="max-height: 200px;">
                        </div>
                    @endif

                    <div class="mb-4">
                        <label class="form-label fw-bold">Ganti Gambar</label>
                        <input type="file" name="image" accept="image/*" class="form-control">
                        <div class="form-text">Kosongkan jika tidak ingin ganti.</div>
                    </div>

                    <button type="submit" class="btn btn-warning">Update</button>
                    <a href="{{ route('menus.index') }}" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection