<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FinancerSeeder extends Seeder
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
        DB::table('bank_financers')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $today = date('Y-m-d H:i:s');
        $create = [
            [
                'id' => '1',
                'bank_name' => 'Aakash Finance',
                'bank_branch_code' => 'AKF',
                'bank_contact_number' => null,
                'bank_email_address' => null,
                'financer_type' => 2,
                'active_status' => 1,
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'id' => '2',
                'bank_name' => 'Madhya Pradesh Gramin Bank',
                'bank_branch_code' => 'MPGB',
                'bank_contact_number' => null,
                'bank_email_address' => null,
                'financer_type' => 1,
                'active_status' => 1,
                'created_at' => $today,
                'updated_at' => $today,
            ],
        ];
        DB::table('bank_financers')->insert($create);
        DB::commit();
    }
}
