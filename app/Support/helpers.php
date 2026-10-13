<?php

use App\Support\Localization;

if (! function_exists('localized_route')) {
    /**
     * URL d'une route dans la langue courante, ou dans la langue demandée.
     *
     * `localized_route('projects.index')` cible `fr.projects.index` ou
     * `en.projects.index` selon la langue active.
     *
     * @param  array<string, mixed>  $parameters
     */
    function localized_route(string $name, array $parameters = [], ?string $locale = null, bool $absolute = true): string
    {
        return Localization::route($name, $parameters, $locale, $absolute);
    }
}

if (! function_exists('csp_nonce')) {
    /**
     * Nonce de la politique de sécurité du contenu, constant sur la requête.
     */
    function csp_nonce(): string
    {
        return app('csp.nonce');
    }
}
