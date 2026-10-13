@php
    $identity = config('portfolio.identity');
    $onHome = request()->routeIs(\App\Support\Localization::routeName('home'));
    $home = localized_route('home');
    $anchor = fn (string $id): string => ($onHome ? '' : $home).'#'.$id;
@endphp

<footer class="relative mt-8 border-t border-white/8">
    <div class="mx-auto w-full max-w-7xl px-6 py-16 lg:px-8">
        <div class="grid gap-12 md:grid-cols-2 lg:grid-cols-[1.6fr_1fr_1fr_1fr]">
            <div>
                <p class="text-base font-semibold tracking-tight text-ink">{{ $identity['name'] }}</p>
                <p class="mt-1 text-sm text-gold">{{ __('site.role') }}</p>
                <p class="mt-4 max-w-sm text-sm leading-relaxed text-ink-muted">{{ __('site.footer.tagline') }}</p>
            </div>

            <div>
                <p class="mb-4 text-sm font-semibold text-ink">{{ __('site.footer.navigation') }}</p>
                <ul class="flex flex-col gap-2.5 text-sm">
                    <li><a href="{{ $anchor('about') }}" class="text-ink-muted transition-colors hover:text-gold">{{ __('site.nav.about') }}</a></li>
                    <li><a href="{{ $anchor('expertise') }}" class="text-ink-muted transition-colors hover:text-gold">{{ __('site.nav.expertise') }}</a></li>
                    <li><a href="{{ localized_route('projects.index') }}" class="text-ink-muted transition-colors hover:text-gold">{{ __('site.nav.projects') }}</a></li>
                    <li><a href="{{ $anchor('contact') }}" class="text-ink-muted transition-colors hover:text-gold">{{ __('site.nav.contact') }}</a></li>
                </ul>
            </div>

            <div>
                <p class="mb-4 text-sm font-semibold text-ink">{{ __('site.footer.legal') }}</p>
                <ul class="flex flex-col gap-2.5 text-sm">
                    <li><a href="{{ localized_route('legal') }}" class="text-ink-muted transition-colors hover:text-gold">{{ __('legal.notice.title') }}</a></li>
                    <li><a href="{{ localized_route('privacy') }}" class="text-ink-muted transition-colors hover:text-gold">{{ __('legal.privacy.title') }}</a></li>
                </ul>
            </div>

            <div>
                <p class="mb-4 text-sm font-semibold text-ink">{{ __('site.footer.elsewhere') }}</p>
                <ul class="flex flex-col gap-2.5 text-sm">
                    <li>
                        <a
                            href="{{ $identity['socials']['linkedin'] }}"
                            class="inline-flex items-center gap-2 text-ink-muted transition-colors hover:text-gold"
                            rel="noopener noreferrer"
                            target="_blank"
                        >
                            <x-icon name="linkedin" :size="16" />
                            LinkedIn
                        </a>
                    </li>
                    <li>
                        <a href="mailto:{{ $identity['email'] }}" class="inline-flex items-center gap-2 text-ink-muted transition-colors hover:text-gold">
                            <x-icon name="mail" :size="16" />
                            {{ $identity['email'] }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="mt-14 flex flex-col gap-3 border-t border-white/8 pt-6 text-[0.8125rem] text-ink-faint sm:flex-row sm:items-center sm:justify-between">
            <p>© {{ now()->year }} {{ $identity['name'] }}. {{ __('site.footer.rights') }}</p>
            <p>{{ __('site.footer.built') }}</p>
        </div>
    </div>
</footer>
