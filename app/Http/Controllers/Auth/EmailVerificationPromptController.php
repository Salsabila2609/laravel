<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;

class EmailVerificationPromptController extends Controller
{
    /**
     * Menampilkan halaman verifikasi email dengan keamanan tambahan.
     */
    public function __invoke(Request $request): RedirectResponse|Response
    {
        $user = $request->user();
        $throttleKey = 'email-verification-prompt:' . $user->id;

        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            Log::warning('Brute-force attempt on email verification prompt', [
                'user_id' => $user->id,
                'ip' => $request->ip(),
            ]);
            return back()->withErrors(['email' => __('Terlalu banyak permintaan verifikasi. Silakan coba lagi nanti.')]);
        }

        RateLimiter::hit($throttleKey, 600);

        Log::info('User accessed email verification prompt', [
            'user_id' => $user->id,
            'ip' => $request->ip(),
        ]);

        return Inertia::render('Auth/VerifyEmail', [
            'status' => session('status'),
        ]);
    }
}
