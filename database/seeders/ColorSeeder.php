<?php

namespace Database\Seeders;

use App\Models\BikeModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        DB::beginTransaction();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('bike_colors')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $today = date('Y-m-d H:i:s');
        $colors = [
            [
                'color_name' => "BLK/RED",
                'color_code' => "BLK/RED",
                'active_status' => 1,
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'color_name' => 'BLK/BLUE',
                'color_code' => 'BLK/BLUE',
                'active_status' => 1,
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'color_name' => 'BLK/SILVER',
                'color_code' => 'BLK/SILVER',
                'active_status' => 1,
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'color_name' => 'BLK/GREEN',
                'color_code' => 'BLK/GREEN',
                'active_status' => 1,
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'color_name' => 'GREEY',
                'color_code' => 'GREEY',
                'active_status' => 1,
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'color_name' => 'RED',
                'color_code' => 'RED',
                'active_status' => 1,
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'color_name' => 'WHITE',
                'color_code' => 'WHITE',
                'active_status' => 1,
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'color_name' => 'BLUE',
                'color_code' => 'BLUE',
                'active_status' => 1,
                'created_at' => $today,
                'updated_at' => $today,
            ],
        ];
        $create = [];
        $bikeModels = BikeModel::get();
        foreach($bikeModels as $bikeModel){
            foreach($colors as $color){
                $color['bike_model'] = $bikeModel['id'];
                $create[] = $color;
            }
        }
        DB::table('bike_colors')->insert($create);
        DB::commit();
    }
}
