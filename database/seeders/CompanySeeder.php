<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $companies = [
            [
                'name' => 'Яндекс',
                'email' => 'yandex@yand.com',
                'logo_path' => '/',
                'address' => 'Москва, ул.Красная площадь, д.1'
            ],
            [
                'name' => 'Зубы',
                'email' => 'zuby@yand.com',
                'logo_path' => '/',
                'address' => 'Москва, ул.Тверская, д.1'
            ],
        ];

        foreach ($companies as $company) {
            Company::create($company);
        }
    }
}
