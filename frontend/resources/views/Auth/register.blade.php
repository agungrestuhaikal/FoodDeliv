@extends('layouts.app')
@section('title', 'Daftar Akun')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="col-md-6">
        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="card-header bg-success text-white py-3 text-center">
                <h4 class="mb-0 fw-bold">Daftar Akun Baru</h4>
                <small>Bergabunglah dan nikmati makanan lezat!</small>
            </div>
            <div class="card-body p-4 p-md-5">
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    {{-- Nama Lengkap --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control rounded-3 p-2" placeholder="Contoh: Agung Restu" required autofocus>
                    </div>

                    {{-- Email --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email Address</label>
                        <input type="email" name="email" class="form-control rounded-3 p-2" placeholder="nama@email.com" required>
                    </div>

                    {{-- Password --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Password</label>
                            <input type="password" name="password" class="form-control rounded-3 p-2" placeholder="********" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="form-control rounded-3 p-2" placeholder="********" required>
                        </div>
                    </div>

                    {{-- Tombol Daftar --}}
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-success fw-bold py-2 rounded-3 shadow-sm">
                            <i class="bi bi-person-plus-fill me-2"></i> DAFTAR SEKARANG
                        </button>
                    </div>

                    <div class="text-center">
                        <p class="text-muted mb-0">Sudah punya akun? 
                            <a href="{{ route('login') }}" class="text-success fw-bold text-decoration-none">Login di sini</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection