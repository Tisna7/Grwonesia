<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
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
            $request->session()->regenerate();
            $request->session()->forget('url.intended');

            $user = $request->user();
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
            return redirect()->route(Auth::user()->getDashboardRouteName());
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'role' => ['required', 'string', 'in:user,business,government'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);
        $request->session()->regenerate();
        $request->session()->forget('url.intended');

        return redirect()->route($user->getDashboardRouteName())->with('success', 'Akun berhasil dibuat! Selamat datang di Grownesia.');
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
