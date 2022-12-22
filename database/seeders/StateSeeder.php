<?php

namespace Database\Seeders;

use App\Models\State;
use Illuminate\Database\Seeder;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dataArray = array(
            ['id' => 1, 'country_id' => 1, 'state_name' => 'Madhya Pradesh', 'state_code' => "MP"]
        );
        foreach ($dataArray as $data) {
            State::updateOrCreate(
                ['id' => $data['id']],
                $data
            );
        }
    }
}
