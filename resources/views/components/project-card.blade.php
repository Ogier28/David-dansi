@props([
    'project',
    'variant' => 'default',
])

@php
    $large = $variant === 'large';
    $url = localized_route('projects.show', ['project' => $project['slug']]);
@endphp

<a
    href="{{ $url }}"
    {{ $attributes->class([
        'group glass glass-edge relative block overflow-hidden rounded-panel transition-colors duration-300 hover:border-gold/30',
    ]) }}
>
    <div class="{{ $large ? 'grid lg:grid-cols-[minmax(0,1.05fr)_minmax(0,1fr)]' : '' }}">
        <div class="relative overflow-hidden {{ $large ? 'lg:h-full' : '' }}">
            <img
                src="{{ asset($project['image']) }}"
                alt="{{ $project['title'] }}"
                width="1600"
                height="1000"
                loading="lazy"
                decoding="async"
                class="aspect-[8/5] w-full object-cover transition-transform duration-700 ease-out group-hover:scale-[1.03] {{ $large ? 'lg:aspect-auto lg:h-full' : '' }}"
            >
            {{-- Voile sombre : le texte des étiquettes reste lisible quelle que soit l'image --}}
            <div class="absolute inset-0 bg-gradient-to-t from-base/85 via-base/15 to-transparent"></div>

            <div class="absolute inset-x-0 bottom-0 flex items-center justify-between gap-3 p-5">
                <x-ui.badge tone="gold">{{ $project['category_label'] }}</x-ui.badge>
                <span class="tabular text-[0.8125rem] font-medium text-ink-muted">{{ $project['year'] }}</span>
            </div>
        </div>

        <div class="flex flex-col gap-4 p-6 sm:p-7">
            <h3 class="text-[1.25rem] font-semibold leading-snug tracking-tight text-ink transition-colors group-hover:text-gold-soft {{ $large ? 'sm:text-[1.5rem]' : '' }}">
                {{ $project['title'] }}
            </h3>

            <p class="text-[0.9375rem] leading-relaxed text-ink-muted">
                {{ $large ? $project['excerpt'] : str($project['excerpt'])->limit(130) }}
            </p>

            @if ($project['metrics'] !== [])
                <div class="-ml-px -mt-px mt-auto grid grid-cols-3 pt-2">
                    @foreach ($project['metrics'] as $metric)
                        <div class="border-l border-t border-white/8 px-3 py-3 first:pl-0 first:border-l-0">
                            <p class="tabular text-[1.0625rem] font-bold leading-none tracking-tight text-ink">{{ $metric['value'] }}</p>
                            <p class="mt-1.5 text-[0.75rem] leading-tight text-ink-faint">{{ $metric['label'] }}</p>
                        </div>
                    @endforeach
                </div>
            @endif

            @if ($large)
                <p class="mt-2 flex flex-wrap gap-2">
                    @foreach ($project['tags'] as $tag)
                        <x-ui.badge>{{ $tag }}</x-ui.badge>
                    @endforeach
                </p>
            @endif
        </div>
    </div>
</a>
