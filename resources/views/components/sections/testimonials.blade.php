@props(['testimonials'])

@if ($testimonials !== [])
    <x-ui.section
        id="testimonials"
        width="max-w-7xl"
        :eyebrow="__('home.testimonials.eyebrow')"
        :title="__('home.testimonials.title')"
    >
        <div class="grid gap-4 lg:grid-cols-3">
            @foreach ($testimonials as $testimonial)
                <x-ui.glass
                    as="figure"
                    class="flex flex-col p-7"
                    data-reveal
                    style="--reveal-delay: {{ $loop->index * 80 }}ms"
                >
                    {{-- Guillemet typographique plutôt qu'une icône : c'est de la citation, pas de l'ornement. --}}
                    <span class="mb-2 block font-bold leading-[0.6] text-gold/25" style="font-size: 3.5rem" aria-hidden="true">&ldquo;</span>

                    <blockquote class="text-[0.9375rem] leading-relaxed text-ink-muted">
                        {{ $testimonial['quote'] }}
                    </blockquote>

                    <figcaption class="mt-6 border-t border-white/8 pt-5">
                        <p class="text-[0.9375rem] font-semibold text-ink">{{ $testimonial['name'] }}</p>
                        <p class="mt-0.5 text-[0.8125rem] text-ink-faint">{{ $testimonial['role'] }}</p>
                    </figcaption>
                </x-ui.glass>
            @endforeach
        </div>
    </x-ui.section>
@endif
