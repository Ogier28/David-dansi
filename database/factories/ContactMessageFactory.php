<?php

namespace Database\Factories;

use App\Models\ContactMessage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ContactMessage>
 */
class ContactMessageFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'subject' => fake()->randomElement(config('portfolio.contact.subjects')),
            'message' => fake()->paragraph(),
            'locale' => fake()->randomElement(array_keys(config('portfolio.locales'))),
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
        ];
    }

    public function read(): static
    {
        return $this->state(fn (array $attributes): array => [
            'read_at' => now(),
        ]);
    }
}
