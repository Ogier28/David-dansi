@props([
    'title',
    'description',
    'image' => null,
    'type' => 'website',
])

@php
    $localeMeta = \App\Support\Localization::meta();
    $alternates = \App\Support\Localization::alternates();
    $identity = config('portfolio.identity');
    $image = asset($image ?? config('portfolio.seo.og_image'));
@endphp

<title>{{ $title }}</title>
<meta name="description" content="{{ $description }}">
<meta name="author" content="{{ $identity['name'] }}">
<meta name="theme-color" content="#070A11">
<meta name="robots" content="index, follow, max-image-preview:large">

<link rel="canonical" href="{{ url()->current() }}">

{{-- Équivalences de langue : chaque page déclare ses variantes et la version par défaut. --}}
@foreach ($alternates as $code => $url)
    <link rel="alternate" hreflang="{{ config("portfolio.locales.{$code}.html") }}" href="{{ $url }}">
@endforeach
<link rel="alternate" hreflang="x-default" href="{{ $alternates[\App\Support\Localization::fallback()] }}">

<meta property="og:type" content="{{ $type }}">
<meta property="og:site_name" content="{{ $identity['name'] }}">
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:locale" content="{{ $localeMeta['og'] }}">
@foreach ($alternates as $code => $url)
    @if ($code !== app()->getLocale())
        <meta property="og:locale:alternate" content="{{ config("portfolio.locales.{$code}.og") }}">
    @endif
@endforeach
<meta property="og:image" content="{{ $image }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:alt" content="{{ $identity['name'] }} — {{ __('site.role') }}">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $title }}">
<meta name="twitter:description" content="{{ $description }}">
<meta name="twitter:image" content="{{ $image }}">
@if (config('portfolio.seo.twitter_handle'))
    <meta name="twitter:site" content="{{ config('portfolio.seo.twitter_handle') }}">
@endif

{{-- Police auto-hébergée : préchargée pour éviter un rendu en deux temps du titre principal. --}}
<link
    rel="preload"
    href="{{ asset('fonts/plus-jakarta-sans-latin-wght-normal.woff2') }}"
    as="font"
    type="font/woff2"
    crossorigin
>

<link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
<link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
