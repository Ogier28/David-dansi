@props(['entries'])

<x-ui.section
    id="timeline"
    :eyebrow="__('home.timeline.eyebrow')"
    :title="__('home.timeline.title')"
    :lead="__('home.timeline.lead')"
>
    {{-- Le parcours est une séquence : il est rendu comme un axe gradué, repères datés compris. --}}
    <ol class="relative flex flex-col gap-10">
        <div
            class="absolute bottom-4 left-4 top-4 w-px bg-gradient-to-b from-white/20 via-white/10 to-transparent sm:left-36"
            aria-hidden="true"
        ></div>

        @foreach ($entries as $entry)
            <li class="relative pl-14 sm:grid sm:grid-cols-[9rem_minmax(0,1fr)] sm:pl-0" data-reveal>
                <span class="absolute left-4 top-0.5 grid h-8 w-8 -translate-x-1/2 place-items-center rounded-full border border-white/12 bg-surface text-gold sm:left-36">
                    <x-icon :name="$entry['type'] === 'education' ? 'graduation' : 'briefcase'" :size="15" />
                </span>

                <div class="sm:pr-8 sm:text-right">
                    <p class="tabular text-sm font-semibold text-ink">
                        {{ $entry['start'] }} — {{ $entry['end'] ?? __('home.timeline.current') }}
                    </p>
                    <p class="mt-1 text-xs text-ink-faint">{{ __("home.timeline.types.{$entry['type']}") }}</p>
                </div>

                <div class="mt-3 sm:mt-0 sm:pl-10">
                    <h3 class="text-[1.0625rem] font-semibold tracking-tight text-ink">{{ $entry['role'] }}</h3>
                    <p class="mt-1 text-[0.9375rem] text-gold-soft">{{ $entry['organisation'] }}</p>
                    <p class="mt-3 max-w-prose text-[0.9375rem] leading-relaxed text-ink-muted">{{ $entry['text'] }}</p>
                </div>
            </li>
        @endforeach
    </ol>
</x-ui.section>
