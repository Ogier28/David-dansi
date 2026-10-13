<?php

namespace App\Support;

use Illuminate\Http\Request;

/**
 * Routes localisées.
 *
 * Chaque page est enregistrée une fois par langue, sous un nom préfixé par le
 * code de langue (`fr.projects.index`, `en.projects.index`). Cette classe
 * résout ces noms, produit les URL de la langue courante et calcule les
 * équivalences entre langues pour le sélecteur et les balises hreflang.
 */
class Localization
{
    /**
     * Cookie mémorisant la langue choisie (un an).
     */
    public const COOKIE = 'portfolio_locale';

    public const COOKIE_MINUTES = 60 * 24 * 365;

    /**
     * @return array<int, string>
     */
    public static function available(): array
    {
        return array_keys(config('portfolio.locales'));
    }

    public static function fallback(): string
    {
        return static::available()[0];
    }

    public static function supports(?string $locale): bool
    {
        return $locale !== null && in_array($locale, static::available(), true);
    }

    /**
     * @return array{name: string, short: string, html: string, og: string}
     */
    public static function meta(?string $locale = null): array
    {
        $locale = $locale ?? app()->getLocale();

        return config("portfolio.locales.{$locale}", config('portfolio.locales.'.static::fallback()));
    }

    /**
     * Segment d'URL localisé, par exemple `realisations` ou `projects`.
     */
    public static function slug(string $key, ?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();

        return config("portfolio.route_slugs.{$locale}.{$key}");
    }

    public static function routeName(string $name, ?string $locale = null): string
    {
        return ($locale ?? app()->getLocale()).'.'.$name;
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public static function route(string $name, array $parameters = [], ?string $locale = null, bool $absolute = true): string
    {
        return route(static::routeName($name, $locale), $parameters, $absolute);
    }

    /**
     * Nom de la route courante débarrassé de son préfixe de langue.
     */
    public static function baseRouteName(): ?string
    {
        $name = request()->route()?->getName();

        if ($name === null) {
            return null;
        }

        foreach (static::available() as $locale) {
            if (str_starts_with($name, $locale.'.')) {
                return substr($name, strlen($locale) + 1);
            }
        }

        return null;
    }

    /**
     * URL équivalente de la page courante dans chaque langue.
     *
     * Les slugs de projet étant traduits, la correspondance passe par le
     * contenu et non par un simple remplacement de préfixe.
     *
     * @return array<string, string>
     */
    public static function alternates(): array
    {
        $base = static::baseRouteName();
        $current = app()->getLocale();
        $parameters = request()->route()?->parameters() ?? [];

        $urls = [];

        foreach (static::available() as $locale) {
            if ($base === null) {
                $urls[$locale] = static::route('home', [], $locale);

                continue;
            }

            $localeParameters = $parameters;

            if ($base === 'projects.show' && isset($parameters['project'])) {
                $translated = app(Portfolio::class)
                    ->translateProjectSlug($parameters['project'], $current, $locale);

                if ($translated === null) {
                    $urls[$locale] = static::route('projects.index', [], $locale);

                    continue;
                }

                $localeParameters['project'] = $translated;
            }

            $urls[$locale] = static::route($base, $localeParameters, $locale);
        }

        return $urls;
    }

    /**
     * Langue à servir à un visiteur arrivant sur `/` : choix mémorisé d'abord,
     * puis négociation avec les préférences du navigateur.
     */
    public static function negotiate(Request $request): string
    {
        $remembered = $request->cookie(static::COOKIE);

        if (static::supports($remembered)) {
            return $remembered;
        }

        $preferred = $request->getPreferredLanguage(static::available());

        return static::supports($preferred) ? $preferred : static::fallback();
    }
}
