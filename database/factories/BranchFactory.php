<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

class BranchFactory extends Factory
{
    protected $model = Branch::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company . ' Branch',
            'address' => $this->faker->address,
            'city' => $this->faker->city,
            'phone' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'manager_name' => $this->faker->name,
            'tenant_id' => Tenant::factory()->withDomain(),
            'capacity' => $this->faker->numberBetween(50, 500),
            'note' => $this->faker->sentence,

        ];
    }
}
