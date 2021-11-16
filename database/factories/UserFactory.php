<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'height' => $this->faker->numberBetween(145, 201),
            'weight' => $this->faker->numberBetween(67, 132),
            'eye_color' => $this->faker->randomElement(['green', 'dark_gray', 'red', 'blue', 'brown']),
            'hair_color' => $this->faker->randomElement(['black', 'green', 'white', 'red', 'blue', 'brown']),
            'stance' => $this->faker->randomElement(['orthodox', 'south-paw']),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
