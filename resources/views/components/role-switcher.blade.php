<div x-data="{ open: false }" class="relative inline-block text-left">
    <button @click="open = !open" @click.away="open = false" 
            class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-purple-950/80 hover:bg-purple-900 border border-primary-400/20 text-xs font-semibold text-white transition backdrop-blur-md shadow-md">
        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
        <span class="text-slate-300">Ekosistem:</span>
        <span class="text-primary-300 font-bold">{{ auth()->check() ? auth()->user()->role->label() : 'Tamu' }}</span>
        <svg class="w-3.5 h-3.5 text-slate-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    <div x-show="open" 
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute right-0 mt-2 w-64 glass-card border border-primary-400/25 rounded-2xl shadow-2xl p-2 z-50 divide-y divide-primary-400/10"
         style="display: none;">
        
        <div class="px-3 py-2 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
            Switch Demo Account
        </div>

        <div class="py-1 space-y-1">
            <!-- Consumer / User -->
            <a href="{{ route('switch-role', 'user') }}" 
               class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-medium transition {{ auth()->check() && auth()->user()->role->value === 'user' ? 'bg-primary-600/30 text-white border border-primary-500/40 font-bold' : 'text-slate-300 hover:bg-primary-950/50 hover:text-white' }}">
                <div class="flex items-center gap-2.5">
                    <span class="w-2 h-2 rounded-full bg-blue-400"></span>
                    <span>Pembeli (Consumer)</span>
                </div>
                @if(auth()->check() && auth()->user()->role->value === 'user')
                    <span class="text-[10px] bg-emerald-500/20 text-emerald-400 px-1.5 py-0.5 rounded font-bold">Aktif</span>
                @endif
            </a>

            <!-- Business UMKM -->
            <a href="{{ route('switch-role', 'business') }}" 
               class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-medium transition {{ auth()->check() && auth()->user()->role->value === 'business' ? 'bg-primary-600/30 text-white border border-primary-500/40 font-bold' : 'text-slate-300 hover:bg-primary-950/50 hover:text-white' }}">
                <div class="flex items-center gap-2.5">
                    <span class="w-2 h-2 rounded-full bg-purple-400"></span>
                    <span>UMKM Studio (Business)</span>
                </div>
                @if(auth()->check() && auth()->user()->role->value === 'business')
                    <span class="text-[10px] bg-emerald-500/20 text-emerald-400 px-1.5 py-0.5 rounded font-bold">Aktif</span>
                @endif
            </a>

            <!-- Government -->
            <a href="{{ route('switch-role', 'government') }}" 
               class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-medium transition {{ auth()->check() && auth()->user()->role->value === 'government' ? 'bg-primary-600/30 text-white border border-primary-500/40 font-bold' : 'text-slate-300 hover:bg-primary-950/50 hover:text-white' }}">
                <div class="flex items-center gap-2.5">
                    <span class="w-2 h-2 rounded-full bg-indigo-400"></span>
                    <span>Dinas UMKM (Government)</span>
                </div>
                @if(auth()->check() && auth()->user()->role->value === 'government')
                    <span class="text-[10px] bg-emerald-500/20 text-emerald-400 px-1.5 py-0.5 rounded font-bold">Aktif</span>
                @endif
            </a>

            <!-- Admin -->
            <a href="{{ route('switch-role', 'admin') }}" 
               class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-medium transition {{ auth()->check() && auth()->user()->role->value === 'admin' ? 'bg-primary-600/30 text-white border border-primary-500/40 font-bold' : 'text-slate-300 hover:bg-primary-950/50 hover:text-white' }}">
                <div class="flex items-center gap-2.5">
                    <span class="w-2 h-2 rounded-full bg-pink-400"></span>
                    <span>Super Admin</span>
                </div>
                @if(auth()->check() && auth()->user()->role->value === 'admin')
                    <span class="text-[10px] bg-emerald-500/20 text-emerald-400 px-1.5 py-0.5 rounded font-bold">Aktif</span>
                @endif
            </a>
        </div>
    </div>
</div>
