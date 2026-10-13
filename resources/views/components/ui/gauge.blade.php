@props([
    'level',
    'max' => 5,
    'label' => null,
])

{{-- Jauge segmentée de hauteur croissante : lecture d'instrument plutôt que barre de progression. --}}
<div class="flex items-end gap-[3px]" role="img" @if ($label) aria-label="{{ $label }}" @endif>
    @for ($step = 1; $step <= $max; $step++)
        @php
            $height = match ($step) {
                1 => 'h-1.5',
                2 => 'h-2.5',
                3 => 'h-3.5',
                4 => 'h-4.5',
                default => 'h-5',
            };
        @endphp
        <span class="w-[3px] rounded-full {{ $height }} {{ $step <= $level ? 'bg-gold' : 'bg-white/12' }}"></span>
    @endfor
</div>
