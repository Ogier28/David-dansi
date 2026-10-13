@php
    $alternates = \App\Support\Localization::alternates();
    $current = app()->getLocale();
    $locales = config('portfolio.locales');
@endphp

<div class="relative" x-data="{ open: false }" @keydown.escape="open = false">
    <button
        type="button"
        class="btn btn-glass px-3.5 py-2 text-sm"
        :aria-expanded="open ? 'true' : 'false'"
        aria-haspopup="true"
        aria-label="{{ __('site.language.label') }}"
        @click="open = ! open"
    >
        <x-icon name="globe" :size="16" class="text-ink-muted" />
        <span class="font-semibold">{{ $locales[$current]['short'] }}</span>
        <x-icon name="chevron-down" :size="14" class="text-ink-faint" />
    </button>

    <div
        x-show="open"
        x-cloak
        x-transition.opacity.duration.150ms
        @click.outside="open = false"
        class="glass glass-edge absolute right-0 z-50 mt-2 w-44 overflow-hidden rounded-tile p-1.5"
    >
        @foreach ($locales as $code => $locale)
            <a
                href="{{ $alternates[$code] ?? localized_route('home', [], $code) }}"
                hreflang="{{ $locale['html'] }}"
                lang="{{ $locale['html'] }}"
                @if ($code === $current) aria-current="true" @endif
                class="flex items-center justify-between gap-3 rounded-field px-3 py-2 text-sm transition-colors {{ $code === $current ? 'bg-white/8 text-gold' : 'text-ink-muted hover:bg-white/5 hover:text-ink' }}"
                title="{{ __('site.language.switch_to', ['language' => $locale['name']]) }}"
            >
                <span>{{ $locale['name'] }}</span>
                <span class="text-xs font-semibold tracking-wide">{{ $locale['short'] }}</span>
            </a>
        @endforeach
    </div>
</div>
