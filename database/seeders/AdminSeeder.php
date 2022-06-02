<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'company_id' => 1,
            'department_id' => 1,
            'position_id' => 1,
            'fin_code' => '1234657',
            'serial_number' => '1234567',
            'serial_code' => 'AA',
            'name' => 'Murad',
            'surname' => 'Ahadli',
            'phone' => '0503791801',
            'email' => 'admin@gmail.com',
            'address' => 'sdsdsdsdfdf dfsddasds sfdfdfd',
            'password' => Hash::make('123456'),
            'role' => 1
        ]);
    }
}
