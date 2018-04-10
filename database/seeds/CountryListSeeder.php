<?php

use Illuminate\Database\Seeder;

class CountryListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countries = \App\Country::all_countries();
        if(count($countries) > 0) {
            foreach ($countries AS $country) {
                \App\Country::create([
                    'id' => $country[0],
                    'name' => $country[1],
                    'code' => $country[2],
                ]);
            }
        }
    }
}
