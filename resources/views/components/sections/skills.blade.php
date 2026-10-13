@props(['groups', 'certifications'])

<x-ui.section
    id="skills"
    width="max-w-7xl"
    :eyebrow="__('home.skills.eyebrow')"
    :title="__('home.skills.title')"
    :lead="__('home.skills.lead')"
>
    <div class="grid gap-4 lg:grid-cols-2">
        @foreach ($groups as $group)
            <x-ui.glass class="p-7" data-reveal style="--reveal-delay: {{ $loop->index * 70 }}ms">
                <div class="mb-6 flex items-baseline justify-between gap-4">
                    <h3 class="text-[1.0625rem] font-semibold tracking-tight text-ink">{{ $group['title'] }}</h3>
                    <span class="text-xs text-ink-faint">{{ __('home.skills.legend') }}</span>
                </div>

                <ul class="flex flex-col divide-y divide-white/6">
                    @foreach ($group['items'] as $item)
                        <li class="flex items-center justify-between gap-6 py-3 first:pt-0 last:pb-0">
                            <span class="text-[0.9375rem] text-ink-muted">{{ $item['name'] }}</span>
                            <x-ui.gauge
                                :level="$item['level']"
                                :label="__('skills.level_label', ['level' => $item['level'], 'description' => $item['description']])"
                                class="shrink-0"
                            />
                        </li>
                    @endforeach
                </ul>
            </x-ui.glass>
        @endforeach
    </div>

    <h3 class="mb-5 mt-14 text-[1.0625rem] font-semibold tracking-tight text-ink" data-reveal>
        {{ __('skills.certifications_title') }}
    </h3>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ($certifications as $certification)
            <x-ui.glass
                radius="tile"
                class="flex items-start gap-4 p-5"
                data-reveal
                style="--reveal-delay: {{ $loop->index * 60 }}ms"
            >
                <span class="grid h-10 w-10 shrink-0 place-items-center rounded-field border border-gold/20 bg-gold/8 text-gold">
                    <x-icon name="award" :size="19" />
                </span>

                <div>
                    <p class="text-[0.9375rem] font-semibold leading-snug text-ink">{{ $certification['title'] }}</p>
                    <p class="mt-1 text-[0.8125rem] text-ink-faint">{{ $certification['issuer'] }}</p>
                    <p class="tabular mt-2 text-xs font-medium text-gold">{{ $certification['year'] }}</p>
                </div>
            </x-ui.glass>
        @endforeach
    </div>
</x-ui.section>
