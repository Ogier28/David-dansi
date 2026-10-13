@php
    $counts = collect($categories)
        ->mapWithKeys(fn (array $category): array => [
            $category['key'] => $projects->where('category', $category['key'])->count(),
        ])
        ->put('all', $projects->count())
        ->all();
@endphp

<x-layout.app
    :meta="$meta"
    :breadcrumbs="[
        ['label' => __('site.nav.home'), 'url' => localized_route('home')],
        ['label' => __('projects.index.title'), 'url' => localized_route('projects.index')],
    ]"
>
    <div class="pb-24 pt-[calc(var(--header-height)+3.5rem)] sm:pb-32 sm:pt-[calc(var(--header-height)+5rem)]">
        <div class="mx-auto w-full max-w-7xl px-6 lg:px-8">
            <x-ui.heading
                :eyebrow="__('home.projects.eyebrow')"
                :title="__('projects.index.title')"
                :lead="__('projects.index.lead')"
                level="h1"
                class="hero-enter"
            />

            <div x-data="{ filter: 'all', counts: @js($counts) }" class="mt-12">
                <div class="flex flex-wrap items-center gap-2.5" role="group" aria-label="{{ __('projects.index.filter_label') }}">
                    <button
                        type="button"
                        @click="filter = 'all'"
                        :aria-pressed="filter === 'all' ? 'true' : 'false'"
                        class="btn px-4 py-2 text-sm"
                        :class="filter === 'all' ? 'btn-gold' : 'btn-glass'"
                    >
                        {{ __('projects.index.all') }}
                        <span class="tabular text-xs opacity-70">{{ $counts['all'] }}</span>
                    </button>

                    @foreach ($categories as $category)
                        <button
                            type="button"
                            @click="filter = @js($category['key'])"
                            :aria-pressed="filter === @js($category['key']) ? 'true' : 'false'"
                            class="btn px-4 py-2 text-sm"
                            :class="filter === @js($category['key']) ? 'btn-gold' : 'btn-glass'"
                        >
                            {{ $category['label'] }}
                            <span class="tabular text-xs opacity-70">{{ $counts[$category['key']] }}</span>
                        </button>
                    @endforeach
                </div>

                <div class="mt-8 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                    @foreach ($projects as $project)
                        <div
                            x-show="filter === 'all' || filter === @js($project['category'])"
                            x-transition.opacity.duration.250ms
                        >
                            <x-project-card
                                :project="$project"
                                class="h-full"
                                data-reveal
                                style="--reveal-delay: {{ $loop->index * 60 }}ms"
                            />
                        </div>
                    @endforeach
                </div>

                <p x-show="counts[filter] === 0" x-cloak class="mt-10 text-center text-ink-muted">
                    {{ __('projects.index.empty') }}
                </p>
            </div>
        </div>
    </div>
</x-layout.app>
