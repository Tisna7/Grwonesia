<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Grownesia Business</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen antialiased" x-data="{ sidebarOpen: false }">
    <div class="ambient-glow"></div>

    <!-- Sidebar -->
    <aside class="fixed inset-y-0 left-0 z-40 w-64 glass-card !rounded-none border-r border-primary-400/15 flex flex-col transition-transform duration-300 lg:translate-x-0"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
        <div class="px-5 py-6 border-b border-primary-400/10">
            <a href="{{ route('business.dashboard') }}" class="flex items-center gap-3">
                <span class="w-9 h-9 rounded-xl ai-gradient flex items-center justify-center text-white font-bold text-lg shadow-lg shadow-primary-600/40" style="font-family: 'Space Grotesk'">G</span>
                <span>
                    <span class="block text-lg font-bold tracking-tight text-white leading-none" style="font-family: 'Space Grotesk'">Grownesia</span>
                    <span class="block mt-1 text-[10px] font-semibold uppercase tracking-[0.18em] text-primary-300/80">Business Studio</span>
                </span>
            </a>
        </div>
        <nav class="flex-1 px-3 py-3 overflow-y-auto">
            <p class="section-label">Operasional</p>
            <div class="space-y-1">
                <a href="{{ route('business.dashboard') }}" class="sidebar-link {{ request()->routeIs('business.dashboard') ? 'active' : '' }}">
                    <x-icon name="dashboard"/> Dashboard
                </a>
                <a href="{{ route('business.products.index') }}" class="sidebar-link {{ request()->routeIs('business.products.*') ? 'active' : '' }}">
                    <x-icon name="package"/> Produk
                </a>
                <a href="{{ route('business.orders.index') }}" class="sidebar-link {{ request()->routeIs('business.orders.*') ? 'active' : '' }}">
                    <x-icon name="file-text"/> Pesanan
                </a>
            </div>

            <p class="section-label">AI Tools</p>
            <div class="space-y-1">
                <a href="{{ route('business.inventory') }}" class="sidebar-link {{ request()->routeIs('business.inventory') ? 'active' : '' }}">
                    <x-icon name="trending-up"/> Inventori AI
                </a>
                <a href="{{ route('business.coach') }}" class="sidebar-link {{ request()->routeIs('business.coach*') ? 'active' : '' }}">
                    <x-icon name="bot"/> Business Coach
                </a>
                <a href="{{ route('business.marketing') }}" class="sidebar-link {{ request()->routeIs('business.marketing*') ? 'active' : '' }}">
                    <x-icon name="sparkles"/> Marketing Center
                </a>
                <a href="{{ route('business.finance') }}" class="sidebar-link {{ request()->routeIs('business.finance') ? 'active' : '' }}">
                    <x-icon name="wallet"/> Keuangan AI
                </a>
            </div>

            <p class="section-label">Koneksi</p>
            <div class="space-y-1">
                <a href="{{ route('business.whatsapp') }}" class="sidebar-link {{ request()->routeIs('business.whatsapp*') ? 'active' : '' }}">
                    <x-icon name="message-circle"/> WhatsApp
                </a>
                <a href="{{ route('business.instagram') }}" class="sidebar-link {{ request()->routeIs('business.instagram*') ? 'active' : '' }}">
                    <x-icon name="instagram"/> Instagram
                </a>
            </div>
        </nav>
        <div class="px-4 py-4 border-t border-primary-400/10">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 shrink-0 rounded-full ai-gradient flex items-center justify-center text-white font-bold text-sm">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-bold text-white truncate">{{ auth()->user()->business?->name }}</p>
                    <p class="text-[10px] text-slate-500 truncate">{{ auth()->user()->email }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Keluar" class="p-2 rounded-lg text-slate-500 hover:text-rose-400 hover:bg-rose-500/10 transition-colors">
                        <x-icon name="log-out" class="w-4 h-4"/>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Overlay mobile -->
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" class="fixed inset-0 z-30 bg-black/60 lg:hidden"></div>

    <!-- Main -->
    <div class="lg:pl-64 flex flex-col min-h-screen">
        <header class="sticky top-0 z-20 glass-card !rounded-none border-b border-primary-400/10 px-6 py-4 flex items-center justify-between backdrop-blur-xl">
            <div class="flex items-center gap-3">
                <button class="lg:hidden text-slate-300" @click="sidebarOpen = true">☰</button>
                <div>
                    <h1 class="text-lg font-bold text-white leading-tight">@yield('title', 'Dashboard')</h1>
                    <p class="text-[11px] text-slate-500">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                @hasSection('header-extra')
                    @yield('header-extra')
                @endif
                @php $biz = auth()->user()->business; @endphp
                @if ($biz?->isVerified())
                    <span class="hidden sm:inline-flex badge-pill bg-emerald-500/12 text-emerald-400 border-emerald-500/35" title="Diverifikasi Grownesia {{ $biz->verified_at?->format('d M Y') }}">
                        <x-icon name="badge-check" class="w-3.5 h-3.5"/> Terverifikasi
                    </span>
                @elseif ($biz?->verification_status === 'rejected')
                    <span class="hidden sm:inline-flex badge-pill bg-rose-500/12 text-rose-400 border-rose-500/35" title="{{ $biz->verification_note }}">
                        Verifikasi Ditolak
                    </span>
                @else
                    <span class="hidden sm:inline-flex badge-pill bg-amber-500/12 text-amber-400 border-amber-500/35">
                        Menunggu Verifikasi
                    </span>
                @endif
                <span class="hidden md:inline-flex badge-pill bg-primary-600/10 text-primary-300 border-primary-500/25">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    {{ auth()->user()->role->label() }}
                </span>
            </div>
        </header>

        <main class="flex-1 px-6 py-6">
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                     class="mb-4 glass-card border-emerald-500/40 px-4 py-3 text-sm text-emerald-400 flex items-center justify-between">
                    <span>{{ session('success') }}</span>
                    <button @click="show = false" class="text-slate-500 hover:text-white">✕</button>
                </div>
            @endif
            @if (session('error'))
                <div x-data="{ show: true }" x-show="show"
                     class="mb-4 glass-card border-rose-500/40 px-4 py-3 text-sm text-rose-400 flex items-center justify-between">
                    <span>{{ session('error') }}</span>
                    <button @click="show = false" class="text-slate-500 hover:text-white">✕</button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
