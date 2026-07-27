@props(['score', 'label'])

@php
    $circumference = 2 * M_PI * 54;
    $offset = $circumference * (1 - $score / 100);
    $color = $score >= 70 ? '#10B981' : ($score >= 40 ? '#F59E0B' : '#F43F5E');
@endphp

<div class="flex flex-col items-center">
    <div class="relative w-36 h-36">
        <svg class="w-36 h-36 -rotate-90" viewBox="0 0 120 120">
            <circle cx="60" cy="60" r="54" fill="none" stroke="rgba(124,58,237,0.15)" stroke-width="10"/>
            <circle cx="60" cy="60" r="54" fill="none" stroke="url(#gaugeGradient)" stroke-width="10"
                    stroke-linecap="round"
                    stroke-dasharray="{{ $circumference }}"
                    stroke-dashoffset="{{ $offset }}"/>
            <defs>
                <linearGradient id="gaugeGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#7C3AED"/>
                    <stop offset="100%" stop-color="{{ $color }}"/>
                </linearGradient>
            </defs>
        </svg>
        <div class="absolute inset-0 flex flex-col items-center justify-center">
            <span class="text-3xl font-extrabold text-white">{{ $score }}</span>
            <span class="text-[10px] text-slate-400">/ 100</span>
        </div>
    </div>
    <span class="mt-2 badge-pill" style="background: {{ $color }}1f; color: {{ $color }}; border-color: {{ $color }}66;">
        {{ $label }}
    </span>
</div>
