<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Package;
use App\Models\Hotel;
use App\Models\Tenant;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class PackageFactory extends Factory
{



        protected $model = Package::class;

    public function definition(): array
    {

        $tenantId = Tenant::inRandomOrder()->first()?->id ?? 1;

        return [
            'package_name' => $this->faker->words(3, true),
            'package_type' => $this->faker->randomElement(['Basic', 'Premium', 'VIP']),
            'description' => $this->faker->sentence(),
            'features' => $this->faker->sentence(5),
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->date(),
            'MKduration' => $this->faker->numberBetween(1, 14),
            'MDduration' => $this->faker->numberBetween(1, 14),
            'total_price_dinar' => $this->faker->randomFloat(2, 100, 5000),
            'total_price_usd' => $this->faker->randomFloat(2, 50, 2000),
            'currency' => $this->faker->randomElement(['dinar', 'usd']),
            'season' => $this->faker->randomElement(['Umrah','Hajj','Ramadan','Eid','Normal']),
            'status' => $this->faker->randomElement(['active','inactive']),
            'note' => $this->faker->optional()->sentence(),
            'tenant_id' => $tenantId,
            'MKHotel' => null,
            'MDHotel' => null,
        ];
    }
}
