<x-layout.app
    :meta="$meta"
    type="article"
    :breadcrumbs="[
        ['label' => __('site.nav.home'), 'url' => localized_route('home')],
        ['label' => __('projects.index.title'), 'url' => localized_route('projects.index')],
        ['label' => $project['title'], 'url' => localized_route('projects.show', ['project' => $project['slug']])],
    ]"
>
    <article class="pb-24 pt-[calc(var(--header-height)+2.5rem)] sm:pb-32 sm:pt-[calc(var(--header-height)+3.5rem)]">
        <div class="mx-auto w-full max-w-5xl px-6 lg:px-8">
            <nav aria-label="{{ __('projects.index.title') }}" class="mb-8">
                <a
                    href="{{ localized_route('projects.index') }}"
                    class="inline-flex items-center gap-2 text-sm text-ink-muted transition-colors hover:text-gold"
                >
                    <x-icon name="arrow-left" :size="16" />
                    {{ __('site.actions.back_to_projects') }}
                </a>
            </nav>

            <header class="hero-enter">
                <div class="flex flex-wrap items-center gap-3">
                    <x-ui.badge tone="gold">{{ $project['category_label'] }}</x-ui.badge>
                    <span class="tabular text-sm text-ink-faint">{{ $project['year'] }}</span>
                </div>

                <h1 class="mt-5 text-[clamp(2rem,4.2vw,3.2rem)] font-bold leading-[1.06] text-balance-tight text-ink">
                    {{ $project['title'] }}
                </h1>

                <p class="mt-6 max-w-2xl text-lg leading-relaxed text-ink-muted">{{ $project['excerpt'] }}</p>
            </header>

            <x-ui.glass class="mt-12 overflow-hidden p-2.5" data-reveal>
                <img
                    src="{{ asset($project['image']) }}"
                    alt="{{ $project['title'] }}"
                    width="1600"
                    height="1000"
                    class="w-full rounded-tile object-cover"
                    decoding="async"
                >
            </x-ui.glass>

            {{-- Fiche signalétique : les quatre informations qu'un maître d'ouvrage cherche d'abord --}}
            <x-ui.glass class="mt-4 overflow-hidden" data-reveal>
                <dl class="-ml-px -mt-px grid grid-cols-2 lg:grid-cols-4">
                    @foreach ([
                        'client' => $project['client'],
                        'location' => $project['location'],
                        'scope' => $project['scope'],
                        'year' => $project['year'],
                    ] as $key => $value)
                        <div class="border-l border-t border-white/8 p-5">
                            <dt class="text-[0.8125rem] text-ink-faint">{{ __("projects.detail.{$key}") }}</dt>
                            <dd class="mt-1 text-[0.9375rem] font-medium leading-snug text-ink">{{ $value }}</dd>
                        </div>
                    @endforeach
                </dl>
            </x-ui.glass>

            <div class="mt-16 grid gap-12 lg:grid-cols-[minmax(0,1fr)_minmax(0,0.42fr)] lg:gap-14">
                <div class="flex flex-col gap-11">
                    <section data-reveal>
                        <h2 class="mb-4 flex items-center gap-3 text-[1.0625rem] font-semibold text-ink">
                            <span class="h-px w-7 bg-gold"></span>
                            {{ __('projects.detail.context') }}
                        </h2>
                        <p class="max-w-prose text-[1.0625rem] leading-relaxed text-ink-muted">{{ $project['context'] }}</p>
                    </section>

                    <section data-reveal>
                        <h2 class="mb-4 flex items-center gap-3 text-[1.0625rem] font-semibold text-ink">
                            <span class="h-px w-7 bg-gold"></span>
                            {{ __('projects.detail.approach') }}
                        </h2>
                        <p class="max-w-prose text-[1.0625rem] leading-relaxed text-ink-muted">{{ $project['approach'] }}</p>
                    </section>

                    <section data-reveal>
                        <h2 class="mb-4 flex items-center gap-3 text-[1.0625rem] font-semibold text-ink">
                            <span class="h-px w-7 bg-gold"></span>
                            {{ __('projects.detail.results') }}
                        </h2>
                        <p class="max-w-prose text-[1.0625rem] leading-relaxed text-ink-muted">{{ $project['results'] }}</p>

                        <ul class="mt-6 flex flex-col gap-3">
                            @foreach ($project['result_items'] as $item)
                                <li class="flex items-start gap-3 text-[0.9375rem] leading-relaxed text-ink">
                                    <x-icon name="check" :size="17" class="mt-1 shrink-0 text-gold" />
                                    {{ $item }}
                                </li>
                            @endforeach
                        </ul>
                    </section>
                </div>

                <aside class="flex flex-col gap-4">
                    <x-ui.glass radius="tile" class="p-6" data-reveal>
                        <p class="mb-5 text-[0.8125rem] text-ink-faint">{{ __('projects.detail.results') }}</p>
                        <div class="flex flex-col gap-5">
                            @foreach ($project['metrics'] as $metric)
                                <div>
                                    <p class="tabular text-[1.5rem] font-bold leading-none tracking-tight text-gold">{{ $metric['value'] }}</p>
                                    <p class="mt-1.5 text-[0.8125rem] leading-snug text-ink-muted">{{ $metric['label'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </x-ui.glass>

                    <x-ui.glass radius="tile" class="p-6" data-reveal>
                        <p class="mb-4 text-[0.8125rem] text-ink-faint">{{ __('projects.detail.tools') }}</p>
                        <p class="flex flex-wrap gap-2">
                            @foreach ($project['tags'] as $tag)
                                <x-ui.badge>{{ $tag }}</x-ui.badge>
                            @endforeach
                        </p>
                    </x-ui.glass>
                </aside>
            </div>

            {{-- Appel à l'action : la suite logique après la lecture d'une fiche --}}
            <x-ui.glass raised class="mt-16 flex flex-col gap-6 p-8 sm:flex-row sm:items-center sm:justify-between sm:p-10" data-reveal>
                <div>
                    <p class="text-[1.25rem] font-semibold tracking-tight text-ink">{{ __('projects.detail.cta_title') }}</p>
                    <p class="mt-2 max-w-md text-[0.9375rem] leading-relaxed text-ink-muted">{{ __('projects.detail.cta_text') }}</p>
                </div>
                <x-ui.button :href="localized_route('home').'#contact'" class="shrink-0">
                    {{ __('site.actions.contact') }}
                </x-ui.button>
            </x-ui.glass>

            @if ($previous || $next)
                <nav class="mt-4 grid gap-4 sm:grid-cols-2" aria-label="{{ __('projects.index.title') }}">
                    @if ($previous)
                        <a
                            href="{{ localized_route('projects.show', ['project' => $previous['slug']]) }}"
                            class="glass glass-edge group flex items-center gap-4 rounded-panel p-6 transition-colors hover:border-gold/30"
                        >
                            <x-icon name="arrow-left" :size="18" class="shrink-0 text-ink-faint transition-colors group-hover:text-gold" />
                            <span>
                                <span class="block text-[0.8125rem] text-ink-faint">{{ __('projects.detail.previous') }}</span>
                                <span class="mt-1 block text-[0.9375rem] font-medium leading-snug text-ink">{{ $previous['title'] }}</span>
                            </span>
                        </a>
                    @endif

                    @if ($next)
                        <a
                            href="{{ localized_route('projects.show', ['project' => $next['slug']]) }}"
                            class="glass glass-edge group flex items-center justify-end gap-4 rounded-panel p-6 text-right transition-colors hover:border-gold/30 sm:col-start-2"
                        >
                            <span>
                                <span class="block text-[0.8125rem] text-ink-faint">{{ __('projects.detail.next') }}</span>
                                <span class="mt-1 block text-[0.9375rem] font-medium leading-snug text-ink">{{ $next['title'] }}</span>
                            </span>
                            <x-icon name="arrow-right" :size="18" class="shrink-0 text-ink-faint transition-colors group-hover:text-gold" />
                        </a>
                    @endif
                </nav>
            @endif
        </div>
    </article>
</x-layout.app>
