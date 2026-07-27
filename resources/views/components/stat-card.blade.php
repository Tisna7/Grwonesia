@props(['label', 'value', 'delta' => null, 'icon' => null])

@php
    $knownIcons = ['dashboard', 'package', 'file-text', 'trending-up', 'trending-down', 'bot', 'sparkles', 'message-circle', 'wallet', 'globe', 'clipboard-list', 'sliders', 'monitor', 'badge-check', 'cpu', 'users', 'store', 'zap', 'activity', 'percent', 'target', 'flame', 'map', 'trophy', 'shield'];
@endphp

<div class="glass-card glass-card-hover p-5">
    <div class="flex items-start justify-between gap-3">
        <div class="min-w-0">
            <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-500">{{ $label }}</p>
            <p class="mt-2 text-2xl font-extrabold tracking-tight text-white truncate" style="font-family: 'Space Grotesk'">{{ $value }}</p>
            @if (! is_null($delta))
                <p class="mt-1.5 inline-flex items-center gap-1 text-xs font-semibold {{ $delta >= 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                    <x-icon :name="$delta >= 0 ? 'trending-up' : 'trending-down'" class="w-3.5 h-3.5"/>
                    {{ number_format(abs($delta), 1, ',', '.') }}% vs periode lalu
                </p>
            @endif
        </div>
        @if ($icon)
            <div class="icon-tile shrink-0">
                @if (in_array($icon, $knownIcons, true))
                    <x-icon :name="$icon"/>
                @else
                    <span class="text-lg">{{ $icon }}</span>
                @endif
            </div>
        @endif
    </div>
</div>
