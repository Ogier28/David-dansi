@props([
    'meta' => [],
    'railSections' => [],
    'breadcrumbs' => [],
    'type' => 'website',
])

<!DOCTYPE html>
<html lang="{{ \App\Support\Localization::meta()['html'] }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <x-seo.head
        :title="$meta['title'] ?? __('seo.default.title')"
        :description="$meta['description'] ?? __('seo.default.description')"
        :image="$meta['image'] ?? null"
        :type="$type"
    />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <x-seo.structured-data :breadcrumbs="$breadcrumbs" />
</head>

<body class="min-h-screen antialiased">
    <a
        href="#content"
        class="sr-only focus:not-sr-only focus:fixed focus:left-6 focus:top-6 focus:z-[100] focus:rounded-full focus:bg-gold focus:px-5 focus:py-2.5 focus:text-sm focus:font-semibold focus:text-base-deep"
    >
        {{ __('site.actions.skip_to_content') }}
    </a>

    <x-layout.backdrop />
    <x-layout.header />

    @if ($railSections !== [])
        <x-layout.rail :sections="$railSections" />
    @endif

    <main id="content">
        {{ $slot }}
    </main>

    <x-layout.footer />

    @stack('scripts')
</body>
</html>
