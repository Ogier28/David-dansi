@props([
    'value',
    'decimals' => 0,
    'prefix' => '',
    'suffix' => '',
    'label',
    'note' => null,
])

@php
    // Valeur finale écrite dans le HTML : lisible sans JavaScript et par les
    // lecteurs d'écran. Le compteur ne fait que l'animer jusque-là.
    $formatted = number_format($value, $decimals, ',', ' ');

    if (app()->getLocale() !== 'fr') {
        $formatted = number_format($value, $decimals, '.', ',');
    }
@endphp

<div {{ $attributes->class('flex flex-col gap-1') }}>
    <p class="flex items-baseline gap-0.5 text-[clamp(1.9rem,3vw,2.6rem)] font-bold leading-none tracking-tight text-ink">
        @if ($prefix)
            <span class="text-gold">{{ $prefix }}</span>
        @endif
        <span
            class="tabular"
            data-metric
            data-count-to="{{ $value }}"
            data-count-decimals="{{ $decimals }}"
        >{{ $formatted }}</span>
        @if ($suffix)
            <span class="text-[0.6em] font-semibold text-gold">{{ $suffix }}</span>
        @endif
    </p>

    <p class="text-sm font-medium text-ink">{{ $label }}</p>

    @if ($note)
        <p class="text-[0.8125rem] leading-snug text-ink-faint">{{ $note }}</p>
    @endif
</div>
