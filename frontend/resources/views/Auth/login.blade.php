@extends('layouts.app')
@section('title', 'Login')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="col-md-5">
        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="card-header bg-success text-white py-3 text-center">
                <h4 class="mb-0 fw-bold">Selamat Datang Kembali!</h4>
                <small>Silakan login untuk melanjutkan</small>
            </div>
            <div class="card-body p-4 p-md-5">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email Address</label>
                        <input type="email" name="email" class="form-control rounded-3 p-2" placeholder="nama@email.com" required autofocus>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Password</label>
                        <input type="password" name="password" class="form-control rounded-3 p-2" placeholder="********" required>
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-success fw-bold py-2 rounded-3 shadow-sm">
                            <i class="bi bi-box-arrow-in-right me-2"></i> LOGIN
                        </button>
                    </div>

                    <div class="text-center">
                        <p class="text-muted mb-0">Belum punya akun? 
                            <a href="{{ route('register') }}" class="text-success fw-bold text-decoration-none">Daftar Customer</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection