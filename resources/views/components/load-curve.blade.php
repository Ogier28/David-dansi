@php
    /*
     * Courbe de charge d'un site tertiaire sur 24 heures, avant et après
     * optimisation. Les valeurs sont en kilowatts appelés ; modifier les deux
     * tableaux suffit à redessiner le graphique.
     */
    $before = [320, 310, 330, 420, 720, 890, 760, 830, 800, 600, 420, 350, 320];
    $after = [150, 140, 150, 260, 480, 540, 470, 520, 500, 360, 230, 170, 150];

    $left = 20;
    $right = 780;
    $baseline = 240;
    $scale = 0.2; // 1 000 kW occupent 200 unités de hauteur

    $toPoints = function (array $values) use ($left, $right, $baseline, $scale): array {
        $step = ($right - $left) / (count($values) - 1);

        return array_map(fn (int $value, int $index): array => [
            round($left + $index * $step, 2),
            round($baseline - $value * $scale, 2),
        ], $values, array_keys($values));
    };

    /*
     * Lissage cubique à tangentes horizontales : la courbe passe exactement par
     * chaque relevé, sans dépassement au-dessus des pointes.
     */
    $toPath = function (array $points): string {
        $path = 'M '.$points[0][0].' '.$points[0][1];

        for ($i = 0; $i < count($points) - 1; $i++) {
            [$x1, $y1] = $points[$i];
            [$x2, $y2] = $points[$i + 1];
            $handle = round(($x2 - $x1) / 3, 2);

            $path .= ' C '.($x1 + $handle).' '.$y1.', '.($x2 - $handle).' '.$y2.', '.$x2.' '.$y2;
        }

        return $path;
    };

    $beforePoints = $toPoints($before);
    $afterPoints = $toPoints($after);

    $beforePath = $toPath($beforePoints);
    $afterPath = $toPath($afterPoints);
    $afterArea = $afterPath.' L '.$right.' '.$baseline.' L '.$left.' '.$baseline.' Z';

    $hours = [0, 6, 12, 18, 24];
    $peakBefore = $beforePoints[5];
    $peakAfter = $afterPoints[5];

    $chartId = 'load-curve-'.app()->getLocale();
@endphp

<figure class="relative">
    <x-ui.glass class="overflow-hidden p-5 sm:p-7">
        <figcaption class="mb-5 flex flex-wrap items-start justify-between gap-x-6 gap-y-3">
            <p class="max-w-xs text-[0.8125rem] leading-snug text-ink-muted">
                {{ __('home.hero.chart.caption') }}
            </p>

            <div class="flex items-center gap-5 text-[0.8125rem]">
                <span class="flex items-center gap-2 text-ink-faint">
                    <svg width="16" height="4" aria-hidden="true" class="shrink-0">
                        <line x1="0" y1="2" x2="16" y2="2" stroke="currentColor" stroke-width="2" stroke-dasharray="4 3" />
                    </svg>
                    {{ __('home.hero.chart.before') }}
                </span>
                <span class="flex items-center gap-2 text-gold-soft">
                    <svg width="16" height="4" aria-hidden="true" class="shrink-0">
                        <line x1="0" y1="2" x2="16" y2="2" stroke="currentColor" stroke-width="2.5" />
                    </svg>
                    {{ __('home.hero.chart.after') }}
                </span>
            </div>
        </figcaption>

        <svg
            viewBox="0 0 800 280"
            class="h-auto w-full"
            role="img"
            aria-labelledby="{{ $chartId }}-title"
            aria-describedby="{{ $chartId }}-desc"
        >
            <title id="{{ $chartId }}-title">{{ __('home.hero.chart.caption') }}</title>

            <defs>
                <linearGradient id="{{ $chartId }}-fill" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0%" stop-color="#F5C518" stop-opacity="0.22" />
                    <stop offset="100%" stop-color="#F5C518" stop-opacity="0" />
                </linearGradient>
            </defs>

            {{-- Graduations horizontales : repères de lecture, pas décor --}}
            @foreach ([40, 90, 140, 190] as $y)
                <line x1="{{ $left }}" y1="{{ $y }}" x2="{{ $right }}" y2="{{ $y }}" stroke="currentColor" stroke-width="1" class="text-white/6" />
            @endforeach

            <line x1="{{ $left }}" y1="{{ $baseline }}" x2="{{ $right }}" y2="{{ $baseline }}" stroke="currentColor" stroke-width="1" class="text-white/15" />

            @foreach ($hours as $hour)
                @php $x = round($left + ($hour / 24) * ($right - $left), 2); @endphp
                <line x1="{{ $x }}" y1="{{ $baseline }}" x2="{{ $x }}" y2="{{ $baseline + 6 }}" stroke="currentColor" stroke-width="1" class="text-white/25" />
                <text x="{{ $x }}" y="{{ $baseline + 22 }}" text-anchor="middle" class="fill-ink-faint text-[11px]">{{ $hour }} h</text>
            @endforeach

            {{-- Profil avant optimisation --}}
            <path d="{{ $beforePath }}" fill="none" stroke="#4A6585" stroke-width="2" stroke-dasharray="6 5" stroke-linecap="round" />

            {{-- Profil après optimisation : aire puis tracé animé --}}
            <path d="{{ $afterArea }}" fill="url(#{{ $chartId }}-fill)" data-draw-fill />
            <path
                d="{{ $afterPath }}"
                fill="none"
                stroke="#F5C518"
                stroke-width="2.75"
                stroke-linecap="round"
                stroke-linejoin="round"
                data-draw-path
            />

            {{-- Annotation de la pointe du matin, seul commentaire porté par le graphique --}}
            <g data-draw-fill>
                <line
                    x1="{{ $peakBefore[0] }}" y1="{{ $peakBefore[1] }}"
                    x2="{{ $peakAfter[0] }}" y2="{{ $peakAfter[1] }}"
                    stroke="currentColor" stroke-width="1" stroke-dasharray="3 3" class="text-white/30"
                />
                <circle cx="{{ $peakBefore[0] }}" cy="{{ $peakBefore[1] }}" r="3.5" fill="#4A6585" />
                <circle cx="{{ $peakAfter[0] }}" cy="{{ $peakAfter[1] }}" r="4.5" fill="#F5C518" />
                <text x="{{ $peakBefore[0] + 14 }}" y="{{ $peakBefore[1] - 8 }}" class="fill-ink text-[12px] font-semibold">890 kW</text>
                <text x="{{ $peakAfter[0] + 12 }}" y="{{ $peakAfter[1] + 30 }}" class="fill-gold text-[12px] font-semibold">540 kW</text>
            </g>
        </svg>

        <p class="mt-4 flex items-center gap-2 text-[0.8125rem] text-ink-muted">
            <x-icon name="zap" :size="15" class="shrink-0 text-gold" />
            {{ __('home.hero.chart.peak') }}
        </p>
    </x-ui.glass>

    <p id="{{ $chartId }}-desc" class="sr-only">{{ __('home.hero.chart.description') }}</p>
</figure>
