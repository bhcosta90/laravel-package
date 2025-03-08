<?php

declare(strict_types = 1);

namespace Database\Factories;

use App\Models\Email;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmailFactory extends Factory
{
    protected $model = Email::class;

    public function definition(): array
    {
        return [
            'value' => fake()->freeEmail(),
        ];
    }
}
