<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ========================================================
    // LOGIC LOGIN (Untuk Customer & Restoran)
    // ========================================================

    // 1. Tampilkan Form Login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // 2. Proses Login
    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Coba Login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Cek Role untuk Redirect
            $role = Auth::user()->role;

            if ($role === 'restaurant') {
                // Jika Restoran -> Masuk Dashboard
                return redirect()->intended(route('restaurant.dashboard'));
            } else {
                // Jika Customer -> Masuk Halaman Pesan Menu
                return redirect()->intended(route('order.index'));
            }
        }

        // Jika Gagal Login
        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    // ========================================================
    // LOGIC REGISTER (KHUSUS CUSTOMER)
    // ========================================================

    // 3. Tampilkan Form Register
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // 4. Proses Register Customer Baru
    public function register(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed', // wajib ada field password_confirmation di view
        ]);

        // Create User Baru (FORCE ROLE = CUSTOMER)
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer', // <--- Kuncinya di sini
        ]);

        // Otomatis login setelah daftar
        Auth::login($user);

        // Redirect ke halaman pesan
        return redirect()->route('order.index');
    }

    // ========================================================
    // LOGIC LOGOUT
    // ========================================================

    public function logout(Request $request)
    {
        // 1. Logout system
        Auth::guard('web')->logout();

        // 2. Matikan sesi & token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // 3. PENTING: Jangan redirect ke '/', tapi ke '/login'
        // Tambahkan header untuk mencegah browser menyimpan cache halaman lama
        return redirect('/')
            ->with('success', 'Anda berhasil keluar.')
            ->withHeaders([
                'Cache-Control' => 'no-cache, no-store, max-age=0, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => 'Sat, 01 Jan 1990 00:00:00 GMT',
            ]);
    }
}