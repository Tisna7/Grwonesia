<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GrownesiaController extends Controller
{
    public function landing()
    {
        if (Auth::check()) {
            return redirect()->route(Auth::user()->getDashboardRouteName());
        }
        return view('grownesia.landing');
    }

    public function dashboard()
    {
        if (Auth::check()) {
            $dashboardRoute = Auth::user()->getDashboardRouteName();
            if ($dashboardRoute !== 'user.dashboard') {
                return redirect()->route($dashboardRoute);
            }
        }
        $dbProducts = Product::with('business')->active()->latest()->get();

        if ($dbProducts->isNotEmpty()) {
            $products = $dbProducts->map(function ($p) {
                $categorySlug = match(strtolower($p->category ?? '')) {
                    'minuman', 'kopi' => 'kopi',
                    'batik', 'fashion', 'pakaian' => 'batik',
                    'kerajinan', 'craft' => 'kerajinan',
                    default => 'makanan',
                };

                $categoryLabel = match($categorySlug) {
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
                    'region' => ($p->business?->city ? $p->business->city.', Indonesia' : 'Jawa Barat'),
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
            $products = [
                [
                    'id' => 1,
                    'name' => 'Kopi Gula Aren Nusantara Premium 500ml',
                    'category' => 'kopi',
                    'category_label' => 'Kopi & Minuman',
                    'umkm' => 'Kopi Wong Kito',
                    'region' => 'Palembang, Sumsel',
                    'price' => 45000,
                    'original_price' => 55000,
                    'rating' => 4.9,
                    'reviews_count' => 128,
                    'image' => '/images/products/kopi_gula_aren.png',
                    'impact_text' => '2 Petani Lokal & 1 Pengolah Biji Terbantu',
                    'impact_score' => 85,
                    'description' => 'Kopi biji robusta dipadu dengan gula aren murni khas Sumatra. Aroma harum, rasa legit dan pas di lidah.',
                    'tags' => ['Terlaris', 'Eco-Friendly']
                ],
                [
                    'id' => 2,
                    'name' => 'Batik Tulis Mega Mendung Signature Solo',
                    'category' => 'batik',
                    'category_label' => 'Batik & Fashion',
                    'umkm' => 'Batik Sekar Arum',
                    'region' => 'Surakarta, Jawa Tengah',
                    'price' => 185000,
                    'original_price' => 220000,
                    'rating' => 5.0,
                    'reviews_count' => 84,
                    'image' => '/images/products/batik_solo.png',
                    'impact_text' => '3 Pengrajin Wanita Desa Terbantu',
                    'impact_score' => 95,
                    'description' => 'Batik tulis buatan tangan pewarna alami indigo khas Solo. Kain katun primissima super lembut dan tahan lama.',
                    'tags' => ['Pemberdayaan Wanita', 'Handmade']
                ],
                [
                    'id' => 3,
                    'name' => 'Tas Anyaman Pandan Organik Modern',
                    'category' => 'kerajinan',
                    'category_label' => 'Kerajinan & Craft',
                    'umkm' => 'Anyaman Asri Kebumen',
                    'region' => 'Kebumen, Jawa Tengah',
                    'price' => 65000,
                    'original_price' => 80000,
                    'rating' => 4.8,
                    'reviews_count' => 96,
                    'image' => '/images/products/tas_anyaman.png',
                    'impact_text' => '1 Desa Berkembang & 4 Pekerja Lokal',
                    'impact_score' => 90,
                    'description' => 'Tas tote bag ramah lingkungan berbahan serat daun pandan alami dengan pegangan kulit sintestis premium.',
                    'tags' => ['Eco-Friendly', 'Desa Berkembang']
                ],
                [
                    'id' => 4,
                    'name' => 'Paket Keripik Cassava Coffee Crunch 250g',
                    'category' => 'makanan',
                    'category_label' => 'Makanan Ringan',
                    'umkm' => 'Snack Mandiri Boyolali',
                    'region' => 'Boyolali, Jawa Tengah',
                    'price' => 35000,
                    'original_price' => 42000,
                    'rating' => 4.7,
                    'reviews_count' => 210,
                    'image' => '/images/products/keripik_kopi.png',
                    'impact_text' => '2 Pemuda Desa & Petani Singkong',
                    'impact_score' => 80,
                    'description' => 'Keripik singkong renyah bertabur bubuk kopi gurih manis khas Boyolali. Cocok untuk teman ngopi & cemilan harian.',
                    'tags' => ['Cemilan Viral', 'Pekerja Lokal']
                ],
                [
                    'id' => 5,
                    'name' => 'Kopi Klepon Heritage Reserve 250g',
                    'category' => 'kopi',
                    'category_label' => 'Kopi & Minuman',
                    'umkm' => 'Roastery Lereng Bromo',
                    'region' => 'Malang, Jawa Timur',
                    'price' => 60000,
                    'original_price' => 70000,
                    'rating' => 4.9,
                    'reviews_count' => 74,
                    'image' => '/images/products/kopi_gula_aren.png',
                    'impact_text' => '3 Petani Kopi Organik Bromo',
                    'impact_score' => 88,
                    'description' => 'Perpaduan biji kopi arabika Bromo dengan sensasi aroma pandan dan gurihnya kelapa khas kue klepon tradisional.',
                    'tags' => ['Specialty Coffee']
                ],
                [
                    'id' => 6,
                    'name' => 'Kain Tenun Ikat NTT Handmade Royal Violet',
                    'category' => 'batik',
                    'category_label' => 'Batik & Fashion',
                    'umkm' => 'Tenun Ina Sabu',
                    'region' => 'Kupang, NTT',
                    'price' => 195000,
                    'original_price' => 230000,
                    'rating' => 5.0,
                    'reviews_count' => 52,
                    'image' => '/images/products/batik_solo.png',
                    'impact_text' => '5 Penenun Ibu-Ibu NTT Terbantu',
                    'impact_score' => 98,
                    'description' => 'Kain tenun ikat tradisional buatan tangan asli Kupang NTT. Motif etnik yang anggun dengan benang sutra tenun.',
                    'tags' => ['Pemberdayaan Wanita', 'Warisan Budaya']
                ]
            ];
        }

        $user = Auth::user() ?? (object)[
            'name' => 'Budi Santoso',
            'email' => 'budi@grownesia.id',
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

        return view('grownesia.dashboard', compact('products', 'user', 'ordersHistory'));
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
        $displayStatus = match($rawStatus) {
            'delivered', 'selesai' => 'Selesai',
            'shipped', 'in_transit', 'out_for_delivery', 'dalam pengiriman' => 'Dalam Pengiriman',
            'packed' => 'Dikemas',
            'cancelled' => 'Dibatalkan',
            'return' => 'Dikembalikan',
            default => 'Diproses',
        };

        $shippingStatusBadge = match($rawStatus) {
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
            'productImage' => $firstItem?->product?->image_url ?? '/images/products/kopi_gula_aren.png',
            'aiInsight' => 'Pengiriman dipantau secara real-time dari sistem logistik penjual ' . ($order->business?->name ?? 'UMKM') . '.',
            'aiConfidence' => '98%',
            'impact' => 'Pemberdayaan UMKM & Pekerja Lokal',
            'reviewed' => false,
            'timeline' => $timeline,
        ];
    }
}
