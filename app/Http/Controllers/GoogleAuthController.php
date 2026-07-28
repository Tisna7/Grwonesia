<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Redirect ke halaman login Google.
     */
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle callback dari Google OAuth.
     */
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors([
                'email' => 'Login dengan Google gagal. Silakan coba lagi.',
            ]);
        }

        // Cari atau buat user berdasarkan google_id atau email
        $user = User::where('google_id', $googleUser->getId())->first();

        if (! $user) {
            // Cek apakah email sudah terdaftar (akun manual)
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // Tautkan akun Google ke akun yang sudah ada
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar'    => $googleUser->getAvatar(),
                    'status'    => 'terverifikasi',
                    'email_verified_at' => $user->email_verified_at ?? now(),
                ]);
            } else {
                // Buat akun baru dengan role default 'user'
                $user = User::create([
                    'name'      => $googleUser->getName(),
                    'email'     => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar'    => $googleUser->getAvatar(),
                    'role'      => UserRole::Consumer->value,
                    'status'    => 'terverifikasi',
                    'email_verified_at' => now(),
                    'password'  => null,
                ]);
            }
        }

        Auth::login($user, remember: true);
        request()->session()->regenerate();

        return redirect()->route($user->getDashboardRouteName())
            ->with('success', 'Selamat datang, '.$user->name.'! Berhasil masuk dengan Google.');
    }
}
