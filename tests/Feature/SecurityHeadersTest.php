<?php

namespace Tests\Feature;

use Illuminate\Cache\RateLimiter;
use Tests\TestCase;

class SecurityHeadersTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_responses_carry_the_security_headers(): void
    {
        $response = $this->get('/fr');

        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'DENY');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeader('Cross-Origin-Opener-Policy', 'same-origin');
        $this->assertStringContainsString('camera=()', $response->headers->get('Permissions-Policy'));
    }

    public function test_the_content_security_policy_locks_down_the_page(): void
    {
        $policy = $this->get('/fr')->headers->get('Content-Security-Policy');

        $this->assertStringContainsString("default-src 'self'", $policy);
        $this->assertStringContainsString("frame-ancestors 'none'", $policy);
        $this->assertStringContainsString("object-src 'none'", $policy);
        $this->assertStringContainsString("base-uri 'self'", $policy);
        $this->assertStringContainsString("form-action 'self'", $policy);
        $this->assertStringContainsString('https://challenges.cloudflare.com', $policy);

        // Aucun script en ligne non signé ne doit pouvoir s'exécuter.
        $this->assertStringNotContainsString("script-src 'self' 'unsafe-inline'", $policy);
        $this->assertMatchesRegularExpression("/script-src [^;]*'nonce-[A-Za-z0-9]+'/", $policy);
    }

    public function test_inline_structured_data_carries_the_nonce(): void
    {
        $response = $this->get('/fr');

        preg_match("/'nonce-([A-Za-z0-9]+)'/", $response->headers->get('Content-Security-Policy'), $matches);

        $response->assertSee('<script type="application/ld+json" nonce="'.$matches[1].'">', false);
    }

    public function test_the_development_server_is_not_allowed_outside_local(): void
    {
        $this->app->detectEnvironment(fn (): string => 'production');

        $policy = $this->get('/fr')->headers->get('Content-Security-Policy');

        $this->assertStringNotContainsString('localhost:5173', $policy);
    }

    public function test_public_pages_are_rate_limited(): void
    {
        $this->assertNotNull(
            app(RateLimiter::class)->limiter('public'),
            'Le limiteur « public » doit être défini.'
        );
    }
}
