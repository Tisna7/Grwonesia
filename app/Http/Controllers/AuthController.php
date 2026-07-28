<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpVerificationMail;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            if (! Auth::user()->isVerified()) {
                return redirect()->route('verification.notice');
            }
            return redirect()->route(Auth::user()->getDashboardRouteName());
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = $request->user();

            // Check if email/status is verified
            if (! $user->isVerified()) {
                $user->update(['status' => 'pending']);

                $hasValidOtp = !empty($user->verification_code) && $user->verification_expires_at && $user->verification_expires_at->isFuture();

                if (!$hasValidOtp) {
                    $otp = sprintf('%06d', mt_rand(100000, 999999));
                    $user->update([
                        'verification_code' => $otp,
                        'verification_expires_at' => now()->addMinutes(15),
                    ]);

                    try {
                        Mail::to($user->email)->send(new OtpVerificationMail($user->name, $otp));
                    } catch (\Throwable $e) {
                        \Log::error('Gagal mengirim email OTP: ' . $e->getMessage());
                    }
                }

                Auth::logout();

                session([
                    'pending_verification_user_id' => $user->id,
                    'pending_verification_email' => $user->email,
                ]);

                return redirect()->route('verification.notice')
                    ->with('info', 'Silakan masukkan 6-digit kode OTP yang dikirim ke email Anda.');
            }

            // Sync email_verified_at if null
            if (is_null($user->email_verified_at) && $user->status === 'terverifikasi') {
                $user->update(['email_verified_at' => now()]);
            }

            $request->session()->regenerate();
            $request->session()->forget('url.intended');

            return redirect()->route($user->getDashboardRouteName())
                ->with('success', 'Selamat datang kembali, '.$user->name.'!');
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi yang Anda masukkan tidak sesuai.',
        ])->onlyInput('email');
    }

    public function showRegisterForm()
    {
        if (Auth::check()) {
            if (! Auth::user()->isVerified()) {
                return redirect()->route('verification.notice');
            }
            return redirect()->route(Auth::user()->getDashboardRouteName());
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $existingUser = User::where('email', $request->email)->first();

        if ($existingUser) {
            if ($existingUser->status === 'terverifikasi' || !is_null($existingUser->email_verified_at)) {
                return back()->withErrors([
                    'email' => 'Email sudah terdaftar dan telah diverifikasi. Silakan login.',
                ])->onlyInput('email');
            }

            $hasValidOtp = !empty($existingUser->verification_code) && $existingUser->verification_expires_at && $existingUser->verification_expires_at->isFuture();

            if ($hasValidOtp) {
                $otp = $existingUser->verification_code;
                $existingUser->update([
                    'name' => $request->name,
                    'password' => $request->password,
                    'status' => 'pending',
                ]);
            } else {
                $otp = sprintf('%06d', mt_rand(100000, 999999));
                $existingUser->update([
                    'name' => $request->name,
                    'password' => $request->password,
                    'status' => 'pending',
                    'verification_code' => $otp,
                    'verification_expires_at' => now()->addMinutes(15),
                ]);
            }

            $user = $existingUser;
        } else {
            $otp = sprintf('%06d', mt_rand(100000, 999999));

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'role' => \App\Enums\UserRole::Consumer,
                'password' => $request->password,
                'status' => 'pending',
                'email_verified_at' => null,
                'verification_code' => $otp,
                'verification_expires_at' => now()->addMinutes(15),
            ]);
        }

        try {
            Mail::to($user->email)->send(new OtpVerificationMail($user->name, $otp));
        } catch (\Throwable $e) {
            \Log::error('Gagal mengirim email OTP saat registrasi: ' . $e->getMessage());
        }

        session([
            'pending_verification_user_id' => $user->id,
            'pending_verification_email' => $user->email,
        ]);

        return redirect()->route('verification.notice')
            ->with('success', 'Kode verifikasi 6 digit telah dikirimkan ke email '.$user->email.'.');
    }

    public function showVerifyForm(Request $request)
    {
        $userId = session('pending_verification_user_id') ?? $request->query('user_id');
        $user = $userId ? User::find($userId) : null;

        if (!$user && session('pending_verification_email')) {
            $user = User::where('email', session('pending_verification_email'))->where('status', '!=', 'terverifikasi')->first();
        }

        if (!$user) {
            return redirect()->route('register')->with('error', 'Silakan mendaftar terlebih dahulu.');
        }

        if ($user->isVerified()) {
            if (is_null($user->email_verified_at)) {
                $user->update(['email_verified_at' => now(), 'status' => 'terverifikasi']);
            }
            Auth::login($user);
            return redirect()->route($user->getDashboardRouteName());
        }

        return view('auth.verify-otp', compact('user'));
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'verification_code' => ['required', 'string', 'size:6'],
        ]);

        $userId = session('pending_verification_user_id') ?? $request->input('user_id');
        $userEmail = session('pending_verification_email') ?? $request->input('email');

        $user = null;
        if ($userId) {
            $user = User::find($userId);
        }
        if (!$user && $userEmail) {
            $user = User::where('email', $userEmail)->first();
        }

        if (!$user) {
            return redirect()->route('register')->with('error', 'Sesi verifikasi berakhir. Silakan mendaftar ulang.');
        }

        $inputOtp = trim((string) $request->verification_code);
        $dbOtp = trim((string) $user->verification_code);

        if ($dbOtp !== '' && $dbOtp === $inputOtp) {
            if ($user->verification_expires_at && $user->verification_expires_at->isPast()) {
                $user->update(['status' => 'pending']);
                return back()->with('error', 'Kode OTP telah kedaluwarsa (masa berlaku 15 menit). Silakan klik "Kirim Ulang Kode OTP".');
            }

            // High priority request fulfilled: status becomes 'terverifikasi'
            $user->update([
                'status' => 'terverifikasi',
                'email_verified_at' => now(),
                'verification_code' => null,
                'verification_expires_at' => null,
            ]);

            Auth::login($user);
            $request->session()->regenerate();
            session()->forget(['pending_verification_user_id', 'pending_verification_email']);

            return redirect()->route($user->getDashboardRouteName())
                ->with('success', 'Email berhasil diverifikasi! Status akun Anda kini terverifikasi.');
        }

        // High priority request fulfilled: status remains 'pending' when OTP is wrong
        $user->update(['status' => 'pending']);

        return back()->with('error', 'Kode OTP 6 digit yang Anda masukkan salah. Silakan periksa kembali email Anda.');
    }

    public function resendOtp(Request $request)
    {
        $userId = session('pending_verification_user_id') ?? $request->input('user_id');
        $userEmail = session('pending_verification_email') ?? $request->input('email');

        $user = null;
        if ($userId) {
            $user = User::find($userId);
        }
        if (!$user && $userEmail) {
            $user = User::where('email', $userEmail)->first();
        }

        if (!$user) {
            return redirect()->route('register')->with('error', 'Sesi verifikasi tidak ditemukan.');
        }

        $user->update(['status' => 'pending']);

        $hasValidOtp = !empty($user->verification_code) && $user->verification_expires_at && $user->verification_expires_at->isFuture();

        if ($hasValidOtp) {
            $otp = $user->verification_code;
        } else {
            $otp = sprintf('%06d', mt_rand(100000, 999999));
            $user->update([
                'verification_code' => $otp,
                'verification_expires_at' => now()->addMinutes(15),
            ]);
        }

        try {
            Mail::to($user->email)->send(new OtpVerificationMail($user->name, $otp));
        } catch (\Throwable $e) {
            \Log::error('Gagal mengirim ulang email OTP: ' . $e->getMessage());
        }

        session([
            'pending_verification_user_id' => $user->id,
            'pending_verification_email' => $user->email,
        ]);

        return back()->with('success', 'Kode OTP 6 digit baru telah dikirimkan ke email '.$user->email.'.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar dari akun Grownesia.');
    }

    public function switchRole(Request $request, string $role)
    {
        $emails = [
            'user' => 'budi@grownesia.id',
            'business' => 'demo@grownesia.test',
            'government' => 'gov@grownesia.test',
            'admin' => 'admin@grownesia.test',
        ];

        if (! array_key_exists($role, $emails)) {
            return back()->with('error', 'Role tidak valid.');
        }

        $user = User::where('email', $emails[$role])->first();

        if (! $user) {
            return back()->with('error', 'Akun demo untuk role ini belum tersedia.');
        }

        Auth::login($user);
        $request->session()->regenerate();

        $roleLabels = [
            'user' => 'Pembeli / Consumer',
            'business' => 'UMKM (Business Studio)',
            'government' => 'Government (Dinas UMKM)',
            'admin' => 'Super Admin',
        ];

        return redirect()->route($user->getDashboardRouteName())
            ->with('success', 'Berhasil beralih ke akun '.$roleLabels[$role].' ('.$user->name.').');
    }
}
