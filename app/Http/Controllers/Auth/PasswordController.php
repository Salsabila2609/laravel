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
     * Perbarui password pengguna dengan keamanan tinggi dan feedback yang jelas.
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

        // Validasi input dengan syarat keamanan tinggi
        $validated = $request->validate([
            'current_password' => ['required', 'string', 'current_password'],
            'password' => [
                'required',
                'string',
                'confirmed',
                'min:10', // Minimal 10 karakter
                'max:100',
                Password::min(12)
                    ->letters()    // Harus ada huruf
                    ->mixedCase()  // Harus ada huruf besar dan kecil
                    ->numbers()    // Harus ada angka
                    ->symbols(),   // Harus ada simbol
            ],
        ], [
            // Pesan error khusus jika password tidak memenuhi syarat
            'password.min' => 'Password harus minimal 12 karakter.',
            'password.letters' => 'Password harus mengandung setidaknya satu huruf.',
            'password.mixedCase' => 'Password harus memiliki huruf besar dan huruf kecil.',
            'password.numbers' => 'Password harus mengandung setidaknya satu angka.',
            'password.symbols' => 'Password harus mengandung setidaknya satu simbol (misalnya @, #, !, dll).',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // Jika password lama salah, tambahkan hit rate limiter
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
