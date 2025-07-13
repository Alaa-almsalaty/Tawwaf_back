<?php

namespace Database\Factories;

use App\Models\PersonalInfo;
use App\Models\Passport;
use Illuminate\Database\Eloquent\Factories\Factory;

class PersonalInfoFactory extends Factory
{
    protected $model = PersonalInfo::class;

    public function definition()
    {
        return [
            'first_name_ar' => 'أحمد',
            'first_name_en' => $this->faker->firstName,
            'second_name_ar' => 'محمد',
            'second_name_en' => $this->faker->firstName,
            'grand_father_name_ar' => 'علي',
            'grand_father_name_en' => $this->faker->firstName,
            'last_name_ar' => 'سعيد',
            'last_name_en' => $this->faker->lastName,
            'DOB' => $this->faker->date('Y-m-d', '-30 years'),
            'family_status' => $this->faker->randomElement(['single', 'married', 'divorced', 'widowed']),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'medical_status' => $this->faker->randomElement(['healthy', 'sick', 'disabled']),
            'phone' => $this->faker->phoneNumber,
            'passport_no' => Passport::factory(),
        ];
    }
}
