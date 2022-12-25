<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModelSeeder extends Seeder
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
        DB::table('bike_models')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $today = date('Y-m-d H:i:s');
        $create = [
            [
                'brand_id' => 1,
                'model_name' => 'ACTIVA 125 DRUM',
                'model_code' => 'CODE',
                'active_status' => 1,
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'brand_id' => 1,
                'model_name' => 'ACTIVA 125 DISK',
                'model_code' => 'CODE',
                'active_status' => 1,
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'brand_id' => 1,
                'model_name' => 'ACTIVA 125 DRUM ALLOY',
                'model_code' => 'CODE',
                'active_status' => 1,
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'brand_id' => 1,
                'model_name' => 'ACTIVA 6G STD',
                'model_code' => 'CODE',
                'active_status' => 1,
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'brand_id' => 1,
                'model_name' => 'ACTIVA 6G DLX',
                'model_code' => 'CODE',
                'active_status' => 1,
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'brand_id' => 1,
                'model_name' => 'ACTIVA 6G SP. EDITION',
                'model_code' => 'CODE',
                'active_status' => 1,
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'brand_id' => 1,
                'model_name' => 'GRAZIA DRUM',
                'model_code' => 'CODE',
                'active_status' => 1,
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'brand_id' => 1,
                'model_name' => 'GRAZIA DISC',
                'model_code' => 'CODE',
                'active_status' => 1,
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'brand_id' => 1,
                'model_name' => 'GRAZIA DISC REPSOL',
                'model_code' => 'CODE',
                'active_status' => 1,
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'brand_id' => 1,
                'model_name' => 'CD 110 DREAM DLX',
                'model_code' => 'CODE',
                'active_status' => 1,
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'brand_id' => 1,
                'model_name' => 'CB 125 SHINE DRUM',
                'model_code' => 'CODE',
                'active_status' => 1,
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'brand_id' => 1,
                'model_name' => 'CB 125 SHINE DISC',
                'model_code' => 'CODE',
                'active_status' => 1,
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'brand_id' => 1,
                'model_name' => 'CB 125 SHINE DRUM LTD EDI',
                'model_code' => 'CODE',
                'active_status' => 1,
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'brand_id' => 1,
                'model_name' => 'CB 125 SHINE DISC LTD EDI',
                'model_code' => 'CODE',
                'active_status' => 1,
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'brand_id' => 1,
                'model_name' => 'HORNET 2.0',
                'model_code' => 'CODE',
                'active_status' => 1,
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'brand_id' => 1,
                'model_name' => 'UNICORN ABS -160',
                'model_code' => 'CODE',
                'active_status' => 1,
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'brand_id' => 1,
                'model_name' => 'SP 125 SHINE DRUM',
                'model_code' => 'CODE',
                'active_status' => 1,
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'brand_id' => 1,
                'model_name' => 'SP 125 SHINE  DISC',
                'model_code' => 'CODE',
                'active_status' => 1,
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'brand_id' => 1,
                'model_name' => 'NEW DIO STD',
                'model_code' => 'CODE',
                'active_status' => 1,
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'brand_id' => 1,
                'model_name' => 'NEW DIO DLX',
                'model_code' => 'CODE',
                'active_status' => 1,
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'brand_id' => 1,
                'model_name' => 'LIVO DRUM',
                'model_code' => 'CODE',
                'active_status' => 1,
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'brand_id' => 1,
                'model_name' => 'LIVO DISC',
                'model_code' => 'CODE',
                'active_status' => 1,
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'brand_id' => 1,
                'model_name' => 'X BLADE ABS',
                'model_code' => 'CODE',
                'active_status' => 1,
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'brand_id' => 1,
                'model_name' => 'CB2000X CB190NX 2ID',
                'model_code' => 'CODE',
                'active_status' => 1,
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'brand_id' => 2,
                'model_name' => 'GLAMOUR',
                'model_code' => 'GMR',
                'active_status' => 1,
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'brand_id' => 2,
                'model_name' => 'GLAMOUR XTEC',
                'model_code' => 'GMR',
                'active_status' => 1,
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'brand_id' => 2,
                'model_name' => 'HF DELUXE',
                'model_code' => 'HFD',
                'active_status' => 1,
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'brand_id' => 2,
                'model_name' => 'MAESTRO EDGE 125',
                'model_code' => 'MTE',
                'active_status' => 1,
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'brand_id' => 2,
                'model_name' => 'PASSION PRO',
                'model_code' => 'PPR',
                'active_status' => 1,
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'brand_id' => 2,
                'model_name' => 'PASSION XTEC',
                'model_code' => 'PXT',
                'active_status' => 1,
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'brand_id' => 2,
                'model_name' => 'SPLENDOR +',
                'model_code' => 'SP',
                'active_status' => 1,
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'brand_id' => 2,
                'model_name' => 'SPLENDOR+ XTEC',
                'model_code' => 'SPXT',
                'active_status' => 1,
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'brand_id' => 2,
                'model_name' => 'SUPER SPLENDOR',
                'model_code' => 'SPXT',
                'active_status' => 1,
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'brand_id' => 2,
                'model_name' => 'XTREME 160R',
                'model_code' => 'XTR',
                'active_status' => 1,
                'created_at' => $today,
                'updated_at' => $today,
            ],
        ];
        DB::table('bike_models')->insert($create);
        DB::commit();
    }
}
