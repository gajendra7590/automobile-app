<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dataArray = array(
            ['id' => 1, 'district_id' => 1, 'city_name' => 'Singot', 'city_code' => "SIN"],
        );
        foreach ($dataArray as $data) {
            City::updateOrCreate(
                ['id' => $data['id']],
                $data
            );
        }
    }
}
