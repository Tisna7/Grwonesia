<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Http\Requests\Business\StoreProductRequest;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $business = $request->user()->business;

        $products = $business->products()
            ->when($request->filled('q'), fn ($q) => $q->where('name', 'like', '%'.$request->q.'%'))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('business.products.index', compact('products'));
    }

    public function create(): View
    {
        return view('business.products.create');
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $business = $request->user()->business;

        $data = $request->validated();
        $data['business_id'] = $business->id;
        $data['slug'] = Str::slug($data['name']).'-'.Str::lower(Str::random(5));
        $data['cost_price'] = $data['cost_price'] ?? 0;

        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('products', 'public');
        }

        unset($data['photo']);

        $product = Product::create($data);

        return redirect()->route('business.products.edit', $product)
            ->with('success', 'Produk berhasil ditambahkan. Gunakan AI Optimizer untuk menyempurnakan listing.');
    }

    public function edit(Request $request, Product $product): View
    {
        $this->authorizeProduct($request, $product);

        return view('business.products.edit', compact('product'));
    }

    public function update(StoreProductRequest $request, Product $product): RedirectResponse
    {
        $this->authorizeProduct($request, $product);

        $data = $request->validated();
        $data['cost_price'] = $data['cost_price'] ?? 0;

        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('products', 'public');
            // Foto baru — analisis lama tidak lagi relevan
            $data['ai_photo_analysis'] = null;
        }

        unset($data['photo']);

        $product->update($data);

        return redirect()->route('business.products.edit', $product)
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Request $request, Product $product): RedirectResponse
    {
        $this->authorizeProduct($request, $product);

        $product->delete();

        return redirect()->route('business.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    private function authorizeProduct(Request $request, Product $product): void
    {
        abort_unless($product->business_id === $request->user()->business?->id, 404);
    }
}
