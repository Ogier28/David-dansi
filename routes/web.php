<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LegalController;
use App\Http\Controllers\LocaleEntryController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SeoController;
use App\Support\Localization;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes publiques
|--------------------------------------------------------------------------
|
| Chaque page est enregistrée une fois par langue, avec ses propres segments
| d'URL (/fr/realisations, /en/projects) et sous un nom de route préfixé par
| le code de langue. Le helper localized_route() cible la bonne variante.
|
*/

// La racine ne sert aucun contenu : elle négocie la langue puis redirige.
Route::get('/', LocaleEntryController::class)->name('locale.redirect');

Route::get('sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');
Route::get('robots.txt', [SeoController::class, 'robots'])->name('robots');

foreach (Localization::available() as $locale) {
    Route::prefix($locale)
        ->name($locale.'.')
        ->middleware('throttle:public')
        ->group(function () use ($locale): void {
            $projects = Localization::slug('projects', $locale);

            Route::get('/', HomeController::class)->name('home');

            Route::get($projects, [ProjectController::class, 'index'])->name('projects.index');
            Route::get($projects.'/{project}', [ProjectController::class, 'show'])->name('projects.show');

            Route::get(Localization::slug('legal', $locale), [LegalController::class, 'notice'])->name('legal');
            Route::get(Localization::slug('privacy', $locale), [LegalController::class, 'privacy'])->name('privacy');

            Route::post(Localization::slug('contact', $locale), ContactController::class)
                ->middleware('throttle:contact')
                ->name('contact.store');
        });
}
