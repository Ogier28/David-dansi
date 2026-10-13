<?php

namespace App\Providers;

use App\Support\Portfolio;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Le contenu est lu une fois par requête, pas une fois par section.
        $this->app->singleton(Portfolio::class);

        // Nonce de la politique de sécurité du contenu : résolu paresseusement,
        // donc identique pour l'en-tête HTTP et pour les balises de la page.
        $this->app->singleton('csp.nonce', fn (): string => Str::random(24));
    }

    public function boot(): void
    {
        // En production, les URL générées suivent le schéma d'APP_URL : les
        // liens canoniques et le plan de site restent en HTTPS derrière un proxy.
        if ($this->app->isProduction()) {
            URL::forceHttps();
        }

        // Les balises produites par @vite portent le nonce de la CSP.
        Vite::useCspNonce(csp_nonce());

        $this->configureRateLimiting();
    }

    /**
     * Limiteurs de débit.
     *
     * `contact` protège l'envoi du formulaire. `public` freine l'aspiration
     * du site tout en restant très au-dessus du rythme d'un robot
     * d'indexation légitime.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('contact', fn (Request $request): array => [
            Limit::perMinute(3)->by($request->ip()),
            Limit::perDay(20)->by($request->ip()),
        ]);

        RateLimiter::for('public', fn (Request $request): Limit => Limit::perMinute(120)->by($request->ip()));
    }
}
