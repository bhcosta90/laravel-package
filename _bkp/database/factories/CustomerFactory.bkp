<?php

declare(strict_types = 1);

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
        ];
    }

    public function principal(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'principal',
            ];
        });
    }
}
