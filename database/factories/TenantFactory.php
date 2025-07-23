<?php

namespace Database\Factories;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TenantFactory extends Factory
{
    protected $model = Tenant::class;

    public function definition()
    {
        return [
            'id' => (string) Str::uuid(),
            'company_name' => $this->faker->company,
            'address' => $this->faker->address,
            'city' => $this->faker->city,
            'email' => $this->faker->unique()->safeEmail,
            'status' => $this->faker->randomElement(['trailer', 'year', 'monthly']),
            'balance' => $this->faker->randomFloat(2, 0, 10000),
            'manager_name' => $this->faker->name,
            'phone' => $this->faker->phoneNumber,
            'note' => $this->faker->sentence,
            'active' => $this->faker->boolean,
            'season' => $this->faker->numberBetween(2010, 2025),
            //'created_by' => 1, // You may update this in your tests if needed

        ];
    }

    public function withDomain(): self
{
    return $this->afterCreating(function (Tenant $tenant) {
        $tenant->domains()->create([
            'domain' => Str::slug($tenant->company_name) . '.localhost',
        ]);
    });
}

}
