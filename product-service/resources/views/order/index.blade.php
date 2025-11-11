@extends('layouts.app')
@section('title', 'Customer - Selamat Datang')

@section('content')
<div class="text-center py-5">
    <h1 class="display-4 fw-bold text-primary">Selamat Datang, Pelanggan!</h1>
    <p class="lead mb-4">Pesan menu favorit Anda dengan mudah.</p>
    <a href="{{ route('order.create') }}" class="btn btn-primary btn-lg px-5">
        Lihat Menu & Pesan
    </a>
</div>
@endsection