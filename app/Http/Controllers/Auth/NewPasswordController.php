<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class NewPasswordController extends Controller
{
    /**
     * Menampilkan halaman reset password dengan keamanan tambahan.
     */
    public function create(Request $request): Response
    {
        // Mencegah XSS pada email dan token dengan htmlspecialchars
        $email = htmlspecialchars($request->email, ENT_QUOTES, 'UTF-8');
        $token = htmlspecialchars($request->route('token'), ENT_QUOTES, 'UTF-8');

        return Inertia::render('Auth/ResetPassword', [
            'email' => $email,
            'token' => $token,
        ]);
    }

    /**
     * Menangani permintaan reset password dengan keamanan tinggi.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validasi input untuk mencegah SQL Injection dan serangan lainnya
        $validator = Validator::make($request->all(), [
            'token' => ['required', 'string', 'regex:/^[A-Za-z0-9_\-]+$/'],
            'email' => ['required', 'email', 'max:255'],
            'password' => [
                'required',
                'confirmed',
                Rules\Password::defaults(),
                'max:100', // Membatasi panjang password untuk mencegah serangan DOS
            ],
        ]);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }

        $throttleKey = 'reset-password:' . $request->ip();

        // Proteksi Brute Force: Maksimal 5 percobaan dalam 10 menit
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            Log::warning('Brute-force attempt on password reset', [
                'email' => $request->email,
                'ip' => $request->ip(),
            ]);
            throw ValidationException::withMessages([
                'email' => __('Terlalu banyak percobaan reset password. Silakan coba lagi nanti.'),
            ]);
        }

        // Hit rate limiter
        RateLimiter::hit($throttleKey, 600);

        // Mencegah token palsu atau expired
        if (!$request->filled('token')) {
            throw new BadRequestException(__('Token tidak valid atau telah kedaluwarsa.'));
        }

        // Proses reset password dengan sanitasi input
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));

                Log::info('Password successfully reset', [
                    'user_id' => $user->id,
                    'ip' => $request->ip(),
                ]);
            }
        );

        // Jika berhasil, arahkan ke login
        if ($status == Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', __($status));
        }

        // Jika gagal, catat log keamanan
        Log::error('Password reset failed', [
            'email' => $request->email,
            'status' => $status,
            'ip' => $request->ip(),
        ]);

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }
}
