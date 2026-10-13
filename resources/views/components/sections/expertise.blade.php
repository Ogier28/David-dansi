@props(['areas'])

<x-ui.section
    id="expertise"
    width="max-w-7xl"
    :eyebrow="__('home.expertise.eyebrow')"
    :title="__('home.expertise.title')"
    :lead="__('home.expertise.lead')"
>
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-6">
        @foreach ($areas as $area)
            @php
                $wide = $area['span'] >= 4;
                $spanClass = match ($area['span']) {
                    4 => 'lg:col-span-4',
                    3 => 'lg:col-span-3',
                    default => 'lg:col-span-2',
                };
            @endphp

            <x-ui.glass
                class="{{ $spanClass }} flex flex-col gap-4 p-7 transition-colors duration-300 hover:border-gold/25"
                data-reveal
                style="--reveal-delay: {{ $loop->index * 60 }}ms"
            >
                <span class="grid h-11 w-11 place-items-center rounded-field border border-gold/20 bg-gold/8 text-gold">
                    <x-icon :name="$area['icon']" :size="21" />
                </span>

                <h3 class="text-[1.1875rem] font-semibold tracking-tight text-ink">{{ $area['title'] }}</h3>

                <p class="text-[0.9375rem] leading-relaxed text-ink-muted">{{ $area['text'] }}</p>

                @if ($wide)
                    <ul class="mt-auto grid gap-2.5 pt-2 sm:grid-cols-2">
                        @foreach ($area['items'] as $item)
                            <li class="flex items-start gap-2.5 text-[0.875rem] text-ink-muted">
                                <x-icon name="check" :size="15" class="mt-0.5 shrink-0 text-gold" />
                                {{ $item }}
                            </li>
                        @endforeach
                    </ul>
                @endif
            </x-ui.glass>
        @endforeach
    </div>
</x-ui.section>
