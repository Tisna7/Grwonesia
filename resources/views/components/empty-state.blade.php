@props(['icon' => '📭', 'title' => 'Belum ada data', 'description' => null])

<div class="glass-card p-10 flex flex-col items-center text-center">
    <span class="text-4xl">{{ $icon }}</span>
    <p class="mt-3 text-sm font-semibold text-white">{{ $title }}</p>
    @if ($description)
        <p class="mt-1 text-xs text-slate-400 max-w-sm">{{ $description }}</p>
    @endif
    <div class="mt-4">{{ $slot }}</div>
</div>
