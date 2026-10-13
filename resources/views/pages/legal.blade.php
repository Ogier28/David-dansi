<x-layout.app
    :meta="$meta"
    :breadcrumbs="[
        ['label' => __('site.nav.home'), 'url' => localized_route('home')],
        ['label' => $document['title'], 'url' => url()->current()],
    ]"
>
    <div class="pb-24 pt-[calc(var(--header-height)+3rem)] sm:pb-32 sm:pt-[calc(var(--header-height)+4.5rem)]">
        <div class="mx-auto w-full max-w-3xl px-6 lg:px-8">
            <header class="hero-enter">
                <h1 class="text-[clamp(2rem,4vw,2.9rem)] font-bold text-balance-tight text-ink">{{ $document['title'] }}</h1>
                <p class="mt-4 text-sm text-ink-faint">{{ $document['updated'] }}</p>
            </header>

            <div class="mt-14 flex flex-col gap-11">
                @foreach ($document['sections'] as $section)
                    <section data-reveal>
                        <h2 class="mb-4 flex items-center gap-3 text-[1.125rem] font-semibold text-ink">
                            <span class="h-px w-7 shrink-0 bg-gold"></span>
                            {{ $section['title'] }}
                        </h2>

                        <div class="flex flex-col gap-4">
                            @foreach ($section['body'] as $paragraph)
                                <p class="text-[1.0625rem] leading-relaxed text-ink-muted">{{ $paragraph }}</p>
                            @endforeach
                        </div>
                    </section>
                @endforeach
            </div>

            <div class="mt-16" data-reveal>
                <x-ui.button :href="localized_route('home')" variant="glass">
                    {{ __('site.actions.back_home') }}
                </x-ui.button>
            </div>
        </div>
    </div>
</x-layout.app>
