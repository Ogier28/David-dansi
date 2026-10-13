@props([
    'as' => 'div',
    'raised' => false,
    'radius' => 'panel',
    'edge' => true,
])

@php
    $radiusClass = match ($radius) {
        'tile' => 'rounded-tile',
        'field' => 'rounded-field',
        'pill' => 'rounded-full',
        default => 'rounded-panel',
    };
@endphp

<{{ $as }} {{ $attributes->class([
    'relative',
    $raised ? 'glass-raised' : 'glass',
    'glass-edge' => $edge,
    $radiusClass,
]) }}>
    {{ $slot }}
</{{ $as }}>
