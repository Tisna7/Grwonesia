@props(['title' => 'AI Insight'])

<div class="glass-card p-5 relative overflow-hidden" style="border-color: rgba(236, 72, 153, 0.25);">
    <div class="absolute -top-10 -right-10 w-32 h-32 rounded-full bg-magenta/10 blur-2xl"></div>
    <div class="flex items-start gap-3">
        <div class="w-9 h-9 shrink-0 rounded-xl ai-gradient flex items-center justify-center text-white ai-active-pulse">✦</div>
        <div class="min-w-0">
            <p class="text-sm font-bold ai-gradient-text">{{ $title }}</p>
            <div class="mt-1 text-sm text-slate-300 leading-relaxed">{{ $slot }}</div>
        </div>
    </div>
</div>
