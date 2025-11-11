@extends('layouts.app')
@section('title', 'Tambah Menu')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Tambah Menu Baru</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('menus.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Menu</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Deskripsi</label>
                        <textarea name="description" rows="3" class="form-control">{{ old('description') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Harga (Rp)</label>
                        <input type="number" name="price" step="500" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" required>
                        @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Kategori</label>
                        <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                            <option value="">-- Pilih --</option>
                            <option value="Makanan" {{ old('category')=='Makanan' ? 'selected':'' }}>Makanan</option>
                            <option value="Minuman" {{ old('category')=='Minuman' ? 'selected':'' }}>Minuman</option>
                            <option value="Cemilan" {{ old('category')=='Cemilan' ? 'selected':'' }}>Cemilan</option>
                            <option value="Dessert" {{ old('category')=='Dessert' ? 'selected':'' }}>Dessert</option>
                        </select>
                        @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Gambar Menu</label>
                        <input type="file" name="image" id="image" accept="image/*" class="form-control @error('image') is-invalid @enderror">
                        <div class="form-text">Maksimal 2MB. JPG, PNG, GIF.</div>
                        @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror

                        <div class="mt-3 text-center">
                            <img id="preview" src="" alt="Preview" class="img-fluid rounded shadow-sm" style="max-height: 200px; display: none;">
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success btn-lg">Simpan</button>
                        <a href="{{ route('menus.index') }}" class="btn btn-secondary btn-lg">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('preview');
        if (file) {
            const reader = new FileReader();
            reader.onload = e => {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            preview.src = '';
            preview.style.display = 'none';
        }
    });
</script>
@endsection