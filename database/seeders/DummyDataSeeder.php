<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Department;
use App\Models\Position;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $company = Company::query()->create([
            'name' => 'Optima',
            'email' => 'contact@optima.az',
            'phone' => '+99450123102627',
            'address' => 'Aynalı Plaza'
        ]);
        $departments = [
            [
                'name' => 'IT',
                'company_id' => 1
            ],
            [
                'name' => 'Designer',
                'company_id' => 1
            ],
            [
                'name' => 'Texniki Dəstək',
                'company_id' => 1
            ],
            [
                'name' => 'Programmer',
                'company_id' => 1
            ],
            [
                'name' => 'Project Manager',
                'company_id' => 1
            ],
        ];
        foreach ($departments as $department) {
            $create = Department::query()->create($department);
        }
        $positions = [
            [
                'name' => 'L1',
                'department_id' => 1,
            ],
            [
                'name' => 'L2',
                'department_id' => 1,
            ],
            [
                'name' => 'L3',
                'department_id' => 1,
            ],
            [
                'name' => 'L1',
                'department_id' => 2,
            ],
            [
                'name' => 'L2',
                'department_id' => 2,
            ],
            [
                'name' => 'L3',
                'department_id' => 2,
            ],
            [
                'name' => 'L1',
                'department_id' => 3,
            ],
            [
                'name' => 'L2',
                'department_id' => 3,
            ],
            [
                'name' => 'L3',
                'department_id' => 3,
            ],
            [
                'name' => 'L1',
                'department_id' => 5,
            ],
            [
                'name' => 'L2',
                'department_id' => 5,
            ],
            [
                'name' => 'L3',
                'department_id' => 5,
            ],
            [
                'name' => 'L1',
                'department_id' => 4,
            ],
            [
                'name' => 'L2',
                'department_id' => 4,
            ],
            [
                'name' => 'L3',
                'department_id' => 4,
            ]

        ];
        foreach ($positions as $position) {
            $pCreate = Position::query()->create($position);
        }

        $users = [
            [
                "name" => "Əli",
                "surname" => "Qasımzadə",
                "fin_code" => "1111112",
                "serial_number" => "11111112",
                "phone" => "515555555",
                "email" => "ali.qasımzada@optima.az",
                "address" => "Aynalı Plaza",
                "company_id" => "1",
                "department_id" => "4",
                "position_id" => "11",
                "serial_code" => "AZE",
                "password" => Hash::make('optis123456')
            ],
            [
                "name" => "Nicat",
                "surname" => "Quliyev",
                "fin_code" => "1111113",
                "serial_number" => "11111113",
                "phone" => "515555556",
                "email" => "nicat.quliyev@optima.az",
                "address" => "Aynalı Plaza",
                "company_id" => "1",
                "department_id" => "4",
                "position_id" => "12",
                "serial_code" => "AZE",
                "password" => Hash::make('optis123456')
            ],
            [
                "name" => "Qismət",
                "surname" => "Hüseynov",
                "fin_code" => "1111114",
                "serial_number" => "11111114",
                "phone" => "515555557",
                "email" => "qismat.huseynov@optima.az",
                "address" => "Aynalı Plaza",
                "company_id" => "1",
                "department_id" => "4",
                "position_id" => "10",
                "serial_code" => "AZE",
                "password" => Hash::make('optis123456')
            ],
            [
                "name" => "Murad",
                "surname" => "Əhədli",
                "fin_code" => "1111115",
                "serial_number" => "11111115",
                "phone" => "515555558",
                "email" => "murad.ahadli@optima.az",
                "address" => "Aynalı Plaza",
                "company_id" => "1",
                "department_id" => "4",
                "position_id" => "10",
                "serial_code" => "AZE",
                "password" => Hash::make('optis123456')
            ],
            [
                "name" => "Tofiq",
                "surname" => "Əliyev",
                "fin_code" => "1111116",
                "serial_number" => "11111116",
                "phone" => "515555559",
                "email" => "tofig.aliyev@optima.az",
                "address" => "Aynalı Plaza",
                "company_id" => "1",
                "department_id" => "5",
                "position_id" => "15",
                "serial_code" => "AZE",
                "password" => Hash::make('optis123456')
            ],
            [
                "name" => "Bilal",
                "surname" => "Sadıqov",
                "fin_code" => "1111117",
                "serial_number" => "11111117",
                "phone" => "515555560",
                "email" => "bilal.sadigov@optima.az",
                "address" => "Aynalı Plaza",
                "company_id" => "1",
                "department_id" => "5",
                "position_id" => "15",
                "serial_code" => "AZE",
                "password" => Hash::make('optis123456')
            ],
            [
                "name" => "Murad ",
                "surname" => "Əlizadə",
                "fin_code" => "1111118",
                "serial_number" => "11111118",
                "phone" => "515555561",
                "email" => "murad.alizada@optima.az",
                "address" => "Aynalı Plaza",
                "company_id" => "1",
                "department_id" => "1",
                "position_id" => "3",
                "serial_code" => "AZE",
                "password" => Hash::make('optis123456')
            ],
            [
                "name" => "Asəf",
                "surname" => "Hacıyev ",
                "fin_code" => "1111119",
                "serial_number" => "11111119",
                "phone" => "515555562",
                "email" => "asef.haciyev@optima.az",
                "address" => "Aynalı Plaza",
                "company_id" => "1",
                "department_id" => "1",
                "position_id" => "1",
                "serial_code" => "AZE",
                "password" => Hash::make('optis123456')
            ],
            [
                "name" => "Tural",
                "surname" => "Ələsgərov ",
                "fin_code" => "1111120",
                "serial_number" => "11111120",
                "phone" => "552257948",
                "email" => "tural.alasagarov@optima.az",
                "address" => "Aynalı Plaza",
                "company_id" => "1",
                "department_id" => "1",
                "position_id" => "2",
                "serial_code" => "AZE",
                "password" => Hash::make('optis123456')
            ],
            [
                "name" => "Səid",
                "surname" => "Kərim",
                "fin_code" => "1111121",
                "serial_number" => "11111121",
                "phone" => "552257912",
                "email" => "seid.kerim@optima.az",
                "address" => "Aynalı Plaza",
                "company_id" => "1",
                "department_id" => "1",
                "position_id" => "3",
                "serial_code" => "AZE",
                "password" => Hash::make('optis123456')
            ],
            [
                "name" => "Elçin ",
                "surname" => "Həsənov  ",
                "fin_code" => "1111122",
                "serial_number" => "11111122",
                "phone" => "552250182",
                "email" => "elchin.hasanov@optima.az",
                "address" => "Aynalı Plaza",
                "company_id" => "1",
                "department_id" => "3",
                "position_id" => "8",
                "serial_code" => "AZE",
                "password" => Hash::make('optis123456')
            ],
            [
                "name" => "Nicat",
                "surname" => "Əlicanov",
                "fin_code" => "1111126",
                "serial_number" => "11111126",
                "phone" => "515555569",
                "email" => "nicar.alicanov@optima.az",
                "address" => "Aynalı Plaza",
                "company_id" => "1",
                "department_id" => "2",
                "position_id" => "6",
                "serial_code" => "AZE",
                "password" => Hash::make('optis123456')
            ],
            [
                "name" => "Fidan ",
                "surname" => "Dostəliyeva",
                "fin_code" => "1111127",
                "serial_number" => "11111127",
                "phone" => "515555570",
                "email" => "fidan.dostaliyeva@optima.za",
                "address" => "Aynalı Plaza",
                "company_id" => "1",
                "department_id" => "2",
                "position_id" => "6",
                "serial_code" => "AZE",
                "password" => Hash::make('optis123456')
            ]
        ];

        foreach ($users as $user) {
            User::query()->create($user);
        }
    }
}
