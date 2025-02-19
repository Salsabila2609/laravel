<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class PasswordResetLinkController extends Controller
{
    /**
     * Tampilkan halaman permintaan reset password.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/ForgotPassword', [
            'status' => session('status'),
        ]);
    }

    /**
     * Tangani permintaan tautan reset password dengan keamanan tinggi.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $throttleKey = 'reset-password:' . $request->ip();

        // Proteksi Brute Force - Maksimal 3 permintaan dalam 15 menit
        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            Log::warning('Brute-force attempt on password reset request', ['ip' => $request->ip()]);
            throw ValidationException::withMessages([
                'email' => __('Terlalu banyak permintaan reset password. Silakan coba lagi nanti.'),
            ]);
        }

        // Kirim tautan reset password
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // Jika berhasil, bersihkan rate limiter
        if ($status == Password::RESET_LINK_SENT) {
            RateLimiter::clear($throttleKey);
            return back()->with('status', __($status));
        }

        // Jika gagal, tambah hit ke rate limiter
        RateLimiter::hit($throttleKey, 900); // 15 menit cooldown
        Log::warning('Failed password reset attempt', ['email' => $request->email, 'ip' => $request->ip()]);

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }
}
