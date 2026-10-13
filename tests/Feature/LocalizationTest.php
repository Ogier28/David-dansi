<?php

namespace Tests\Feature;

use App\Support\Localization;
use Tests\TestCase;

class LocalizationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_root_follows_the_browser_language(): void
    {
        $this->get('/', ['Accept-Language' => 'en-GB,en;q=0.9'])
            ->assertRedirect(route('en.home'));

        $this->get('/', ['Accept-Language' => 'fr-FR,fr;q=0.9'])
            ->assertRedirect(route('fr.home'));
    }

    public function test_root_falls_back_to_the_first_configured_language(): void
    {
        $this->get('/', ['Accept-Language' => 'de-DE,de;q=0.9'])
            ->assertRedirect(route('fr.home'));
    }

    public function test_remembered_language_wins_over_the_browser(): void
    {
        $this->withCookie(Localization::COOKIE, 'en')
            ->get('/', ['Accept-Language' => 'fr-FR'])
            ->assertRedirect(route('en.home'));
    }

    public function test_visiting_a_language_remembers_it(): void
    {
        $this->get('/en')->assertCookie(Localization::COOKIE, 'en');
        $this->get('/fr')->assertCookie(Localization::COOKIE, 'fr');
    }

    public function test_each_language_has_its_own_url_segments(): void
    {
        $this->get('/fr/realisations')->assertOk();
        $this->get('/en/projects')->assertOk();

        // Les segments d'une langue ne répondent pas sous une autre.
        $this->get('/en/realisations')->assertNotFound();
        $this->get('/fr/projects')->assertNotFound();
    }

    public function test_language_switcher_points_to_the_translated_page(): void
    {
        $this->get('/fr/realisations/audit-energetique-campus')
            ->assertSee(route('en.projects.show', ['project' => 'campus-energy-audit']), false);
    }

    public function test_missing_pages_keep_the_language_of_the_url(): void
    {
        $this->get('/en/nothing-here')
            ->assertNotFound()
            ->assertSee(__('site.errors.404.title', [], 'en'));

        $this->get('/fr/rien-ici')
            ->assertNotFound()
            ->assertSee(__('site.errors.404.title', [], 'fr'));
    }

    public function test_every_translation_file_exists_in_both_languages(): void
    {
        $french = array_map(fn (string $path): string => basename($path), glob(lang_path('fr/*.php')));
        $english = array_map(fn (string $path): string => basename($path), glob(lang_path('en/*.php')));

        sort($french);
        sort($english);

        $this->assertSame($french, $english);
        $this->assertNotEmpty($french);
    }
}
