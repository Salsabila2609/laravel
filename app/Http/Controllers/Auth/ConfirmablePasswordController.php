<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class ConfirmablePasswordController extends Controller
{
    /**
     * Tampilkan halaman konfirmasi password.
     */
    public function show(): Response
    {
        return Inertia::render('Auth/ConfirmPassword');
    }

    /**
     * Konfirmasi password pengguna dengan keamanan tambahan.
     */
    public function store(Request $request): RedirectResponse
    {
       
        $validator = Validator::make($request->all(), [
            'password' => ['required', 'string', 'min:6', 'max:100'],
        ]);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }

        $user = $request->user();
        $throttleKey = 'confirm-password:' . $user->id;

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            Log::warning('Brute-force attempt on password confirmation', ['user_id' => $user->id, 'ip' => $request->ip()]);
            throw ValidationException::withMessages([
                'password' => __('Terlalu banyak percobaan. Silakan coba lagi nanti.'),
            ]);
        }

        $password = htmlspecialchars($request->password, ENT_QUOTES, 'UTF-8');
        
        if (!Auth::guard('web')->validate([
            'email' => $user->email,
            'password' => $password,
        ])) {
            RateLimiter::hit($throttleKey, 600); 
            Log::warning('Password confirmation failed', ['user_id' => $user->id, 'ip' => $request->ip()]);
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        RateLimiter::clear($throttleKey); 
        $request->session()->put('auth.password_confirmed_at', time());

        Log::info('Password confirmed successfully', ['user_id' => $user->id, 'ip' => $request->ip()]);

        return redirect()->intended(route('dashboard', absolute: false));
    }
}
