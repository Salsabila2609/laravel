<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class VerifyEmailController extends Controller
{
    /**
     * Verifikasi email pengguna dengan perlindungan keamanan tambahan.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        $user = $request->user();
        $throttleKey = 'verify-email:' . $user->id;

        // Proteksi Brute Force - Maksimal 5 percobaan dalam 10 menit
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            Log::warning('Brute-force attempt on email verification', ['user_id' => $user->id, 'ip' => $request->ip()]);
            throw ValidationException::withMessages([
                'email' => __('Terlalu banyak percobaan verifikasi. Silakan coba lagi nanti.'),
            ]);
        }

        // Jika email sudah diverifikasi, langsung redirect
        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false) . '?verified=1');
        }

        // Tandai email sebagai terverifikasi
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        RateLimiter::clear($throttleKey); // Bersihkan rate limiter jika berhasil
        Log::info('Email verified successfully', ['user_id' => $user->id, 'ip' => $request->ip()]);

        return redirect()->intended(route('dashboard', absolute: false) . '?verified=1');
    }
}
