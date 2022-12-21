<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createBranches();
    }

    public function createBranches()
    {
        $branches = array(
            [
                'id'                   => 1,
                'branch_manager_name'  => 'Aakash Soni',
                'branch_manager_phone' => '1234567890',
                'branch_name'          => 'Singot',
                'active_status'        => '1'
            ],
            [
                'id'                   => 2,
                'branch_manager_name'  => 'Aakash Soni',
                'branch_manager_phone' => '1234567890',
                'branch_name'          => 'Khandwa',
                'active_status'        => '1'
            ],
            [
                'id'                   => 3,
                'branch_manager_name'  => 'Aakash Soni',
                'branch_manager_phone' => '1234567890',
                'branch_name'          => 'Burhanpur',
                'active_status'        => '1'
            ]
        );
        foreach ($branches as $branch) {
            Branch::updateOrCreate(['id' => $branch['id']], $branch);
        }
    }
}
