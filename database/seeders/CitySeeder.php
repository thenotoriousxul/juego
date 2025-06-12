<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\City;
use App\Models\Country;

class CitySeeder extends Seeder
{
    public function run()
    {
        $mexico = Country::where('name', 'México')->first();
        $usa = Country::where('name', 'Estados Unidos')->first();
        $canada = Country::where('name', 'Canadá')->first();

        $cities = [
            ['country_id' => $mexico->id, 'name' => 'Ciudad de México'],
            ['country_id' => $mexico->id, 'name' => 'Guadalajara'],
            ['country_id' => $mexico->id, 'name' => 'Monterrey'],

            ['country_id' => $usa->id, 'name' => 'Nueva York'],
            ['country_id' => $usa->id, 'name' => 'Los Ángeles'],
            ['country_id' => $usa->id, 'name' => 'Chicago'],

            ['country_id' => $canada->id, 'name' => 'Toronto'],
            ['country_id' => $canada->id, 'name' => 'Vancouver'],
            ['country_id' => $canada->id, 'name' => 'Montreal'],
        ];

        foreach ($cities as $city) {
            City::updateOrCreate(
                ['country_id' => $city['country_id'], 'name' => $city['name']],
                $city
            );
        }
    }
}
