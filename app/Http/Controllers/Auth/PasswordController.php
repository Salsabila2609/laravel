<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Perbarui password pengguna dengan keamanan tinggi.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        $throttleKey = 'change-password:' . $user->id;

        // Proteksi Brute Force - Maksimal 5 percobaan dalam 10 menit
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            Log::warning('Brute-force attempt on password change', ['user_id' => $user->id, 'ip' => $request->ip()]);
            throw ValidationException::withMessages([
                'current_password' => __('Terlalu banyak percobaan. Silakan coba lagi nanti.'),
            ]);
        }

        // Validasi input
        $validated = $request->validate([
            'current_password' => ['required', 'string', 'min:6', 'max:100', 'current_password'],
            'password' => ['required', 'string', Password::defaults(), 'confirmed', 'max:100'],
        ]);

        // Jika password lama salah, tambahkan ke hit rate limiter
        if (!Hash::check($validated['current_password'], $user->password)) {
            RateLimiter::hit($throttleKey, 600); // 10 menit cooldown
            Log::warning('Password change failed - incorrect current password', ['user_id' => $user->id, 'ip' => $request->ip()]);
            throw ValidationException::withMessages([
                'current_password' => __('Password lama salah.'),
            ]);
        }

        // Update password dengan hashing
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        RateLimiter::clear($throttleKey); // Reset rate limiter jika berhasil
        Log::info('Password changed successfully', ['user_id' => $user->id, 'ip' => $request->ip()]);

        return back()->with('status', __('Password berhasil diperbarui.'));
    }
}
