<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Hotel>
 */
class HotelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
      return [
            'hotel_name' => $this->faker->unique()->company(),
            'manager_name' => $this->faker->name(),
            'address' => $this->faker->address(),
            'city' => $this->faker->city(),
            'phone' => $this->faker->phoneNumber(),
            'capacity' => $this->faker->numberBetween(10, 500),
            'rooms_count' => $this->faker->numberBetween(1, 100),
            'stars' => $this->faker->randomElement(['one', 'two', 'three', 'four', 'five', 'six', 'seven']),
            'distance_from_center' => $this->faker->randomFloat(2, 0, 100),
            'note' => $this->faker->text(),
            'provider_id' => null,
        ];
    }
}
