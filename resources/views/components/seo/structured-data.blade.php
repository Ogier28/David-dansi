@props([
    'breadcrumbs' => [],
])

@php
    $identity = config('portfolio.identity');
    $home = localized_route('home');
    $locale = app()->getLocale();

    $person = [
        '@type' => 'Person',
        '@id' => $home.'#person',
        'name' => $identity['name'],
        'jobTitle' => __('site.role'),
        'description' => __('seo.default.description'),
        'url' => $home,
        'image' => asset($identity['portrait']),
        'email' => 'mailto:'.$identity['email'],
        'telephone' => $identity['phone'],
        'address' => [
            '@type' => 'PostalAddress',
            'addressLocality' => $identity['location'],
        ],
        'knowsLanguage' => array_values(array_map(
            fn (array $meta): string => $meta['html'],
            config('portfolio.locales')
        )),
        'knowsAbout' => array_map(
            fn (array $area): string => $area['title'],
            app(\App\Support\Portfolio::class)->expertise()
        ),
        'sameAs' => [$identity['socials']['linkedin']],
    ];

    $service = [
        '@type' => 'ProfessionalService',
        '@id' => $home.'#service',
        'name' => $identity['name'].' — '.__('site.role'),
        'description' => __('seo.professional_service'),
        'url' => $home,
        'image' => asset(config('portfolio.seo.og_image')),
        'provider' => ['@id' => $home.'#person'],
        'email' => $identity['email'],
        'telephone' => $identity['phone'],
        'areaServed' => ['West Africa', 'Europe'],
        'availableLanguage' => array_values(array_map(
            fn (array $meta): string => $meta['name'],
            config('portfolio.locales')
        )),
        'knowsAbout' => array_map(
            fn (array $area): string => $area['title'],
            app(\App\Support\Portfolio::class)->expertise()
        ),
    ];

    $website = [
        '@type' => 'WebSite',
        '@id' => $home.'#website',
        'url' => $home,
        'name' => $identity['name'],
        'inLanguage' => \App\Support\Localization::meta()['html'],
        'publisher' => ['@id' => $home.'#person'],
    ];

    $graph = [$person, $service, $website];

    if ($breadcrumbs !== []) {
        $graph[] = [
            '@type' => 'BreadcrumbList',
            'itemListElement' => array_map(fn (array $crumb, int $index): array => [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $crumb['label'],
                'item' => $crumb['url'],
            ], $breadcrumbs, array_keys($breadcrumbs)),
        ];
    }

    $payload = [
        '@context' => 'https://schema.org',
        '@graph' => $graph,
    ];
@endphp

<script type="application/ld+json" nonce="{{ csp_nonce() }}">{!! json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
