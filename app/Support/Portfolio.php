<?php

namespace App\Support;

use Illuminate\Support\Collection;

/**
 * Accès au contenu du portfolio.
 *
 * La structure vient de config/portfolio.php, les textes de lang/<locale>/*.php.
 * Chaque résultat est mémoïsé par langue : une page rend plusieurs fois la même
 * liste de projets, inutile de refaire la résolution des traductions.
 */
class Portfolio
{
    /** @var array<string, mixed> */
    protected array $memo = [];

    /**
     * @return array<string, mixed>
     */
    public function identity(): array
    {
        return config('portfolio.identity');
    }

    /**
     * @return array<string, array{name: string, short: string, html: string, og: string}>
     */
    public function locales(): array
    {
        return config('portfolio.locales');
    }

    public function sectionEnabled(string $key): bool
    {
        return (bool) config("portfolio.sections.{$key}", true);
    }

    /**
     * Relevés du hero, textes et format d'affichage résolus.
     *
     * @return array<int, array{key: string, value: float, decimals: int, prefix: string, suffix: string, label: string, note: string}>
     */
    public function metrics(): array
    {
        return $this->remember('metrics', function (): array {
            return array_map(fn (array $metric): array => [
                'key' => $metric['key'],
                'value' => (float) $metric['value'],
                'decimals' => $metric['decimals'] ?? 0,
                'prefix' => $metric['prefix'] ?? '',
                'suffix' => $metric['suffix'] ?? '',
                'label' => __("home.metrics.{$metric['key']}.label"),
                'note' => __("home.metrics.{$metric['key']}.note"),
            ], config('portfolio.metrics'));
        });
    }

