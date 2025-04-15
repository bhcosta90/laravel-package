<?php

declare(strict_types = 1);

namespace Database\Factories;

use App\Models\{Customer, Order};
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory()->create(),
            'type'        => $this->faker->randomElement(['A', 'B', 'C']),
        ];
    }
}
