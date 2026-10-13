<?php

namespace Tests\Feature;

use App\Mail\ContactMessageReceived;
use App\Models\ContactMessage;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
        Mail::fake();
    }

    /**
     * Charge utile valide : le jeton de formulaire date d'une minute, donc ni
     * instantané ni périmé.
     *
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    protected function payload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Awa Koudjo',
            'email' => 'awa@exemple.com',
            'subject' => 'audit',
            'message' => 'Nous exploitons un entrepôt frigorifique de 4 000 m² et la facture a doublé en deux ans.',
            'consent' => '1',
            'website' => '',
            'form_token' => Crypt::encryptString((string) now()->subMinute()->timestamp),
        ], $overrides);
    }

    public function test_a_valid_message_is_stored_and_notified(): void
    {
        $response = $this->withoutMiddleware(ThrottleRequests::class)
            ->post('/fr/contact', $this->payload());

        $response->assertRedirect();
        $response->assertSessionHas('contact.success');

        $this->assertDatabaseHas('contact_messages', [
            'email' => 'awa@exemple.com',
            'subject' => 'audit',
            'locale' => 'fr',
        ]);

        Mail::assertSent(ContactMessageReceived::class, function (ContactMessageReceived $mail): bool {
            return $mail->hasTo(config('portfolio.contact.recipient'))
                && $mail->hasReplyTo('awa@exemple.com');
        });
    }

    public function test_the_visitor_language_is_recorded_with_the_message(): void
    {
        $this->withoutMiddleware(ThrottleRequests::class)
            ->post('/en/contact', $this->payload());

        $this->assertSame('en', ContactMessage::sole()->locale);
    }

    public function test_a_filled_honeypot_is_rejected(): void
    {
        $response = $this->withoutMiddleware(ThrottleRequests::class)
            ->post('/fr/contact', $this->payload(['website' => 'https://spam.example']));

        $response->assertSessionHasErrors('website');
        $this->assertDatabaseCount('contact_messages', 0);
        Mail::assertNothingSent();
    }

    public function test_an_instant_submission_is_rejected(): void
    {
        $response = $this->withoutMiddleware(ThrottleRequests::class)
            ->post('/fr/contact', $this->payload([
                'form_token' => Crypt::encryptString((string) now()->timestamp),
            ]));

        $response->assertSessionHasErrors('form_token');
        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_an_expired_form_is_rejected(): void
    {
        $response = $this->withoutMiddleware(ThrottleRequests::class)
            ->post('/fr/contact', $this->payload([
                'form_token' => Crypt::encryptString((string) now()->subHours(3)->timestamp),
            ]));

        $response->assertSessionHasErrors('form_token');
        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_a_forged_form_token_is_rejected(): void
    {
        $response = $this->withoutMiddleware(ThrottleRequests::class)
            ->post('/fr/contact', $this->payload(['form_token' => 'nimportequoi']));

        $response->assertSessionHasErrors('form_token');
        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_required_fields_are_validated(): void
    {
        $response = $this->withoutMiddleware(ThrottleRequests::class)
            ->post('/fr/contact', [
                'name' => '',
                'email' => 'pas-une-adresse',
                'subject' => 'inexistant',
                'message' => 'trop court',
                'form_token' => Crypt::encryptString((string) now()->subMinute()->timestamp),
            ]);

        $response->assertSessionHasErrors(['name', 'email', 'subject', 'message', 'consent']);
        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_consent_is_mandatory(): void
    {
        $this->withoutMiddleware(ThrottleRequests::class)
            ->post('/fr/contact', $this->payload(['consent' => null]))
            ->assertSessionHasErrors('consent');
    }

    public function test_submissions_are_rate_limited(): void
    {
        for ($attempt = 0; $attempt < 3; $attempt++) {
            $this->post('/fr/contact', $this->payload())->assertRedirect();
        }

        $this->post('/fr/contact', $this->payload())->assertTooManyRequests();
        $this->assertDatabaseCount('contact_messages', 3);
    }

    public function test_turnstile_is_verified_when_keys_are_configured(): void
    {
        config()->set('services.turnstile.secret', 'cle-secrete');
        Http::fake(['challenges.cloudflare.com/*' => Http::response(['success' => true])]);

        $this->withoutMiddleware(ThrottleRequests::class)
            ->post('/fr/contact', $this->payload(['cf-turnstile-response' => 'jeton-valide']))
            ->assertSessionHas('contact.success');

        Http::assertSent(fn ($request): bool => $request['secret'] === 'cle-secrete'
            && $request['response'] === 'jeton-valide');
    }

    public function test_a_refused_turnstile_token_blocks_the_message(): void
    {
        config()->set('services.turnstile.secret', 'cle-secrete');
        Http::fake(['challenges.cloudflare.com/*' => Http::response(['success' => false, 'error-codes' => ['invalid-input-response']])]);

        $this->withoutMiddleware(ThrottleRequests::class)
            ->post('/fr/contact', $this->payload(['cf-turnstile-response' => 'jeton-invalide']))
            ->assertSessionHasErrors('cf-turnstile-response');

        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_a_missing_turnstile_token_blocks_the_message(): void
    {
        config()->set('services.turnstile.secret', 'cle-secrete');
        Http::fake();

        $this->withoutMiddleware(ThrottleRequests::class)
            ->post('/fr/contact', $this->payload())
            ->assertSessionHasErrors('cf-turnstile-response');

        Http::assertNothingSent();
    }

    public function test_the_message_survives_a_mail_failure(): void
    {
        Mail::shouldReceive('to->send')->andThrow(new \RuntimeException('SMTP indisponible'));

        $response = $this->withoutMiddleware(ThrottleRequests::class)
            ->post('/fr/contact', $this->payload());

        $response->assertSessionHas('contact.failure');
        $this->assertDatabaseCount('contact_messages', 1);
    }

    public function test_the_web_routes_are_covered_by_csrf_protection(): void
    {
        /*
         * Laravel neutralise la vérification du jeton pendant les tests : on
         * vérifie donc que le middleware est bien monté sur le groupe « web »,
         * dont relève la route du formulaire.
         */
        $web = app(Kernel::class)->getMiddlewareGroups()['web'];

        $this->assertContains(PreventRequestForgery::class, $web);
        $this->assertContains(EncryptCookies::class, $web);
    }
}
