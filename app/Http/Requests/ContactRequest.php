<?php

namespace App\Http\Requests;

use App\Rules\TurnstileToken;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

/**
 * Validation du formulaire de contact.
 *
 * Quatre protections se cumulent ici, en plus du jeton CSRF de Laravel et du
 * limiteur de débit porté par la route :
 *
 *  1. honeypot `website`, invisible et donc toujours vide chez un humain ;
 *  2. jeton de formulaire chiffré, qui datent l'affichage du formulaire et
 *     permet de rejeter une soumission instantanée ou périmée ;
 *  3. Cloudflare Turnstile, quand les clés sont configurées ;
 *  4. validation stricte des champs, longueurs comprises.
 */
class ContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'min:2', 'max:120'],
            'email' => ['required', 'string', 'email:rfc', 'max:190'],
            'subject' => ['required', 'string', Rule::in(config('portfolio.contact.subjects'))],
            'message' => ['required', 'string', 'min:20', 'max:4000'],
            'consent' => ['accepted'],
            'form_token' => ['required', 'string'],
            // Honeypot : un champ vide est accepté, un champ rempli non.
            'website' => ['prohibited'],
        ];

        if (filled(config('services.turnstile.secret'))) {
            $rules['cf-turnstile-response'] = ['required', 'string', new TurnstileToken($this->ip())];
        }

        return $rules;
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'website.prohibited' => __('contact.errors.spam', ['email' => config('portfolio.contact.recipient')]),
            'cf-turnstile-response.required' => __('contact.errors.captcha'),
        ];
    }

    /**
     * Contrôle du temps passé sur le formulaire, une fois les champs validés.
     *
     * @return array<int, callable>
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if ($validator->errors()->has('form_token')) {
                    return;
                }

                $issuedAt = $this->formIssuedAt();

                if ($issuedAt === null) {
                    $validator->errors()->add('form_token', __('contact.errors.expired'));

                    return;
                }

                $elapsed = now()->timestamp - $issuedAt;

                if ($elapsed < config('portfolio.contact.min_delay_seconds')) {
                    $validator->errors()->add('form_token', __('contact.errors.too_fast'));

                    return;
                }

                if ($elapsed > config('portfolio.contact.max_age_seconds')) {
                    $validator->errors()->add('form_token', __('contact.errors.expired'));
                }
            },
        ];
    }

    /**
     * Horodatage d'affichage du formulaire, ou null si le jeton est illisible.
     */
    protected function formIssuedAt(): ?int
    {
        try {
            $timestamp = Crypt::decryptString($this->string('form_token')->toString());
        } catch (DecryptException) {
            return null;
        }

        return ctype_digit($timestamp) ? (int) $timestamp : null;
    }
}
