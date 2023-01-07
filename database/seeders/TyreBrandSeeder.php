<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TyreBrandSeeder extends Seeder
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
        DB::table('tyre_brands')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $today = date('Y-m-d H:i:s');
        $create = [
            [
                'name' => 'MRF',
                'description' => 'MRF Tyres',
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'name' => 'Apollo',
                'description' => 'Apollo Tyres',
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'name' => 'JK',
                'description' => 'JK Tyres',
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'name' => 'TVS Eurogrip',
                'description' => 'TVS Eurogrip Tyres',
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'name' => 'Bridgestone',
                'description' => 'Bridgestone Tyres',
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'name' => 'Michelin',
                'description' => 'Michelin Tyres',
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'name' => 'Goodyear',
                'description' => 'Goodyear Tyre and Rubber Company',
                'created_at' => $today,
                'updated_at' => $today,
            ],
        ];
        DB::table('tyre_brands')->insert($create);
        DB::commit();
    }
}
