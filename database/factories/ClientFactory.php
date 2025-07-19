<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\PersonalInfo;
use App\Models\Branch;
use App\Models\Tenant;
use App\Models\Family;
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


    /**
     * Add a family relation to this client
     */
    public function withFamily()
    {
        return $this->afterCreating(function (Client $client) {
            $family = Family::factory()->create([
                'tenant_id' => $client->tenant_id,
                'family_master_id' => $client->id,
            ]);

            $client->family_id = $family->id;
            $client->save();
        });
    }

    public function withFamilyMembers(int $count = 2)
{
    return $this->afterCreating(function (Client $client) use ($count) {
        $family = Family::factory()->create([
            'tenant_id' => $client->tenant_id,
            'family_master_id' => $client->id,
        ]);

        $client->family_id = $family->id;
        $client->save();

        // Create additional family members
        Client::factory($count)->create([
            'tenant_id' => $client->tenant_id,
            'branch_id' => $client->branch_id,
            'family_id' => $family->id,
        ]);
    });
}

}

