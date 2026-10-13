<?php

namespace App\Http\Controllers;

use App\Support\Localization;
use App\Support\Portfolio;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;

/**
 * Fichiers destinés aux robots d'indexation.
 *
 * Le plan de site et robots.txt sont générés plutôt que stockés en statique :
 * les URL suivent ainsi le domaine réel et les langues déclarées en
 * configuration, sans fichier à régénérer après chaque modification.
 */
class SeoController extends Controller
{
    public function sitemap(Portfolio $portfolio): Response
    {
        $lastModified = File::lastModified(config_path('portfolio.php'));

        $pages = [
            ['name' => 'home', 'priority' => '1.0', 'changefreq' => 'monthly'],
            ['name' => 'projects.index', 'priority' => '0.9', 'changefreq' => 'monthly'],
        ];

        foreach (config('portfolio.projects') as $project) {
            $pages[] = [
                'name' => 'projects.show',
                'priority' => '0.8',
                'changefreq' => 'yearly',
                'parameters' => $project['slug'],
            ];
        }

        $pages[] = ['name' => 'legal', 'priority' => '0.2', 'changefreq' => 'yearly'];
        $pages[] = ['name' => 'privacy', 'priority' => '0.2', 'changefreq' => 'yearly'];

        $entries = [];

        foreach ($pages as $page) {
            foreach (Localization::available() as $locale) {
                $entries[] = [
                    'loc' => $this->pageUrl($page, $locale),
                    'alternates' => $this->alternates($page),
                    'priority' => $page['priority'],
                    'changefreq' => $page['changefreq'],
                ];
            }
        }

        return response()
            ->view('seo.sitemap', [
                'entries' => $entries,
                'lastModified' => date(DATE_W3C, $lastModified),
            ])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    public function robots(): Response
    {
        $lines = [
            'User-agent: *',
            'Allow: /',
            // Rien à indexer sous ces chemins, et inutile de les faire explorer.
            'Disallow: /storage/',
            'Disallow: /build/',
            '',
            'Sitemap: '.route('sitemap'),
            '',
        ];

        return response(implode("\n", $lines))
            ->header('Content-Type', 'text/plain; charset=UTF-8');
    }

    /**
     * @param  array{name: string, parameters?: array<string, string>}  $page
     */
    protected function pageUrl(array $page, string $locale): string
    {
        $parameters = isset($page['parameters'])
            ? ['project' => $page['parameters'][$locale]]
            : [];

        return Localization::route($page['name'], $parameters, $locale);
    }

    /**
     * @param  array{name: string, parameters?: array<string, string>}  $page
     * @return array<string, string>
     */
    protected function alternates(array $page): array
    {
        $alternates = [];

        foreach (Localization::available() as $locale) {
            $alternates[Localization::meta($locale)['html']] = $this->pageUrl($page, $locale);
        }

        return $alternates;
    }
}
