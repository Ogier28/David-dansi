<?php

namespace Tests\Feature;

use App\Support\Portfolio;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_home_page_renders_every_section_in_french(): void
    {
        $response = $this->get('/fr');

        $response->assertOk();
        $response->assertSee(__('home.hero.title', [], 'fr'));
        $response->assertSee(__('home.about.title', [], 'fr'));

        foreach (['hero', 'about', 'expertise', 'projects', 'skills', 'timeline', 'testimonials', 'contact'] as $section) {
            $response->assertSee('id="'.$section.'"', false);
        }
    }

    public function test_home_page_renders_in_english(): void
    {
        $response = $this->get('/en');

        $response->assertOk();
        $response->assertSee(__('home.hero.title', [], 'en'));
        $response->assertSee('lang="en"', false);
        $response->assertDontSee(__('home.hero.title', [], 'fr'));
    }

    public function test_home_page_lists_the_featured_projects_only(): void
    {
        $featured = app(Portfolio::class)->featuredProjects();

        $response = $this->get('/fr');

        $this->assertCount(3, $featured);

        foreach ($featured as $project) {
            $response->assertSee($project['title']);
        }
    }

    public function test_disabled_sections_are_not_rendered(): void
    {
        config()->set('portfolio.sections.testimonials', false);

        $response = $this->get('/fr');

        $response->assertOk();
        $response->assertDontSee('id="testimonials"', false);
        $response->assertDontSee(__('testimonials.campus_director.quote', [], 'fr'));
    }

    public function test_contact_form_carries_its_anti_robot_fields(): void
    {
        $response = $this->get('/fr');

        $response->assertSee('name="form_token"', false);
        $response->assertSee('name="website"', false);
        $response->assertSee('name="_token"', false);
    }
}
