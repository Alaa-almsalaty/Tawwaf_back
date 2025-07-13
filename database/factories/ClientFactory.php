<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\PersonalInfo;
use App\Models\Branch;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    protected $model = Client::class;

    public function definition()
    {
        return [
            'is_family_master' => $this->faker->boolean,
            'register_date' => $this->faker->date('Y-m-d', '-1 years'),
            'register_state' => $this->faker->randomElement(['pending', 'completed']),
            'branch_id' => Branch::factory(),
            'tenant_id' => Tenant::factory(),
            'personal_info_id' => PersonalInfo::factory(),
            'family_id' => null,
            'MuhramID' => null,
            'Muhram_relation' => null,
            'note' => $this->faker->sentence,
        ];
    }
}
