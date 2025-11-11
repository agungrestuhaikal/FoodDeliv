@extends('customer.app')
@section('title', 'Selamat Datang')

@section('content')
<div class="text-center py-5">
    <h1 class="display-4 fw-bold">Selamat Datang!</h1>
    <p class="lead">Pilih menu favorit dan pesan sekarang.</p>
    <a href="{{ route('customer.menus') }}" class="btn btn-light btn-lg mt-3">
        Lihat Menu
    </a>
</div>
@endsection