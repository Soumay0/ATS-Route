@props([
    'title',
    'value',
    'icon' => 'fa-satellite-dish',
    'accent' => 'text-accent-primary',
    'ring' => 'bg-accent-primary/10',
    'trend' => null,
])

<div {{ $attributes->merge(['class' => 'glass-panel relative overflow-hidden']) }}>
    <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-accent-primary/70 to-transparent"></div>
    <div class="flex items-start justify-between gap-4">
        <div>
            <p class="text-sm uppercase tracking-[0.3em] text-text-tertiary">{{ $title }}</p>
            <div class="mt-3 text-3xl font-semibold text-white">{{ $value }}</div>
            @if($trend)
                <p class="mt-2 text-sm text-text-secondary">{{ $trend }}</p>
            @endif
        </div>

        <div class="rounded-2xl p-4 {{ $ring }} {{ $accent }} shadow-[0_0_24px_rgba(0,194,255,0.12)]">
            <i class="fa-solid {{ $icon }} text-xl"></i>
        </div>
    </div>
</div>