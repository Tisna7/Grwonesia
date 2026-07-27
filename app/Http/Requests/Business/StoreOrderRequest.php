<?php

namespace App\Http\Requests\Business;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isBusiness() ?? false;
    }

    public function rules(): array
    {
        return [
            'customer_id' => ['nullable', 'integer'],
            'customer_name' => ['required_without:customer_id', 'nullable', 'string', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:30'],
            'status' => ['required', 'in:pending,paid,shipped,completed'],
            'channel' => ['required', 'in:manual,whatsapp,marketplace'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'integer'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ];
    }

    public function attributes(): array
    {
        return [
            'customer_name' => 'nama pelanggan',
            'items' => 'item pesanan',
        ];
    }

    public function messages(): array
    {
        return [
            'items.required' => 'Tambahkan minimal satu produk ke pesanan.',
            'customer_name.required_without' => 'Pilih pelanggan atau isi nama pelanggan baru.',
        ];
    }
}
