<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'company_id' => 1,
            'department_id' => 1,
            'position_id' => 1,
            'fin_code' => '7778899',
            'serial_number' => '1122453',
            'serial_code' => 'AA',
            'name' => 'Murad',
            'surname' => 'Ahadli',
            'phone' => '0705558899',
            'email' => 'murad@ahadli.com',
            'address' => 'Sumgait',
            'password' => Hash::make('123456'),
            'role' => 0
        ];
    }
}
