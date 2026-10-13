@props([
    'id' => null,
    'eyebrow' => null,
    'title' => null,
    'lead' => null,
    'width' => 'max-w-6xl',
    'tight' => false,
])

<section
    @if ($id) id="{{ $id }}" @endif
    {{ $attributes->class(['relative', $tight ? 'py-16 sm:py-20' : 'py-24 sm:py-32']) }}
>
    <div class="mx-auto w-full {{ $width }} px-6 lg:px-8">
        @if ($title || $eyebrow)
            <x-ui.heading
                :eyebrow="$eyebrow"
                :title="$title"
                :lead="$lead"
                class="mb-14 sm:mb-16"
                data-reveal
            />
        @endif

        {{ $slot }}
    </div>
</section>
