<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:30'],
            'business_name' => ['required', 'string', 'max:255'],
            'business_category' => ['required', 'string', 'max:50'],
            'business_city' => ['nullable', 'string', 'max:100'],
            'wa_number' => ['nullable', 'string', 'max:30'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nama lengkap',
            'email' => 'email',
            'password' => 'kata sandi',
            'business_name' => 'nama usaha',
            'business_category' => 'kategori usaha',
        ];
    }
}
