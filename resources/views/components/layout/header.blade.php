@php
    $onHome = request()->routeIs(\App\Support\Localization::routeName('home'));
    $home = localized_route('home');
    $anchor = fn (string $id): string => ($onHome ? '' : $home).'#'.$id;

    $links = [
        ['label' => __('site.nav.about'), 'href' => $anchor('about')],
        ['label' => __('site.nav.expertise'), 'href' => $anchor('expertise')],
        ['label' => __('site.nav.projects'), 'href' => localized_route('projects.index')],
        ['label' => __('site.nav.timeline'), 'href' => $anchor('timeline')],
    ];

    $identity = config('portfolio.identity');
@endphp

<header data-site-header class="fixed inset-x-0 top-0 z-50" x-data="{ menu: false }">
    <div class="header-shell">
        <div class="mx-auto w-full max-w-7xl px-6 lg:px-8">
            <div class="flex h-[var(--header-height)] items-center justify-between gap-4">
                {{-- Monogramme : initiales dans un cadre, lu comme une plaque d'identification --}}
                <a
                    href="{{ $home }}"
                    class="group flex items-center gap-3"
                    aria-label="{{ $identity['name'] }} — {{ __('site.role') }}"
                >
                    <span class="grid h-9 w-9 place-items-center rounded-field border border-gold/40 bg-gold/10 text-[0.8125rem] font-bold text-gold transition-colors group-hover:bg-gold/20">
                        {{ $identity['initials'] }}
                    </span>
                    <span class="hidden flex-col leading-tight sm:flex">
                        <span class="text-[0.9375rem] font-semibold tracking-tight text-ink">{{ $identity['name'] }}</span>
                        <span class="text-xs text-ink-faint">{{ __('site.role') }}</span>
                    </span>
                </a>

                <nav class="hidden lg:block" aria-label="{{ __('site.nav.home') }}">
                    <ul class="flex items-center gap-1">
                        @foreach ($links as $link)
                            <li>
                                <a
                                    href="{{ $link['href'] }}"
                                    class="rounded-full px-3.5 py-2 text-sm font-medium text-ink-muted transition-colors hover:bg-white/5 hover:text-ink"
                                >{{ $link['label'] }}</a>
                            </li>
                        @endforeach
                    </ul>
                </nav>

                <div class="flex items-center gap-2.5">
                    <x-layout.language-switcher />

                    <x-ui.button :href="$anchor('contact')" class="hidden sm:inline-flex">
                        {{ __('site.actions.contact') }}
                    </x-ui.button>

                    <button
                        type="button"
                        class="btn btn-glass p-2.5 lg:hidden"
                        :aria-expanded="menu ? 'true' : 'false'"
                        aria-controls="mobile-menu"
                        @click="menu = ! menu"
                    >
                        <span class="sr-only" x-text="menu ? @js(__('site.actions.close_menu')) : @js(__('site.actions.open_menu'))"></span>
                        <x-icon name="menu" :size="20" x-show="! menu" />
                        <x-icon name="close" :size="20" x-show="menu" x-cloak />
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Panneau mobile --}}
    <div
        id="mobile-menu"
        x-show="menu"
        x-cloak
        x-transition.opacity.duration.200ms
        @keydown.escape.window="menu = false"
        class="lg:hidden"
    >
        <div class="mx-4 mt-2 overflow-hidden rounded-panel border border-white/10 bg-base/95 p-2 backdrop-blur-xl">
            <nav aria-label="{{ __('site.nav.home') }}">
                <ul class="flex flex-col">
                    @foreach ($links as $link)
                        <li>
                            <a
                                href="{{ $link['href'] }}"
                                @click="menu = false"
                                class="flex items-center justify-between rounded-field px-4 py-3 text-[0.9375rem] font-medium text-ink-muted transition-colors hover:bg-white/5 hover:text-ink"
                            >
                                {{ $link['label'] }}
                                <x-icon name="arrow-right" :size="16" class="text-ink-faint" />
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>

            <div class="p-2 pt-3">
                <x-ui.button :href="$anchor('contact')" class="w-full" @click="menu = false">
                    {{ __('site.actions.contact') }}
                </x-ui.button>
            </div>
        </div>
    </div>
</header>
