<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Tampilkan halaman login dengan keamanan tambahan.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Tangani permintaan login dengan proteksi keamanan tinggi.
     */
    public function store(Request $request)
    {
        // Validasi input untuk mencegah SQL Injection, XSS, dan eksploitasi lainnya
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:6', 'max:100'],
        ]);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }

        $throttleKey = 'login:' . $request->ip();

        // Proteksi Brute Force - Maksimal 5 percobaan dalam 10 menit
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            Log::warning('Brute-force detected', ['ip' => $request->ip()]);
            throw ValidationException::withMessages([
                'email' => __('Terlalu banyak percobaan login. Silakan coba lagi nanti.'),
            ]);
        }

        // Mencegah manipulasi input berbahaya
        $credentials = [
            'email' => htmlspecialchars($request->email, ENT_QUOTES, 'UTF-8'),
            'password' => $request->password,
        ];

        // Coba login
        if (!Auth::attempt($credentials, $request->filled('remember'))) {
            RateLimiter::hit($throttleKey, 600); // Tambah hit jika gagal login (10 menit)
            Log::warning('Login failed', ['email' => $request->email, 'ip' => $request->ip()]);
            return back()->withErrors([
                'email' => __('Email atau password salah.'),
            ]);
        }

        RateLimiter::clear($throttleKey); // Reset jika login berhasil
        $request->session()->regenerate(); // Mencegah session fixation attack

        // Logging keberhasilan login
        Log::info('User logged in', ['email' => $request->email, 'ip' => $request->ip()]);

        return redirect()->intended('/Admin/Dashboard/Dashboard');
    }

    /**
     * Logout dan hancurkan sesi dengan keamanan tambahan.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Log::info('User logged out', ['ip' => $request->ip()]);

        return redirect('/login');
    }
}
