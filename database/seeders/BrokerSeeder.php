<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrokerSeeder extends Seeder
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
        DB::table('brokers')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $today = date('Y-m-d H:i:s');
        $create = [
            [
                'name' => 'Suresh Modi',
                'email' => 'suresh.modi@yopmail.com',
                'mobile_number' => '9876543210',
                'gender' => '1',
                'created_at' => $today,
                'updated_at' => $today,
            ],
        ];
        DB::table('brokers')->insert($create);
        DB::commit();
    }
}
