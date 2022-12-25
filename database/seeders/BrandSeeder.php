<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandSeeder extends Seeder
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
        DB::table('bike_brands')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $today = date('Y-m-d H:i:s');
        $create = [
            [
                'name' => 'HONDA',
                'code' => 'HND',
                'description' =>  'HONDA description',
                'active_status' => 1,
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'name' => 'HERO',
                'code' => 'HR',
                'description' =>  'HERO description',
                'active_status' => 1,
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'name' => 'BAJAJ',
                'code' => 'BJJ',
                'description' =>  'BAJAJ description',
                'active_status' => 1,
                'created_at' => $today,
                'updated_at' => $today,
            ],
        ];
        DB::table('bike_brands')->insert($create);
        DB::commit();
    }
}
