<?php
// FILE: app/Http/Controllers/User/DashboardController.php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
  public function index(Request $request, $id = null): View
  {
    $path = trim($request->path(), '/');

    $activeTab = 'katalog';
    $initialProductId = null;

    if (str_starts_with($path, 'produk/') || str_starts_with($path, 'products/')) {
      $activeTab = 'detail';
      $initialProductId = (int) ($id ?? basename($path));
    } elseif ($path === 'checkout') {
      $activeTab = 'cart';
    } elseif ($path === 'cart' || $path === 'keranjang') {
      $activeTab = 'cart';
    } elseif ($path === 'orders' || $path === 'pesanan') {
      $activeTab = 'orders';
    } elseif ($path === 'tracking' || $path === 'lacak') {
      $activeTab = 'tracking';
    } elseif ($path === 'profile' || $path === 'profil') {
      $activeTab = 'profile';
    } elseif ($path === 'ai-assistant') {
      $activeTab = 'ai-assistant';
    } elseif ($path === 'ai-gift') {
      $activeTab = 'ai-gift';
    } elseif ($path === 'ai-compare') {
      $activeTab = 'ai-compare';
    } elseif ($path === 'favorites' || $path === 'favorit') {
      $activeTab = 'favorites';
    }

    $dbProducts = Product::with('business')->active()->latest()->get();

    if ($dbProducts->isNotEmpty()) {
      $products = $dbProducts->map(function ($p) {
        $categorySlug = match (strtolower($p->category ?? '')) {
          'minuman', 'kopi' => 'kopi',
          'batik', 'fashion', 'pakaian' => 'batik',
          'kerajinan', 'craft' => 'kerajinan',
          default => 'makanan',
        };

        $categoryLabel = match ($categorySlug) {
          'kopi' => 'Kopi & Minuman',
          'batik' => 'Batik & Fashion',
          'kerajinan' => 'Kerajinan & Craft',
          default => 'Makanan Ringan',
        };

        return [
          'id' => $p->id,
          'name' => $p->name,
          'category' => $categorySlug,
          'category_label' => $categoryLabel,
          'umkm' => $p->business?->name ?? 'UMKM Mitra',
          'region' => ($p->business?->city ? $p->business->city . ', Indonesia' : 'Jawa Barat'),
          'price' => (float) $p->price,
          'original_price' => round((float) $p->price * 1.2),
          'rating' => 4.9,
          'reviews_count' => rand(30, 200),
          'image' => $p->image_url,
          'impact_text' => 'Pemberdayaan Petani & Pekerja Lokal',
          'impact_score' => rand(80, 98),
          'description' => $p->description ?? 'Produk berkualitas buatan UMKM lokal mitra Grownesia.',
          'tags' => ['Mitra Terverifikasi', 'Pekerja Lokal'],
        ];
      })->toArray();
    } else {
      $products = [];
    }

    $user = Auth::user() ?? (object) [
      'name' => 'Budi Santoso',
      'email' => 'budi@grownesia.id',
      'phone' => '0812-3456-7890',
      'address' => 'Jl. Sudirman No. 45, Kebayoran Baru, Jakarta Selatan',
    ];

    // Fetch real database orders for the customer/user
    $userEmail = Auth::user()?->email ?? 'budi@grownesia.id';
    $dbOrders = Order::with(['business', 'items.product', 'customer'])
      ->whereHas('customer', function ($q) use ($userEmail) {
        $q->where('email', $userEmail);
      })
      ->latest('ordered_at')
      ->latest('id')
      ->get();

    if ($dbOrders->isEmpty()) {
      // Fallback to recent orders in DB so live tracking is always populated for demonstration
      $dbOrders = Order::with(['business', 'items.product', 'customer'])
        ->latest('ordered_at')
        ->latest('id')
        ->take(5)
        ->get();
    }

    $ordersHistory = $dbOrders->map(fn($o) => $this->formatOrderForFrontend($o))->values()->toArray();

    // Fetch dynamic reviews
    $reviews = \App\Models\ProductReview::latest()->get()->map(function ($rev) {
      return [
        'id' => $rev->id,
        'productId' => $rev->product_id,
        'userName' => $rev->user_name,
        'rating' => (int) $rev->rating,
        'date' => $rev->created_at ? $rev->created_at->format('d M Y') : 'Hari ini',
        'comment' => $rev->comment,
        'verified' => (bool) $rev->verified,
      ];
    })->toArray();

    // Fetch dynamic notifications
    $dbNotifs = \App\Models\UserNotification::where('user_id', Auth::id() ?? ($user->id ?? 1))
      ->latest()
      ->get();
    $notifications = $dbNotifs->map(function ($notif) {
      return [
        'id' => $notif->id,
        'title' => $notif->title,
        'desc' => $notif->desc,
        'time' => $notif->time_label,
      ];
    })->toArray();

    $unreadNotifCount = \App\Models\UserNotification::where('user_id', Auth::id() ?? ($user->id ?? 1))
      ->where('is_read', false)
      ->count();

    // Setup user profile data
    $userProfile = [
      'name' => Auth::user()?->name ?? 'Budi Santoso',
      'email' => Auth::user()?->email ?? 'budi@grownesia.id',
      'phone' => Auth::user()?->raw_phone ?? Auth::user()?->phone ?? '0812-3456-7890',
      'address' => Auth::user()?->address ?? 'Jl. Sudirman No. 45, Kebayoran Baru, Jakarta Selatan',
    ];

    // Calculate dynamic user impact based on orders
    $totalJobs = 8;
    $villagesHelped = 3;
    $craftswomenHelped = 5;
    foreach ($dbOrders as $o) {
      $status = strtolower($o->shipping_status ?? '');
      if ($status === 'delivered' || $status === 'completed' || $status === 'selesai') {
        $totalJobs += 3;
        $villagesHelped += 1;
        $craftswomenHelped += 2;
      }
    }
    $userImpact = [
      'totalJobs' => $totalJobs,
      'villagesHelped' => $villagesHelped,
      'craftswomenHelped' => $craftswomenHelped,
    ];

    // Fetch dynamic favorites for logged in user
    $userFavorites = Auth::check()
      ? \App\Models\Favorite::where('user_id', Auth::id())->pluck('product_id')->map(fn($id) => (int) $id)->toArray()
      : [];

    return view('user.dashboard', compact(
      'products',
      'user',
      'ordersHistory',
      'reviews',
      'notifications',
      'unreadNotifCount',
      'userProfile',
      'userImpact',
      'activeTab',
      'initialProductId',
      'userFavorites'
    ));
  }

  public function getShippingStatus(Order $order): JsonResponse
  {
    $order->load(['business', 'items.product', 'customer']);
    return response()->json([
      'success' => true,
      'order' => $this->formatOrderForFrontend($order),
    ]);
  }

  private function formatOrderForFrontend(Order $order): array
  {
    $firstItem = $order->items->first();
    $itemsSummary = $order->items->map(function ($item) {
      return $item->product_name . ' (' . $item->quantity . 'x)';
    })->join(', ');

    $rawStatus = strtolower($order->shipping_status ?? 'pending');
    $displayStatus = match ($rawStatus) {
      'delivered', 'selesai' => 'Selesai',
      'shipped', 'in_transit', 'out_for_delivery', 'dalam pengiriman' => 'Dalam Pengiriman',
      'packed' => 'Dikemas',
      'cancelled' => 'Dibatalkan',
      'return' => 'Dikembalikan',
      default => 'Diproses',
    };

    $shippingStatusBadge = match ($rawStatus) {
      'delivered' => 'Delivered',
      'shipped' => 'Out For Delivery',
      'packed' => 'Order Packed',
      'pending' => 'Pending Pickup',
      'cancelled' => 'Cancelled',
      'return' => 'Return',
      default => ucfirst($rawStatus),
    };

    $courier = $order->courier ?: 'JNE Express';
    $courierParts = explode(' ', trim($courier));
    $courierLogo = strtoupper($courierParts[0] ?: 'JNE');

    $timeline = $order->shipping_timeline;
    if (empty($timeline) || !is_array($timeline)) {
      $timeline = [
        [
          'time' => $order->ordered_at ? $order->ordered_at->format('d M, H:i') : now()->format('d M, H:i'),
          'location' => $order->business?->city ?? 'Gudang Penjual',
          'desc' => 'Order Confirmed',
          'icon' => 'check',
          'done' => true,
        ],
        [
          'time' => $order->updated_at ? $order->updated_at->format('d M, H:i') : now()->format('d M, H:i'),
          'location' => $order->current_location ?: ($order->business?->city ?: 'Gudang Penjual'),
          'desc' => 'Status: ' . $displayStatus,
          'icon' => 'package',
          'done' => in_array($rawStatus, ['packed', 'shipped', 'delivered']),
        ],
      ];
    } else {
      $timeline = array_map(function ($t) {
        return [
          'time' => $t['timestamp'] ?? ($t['time'] ?? now()->format('d M, H:i')),
          'location' => $t['location'] ?? '',
          'desc' => $t['title'] ?? ($t['description'] ?? ($t['desc'] ?? '')),
          'icon' => $t['icon'] ?? 'check',
          'done' => true,
        ];
      }, $timeline);
    }

    return [
      'id' => $order->order_number ?: ('GRW-' . $order->id),
      'db_id' => $order->id,
      'date' => $order->ordered_at ? $order->ordered_at->format('d F Y') : $order->created_at->format('d F Y'),
      'productId' => $firstItem?->product_id ?? 1,
      'productName' => $firstItem?->product_name ?? 'Produk UMKM',
      'items' => $itemsSummary ?: 'Produk UMKM',
      'total' => (float) $order->total,
      'status' => $displayStatus,
      'shippingStatus' => $shippingStatusBadge,
      'courier' => $courier,
      'courierLogo' => $courierLogo,
      'trackingNumber' => $order->tracking_number ?: 'Menunggu No. Resi',
      'currentLocation' => $order->current_location ?: ($order->business?->city ?: 'Gudang Penjual'),
      'lastUpdated' => $order->updated_at ? $order->updated_at->diffForHumans() : 'Baru saja',
      'estimatedArrival' => $order->estimated_arrival ?: '2-3 Hari Kerja',
      'businessName' => $order->business?->name ?? 'UMKM Mitra',
      'shippingAddress' => $order->shipping_address ?: ($order->customer?->city ?: 'Jl. Sudirman No. 45, Jakarta'),
      'shippingCost' => (float) ($order->shipping_cost ?? 0),
      'paymentMethod' => 'Marketplace Payment',
      'productImage' => $firstItem?->product?->image_url ?? '/images/products/kopi_gula_aren.webp',
      'aiInsight' => 'Pengiriman dipantau secara real-time dari sistem logistik penjual ' . ($order->business?->name ?? 'UMKM') . '.',
      'aiConfidence' => '98%',
      'impact' => 'Pemberdayaan UMKM & Pekerja Lokal',
      'reviewed' => \App\Models\ProductReview::where('order_id', $order->id)->exists(),
      'timeline' => $timeline,
    ];
  }

  public function updateProfile(Request $request): JsonResponse
  {
    $request->validate([
      'name' => ['required', 'string', 'max:255'],
      'email' => ['required', 'string', 'email', 'max:255'],
      'phone' => ['required', 'string'],
      'address' => ['nullable', 'string'],
    ]);

    $user = Auth::user();
    if (!$user) {
      return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
    }

    $phoneInput = $request->input('phone');
    $cleanedPhone = preg_replace('/\D/', '', $phoneInput);
    $resolvedJid = $cleanedPhone;

    // Call WA Gateway resolve JID
    try {
      $response = \Illuminate\Support\Facades\Http::withHeaders([
        'Authorization' => 'Bearer ' . env('WA_GATEWAY_TOKEN'),
      ])->timeout(5)->get('http://localhost:3010/resolve', [
            'phone' => $cleanedPhone
          ]);

      if ($response->successful()) {
        $data = $response->json();
        if (!empty($data['exists']) && !empty($data['jid'])) {
          $jidParts = explode('@', $data['jid']);
          $resolvedJid = $jidParts[0];
        }
      }
    } catch (\Throwable $e) {
      \Log::warning('Gagal resolve JID dari WA Gateway: ' . $e->getMessage());
    }

    $user->update([
      'name' => $request->name,
      'email' => $request->email,
      'phone' => $resolvedJid,
      'raw_phone' => $phoneInput,
      'address' => $request->address,
    ]);

    return response()->json([
      'success' => true,
      'message' => 'Profil berhasil diperbarui!',
      'user' => [
        'name' => $user->name,
        'email' => $user->email,
        'phone' => $phoneInput,
        'address' => $user->address,
      ]
    ]);
  }
}
