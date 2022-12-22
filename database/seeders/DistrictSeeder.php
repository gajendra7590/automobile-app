<?php

namespace Database\Seeders;

use App\Models\District;
use Illuminate\Database\Seeder;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dataArray = array(
            ['id' => 1, 'state_id' => 1, 'district_name' => 'Khandwa', 'district_code' => "KNW"],
            ['id' => 2, 'state_id' => 1, 'district_name' => 'Burhanpur', 'district_code' => "BRH"],
            ['id' => 3, 'state_id' => 1, 'district_name' => 'Khargone', 'district_code' => "KRG"]
        );
        foreach ($dataArray as $data) {
            District::updateOrCreate(
                ['id' => $data['id']],
                $data
            );
        }
    }
}
