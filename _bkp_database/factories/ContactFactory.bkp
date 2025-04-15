<?php

declare(strict_types = 1);

namespace Database\Factories;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    protected $model = Contact::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name,
        ];
    }

    public function principal(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'is_principal' => true,
            ];
        });
    }
}
