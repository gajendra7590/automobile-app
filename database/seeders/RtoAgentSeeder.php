<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RtoAgentSeeder extends Seeder
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
        DB::table('rto_agents')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $today = date('Y-m-d H:i:s');
        $create = [
            [
                'id' => '1',
                'agent_name' => 'Shubham RTO',
                'active_status' => 1,
                'created_at' => $today,
                'updated_at' => $today,
            ]
        ];
        DB::table('rto_agents')->insert($create);
        DB::commit();
    }
}
