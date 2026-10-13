<x-layout.app :meta="$meta" :rail-sections="$railSections">
    <x-sections.hero :identity="$identity" :metrics="$metrics" />
    <x-sections.about :identity="$identity" />
    <x-sections.expertise :areas="$expertise" />
    <x-sections.projects :projects="$projects" :project-count="$projectCount" />
    <x-sections.skills :groups="$skillGroups" :certifications="$certifications" />
    <x-sections.timeline :entries="$timeline" />
    <x-sections.testimonials :testimonials="$testimonials" />
    <x-sections.contact :identity="$identity" />
</x-layout.app>
