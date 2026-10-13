<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Vérifie un jeton Cloudflare Turnstile auprès de l'API de validation.
 *
 * La règle n'est ajoutée à la requête que si une clé secrète est configurée :
 * en développement, sans clés, le formulaire reste utilisable et protégé par
 * le honeypot, le délai minimum et le limiteur de débit.
 */
class TurnstileToken implements ValidationRule
{
    protected const ENDPOINT = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

    public function __construct(protected ?string $ipAddress = null) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || $value === '') {
            $fail('contact.errors.captcha')->translate();

            return;
        }

        try {
            $response = Http::asForm()
                ->timeout(8)
                ->retry(2, 200, throw: false)
                ->post(self::ENDPOINT, array_filter([
                    'secret' => config('services.turnstile.secret'),
                    'response' => $value,
                    'remoteip' => $this->ipAddress,
                ]));
        } catch (\Throwable $exception) {
            // Cloudflare injoignable : on refuse l'envoi plutôt que d'ouvrir
            // la porte, et on trace l'incident pour pouvoir le diagnostiquer.
            Log::warning('Vérification Turnstile impossible.', ['exception' => $exception->getMessage()]);

            $fail('contact.errors.captcha')->translate();

            return;
        }

        if ($response->successful() && $response->json('success') === true) {
            return;
        }

        Log::info('Jeton Turnstile refusé.', [
            'status' => $response->status(),
            'errors' => $response->json('error-codes', []),
        ]);

        $fail('contact.errors.captcha')->translate();
    }
}
