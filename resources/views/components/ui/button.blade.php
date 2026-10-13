@props([
    'href' => null,
    'variant' => 'gold',
    'icon' => null,
    'iconPosition' => 'after',
    'type' => 'submit',
])

@php
    $variantClass = match ($variant) {
        'glass' => 'btn-glass',
        'ghost' => 'text-ink-muted hover:text-gold',
        default => 'btn-gold',
    };

    $classes = ['btn', $variantClass];
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->class($classes) }}>
        @if ($icon && $iconPosition === 'before')
            <x-icon :name="$icon" :size="17" />
        @endif
        {{ $slot }}
        @if ($icon && $iconPosition === 'after')
            <x-icon :name="$icon" :size="17" />
        @endif
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->class($classes) }}>
        @if ($icon && $iconPosition === 'before')
            <x-icon :name="$icon" :size="17" />
        @endif
        {{ $slot }}
        @if ($icon && $iconPosition === 'after')
            <x-icon :name="$icon" :size="17" />
        @endif
    </button>
@endif
