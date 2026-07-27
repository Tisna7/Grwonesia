<?php

namespace App\Http\Requests\Business;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isBusiness() ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['nullable', 'string', 'max:60'],
            'category' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:5000'],
            'price' => ['required', 'numeric', 'min:0'],
            'cost_price' => ['nullable', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'min_stock' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'in:active,inactive'],
            'photo' => ['nullable', 'image', 'max:4096'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nama produk',
            'category' => 'kategori',
            'price' => 'harga jual',
            'cost_price' => 'harga modal',
            'stock' => 'stok',
            'min_stock' => 'stok minimum',
            'photo' => 'foto produk',
        ];
    }
}
