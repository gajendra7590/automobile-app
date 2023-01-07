<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BatteryBrandSeeder extends Seeder
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
        DB::table('battery_brands')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $today = date('Y-m-d H:i:s');
        $create = [
            [
                'name' => 'Exide',
                'description' => 'Exide Industries ltd.',
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'name' => 'Amara Raja',
                'description' => 'Amara Raja Batteries Ltd.',
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'name' => 'Eveready',
                'description' => 'Eveready Industries India ltd.',
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'name' => 'Panasonic',
                'description' => 'Panasonic Energy India company ltd.',
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'name' => 'Goldstar',
                'description' => 'Goldstar Power ltd.',
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'name' => 'HBL',
                'description' => 'HBL Power Systems ltd.',
                'created_at' => $today,
                'updated_at' => $today,
            ],
        ];
        DB::table('battery_brands')->insert($create);
        DB::commit();
    }
}
