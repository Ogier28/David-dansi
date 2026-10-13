<?php

namespace Tests\Feature;

use App\Support\Portfolio;
use Tests\TestCase;

class ProjectPageTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_index_lists_every_project_and_every_category(): void
    {
        $portfolio = app(Portfolio::class);

        $response = $this->get('/fr/realisations');

        $response->assertOk();

        foreach ($portfolio->projects() as $project) {
            $response->assertSee($project['title']);
        }

        foreach ($portfolio->projectCategories() as $category) {
            $response->assertSee($category['label']);
        }
    }

    public function test_detail_page_shows_the_full_case_study(): void
    {
        $project = app(Portfolio::class)->findProjectBySlug('audit-energetique-campus', 'fr');

        $response = $this->get('/fr/realisations/audit-energetique-campus');

        $response->assertOk();
        $response->assertSee($project['title']);
        $response->assertSee($project['context']);
        $response->assertSee($project['approach']);
        $response->assertSee($project['results']);
        $response->assertSee($project['result_items'][0]);
        $response->assertSee($project['client']);
    }

    public function test_detail_page_links_to_the_neighbouring_projects(): void
    {
        $neighbours = app(Portfolio::class)->projectNeighbours('campus_audit');

        $this->get('/fr/realisations/audit-energetique-campus')
            ->assertSee($neighbours['next']['title'])
            ->assertSee($neighbours['previous']['title']);
    }

    public function test_unknown_slug_returns_a_not_found_response(): void
    {
        $this->get('/fr/realisations/projet-inconnu')->assertNotFound();
    }

    public function test_a_slug_from_another_language_is_not_served(): void
    {
        $this->get('/fr/realisations/campus-energy-audit')->assertNotFound();
        $this->get('/en/projects/audit-energetique-campus')->assertNotFound();
    }

    public function test_every_project_is_reachable_in_both_languages(): void
    {
        foreach (config('portfolio.projects') as $project) {
            $this->get('/fr/realisations/'.$project['slug']['fr'])->assertOk();
            $this->get('/en/projects/'.$project['slug']['en'])->assertOk();
        }
    }
}
