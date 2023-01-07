<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GstRateSeeder extends Seeder
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
        DB::table('gst_rates')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $today = date('Y-m-d H:i:s');
        $create = [
            [
                'id' => '1',
                'gst_rate' => 28,
                'cgst_rate' => 18,
                'sgst_rate' => 12,
                'igst_rate' => 10,
                'active_status'=> 1,
                'created_at' => $today,
                'updated_at' => $today,
            ],
        ];
        DB::table('gst_rates')->insert($create);
        DB::commit();
    }
}
