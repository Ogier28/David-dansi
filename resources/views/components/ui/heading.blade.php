@props([
    'eyebrow' => null,
    'title' => null,
    'lead' => null,
    'level' => 'h2',
    'leadClass' => 'max-w-2xl',
])

<header {{ $attributes }}>
    @if ($eyebrow)
        {{-- Repère de section traité comme une graduation d'axe, pas comme une étiquette décorative. --}}
        <div class="mb-5 flex items-center gap-3">
            <span class="h-px w-7 shrink-0 bg-gold"></span>
            <span class="text-sm font-medium text-ink-muted">{{ $eyebrow }}</span>
            <span class="h-px flex-1 bg-white/8"></span>
        </div>
    @endif

    @if ($title)
        <{{ $level }} class="max-w-3xl text-[clamp(1.9rem,3.4vw,2.9rem)] font-semibold text-balance-tight text-ink">
            {{ $title }}
        </{{ $level }}>
    @endif

    @if ($lead)
        <p class="mt-5 text-lg leading-relaxed text-ink-muted {{ $leadClass }}">{{ $lead }}</p>
    @endif

    {{ $slot }}
</header>
