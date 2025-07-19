<?php

namespace Database\Factories;

use App\Models\Family;
use App\Models\Client;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

class FamilyFactory extends Factory
{
    protected $model = Family::class;

    public function definition()
    {
        return [
            'family_master_id' => Client::factory(),
            'tenant_id' => Tenant::factory(),
            'family_name_ar' => 'سعيد',
            'family_name_en' => $this->faker->lastName,
            'family_size' => 1,
            'note' => $this->faker->sentence,
        ];
    }


}
