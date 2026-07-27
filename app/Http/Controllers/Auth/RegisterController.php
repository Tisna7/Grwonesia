<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Business;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(RegisterRequest $request): RedirectResponse
    {
        $user = DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'phone' => $request->phone,
                'role' => 'business',
            ]);

            Business::create([
                'user_id' => $user->id,
                'name' => $request->business_name,
                'slug' => Str::slug($request->business_name).'-'.Str::lower(Str::random(5)),
                'category' => $request->business_category,
                'city' => $request->business_city,
                'wa_number' => $request->wa_number,
            ]);

            return $user;
        });

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('business.dashboard')
            ->with('success', 'Selamat datang di Grownesia! Akun bisnis Anda berhasil dibuat.');
    }
}
