<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Grownesia Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen antialiased" x-data="{ sidebarOpen: false }">
    <div class="ambient-glow"></div>

    <aside class="fixed inset-y-0 left-0 z-40 w-64 glass-card !rounded-none border-r border-primary-400/15 flex flex-col transition-transform duration-300 lg:translate-x-0"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
        <div class="px-5 py-6 border-b border-primary-400/10">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                <span class="w-9 h-9 rounded-xl bg-gradient-to-br from-magenta to-primary-700 flex items-center justify-center text-white font-bold text-lg shadow-lg" style="font-family: 'Space Grotesk'; box-shadow: 0 8px 20px rgba(236,72,153,0.35)">G</span>
                <span>
                    <span class="block text-lg font-bold tracking-tight text-white leading-none" style="font-family: 'Space Grotesk'">Grownesia</span>
                    <span class="block mt-1 text-[10px] font-semibold uppercase tracking-[0.18em]" style="color: rgba(244,114,182,0.8)">Master Control</span>
                </span>
            </a>
        </div>
        <nav class="flex-1 px-3 py-3 overflow-y-auto">
            <p class="section-label">Platform</p>
            <div class="space-y-1">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <x-icon name="monitor"/> Master Dashboard
                </a>
                <a href="{{ route('admin.businesses') }}" class="sidebar-link {{ request()->routeIs('admin.businesses*') ? 'active' : '' }}">
                    <x-icon name="badge-check"/> Verifikasi Bisnis
                </a>
                <a href="{{ route('admin.ai-center') }}" class="sidebar-link {{ request()->routeIs('admin.ai-center') ? 'active' : '' }}">
                    <x-icon name="cpu"/> AI Management
                </a>
            </div>

            <p class="section-label">Ekosistem</p>
            <div class="space-y-1">
                <a href="{{ route('user.dashboard') }}" target="_blank" class="sidebar-link">
                    <x-icon name="shopping-bag"/> Marketplace (Pembeli)
                </a>
            </div>
        </nav>
        <div class="px-4 py-4 border-t border-primary-400/10">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 shrink-0 rounded-full ai-gradient flex items-center justify-center text-white">
                    <x-icon name="shield" class="w-4 h-4"/>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-bold text-white truncate">{{ auth()->user()->name }}</p>
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

    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" class="fixed inset-0 z-30 bg-black/60 lg:hidden"></div>

    <div class="lg:pl-64 flex flex-col min-h-screen">
        <header class="sticky top-0 z-20 glass-card !rounded-none border-b border-primary-400/10 px-6 py-4 flex items-center justify-between backdrop-blur-xl">
            <div class="flex items-center gap-3">
                <button class="lg:hidden text-slate-300" @click="sidebarOpen = true">☰</button>
                <div>
                    <h1 class="text-lg font-bold text-white leading-tight">@yield('title', 'Dashboard')</h1>
                    <p class="text-[11px] text-slate-500">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
            </div>
            <x-role-switcher />
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
