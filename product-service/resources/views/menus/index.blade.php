@extends('layouts.app')

@section('title', 'Daftar Menu')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0">Daftar Menu</h5>
    </div>
    <div class="card-body">
        <a href="{{ route('menus.create') }}" class="btn btn-primary mb-3">
            <i class="fas fa-plus"></i> Tambah Menu
        </a>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Harga</th>
                        <th>Kategori</th>
                        <th width="180">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($menus as $menu)
                        <tr>
                            <td>{{ $menu['id'] }}</td>
                            <td>
                                <strong>{{ $menu['name'] }}</strong>
                                @if($menu['image_url'])
                                    <br><img src="{{ $menu['image_url'] }}" alt="" style="width:50px; height:50px; object-fit:cover; border-radius:5px; margin-top:5px;">
                                @endif
                            </td>
                            <td>Rp {{ number_format($menu['price'], 0, ',', '.') }}</td>
                            <td><span class="badge bg-info">{{ $menu['category'] }}</span></td>
                            <td>
                                <a href="{{ route('menus.show', $menu['id']) }}" class="btn btn-sm btn-outline-info" title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('menus.edit', $menu['id']) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('menus.destroy', $menu['id']) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus"
                                            onclick="return confirm('Yakin ingin menghapus menu ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-2x mb-3 d-block"></i>
                                Belum ada menu.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection