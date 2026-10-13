<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * En-têtes de sécurité HTTP appliqués à toutes les réponses.
 *
 * La politique de contenu (CSP) autorise explicitement ce dont le site a
 * besoin et rien d'autre : scripts du domaine signés par un nonce, polices et
 * images locales, et le seul tiers du projet, Cloudflare Turnstile.
 */
class SecurityHeaders
{
    /**
     * Domaine du widget anti-robot, seule origine tierce du site.
     */
    protected const TURNSTILE = 'https://challenges.cloudflare.com';

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $headers = [
            'Content-Security-Policy' => $this->contentSecurityPolicy(),
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options' => 'DENY',
            'Referrer-Policy' => 'strict-origin-when-cross-origin',
            'Permissions-Policy' => 'accelerometer=(), autoplay=(), camera=(), display-capture=(), encrypted-media=(), geolocation=(), gyroscope=(), magnetometer=(), microphone=(), midi=(), payment=(), usb=(), xr-spatial-tracking=()',
            'Cross-Origin-Opener-Policy' => 'same-origin',
            'Cross-Origin-Resource-Policy' => 'same-origin',
            'X-Permitted-Cross-Domain-Policies' => 'none',
        ];

        // HSTS seulement en HTTPS : l'envoyer en HTTP n'a aucun effet et piège le développement local.
        if ($request->secure()) {
            $headers['Strict-Transport-Security'] = 'max-age=31536000; includeSubDomains';
        }

        foreach ($headers as $header => $value) {
            $response->headers->set($header, $value, false);
        }

        return $response;
    }

    protected function contentSecurityPolicy(): string
    {
        $nonce = "'nonce-".csp_nonce()."'";

        // 'unsafe-eval' : Alpine.js compile ses expressions (x-data, x-show) à
        // l'exécution. Les scripts injectés restent bloqués, faute de nonce.
        $script = ["'self'", $nonce, "'unsafe-eval'", self::TURNSTILE];

        // 'unsafe-inline' : les attributs style= du rendu (jauges, délais
        // d'animation) sont des styles en ligne aux yeux de la CSP.
        $style = ["'self'", "'unsafe-inline'"];
        $connect = ["'self'", self::TURNSTILE];

        // Serveur de développement Vite : modules et rechargement à chaud.
        if (app()->isLocal()) {
            $vite = ['http://localhost:5173', 'http://127.0.0.1:5173'];
            $script = array_merge($script, $vite);
            $style = array_merge($style, $vite);
            $connect = array_merge($connect, $vite, ['ws://localhost:5173', 'ws://127.0.0.1:5173']);
        }

        $directives = [
            "default-src 'self'",
            'script-src '.implode(' ', $script),
            'style-src '.implode(' ', $style),
            'connect-src '.implode(' ', $connect),
            "img-src 'self' data:",
            "font-src 'self'",
            'frame-src '.self::TURNSTILE,
            "frame-ancestors 'none'",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
            "worker-src 'self'",
            "manifest-src 'self'",
        ];

        return implode('; ', $directives);
    }
}
