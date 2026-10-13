@props(['sections' => []])

{{--
    Rail de mesure : graduations repérant la section visible. Purement
    additionnel, masqué sous 1280 px où le header suffit.
--}}
<nav
    class="fixed left-6 top-1/2 z-40 hidden -translate-y-1/2 xl:block"
    aria-label="{{ __('site.nav.home') }}"
>
    <ul class="flex flex-col gap-4">
        @foreach ($sections as $section)
            <li>
                <a
                    href="#{{ $section['id'] }}"
                    class="rail-link group flex items-center gap-3"
                    data-rail-link="{{ $section['id'] }}"
                    aria-current="false"
                >
                    <span class="rail-tick"></span>
                    <span class="rail-label text-xs font-medium text-ink-muted">{{ $section['label'] }}</span>
                </a>
            </li>
        @endforeach
    </ul>
</nav>
