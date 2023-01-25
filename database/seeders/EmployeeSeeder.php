<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $employees = [
            [
                'name' => 'Петр Петров',
                'email' => 'yandex@yand.com',
                'phone' => '+79999999999',
                'company_id' => '1'

            ],
            [
                'name' => 'Иван Иванов',
                'email' => 'zuby@yand.com',
                'phone' => '+79999999999',
                'company_id' => '1'
            ],
            [
                'name' => 'Сидор Сидоров',
                'email' => 'yandex@yand.com',
                'phone' => '+79999999999',
                'company_id' => '2'

            ],
            [
                'name' => 'Павел Павлов',
                'email' => 'zuby@yand.com',
                'phone' => '+79999999999',
                'company_id' => '2'
            ],
        ];

        foreach ($employees as $employee) {
            Employee::create($employee);
        }
    }
}
