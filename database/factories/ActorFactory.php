<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Actor>
 */
class ActorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'       => $this->faker->name(),
            'birthdate'  => $this->faker->randomElement(['19BBY', '41.9BBY', '58ABY', 'unknown']),
            'gender'     => $this->faker->randomElement(['male', 'female', 'n/a', 'unknown']),
            'height'     => (string) $this->faker->numberBetween(150, 220),
            'mass'       => (string) $this->faker->numberBetween(50, 120),
            'hair_color' => $this->faker->safeColorName(),
            'skin_color' => $this->faker->safeColorName(),
            'eye_color'  => $this->faker->safeColorName(),
            'swapi_url'  => $this->faker->unique()->url(),
        ];
    }
}
