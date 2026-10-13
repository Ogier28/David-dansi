@props(['tone' => 'neutral'])

@php
    $toneClass = match ($tone) {
        'gold' => 'border-gold/35 bg-gold/10 text-gold-soft',
        default => 'border-white/10 bg-white/5 text-ink-muted',
    };
@endphp

<span {{ $attributes->class([
    'inline-flex items-center gap-1.5 rounded-full border px-3 py-1 text-[0.8125rem] font-medium',
    $toneClass,
]) }}>
    {{ $slot }}
</span>
