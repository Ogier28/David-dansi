<?php

namespace Tests\Feature;

use Tests\TestCase;

class SeoTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_pages_declare_a_canonical_url_and_both_languages(): void
    {
        $response = $this->get('/fr');

        $response->assertSee('<link rel="canonical" href="'.route('fr.home').'">', false);
        $response->assertSee('hreflang="fr-FR" href="'.route('fr.home').'"', false);
        $response->assertSee('hreflang="en" href="'.route('en.home').'"', false);
        $response->assertSee('hreflang="x-default"', false);
    }

    public function test_project_pages_point_at_their_translated_counterpart(): void
    {
        $this->get('/en/projects/campus-energy-audit')
            ->assertSee('hreflang="fr-FR" href="'.route('fr.projects.show', ['project' => 'audit-energetique-campus']).'"', false);
    }

    public function test_pages_carry_their_own_title_and_description(): void
    {
        $this->get('/fr')
            ->assertSee('<title>'.__('seo.home.title', [], 'fr').'</title>', false)
            ->assertSee(__('seo.home.description', [], 'fr'), false);

        $this->get('/fr/realisations')
            ->assertSee('<title>'.__('seo.projects.title', [], 'fr').'</title>', false);
    }

    public function test_pages_expose_open_graph_and_twitter_cards(): void
    {
        $this->get('/fr')
            ->assertSee('property="og:title"', false)
            ->assertSee('property="og:image"', false)
            ->assertSee('property="og:locale" content="fr_FR"', false)
            ->assertSee('property="og:locale:alternate" content="en_US"', false)
            ->assertSee('name="twitter:card" content="summary_large_image"', false);
    }

    public function test_structured_data_describes_the_person_and_the_service(): void
    {
        $response = $this->get('/fr');

        $response->assertSee('application/ld+json', false);

        preg_match('#<script type="application/ld\+json"[^>]*>(.*?)</script>#s', $response->getContent(), $matches);

        $payload = json_decode($matches[1], true);
        $types = array_column($payload['@graph'], '@type');

        $this->assertSame('https://schema.org', $payload['@context']);
        $this->assertContains('Person', $types);
        $this->assertContains('ProfessionalService', $types);
        $this->assertContains('WebSite', $types);
    }

    public function test_inner_pages_add_a_breadcrumb(): void
    {
        $response = $this->get('/fr/realisations/audit-energetique-campus');

        preg_match('#<script type="application/ld\+json"[^>]*>(.*?)</script>#s', $response->getContent(), $matches);
        $payload = json_decode($matches[1], true);

        $this->assertContains('BreadcrumbList', array_column($payload['@graph'], '@type'));
    }

    public function test_sitemap_lists_every_page_in_every_language(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/xml; charset=UTF-8');

        $xml = $response->getContent();

        foreach (['fr.home', 'en.home', 'fr.projects.index', 'en.projects.index', 'fr.legal', 'en.privacy'] as $route) {
            $this->assertStringContainsString('<loc>'.route($route).'</loc>', $xml);
        }

        foreach (config('portfolio.projects') as $project) {
            $this->assertStringContainsString(route('fr.projects.show', ['project' => $project['slug']['fr']]), $xml);
            $this->assertStringContainsString(route('en.projects.show', ['project' => $project['slug']['en']]), $xml);
        }

        $this->assertStringContainsString('xhtml:link rel="alternate" hreflang="en"', $xml);
    }

    public function test_robots_file_allows_crawling_and_points_to_the_sitemap(): void
    {
        $this->get('/robots.txt')
            ->assertOk()
            ->assertSee('User-agent: *', false)
            ->assertSee('Sitemap: '.route('sitemap'), false);
    }
}
