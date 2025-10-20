<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\PackageRoom;
use App\Models\Package;

class PackageRoomFactory extends Factory
{


    protected $model = PackageRoom::class;

    public function definition(): array
    {    $packageId = Package::inRandomOrder()->first()?->id ?? 1;

        $roomTypes = ['single', 'double', 'triple', 'quad', 'quintuple', 'sextuple', 'septuple'];

        return [
            'package_id' =>$packageId,
            'room_type' => $this->faker->randomElement($roomTypes),
            'total_price_dinar' => $this->faker->randomFloat(2, 500, 5000),
            'total_price_usd' => $this->faker->randomFloat(2, 50, 500),
        ];
    }
    }
