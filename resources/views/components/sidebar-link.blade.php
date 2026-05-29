@props([
    'href',
    'icon' => 'fa-circle',
    'active' => false,
])

<a href="{{ $href }}"
   {{ $attributes->merge(['class' => 'group flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition duration-200']) }}
   @class([
        'bg-accent-primary/10 text-white shadow-[0_0_18px_rgba(0,194,255,0.12)] border border-accent-primary/20' => $active,
        'text-text-secondary hover:bg-white/5 hover:text-white' => ! $active,
   ])>
    <i class="fa-solid {{ $icon }} w-5 text-center text-accent-primary"></i>
    <span>{{ $slot }}</span>
</a>