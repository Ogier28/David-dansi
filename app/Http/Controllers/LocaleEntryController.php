<?php

namespace App\Http\Controllers;

use App\Support\Localization;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Point d'entrée du site.
 *
 * La racine ne sert aucun contenu : elle choisit une langue — celle mémorisée,
 * sinon celle du navigateur — et redirige vers l'accueil correspondant.
 */
class LocaleEntryController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        return redirect()->to(
            Localization::route('home', [], Localization::negotiate($request))
        );
    }
}
