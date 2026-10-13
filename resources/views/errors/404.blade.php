<x-layout.app :meta="['title' => __('site.errors.404.title').' — '.config('portfolio.identity.name')]">
    <div class="grid min-h-[72vh] place-items-center px-6 py-24">
        <div class="max-w-xl text-center">
            <p class="tabular text-sm font-medium text-gold">{{ __('site.errors.404.code') }}</p>

            <h1 class="mt-4 text-[clamp(2rem,4.5vw,3rem)] font-bold text-balance-tight text-ink">
                {{ __('site.errors.404.title') }}
            </h1>

            <p class="mx-auto mt-5 max-w-md text-[1.0625rem] leading-relaxed text-ink-muted">
                {{ __('site.errors.404.text') }}
            </p>

            <div class="mt-9 flex flex-wrap items-center justify-center gap-3">
                <x-ui.button :href="localized_route('home')">{{ __('site.actions.back_home') }}</x-ui.button>
                <x-ui.button :href="localized_route('projects.index')" variant="glass">
                    {{ __('site.actions.all_projects') }}
                </x-ui.button>
            </div>
        </div>
    </div>
</x-layout.app>
