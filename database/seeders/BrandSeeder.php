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
                'id' => '1',
                'baranch_id' => '1',
                'name' => 'HERO MOTOCORP',
                'code' => 'HR',
                'description' =>  'HERO MOTOCORP description',
                'active_status' => 1,
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [

                'id' => '2',
                'baranch_id' => '2',
                'name' => 'HONDA MOTOR COMPANY',
                'code' => 'HND',
                'description' =>  'HONDA description',
                'active_status' => 1,
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'id' => '3',
                'baranch_id' => '3',
                'name' => 'BAJAJ AUTO',
                'code' => 'BJJ',
                'description' =>  'BAJAJ AUTO description',
                'active_status' => 1,
                'created_at' => $today,
                'updated_at' => $today,
            ],
        ];
        DB::table('bike_brands')->insert($create);
        DB::commit();
    }
}
