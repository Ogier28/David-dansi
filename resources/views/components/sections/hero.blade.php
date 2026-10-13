@props(['identity', 'metrics'])

<section id="hero" class="relative pb-20 pt-[calc(var(--header-height)+3.5rem)] sm:pb-28 sm:pt-[calc(var(--header-height)+5.5rem)]">
    <div class="mx-auto w-full max-w-7xl px-6 lg:px-8">
        <div class="grid items-center gap-14 lg:grid-cols-[minmax(0,1fr)_minmax(0,1.04fr)] lg:gap-16">
            <div class="hero-enter">
                <p class="inline-flex items-center gap-2.5 rounded-full border border-gold/25 bg-gold/8 px-3.5 py-1.5 text-[0.8125rem] font-medium text-gold-soft">
                    <span class="relative grid h-2 w-2 place-items-center">
                        <span class="absolute inset-0 rounded-full bg-gold/30"></span>
                        <span class="h-1 w-1 rounded-full bg-gold"></span>
                    </span>
                    {{ __('home.hero.availability') }}
                </p>

                <p class="mt-7 text-base text-ink-muted">
                    <span class="font-semibold text-ink">{{ $identity['name'] }}</span>,
                    <span class="text-gold">{{ __('site.role') }}</span>
                </p>

                <h1 class="mt-3 text-[clamp(2.3rem,4.6vw,3.6rem)] font-bold leading-[1.03] text-balance-tight text-ink">
                    {{ __('home.hero.title') }}
                </h1>

                <p class="mt-7 max-w-xl text-lg leading-relaxed text-ink-muted">
                    {{ __('home.hero.lead') }}
                </p>

                <div class="mt-9 flex flex-wrap items-center gap-3">
                    <x-ui.button href="#contact">{{ __('site.actions.contact') }}</x-ui.button>
                    <x-ui.button :href="localized_route('projects.index')" variant="glass">
                        {{ __('site.actions.projects') }}
                    </x-ui.button>
                </div>
            </div>

            <div class="hero-enter [animation-delay:180ms]">
                <x-load-curve />
            </div>
        </div>

        {{-- Relevés : bande de chiffres séparée du discours, lue comme un bandeau d'instruments --}}
        <x-ui.glass class="mt-16 overflow-hidden sm:mt-20" data-reveal>
            {{-- Les filets intérieurs viennent des bordures des cellules ; les
                 bordures extérieures sont décalées hors du cadre par -mt-px/-ml-px. --}}
            <div class="-ml-px -mt-px grid grid-cols-2 sm:grid-cols-4">
                @foreach ($metrics as $metric)
                    <div class="border-l border-t border-white/8 p-6 sm:p-7">
                        <x-ui.metric
                            :value="$metric['value']"
                            :decimals="$metric['decimals']"
                            :prefix="$metric['prefix']"
                            :suffix="$metric['suffix']"
                            :label="$metric['label']"
                            :note="$metric['note']"
                        />
                    </div>
                @endforeach
            </div>
        </x-ui.glass>
    </div>
</section>
