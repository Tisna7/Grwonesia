<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Services\Instagram\InstagramService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class InstagramController extends Controller
{
    public function index(Request $request, InstagramService $instagram): View
    {
        $business = $request->user()->business;

        return view('business.instagram', [
            'configured' => $instagram->isConfigured(),
            'account' => $instagram->accountInfo(),
            'products' => $business->products()->active()->whereNotNull('photo_path')->orderBy('name')->get(),
            'captions' => $business->marketingContents()
                ->whereIn('type', ['ig_caption', 'promo_copy'])
                ->latest()
                ->take(10)
                ->get(),
            'posts' => $business->igPosts()->with('product')->latest()->paginate(10),
            'appUrlIsLocal' => Str::contains(config('app.url'), ['localhost', '127.0.0.1']),
        ]);
    }

    public function publish(Request $request, InstagramService $instagram): RedirectResponse
    {
        $business = $request->user()->business;

        $validated = $request->validate([
            'product_id' => ['required', 'integer'],
            'caption' => ['required', 'string', 'max:2200'],
        ]);

        $product = $business->products()->findOrFail($validated['product_id']);

        if (! $product->photo_path || ! Storage::disk('public')->exists($product->photo_path)) {
            return back()->with('error', 'Produk ini belum punya foto. Unggah foto dulu di halaman Produk.');
        }

        $imageUrl = asset('storage/'.$product->photo_path);

        $post = $instagram->publishPhoto($business, $imageUrl, $validated['caption'], $product);

        return $post->status === 'published'
            ? back()->with('success', 'Foto berhasil diposting ke Instagram! 🎉')
            : back()->with('error', 'Gagal posting: '.$post->error);
    }
}