    /**
     * @return array<int, array{key: string, icon: string, span: int, title: string, text: string, items: array<int, string>}>
     */
    public function expertise(): array
    {
        return $this->remember('expertise', function (): array {
            return array_map(fn (array $area): array => [
                'key' => $area['key'],
                'icon' => $area['icon'],
                'span' => $area['span'] ?? 1,
                'title' => __("expertise.{$area['key']}.title"),
                'text' => __("expertise.{$area['key']}.text"),
                'items' => (array) __("expertise.{$area['key']}.items"),
            ], config('portfolio.expertise'));
        });
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public function projects(): Collection
    {
        return $this->remember('projects', function (): Collection {
            return collect(config('portfolio.projects'))
                ->map(fn (array $project): array => $this->hydrateProject($project));
        });
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public function featuredProjects(): Collection
    {
        return $this->projects()->where('featured', true)->values();
    }

    /**
     * @return array<int, array{key: string, label: string}>
     */
    public function projectCategories(): array
    {
        return $this->remember('project_categories', function (): array {
            return array_map(fn (string $category): array => [
                'key' => $category,
                'label' => __("projects.categories.{$category}"),
            ], config('portfolio.project_categories'));
        });
    }

    /**
     * Retrouve un projet par son slug dans la langue donnée.
     *
     * @return array<string, mixed>|null
     */
    public function findProjectBySlug(string $slug, ?string $locale = null): ?array
    {
        $locale = $locale ?? app()->getLocale();

        $project = collect(config('portfolio.projects'))
            ->first(fn (array $project): bool => ($project['slug'][$locale] ?? null) === $slug);

        return $project ? $this->hydrateProject($project) : null;
    }

    /**
     * Slug d'un projet dans une autre langue, pour le sélecteur de langue et hreflang.
     */
    public function translateProjectSlug(string $slug, string $fromLocale, string $toLocale): ?string
    {
        $project = collect(config('portfolio.projects'))
            ->first(fn (array $project): bool => ($project['slug'][$fromLocale] ?? null) === $slug);

        return $project['slug'][$toLocale] ?? null;
    }

    /**
     * Projet précédent et suivant, dans l'ordre d'affichage, en bouclant aux extrémités.
     *
     * @return array{previous: array<string, mixed>|null, next: array<string, mixed>|null}
     */
    public function projectNeighbours(string $key): array
    {
        $projects = $this->projects()->values();
        $index = $projects->search(fn (array $project): bool => $project['key'] === $key);

        if ($index === false || $projects->count() < 2) {
            return ['previous' => null, 'next' => null];
        }

        return [
            'previous' => $projects->get(($index - 1 + $projects->count()) % $projects->count()),
            'next' => $projects->get(($index + 1) % $projects->count()),
        ];
    }

    /**
     * @return array<int, array{key: string, title: string, items: array<int, array{name: string, level: int, description: string}>}>
     */
    public function skillGroups(): array
    {
        return $this->remember('skill_groups', function (): array {
            return array_map(fn (array $group): array => [
                'key' => $group['key'],
                'title' => __("skills.groups.{$group['key']}"),
                'items' => array_map(fn (array $item): array => [
                    'name' => $item['name'],
                    'level' => (int) $item['level'],
                    'description' => __("skills.levels.{$item['level']}"),
                ], $group['items']),
            ], config('portfolio.skills.groups'));
        });
    }

    /**
     * @return array<int, array{key: string, year: int, title: string, issuer: string}>
     */
    public function certifications(): array
    {
        return $this->remember('certifications', function (): array {
            return array_map(fn (array $certification): array => [
                'key' => $certification['key'],
                'year' => $certification['year'],
                'title' => __("skills.certifications.{$certification['key']}.title"),
                'issuer' => __("skills.certifications.{$certification['key']}.issuer"),
            ], config('portfolio.skills.certifications'));
        });
    }

    /**
     * @return array<int, array{key: string, type: string, start: int, end: int|null, role: string, organisation: string, text: string}>
     */
    public function timeline(): array
    {
        return $this->remember('timeline', function (): array {
            return array_map(fn (array $entry): array => [
                'key' => $entry['key'],
                'type' => $entry['type'],
                'start' => $entry['start'],
                'end' => $entry['end'],
                'role' => __("timeline.{$entry['key']}.role"),
                'organisation' => __("timeline.{$entry['key']}.organisation"),
                'text' => __("timeline.{$entry['key']}.text"),
            ], config('portfolio.timeline'));
        });
    }

    /**
     * @return array<int, array{key: string, quote: string, name: string, role: string}>
     */
    public function testimonials(): array
    {
        if (! $this->sectionEnabled('testimonials')) {
            return [];
        }

        return $this->remember('testimonials', function (): array {
            return array_map(fn (array $testimonial): array => [
                'key' => $testimonial['key'],
                'quote' => __("testimonials.{$testimonial['key']}.quote"),
                'name' => __("testimonials.{$testimonial['key']}.name"),
                'role' => __("testimonials.{$testimonial['key']}.role"),
            ], config('portfolio.testimonials'));
        });
    }

    /**
     * Objets proposés par le formulaire de contact.
     *
     * @return array<int, array{value: string, label: string}>
     */
    public function contactSubjects(): array
    {
        return $this->remember('contact_subjects', function (): array {
            return array_map(fn (string $subject): array => [
                'value' => $subject,
                'label' => __("contact.subjects.{$subject}"),
            ], config('portfolio.contact.subjects'));
        });
    }

    /**
     * Sections affichées dans le rail de navigation, dans l'ordre de la page.
     *
     * @return array<int, array{id: string, label: string}>
     */
    public function navSections(): array
    {
        return $this->remember('nav_sections', function (): array {
            $sections = ['about', 'expertise', 'projects', 'skills', 'timeline'];

            if ($this->sectionEnabled('testimonials')) {
                $sections[] = 'testimonials';
            }

            $sections[] = 'contact';

            return array_map(fn (string $section): array => [
                'id' => $section,
                'label' => __("site.nav.{$section}"),
            ], $sections);
        });
    }

    /**
     * @param  array<string, mixed>  $project
     * @return array<string, mixed>
     */
    protected function hydrateProject(array $project): array
    {
        $locale = app()->getLocale();
        $key = $project['key'];

        return [
            'key' => $key,
            'slug' => $project['slug'][$locale] ?? reset($project['slug']),
            'slugs' => $project['slug'],
            'category' => $project['category'],
            'category_label' => __("projects.categories.{$project['category']}"),
            'year' => $project['year'],
            'featured' => $project['featured'] ?? false,
            'image' => $project['image'],
            'tags' => $project['tags'] ?? [],
            'metrics' => array_map(fn (array $metric): array => [
                'label' => __("projects.metrics.{$metric['key']}"),
                'value' => $metric['value'],
            ], $project['metrics'] ?? []),
            'title' => __("projects.items.{$key}.title"),
            'excerpt' => __("projects.items.{$key}.excerpt"),
            'client' => __("projects.items.{$key}.client"),
            'location' => __("projects.items.{$key}.location"),
            'scope' => __("projects.items.{$key}.scope"),
            'context' => __("projects.items.{$key}.context"),
            'approach' => __("projects.items.{$key}.approach"),
            'results' => __("projects.items.{$key}.results"),
            'result_items' => (array) __("projects.items.{$key}.result_items"),
        ];
    }

    /**
     * Mémoïsation par langue : changer de langue invalide naturellement l'entrée.
     */
    protected function remember(string $key, callable $resolver): mixed
    {
        $cacheKey = app()->getLocale().':'.$key;

        return $this->memo[$cacheKey] ??= $resolver();
    }
}
