<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;

class CountrySeeder extends Seeder
{
    public function run()
    {
        $countries = [
            ['name' => 'México'],
            ['name' => 'Estados Unidos'],
            ['name' => 'Canadá'],
        ];

        foreach ($countries as $country) {
            Country::updateOrCreate(['name' => $country['name']], $country);
        }
    }
}
