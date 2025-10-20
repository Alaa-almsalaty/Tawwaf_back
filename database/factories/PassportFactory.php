<?php

namespace Database\Factories;

use App\Models\Passport;
use Illuminate\Database\Eloquent\Factories\Factory;

class PassportFactory extends Factory
{
    protected $model = Passport::class;

    public function definition()
    {
        return [
            'passport_number' => $this->faker->unique()->regexify('[A-Z0-9]{8}'),
            'passport_type' => $this->faker->randomElement(['regular', 'diplomatic', 'official', 'ordinary', 'other']),
            'nationality' => $this->faker->country,
            'issue_date' => $this->faker->date('Y-m-d', '-5 years'),
            'expiry_date' => $this->faker->date('Y-m-d', '+5 years'),
            'issue_place' => $this->faker->city,
            'birth_place' => $this->faker->city,
            'issue_authority' => $this->faker->company,
            'passport_img' => 'passport.jpg',
        ];
    }
}
