<?php

namespace App\View\Components;

use App\Support\Portfolio;
use Illuminate\Support\Facades\Crypt;
use Illuminate\View\Component;
use Illuminate\View\View;

/**
 * Formulaire de contact et ses protections.
 *
 * Le jeton de formulaire est un horodatage chiffré : le serveur sait quand le
 * formulaire a été affiché sans faire confiance au client, ce qui permet de
 * rejeter les soumissions instantanées et celles qui arrivent trop tard.
 */
class ContactForm extends Component
{
    public function __construct(protected Portfolio $portfolio) {}

    public function render(): View
    {
        return view('components.contact-form', [
            'subjects' => $this->portfolio->contactSubjects(),
            'formToken' => Crypt::encryptString((string) now()->timestamp),
            'turnstileSiteKey' => config('services.turnstile.site_key'),
        ]);
    }
}
