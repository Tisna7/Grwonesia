<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Http\Requests\Business\StoreOrderRequest;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\WhatsApp\WhatsAppService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $business = $request->user()->business;

        $orders = $business->orders()
            ->with('customer')
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->orderByDesc('ordered_at')
            ->paginate(15)
            ->withQueryString();

        return view('business.orders.index', compact('orders'));
    }

    public function create(Request $request): View
    {
        $business = $request->user()->business;

        return view('business.orders.create', [
            'products' => $business->products()->active()->orderBy('name')->get(),
            'customers' => $business->customers()->orderBy('name')->get(),
        ]);
    }

    public function store(StoreOrderRequest $request, WhatsAppService $whatsApp): RedirectResponse
    {
        $business = $request->user()->business;

        $order = DB::transaction(function () use ($request, $business) {
            $customerId = null;

            if ($request->filled('customer_id')) {
                $customerId = $business->customers()->findOrFail($request->customer_id)->id;
            } elseif ($request->filled('customer_name')) {
                $customerId = Customer::create([
                    'business_id' => $business->id,
                    'name' => $request->customer_name,
                    'phone' => $request->customer_phone,
                ])->id;
            }

            $order = Order::create([
                'business_id' => $business->id,
                'customer_id' => $customerId,
                'order_number' => Order::generateOrderNumber(),
                'status' => $request->status,
                'channel' => $request->channel,
                'total' => 0,
                'total_cost' => 0,
                'notes' => $request->notes,
                'ordered_at' => now(),
            ]);

            $total = 0;
            $totalCost = 0;

            foreach ($request->items as $item) {
                $product = $business->products()
                    ->lockForUpdate()
                    ->findOrFail($item['product_id']);

                $qty = (int) $item['quantity'];

                if ($product->stock < $qty) {
                    throw ValidationException::withMessages([
                        'items' => "Stok {$product->name} tidak cukup (tersisa {$product->stock}).",
                    ]);
                }

                $subtotal = (float) $product->price * $qty;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $qty,
                    'unit_price' => $product->price,
                    'unit_cost' => $product->cost_price,
                    'subtotal' => $subtotal,
                ]);

                $product->decrement('stock', $qty);

                $total += $subtotal;
                $totalCost += (float) $product->cost_price * $qty;
            }

            $order->update(['total' => $total, 'total_cost' => $totalCost]);

            return $order;
        });

        $whatsApp->sendOrderConfirmation($order);

        return redirect()->route('business.orders.show', $order)
            ->with('success', "Pesanan {$order->order_number} berhasil dibuat.");
    }

    public function show(Request $request, Order $order): View
    {
        $this->authorizeOrder($request, $order);

        $order->load(['customer', 'items.product']);

        return view('business.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $this->authorizeOrder($request, $order);

        $request->validate([
            'status' => ['required', 'in:pending,paid,shipped,completed,cancelled'],
        ]);

        $order->update(['status' => $request->status]);

        return back()->with('success', 'Status pesanan diperbarui.');
    }

    private function authorizeOrder(Request $request, Order $order): void
    {
        abort_unless($order->business_id === $request->user()->business?->id, 404);
    }
}
