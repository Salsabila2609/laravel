<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Kirim ulang email verifikasi dengan keamanan tambahan.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();
        $throttleKey = 'email-verification:' . $user->id;

        // Jika email sudah terverifikasi, langsung redirect
        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        // Proteksi brute force - Maksimal 3 permintaan dalam 15 menit
        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            Log::warning('Brute-force attempt on email verification request', [
                'user_id' => $user->id,
                'ip' => $request->ip(),
            ]);
            return back()->withErrors(['email' => __('Terlalu banyak permintaan verifikasi. Silakan coba lagi nanti.')]);
        }

        // Kirim ulang email verifikasi
        $user->sendEmailVerificationNotification();
        RateLimiter::hit($throttleKey, 600); 

        Log::info('Email verification link sent successfully', [
            'user_id' => $user->id,
            'ip' => $request->ip(),
        ]);

        return back()->with('status', 'verification-link-sent');
    }
}
