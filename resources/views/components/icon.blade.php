@props([
    'name',
    'size' => 20,
    'stroke' => 1.6,
])

{{-- Tracés SVG en ligne (bibliothèque Lucide) : pas de police d'icônes, pas de requête supplémentaire. --}}
<svg
    xmlns="http://www.w3.org/2000/svg"
    width="{{ $size }}"
    height="{{ $size }}"
    viewBox="0 0 24 24"
    fill="none"
    stroke="currentColor"
    stroke-width="{{ $stroke }}"
    stroke-linecap="round"
    stroke-linejoin="round"
    aria-hidden="true"
    focusable="false"
    {{ $attributes }}
>
    @switch($name)
        @case('gauge')
            <path d="m12 14 4-4" /><path d="M3.34 19a10 10 0 1 1 17.32 0" />
            @break

        @case('activity')
            <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
            @break

        @case('sun')
            <circle cx="12" cy="12" r="4" />
            <path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41" />
            @break

        @case('cube')
            <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z" />
            <path d="m3.3 7 8.7 5 8.7-5" /><path d="M12 22V12" />
            @break

        @case('clipboard')
            <rect width="8" height="4" x="8" y="2" rx="1" />
            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2" />
            <path d="m9 14 2 2 4-4" />
            @break

        @case('compass')
            <circle cx="12" cy="12" r="10" />
            <path d="m16.24 7.76-2.12 6.36-6.36 2.12 2.12-6.36z" />
            @break

        @case('zap')
            <path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z" />
            @break

        @case('menu')
            <path d="M4 6h16M4 12h16M4 18h16" />
            @break

        @case('close')
            <path d="M18 6 6 18M6 6l12 12" />
            @break

        @case('chevron-down')
            <path d="m6 9 6 6 6-6" />
            @break

        @case('arrow-right')
            <path d="M5 12h14M12 5l7 7-7 7" />
            @break

        @case('arrow-left')
            <path d="M19 12H5M12 19l-7-7 7-7" />
            @break

        @case('arrow-up-right')
            <path d="M7 17 17 7M7 7h10v10" />
            @break

        @case('check')
            <path d="M20 6 9 17l-5-5" />
            @break

        @case('globe')
            <circle cx="12" cy="12" r="10" />
            <path d="M12 2a15 15 0 0 1 0 20 15 15 0 0 1 0-20M2 12h20" />
            @break

        @case('mail')
            <rect width="20" height="16" x="2" y="4" rx="2" />
            <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
            @break

        @case('phone')
            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
            @break

        @case('map-pin')
            <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z" />
            <circle cx="12" cy="10" r="3" />
            @break

        @case('clock')
            <circle cx="12" cy="12" r="10" /><path d="M12 6v6l4 2" />
            @break

        @case('linkedin')
            <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-4 0v7h-4V8h4v1.5A4 4 0 0 1 16 8z" />
            <rect width="4" height="12" x="2" y="9" /><circle cx="4" cy="4" r="2" />
            @break

        @case('briefcase')
            <rect width="20" height="14" x="2" y="7" rx="2" />
            <path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2" />
            @break

        @case('graduation')
            <path d="M22 10v6M2 10l10-5 10 5-10 5z" />
            <path d="M6 12v5c3 2.5 9 2.5 12 0v-5" />
            @break

        @case('award')
            <circle cx="12" cy="8" r="6" />
            <path d="M15.48 12.89 17 22l-5-3-5 3 1.52-9.11" />
            @break

        @case('download')
            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3" />
            @break

        @case('ruler')
            <path d="M3 8h18v8H3z" /><path d="M7 8v3M11 8v4M15 8v3M19 8v4" />
            @break
    @endswitch
</svg>
