<?php

use App\Http\Middleware\SecurityHeaders;
use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Middlewares globaux : ils s'appliquent aussi aux requêtes qui ne
        // correspondent à aucune route, donc aux pages d'erreur.
        $middleware->append([
            SetLocale::class,
            SecurityHeaders::class,
        ]);

        // Derrière un proxy ou un répartiteur de charge (Render, Cloudflare),
        // les en-têtes X-Forwarded-* portent le schéma et l'adresse réels du
        // visiteur. Sans cette déclaration, les URL canoniques seraient
        // générées en http:// et le limiteur de débit verrait l'adresse du
        // proxy pour tout le monde.
        //
        // TRUSTED_PROXIES accepte « * » (tous, cas de Render) ou une liste
        // d'adresses séparées par des virgules. Vide en local : aucun proxy.
        if ($proxies = env('TRUSTED_PROXIES')) {
            $middleware->trustProxies(
                at: $proxies === '*' ? '*' : array_map('trim', explode(',', $proxies)),
            );
        }
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
