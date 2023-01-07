<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DealerSeeder extends Seeder
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
        DB::table('bike_dealers')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $today = date('Y-m-d H:i:s');
        $create = [
            [
                'id' => '1',
                'dealer_code' => 'HRD',
                'company_name' => 'Hero Dealers',
                'active_status' => 1,
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'id' => '2',
                'dealer_code' => 'HDD',
                'company_name' => 'Honda Dealers',
                'active_status' => 1,
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'id' => '3',
                'dealer_code' => 'BJD',
                'company_name' => 'Bajaj Dealers',
                'active_status' => 1,
                'created_at' => $today,
                'updated_at' => $today,
            ],
        ];
        DB::table('bike_dealers')->insert($create);
        DB::commit();
    }
}
