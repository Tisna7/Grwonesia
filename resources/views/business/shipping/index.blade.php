@extends('layouts.business')

@section('title', 'Shipping Management')

@section('content')
<div x-data="shippingManager()" class="space-y-6">

    <!-- TOP SECTION -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white tracking-tight flex items-center gap-2.5 font-heading">
                <x-icon name="truck" class="w-7 h-7 text-primary-400"/>
                Shipping Management
            </h1>
            <p class="text-xs text-slate-400 mt-1">Manage shipment status and tracking information across all logistics channels.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <!-- Search & Filter Form -->
            <form method="GET" action="{{ route('business.shipping.index') }}" class="flex items-center gap-2">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari Resi, Order ID, Kurir..."
                           class="w-56 sm:w-64 pl-9 pr-4 py-2 rounded-xl bg-slate-900/80 border border-primary-400/20 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-primary-400 transition">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>

                <select name="status" onchange="this.form.submit()" class="px-3 py-2 rounded-xl bg-slate-900/80 border border-primary-400/20 text-xs text-slate-300 focus:outline-none focus:border-primary-400">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="packed" {{ request('status') === 'packed' ? 'selected' : '' }}>Packed</option>
                    <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="return" {{ request('status') === 'return' ? 'selected' : '' }}>Return</option>
                </select>
            </form>
        </div>
    </div>

    <!-- SUMMARY CARDS (KPI CARDS) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        
        <!-- Total Shipment -->
        <div class="p-5 rounded-2xl glass-card border border-primary-400/20 relative overflow-hidden group hover:border-primary-400/40 transition">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-400">Total Shipment</span>
                <div class="w-9 h-9 rounded-xl bg-primary-500/20 text-primary-400 flex items-center justify-center">
                    <x-icon name="package" class="w-5 h-5"/>
                </div>
            </div>
            <div class="mt-3 flex items-baseline justify-between">
                <span class="text-2xl font-extrabold text-white font-heading">{{ $kpis['total']['count'] }}</span>
                <span class="text-[11px] font-bold text-emerald-400 flex items-center gap-0.5">
                    ↑ {{ $kpis['total']['percentage'] }}
                </span>
            </div>
            <div class="mt-2 h-7 w-full">
                <svg class="w-full h-full text-primary-400/40 stroke-current" fill="none" viewBox="0 0 100 25">
                    <path stroke-width="2" stroke-linecap="round" d="M0 20 Q 25 5, 50 15 T 100 8"/>
                </svg>
            </div>
        </div>

        <!-- Pending Shipment -->
        <div class="p-5 rounded-2xl glass-card border border-slate-500/20 relative overflow-hidden group hover:border-slate-400/40 transition">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-400">Pending Shipment</span>
                <div class="w-9 h-9 rounded-xl bg-slate-500/20 text-slate-300 flex items-center justify-center">
                    <x-icon name="file-text" class="w-5 h-5"/>
                </div>
            </div>
            <div class="mt-3 flex items-baseline justify-between">
                <span class="text-2xl font-extrabold text-white font-heading">{{ $kpis['pending']['count'] }}</span>
                <span class="text-[11px] font-bold text-slate-400 flex items-center gap-0.5">
                    ↓ {{ $kpis['pending']['percentage'] }}
                </span>
            </div>
            <div class="mt-2 h-7 w-full">
                <svg class="w-full h-full text-slate-400/40 stroke-current" fill="none" viewBox="0 0 100 25">
                    <path stroke-width="2" stroke-linecap="round" d="M0 15 Q 25 22, 50 12 T 100 18"/>
                </svg>
            </div>
        </div>

        <!-- In Delivery -->
        <div class="p-5 rounded-2xl glass-card border border-blue-500/20 relative overflow-hidden group hover:border-blue-400/40 transition">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-400">In Delivery</span>
                <div class="w-9 h-9 rounded-xl bg-blue-500/20 text-blue-400 flex items-center justify-center">
                    <x-icon name="truck" class="w-5 h-5"/>
                </div>
            </div>
            <div class="mt-3 flex items-baseline justify-between">
                <span class="text-2xl font-extrabold text-white font-heading">{{ $kpis['in_delivery']['count'] }}</span>
                <span class="text-[11px] font-bold text-blue-400 flex items-center gap-0.5">
                    ↑ {{ $kpis['in_delivery']['percentage'] }}
                </span>
            </div>
            <div class="mt-2 h-7 w-full">
                <svg class="w-full h-full text-blue-400/40 stroke-current" fill="none" viewBox="0 0 100 25">
                    <path stroke-width="2" stroke-linecap="round" d="M0 22 Q 25 10, 50 18 T 100 5"/>
                </svg>
            </div>
        </div>

        <!-- Delivered -->
        <div class="p-5 rounded-2xl glass-card border border-emerald-500/20 relative overflow-hidden group hover:border-emerald-400/40 transition">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-400">Delivered</span>
                <div class="w-9 h-9 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center">
                    <x-icon name="badge-check" class="w-5 h-5"/>
                </div>
            </div>
            <div class="mt-3 flex items-baseline justify-between">
                <span class="text-2xl font-extrabold text-white font-heading">{{ $kpis['delivered']['count'] }}</span>
                <span class="text-[11px] font-bold text-emerald-400 flex items-center gap-0.5">
                    ↑ {{ $kpis['delivered']['percentage'] }}
                </span>
            </div>
            <div class="mt-2 h-7 w-full">
                <svg class="w-full h-full text-emerald-400/40 stroke-current" fill="none" viewBox="0 0 100 25">
                    <path stroke-width="2" stroke-linecap="round" d="M0 18 Q 25 8, 50 12 T 100 2"/>
                </svg>
            </div>
        </div>

        <!-- Return Shipment -->
        <div class="p-5 rounded-2xl glass-card border border-rose-500/20 relative overflow-hidden group hover:border-rose-400/40 transition">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-400">Return Shipment</span>
                <div class="w-9 h-9 rounded-xl bg-rose-500/20 text-rose-400 flex items-center justify-center">
                    <x-icon name="trending-down" class="w-5 h-5"/>
                </div>
            </div>
            <div class="mt-3 flex items-baseline justify-between">
                <span class="text-2xl font-extrabold text-white font-heading">{{ $kpis['return']['count'] }}</span>
                <span class="text-[11px] font-bold text-rose-400 flex items-center gap-0.5">
                    ↓ {{ $kpis['return']['percentage'] }}
                </span>
            </div>
            <div class="mt-2 h-7 w-full">
                <svg class="w-full h-full text-rose-400/40 stroke-current" fill="none" viewBox="0 0 100 25">
                    <path stroke-width="2" stroke-linecap="round" d="M0 5 Q 25 15, 50 10 T 100 22"/>
                </svg>
            </div>
        </div>

    </div>

    <!-- ORDER TABLE SECTION -->
    <div class="glass-card rounded-2xl border border-primary-400/15 overflow-hidden shadow-2xl">
        <div class="p-5 border-b border-primary-400/10 flex flex-wrap items-center justify-between gap-4">
            <h2 class="text-base font-bold text-white font-heading flex items-center gap-2">
                <span>Shipping Order Table</span>
                <span class="text-xs font-normal text-slate-400">({{ $orders->total() }} total pengiriman)</span>
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-primary-400/10 bg-slate-900/60 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                        <th class="py-3.5 px-5">Order ID</th>
                        <th class="py-3.5 px-5">Customer</th>
                        <th class="py-3.5 px-5">Courier Service</th>
                        <th class="py-3.5 px-5">Tracking Number</th>
                        <th class="py-3.5 px-5">Shipping Status</th>
                        <th class="py-3.5 px-5">Estimated Arrival</th>
                        <th class="py-3.5 px-5 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-primary-400/10 text-xs">
                    @forelse ($orders as $order)
                        @php
                            $shipStatus = strtolower($order->shipping_status ?? 'pending');
                            $courierName = $order->courier ?? 'JNE Express';
                            $resi = $order->tracking_number ?? ('GRW-RESI-'.substr(md5($order->id), 0, 8));
                            $eta = $order->estimated_arrival ?? '2-3 Hari Kerja';

                            $statusBadgeClass = match($shipStatus) {
                                'pending' => 'bg-slate-500/15 text-slate-300 border-slate-500/40',
                                'packed' => 'bg-amber-500/15 text-amber-400 border-amber-500/40',
                                'shipped', 'in_transit' => 'bg-blue-500/15 text-blue-400 border-blue-500/40',
                                'delivered' => 'bg-emerald-500/15 text-emerald-400 border-emerald-500/40',
                                'cancelled', 'return' => 'bg-rose-500/15 text-rose-400 border-rose-500/40',
                                default => 'bg-slate-500/15 text-slate-300 border-slate-500/40',
                            };

                            $statusLabel = match($shipStatus) {
                                'pending' => 'Pending',
                                'packed' => 'Packed',
                                'shipped', 'in_transit' => 'Shipped',
                                'delivered' => 'Delivered',
                                'cancelled' => 'Cancelled',
                                'return' => 'Return',
                                default => ucfirst($shipStatus),
                            };

                            $customerName = $order->customer?->name ?? 'Pembeli Marketplace';
                            $customerPhone = $order->customer?->phone ?? '0812-3456-7890';
                            $customerAddress = $order->shipping_address ?? ($order->customer?->city ? $order->customer->city.', Indonesia' : 'Jl. Sudirman No. 45, Jakarta Selatan');

                            $detailJson = json_encode([
                                'id' => $order->id,
                                'order_number' => $order->order_number,
                                'customer_name' => $customerName,
                                'customer_phone' => $customerPhone,
                                'shipping_address' => $customerAddress,
                                'courier' => $courierName,
                                'tracking_number' => $order->tracking_number ?? "",
                                'shipping_status' => $shipStatus,
                                'estimated_arrival' => $eta,
                                'current_location' => $order->current_location ?? "Gudang Penjual",
                                'items' => $order->items->map(fn($i) => $i->product_name." (x".$i->quantity.")")->join(", "),
                                'total' => number_format($order->total, 0, ",", "."),
                                'timeline' => $order->shipping_timeline ?? []
                            ], JSON_HEX_APOS | JSON_HEX_QUOT);

                            $editJson = json_encode([
                                'id' => $order->id,
                                'order_number' => $order->order_number,
                                'courier' => $order->courier ?? "JNE",
                                'tracking_number' => $order->tracking_number ?? "",
                                'shipping_status' => $shipStatus,
                                'estimated_arrival' => $eta,
                                'current_location' => $order->current_location ?? "",
                                'shipping_address' => $customerAddress,
                                'update_url' => route("business.shipping.update", $order)
                            ], JSON_HEX_APOS | JSON_HEX_QUOT);

                            $printJson = json_encode([
                                'order_number' => $order->order_number,
                                'customer_name' => $customerName,
                                'customer_phone' => $customerPhone,
                                'shipping_address' => $customerAddress,
                                'courier' => $courierName,
                                'tracking_number' => $order->tracking_number ?? "JNE-88291039",
                                'seller_name' => auth()->user()->business?->name ?? "Grownesia Store",
                                'seller_city' => auth()->user()->business?->city ?? "Sukabumi",
                                'items' => $order->items->map(fn($i) => $i->product_name." (x".$i->quantity.")")->join(", ")
                            ], JSON_HEX_APOS | JSON_HEX_QUOT);
                        @endphp
                        <tr class="hover:bg-primary-500/5 transition">
                            <td class="py-4 px-5 font-bold text-white font-mono">
                                {{ $order->order_number }}
                                <span class="block text-[10px] font-normal text-slate-500">{{ $order->ordered_at->format('d M Y, H:i') }}</span>
                            </td>
                            <td class="py-4 px-5">
                                <span class="font-semibold text-slate-200 block">{{ $customerName }}</span>
                                <span class="text-[11px] text-slate-400 truncate max-w-[180px] block">{{ $customerAddress }}</span>
                            </td>
                            <td class="py-4 px-5">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-900 border border-primary-400/20 text-slate-200 text-[11px] font-bold">
                                    <x-icon name="truck" class="w-3.5 h-3.5 text-primary-400"/>
                                    {{ $courierName }}
                                </span>
                            </td>
                            <td class="py-4 px-5 font-mono text-slate-300">
                                @if($order->tracking_number)
                                    <span class="px-2 py-0.5 rounded bg-slate-900 border border-slate-700 text-primary-300 font-bold text-[11px]">{{ $order->tracking_number }}</span>
                                @else
                                    <span class="text-slate-500 italic">Belum diinput</span>
                                @endif
                            </td>
                            <td class="py-4 px-5">
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $statusBadgeClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="py-4 px-5 text-slate-300">
                                {{ $eta }}
                            </td>
                            <td class="py-4 px-5 text-right space-x-1">
                                <!-- View Detail -->
                                <button type="button" @click="openDetailModal({{ $detailJson }})" class="px-2.5 py-1.5 rounded-lg bg-primary-600/20 hover:bg-primary-600/40 text-primary-300 text-[11px] font-semibold border border-primary-400/30 transition" title="View Detail">
                                    Detail
                                </button>

                                <!-- Input Tracking Number & Update Shipment -->
                                <button type="button" @click="openEditModal({{ $editJson }})" class="px-2.5 py-1.5 rounded-lg bg-blue-600/20 hover:bg-blue-600/40 text-blue-300 text-[11px] font-semibold border border-blue-400/30 transition" title="Input Tracking Number / Update Shipment">
                                    Resi / Update
                                </button>

                                <!-- Print Shipping Label -->
                                <button type="button" @click="openPrintLabel({{ $printJson }})" class="px-2.5 py-1.5 rounded-lg bg-amber-600/20 hover:bg-amber-600/40 text-amber-300 text-[11px] font-semibold border border-amber-400/30 transition" title="Print Shipping Label">
                                    Print Label
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400 space-y-3">
                                <div class="w-14 h-14 rounded-2xl bg-slate-900 border border-primary-400/20 flex items-center justify-center mx-auto text-slate-500">
                                    <x-icon name="package" class="w-7 h-7"/>
                                </div>
                                <p class="text-sm font-semibold text-slate-300">Belum ada pengiriman ditemukan</p>
                                <p class="text-xs text-slate-500">Silakan ubah kata kunci atau status pencarian Anda.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($orders->hasPages())
            <div class="p-4 border-t border-primary-400/10">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

    <!-- SHIPMENT DETAIL MODAL -->
    <div x-show="showDetailModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/75 backdrop-blur-md">
        <div @click.away="showDetailModal = false" class="w-full max-w-2xl bg-slate-900 border border-primary-400/30 rounded-2xl p-6 shadow-2xl space-y-5 overflow-y-auto max-h-[90vh]">
            
            <div class="flex items-center justify-between border-b border-primary-400/15 pb-4">
                <div>
                    <span class="text-[10px] font-bold text-primary-400 uppercase tracking-widest">Shipment Detail Modal</span>
                    <h3 class="text-lg font-bold text-white font-heading" x-text="'Pesanan ' + selectedDetail.order_number"></h3>
                </div>
                <button @click="showDetailModal = false" class="text-slate-400 hover:text-white p-1">✕</button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <div class="p-3.5 rounded-xl bg-slate-950/80 border border-primary-400/15 space-y-1">
                    <span class="text-[10px] text-slate-400 uppercase font-bold">Informasi Pelanggan</span>
                    <p class="font-bold text-white" x-text="selectedDetail.customer_name"></p>
                    <p class="text-slate-400" x-text="selectedDetail.customer_phone"></p>
                </div>

                <div class="p-3.5 rounded-xl bg-slate-950/80 border border-primary-400/15 space-y-1">
                    <span class="text-[10px] text-slate-400 uppercase font-bold">Alamat Pengiriman</span>
                    <p class="text-slate-200" x-text="selectedDetail.shipping_address"></p>
                </div>

                <div class="p-3.5 rounded-xl bg-slate-950/80 border border-primary-400/15 space-y-1">
                    <span class="text-[10px] text-slate-400 uppercase font-bold">Ekspedisi & Resi</span>
                    <p class="font-bold text-primary-300" x-text="selectedDetail.courier + ' — ' + (selectedDetail.tracking_number || 'Belum ada resi')"></p>
                </div>

                <div class="p-3.5 rounded-xl bg-slate-950/80 border border-primary-400/15 space-y-1">
                    <span class="text-[10px] text-slate-400 uppercase font-bold">Estimasi Tiba & Status</span>
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-white" x-text="selectedDetail.estimated_arrival"></span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-primary-500/20 text-primary-300 uppercase" x-text="selectedDetail.shipping_status"></span>
                    </div>
                </div>
            </div>

            <!-- TIMELINE COMPONENT (BEAUTIFUL VERTICAL TIMELINE) -->
            <div class="space-y-3 border-t border-primary-400/15 pt-4">
                <h4 class="text-xs font-bold text-primary-300 uppercase tracking-wider flex items-center gap-2">
                    <x-icon name="truck" class="w-4 h-4"/>
                    <span>Shipping Timeline Progress</span>
                </h4>

                <div class="relative pl-6 space-y-5 border-l-2 border-primary-500/30 ml-2">
                    <!-- Default Standard Steps -->
                    <template x-for="(step, idx) in defaultTimelineSteps" :key="'step-'+idx">
                        <div class="relative">
                            <div class="absolute -left-[31px] top-0.5 w-4 h-4 rounded-full border-2 flex items-center justify-center"
                                 :class="isStepActive(step.status) ? 'bg-primary-500 border-primary-300 shadow-md shadow-primary-500/50' : 'bg-slate-900 border-slate-600'">
                                <div class="w-1.5 h-1.5 rounded-full" :class="isStepActive(step.status) ? 'bg-white' : 'bg-slate-600'"></div>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h5 class="text-xs font-bold" :class="isStepActive(step.status) ? 'text-white' : 'text-slate-500'" x-text="step.title"></h5>
                                    <span class="text-[10px] text-slate-400" x-text="step.timestamp"></span>
                                </div>
                                <p class="text-[11px] text-slate-400 mt-0.5" x-text="step.desc"></p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <div class="flex justify-end pt-3 border-t border-primary-400/15">
                <button type="button" @click="showDetailModal = false" class="px-5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs">
                    Tutup
                </button>
            </div>

        </div>
    </div>

    <!-- INPUT TRACKING NUMBER & UPDATE SHIPMENT MODAL -->
    <div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/75 backdrop-blur-md">
        <div @click.away="showEditModal = false" class="w-full max-w-lg bg-slate-900 border border-primary-400/30 rounded-2xl p-6 shadow-2xl space-y-5">
            
            <div class="flex items-center justify-between border-b border-primary-400/15 pb-4">
                <div>
                    <h3 class="text-base font-bold text-white font-heading">Input Resi & Update Pengiriman</h3>
                    <p class="text-xs text-slate-400" x-text="editForm.order_number"></p>
                </div>
                <button @click="showEditModal = false" class="text-slate-400 hover:text-white p-1">✕</button>
            </div>

            <form :action="editForm.update_url" method="POST" class="space-y-4 text-xs">
                @csrf
                @method('PATCH')

                <div>
                    <label class="block font-semibold text-slate-300 mb-1">Kurir Ekspedisi</label>
                    <select name="courier" x-model="editForm.courier" class="w-full p-2.5 rounded-xl bg-slate-950 border border-primary-400/20 text-white focus:outline-none focus:border-primary-400">
                        @foreach ($couriers as $c)
                            <option value="{{ $c }}">{{ $c }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-semibold text-slate-300 mb-1">Nomor Resi Tracking</label>
                    <input type="text" name="tracking_number" x-model="editForm.tracking_number" placeholder="Contoh: JNE8291039120" class="w-full p-2.5 rounded-xl bg-slate-950 border border-primary-400/20 text-white font-mono focus:outline-none focus:border-primary-400">
                </div>

                <div>
                    <label class="block font-semibold text-slate-300 mb-1">Status Pengiriman (Shipping Status)</label>
                    <select name="shipping_status" x-model="editForm.shipping_status" class="w-full p-2.5 rounded-xl bg-slate-950 border border-primary-400/20 text-white focus:outline-none focus:border-primary-400">
                        <option value="pending">Pending (Menunggu Diproses)</option>
                        <option value="packed">Packed (Dikemas Penjual)</option>
                        <option value="shipped">Shipped (Dalam Pengiriman / In Transit)</option>
                        <option value="delivered">Delivered (Telah Diterima Pembeli)</option>
                        <option value="return">Return (Dikembalikan)</option>
                        <option value="cancelled">Cancelled (Dibatalkan)</option>
                    </select>
                </div>

                <div>
                    <label class="block font-semibold text-slate-300 mb-1">Estimasi Tiba (Estimated Arrival)</label>
                    <input type="text" name="estimated_arrival" x-model="editForm.estimated_arrival" placeholder="Contoh: 28 - 29 Juli 2026" class="w-full p-2.5 rounded-xl bg-slate-950 border border-primary-400/20 text-white focus:outline-none focus:border-primary-400">
                </div>

                <div>
                    <label class="block font-semibold text-slate-300 mb-1">Lokasi Terkini (Current Location)</label>
                    <input type="text" name="current_location" x-model="editForm.current_location" placeholder="Contoh: Gateway Hub Jakarta Barat" class="w-full p-2.5 rounded-xl bg-slate-950 border border-primary-400/20 text-white focus:outline-none focus:border-primary-400">
                </div>

                <div class="flex justify-end gap-3 pt-3 border-t border-primary-400/15">
                    <button type="button" @click="showEditModal = false" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-primary-600 hover:bg-primary-500 text-white font-bold shadow-lg shadow-primary-600/30">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- PRINT SHIPPING LABEL MODAL -->
    <div x-show="showPrintModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md">
        <div @click.away="showPrintModal = false" class="w-full max-w-lg bg-white text-slate-900 rounded-2xl p-6 shadow-2xl space-y-4">
            
            <div class="flex items-center justify-between border-b pb-3">
                <span class="text-xs font-bold text-purple-700 uppercase tracking-widest">Label Pengiriman Resmi</span>
                <button @click="showPrintModal = false" class="text-slate-400 hover:text-slate-700">✕</button>
            </div>

            <!-- Label Document Area -->
            <div id="printArea" class="border-2 border-slate-900 p-4 rounded-xl space-y-4 bg-slate-50 font-sans text-xs">
                <div class="flex items-center justify-between border-b-2 border-slate-900 pb-3">
                    <div>
                        <h2 class="text-lg font-black tracking-wider text-slate-900 uppercase">GROWNESIA EXPRESS</h2>
                        <p class="text-[10px] text-slate-600 font-semibold" x-text="'Kurir Mitra: ' + printData.courier"></p>
                    </div>
                    <div class="text-right">
                        <span class="inline-block px-3 py-1 bg-slate-900 text-white font-mono font-bold text-sm rounded" x-text="printData.courier"></span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 border-b-2 border-slate-900 pb-3">
                    <div>
                        <span class="text-[9px] font-bold text-slate-500 uppercase block">PENGIRIM (SELLER):</span>
                        <p class="font-bold text-slate-900" x-text="printData.seller_name"></p>
                        <p class="text-[10px] text-slate-600" x-text="printData.seller_city + ', Indonesia'"></p>
                    </div>
                    <div>
                        <span class="text-[9px] font-bold text-slate-500 uppercase block">PENERIMA (CUSTOMER):</span>
                        <p class="font-bold text-slate-900" x-text="printData.customer_name"></p>
                        <p class="text-[10px] text-slate-600" x-text="printData.customer_phone"></p>
                        <p class="text-[10px] text-slate-700 mt-0.5 leading-tight font-medium" x-text="printData.shipping_address"></p>
                    </div>
                </div>

                <div class="space-y-1">
                    <span class="text-[9px] font-bold text-slate-500 uppercase block">NOMOR RESI & BARCODE:</span>
                    <div class="p-3 bg-white border border-slate-300 rounded text-center space-y-1">
                        <div class="font-mono text-base font-black tracking-widest text-slate-900" x-text="printData.tracking_number"></div>
                        <!-- Barcode Simulation SVG -->
                        <div class="flex justify-center h-8 gap-0.5">
                            <template x-for="i in 35" :key="i">
                                <div class="bg-slate-900 h-full" :style="'width:' + (i % 3 == 0 ? '3px' : '1px')"></div>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="text-[10px] text-slate-600 border-t pt-2">
                    <strong>Isi Paket:</strong> <span x-text="printData.items"></span>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button" @click="showPrintModal = false" class="px-4 py-2 rounded-xl bg-slate-200 text-slate-800 font-bold text-xs">
                    Tutup
                </button>
                <button type="button" @click="window.print()" class="px-5 py-2 rounded-xl bg-purple-700 hover:bg-purple-800 text-white font-bold text-xs shadow-lg">
                    Cetak Label Now
                </button>
            </div>

        </div>
    </div>

</div>

<script>
    function shippingManager() {
        return {
            showDetailModal: false,
            showEditModal: false,
            showPrintModal: false,

            selectedDetail: {
                id: null,
                order_number: '',
                customer_name: '',
                customer_phone: '',
                shipping_address: '',
                courier: '',
                tracking_number: '',
                shipping_status: '',
                estimated_arrival: '',
                current_location: '',
                items: '',
                total: '',
                timeline: []
            },

            editForm: {
                id: null,
                order_number: '',
                courier: 'JNE',
                tracking_number: '',
                shipping_status: 'pending',
                estimated_arrival: '',
                current_location: '',
                shipping_address: '',
                update_url: ''
            },

            printData: {
                order_number: '',
                customer_name: '',
                customer_phone: '',
                shipping_address: '',
                courier: '',
                tracking_number: '',
                seller_name: '',
                seller_city: '',
                items: ''
            },

            defaultTimelineSteps: [
                { status: 'packed', title: 'Order Packed', timestamp: '27 Jul 2026, 09:00', desc: 'Barang telah dikemas rapi dan dilapisi bubble wrap oleh seller.' },
                { status: 'picked_up', title: 'Courier Picked Up', timestamp: '27 Jul 2026, 11:30', desc: 'Kurir telah mengambil paket di lokasi seller.' },
                { status: 'shipped', title: 'Package In Transit', timestamp: '27 Jul 2026, 14:15', desc: 'Paket sedang dalam perjalanan menuju kota tujuan.' },
                { status: 'hub', title: 'Arrived at Local Hub', timestamp: '28 Jul 2026, 06:45', desc: 'Paket telah tiba di pusat sortir hub tujuan.' },
                { status: 'out_for_delivery', title: 'Out For Delivery', timestamp: '28 Jul 2026, 08:30', desc: 'Kurir sedang membawa paket ke alamat tujuan.' },
                { status: 'delivered', title: 'Delivered', timestamp: '28 Jul 2026, 12:10', desc: 'Paket telah diterima dengan sukses.' }
            ],

            openDetailModal(data) {
                this.selectedDetail = data;
                this.showDetailModal = true;
            },

            openEditModal(data) {
                this.editForm = data;
                this.showEditModal = true;
            },

            openPrintLabel(data) {
                this.printData = data;
                this.showPrintModal = true;
            },

            isStepActive(stepStatus) {
                const orderStatus = (this.selectedDetail.shipping_status || 'pending').toLowerCase();
                const levels = { 'pending': 0, 'packed': 1, 'picked_up': 2, 'shipped': 3, 'in_transit': 3, 'hub': 4, 'out_for_delivery': 5, 'delivered': 6 };
                const currentLevel = levels[orderStatus] || 1;
                const stepLevel = levels[stepStatus] || 0;
                return currentLevel >= stepLevel;
            }
        }
    }
</script>
@endsection
