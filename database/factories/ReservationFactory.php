<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\PersonalInfo;
use App\Models\Branch;
use App\Models\Reservation;
use App\Models\Package;
use App\Models\PackageRoom;
use App\Models\Family;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationFactory extends Factory
{
    protected $model = Reservation::class;

    public function definition()
    {
        $visitor = User::inRandomOrder()->where('role', 'visitor')->first();
        $package = Package::inRandomOrder()->first();
        $packageRoomId = PackageRoom::inRandomOrder()->first()?->id ?? 1;

        return [
            'visitor_id' => $visitor ?->id ?? User::factory()->state(['role' => 'visitor']),
            'package_id' => $package ?->id ??Package::factory(),
            'extra_services' => $this->faker->randomElement(['transportation', 'guide', 'meal']),
            'has_transportation' => $this->faker->boolean,
            'package_room_id' => $packageRoomId,
            'has_ticket' => $this->faker->boolean,
            'number_of_travelers' => $this->faker->numberBetween(1, 10),
            'created_by' => null, // You can set this to a user ID if needed
            'reservation_date' => $this->faker->date('Y-m-d', '+1 year'),
            'reservation_state' => $this->faker->randomElement(['pending', 'confirmed', 'cancelled', 'completed', 'sent', 'delivered']),
            'note' => $this->faker->sentence,
        ];
    }
}
