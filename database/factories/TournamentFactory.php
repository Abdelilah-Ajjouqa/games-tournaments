<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tournament>
 */
class TournamentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'start_date' => fake()->date(),
            'end_date' => fake()->dateTimeBetween('+1 day', '+20 day')->format('Y-m-d'),
            'description' => fake()->paragraph()
        ];
    }
}
