@props(['identity'])

@php
    $facts = (array) __('home.about.facts');
    $paragraphs = (array) __('home.about.paragraphs');
@endphp

<x-ui.section id="about" width="max-w-7xl">
    <div class="grid gap-14 lg:grid-cols-[minmax(0,0.85fr)_minmax(0,1.15fr)] lg:items-start lg:gap-16">
        <div class="relative" data-reveal>
            <x-ui.glass class="overflow-hidden p-2.5">
                <img
                    src="{{ asset($identity['portrait']) }}"
                    alt="{{ __('home.about.portrait_alt') }}"
                    width="1000"
                    height="1250"
                    loading="lazy"
                    decoding="async"
                    class="w-full rounded-tile object-cover"
                >
            </x-ui.glass>

            {{-- Plaque de localisation : la même lecture d'étiquette d'instrument que le reste du site --}}
            <x-ui.glass
                raised
                radius="pill"
                class="absolute -bottom-4 left-5 flex items-center gap-2 px-4 py-2.5 text-[0.8125rem] font-medium text-ink"
            >
                <x-icon name="map-pin" :size="15" class="text-gold" />
                {{ $identity['location'] }}
            </x-ui.glass>
        </div>

        <div>
            <x-ui.heading
                :eyebrow="__('home.about.eyebrow')"
                :title="__('home.about.title')"
                data-reveal
            />

            <div class="mt-7 flex flex-col gap-5 text-[1.0625rem] leading-relaxed text-ink-muted" data-reveal>
                @foreach ($paragraphs as $paragraph)
                    <p class="max-w-prose">{{ $paragraph }}</p>
                @endforeach
            </div>

            <div class="-ml-px -mt-px mt-10 grid grid-cols-2 sm:grid-cols-4" data-reveal>
                @foreach ($facts as $fact)
                    <div class="border-l border-t border-white/8 px-5 py-4">
                        <p class="text-[1.0625rem] font-semibold tracking-tight text-gold">{{ $fact['value'] }}</p>
                        <p class="mt-1 text-[0.8125rem] leading-snug text-ink-faint">{{ $fact['label'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-ui.section>
