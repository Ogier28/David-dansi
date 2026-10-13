@props(['projects', 'projectCount' => null])

<x-ui.section
    id="projects"
    width="max-w-7xl"
    :eyebrow="__('home.projects.eyebrow')"
    :title="__('home.projects.title')"
    :lead="__('home.projects.lead')"
>
    @php
        $featured = $projects->first();
        $others = $projects->slice(1);
    @endphp

    <div class="flex flex-col gap-4">
        @if ($featured)
            <x-project-card :project="$featured" variant="large" data-reveal />
        @endif

        @if ($others->isNotEmpty())
            <div class="grid gap-4 md:grid-cols-2">
                @foreach ($others as $project)
                    <x-project-card
                        :project="$project"
                        data-reveal
                        style="--reveal-delay: {{ $loop->index * 80 }}ms"
                    />
                @endforeach
            </div>
        @endif
    </div>

    <div class="mt-10 flex flex-wrap items-center gap-4" data-reveal>
        <x-ui.button :href="localized_route('projects.index')" variant="glass">
            {{ __('site.actions.all_projects') }}
        </x-ui.button>

        @if ($projectCount)
            <p class="text-sm text-ink-faint">{{ trans_choice('projects.index.count', $projectCount, ['count' => $projectCount]) }}</p>
        @endif
    </div>
</x-ui.section>
