<?php

namespace App\Http\Middleware;

use App\Support\Localization;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

/**
 * Applique la langue portée par le premier segment de l'URL.
 *
 * Ce middleware est global et non attaché aux routes : il s'exécute donc
 * aussi quand aucune route ne correspond, ce qui permet de servir la page 404
 * dans la langue demandée par l'URL.
 */
class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->segment(1);

        if (! Localization::supports($locale)) {
            return $next($request);
        }

        app()->setLocale($locale);

        /*
         * Le cookie est mis en file AVANT le reste de la chaîne : c'est le
         * groupe « web » qui l'attache à la réponse et le chiffre, et il le
         * fait au retour de $next(). Les cookies entrants n'étant pas encore
         * déchiffrés à ce stade, la valeur est réécrite à chaque vue plutôt
         * que comparée à l'existante.
         */
        Cookie::queue(
            Localization::COOKIE,
            $locale,
            Localization::COOKIE_MINUTES,
            sameSite: 'lax',
        );

        return $next($request);
    }
}
