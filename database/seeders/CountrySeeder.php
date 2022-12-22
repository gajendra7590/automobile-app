<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dataArray = array(
            ['id' => 1, 'country_name' => 'India', 'country_code' => "IN"]
        );
        foreach ($dataArray as $data) {
            Country::updateOrCreate(
                ['id' => $data['id']],
                $data
            );
        }
    }
}
