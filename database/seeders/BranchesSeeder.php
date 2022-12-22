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
                'branch_name'          => 'AKASH MOTORS',
                'branch_email'         => 'akashmotorssingot@gmail.com',
                'branch_phone'         => '9424571005',
                'branch_phone2'        => '8085367143',
                'branch_address_line'  => 'Khasra No.-704/150,Near Bus Stand Dharni Road',
                'branch_county'        => '1',
                'branch_state'         => '1',
                'branch_district'      => '1',
                'branch_city'          => '1',
                'branch_pincode'       => '450881',
                'gstin_number'         => '23AXVPS0237M1ZX',
                'active_status'        => '1'
            ],
            [
                'id'                   => 2,
                'branch_name'          => 'GURUKRIPA AGENCY',
                'branch_email'         => 'gurukripasingot@gmail.com',
                'branch_phone'         => '9171235037',
                'branch_phone2'        =>  null,
                'branch_address_line'  => 'NEAR BUS STAND,SINGOT',
                'branch_county'        => '1',
                'branch_state'         => '1',
                'branch_district'      => '1',
                'branch_city'          => '1',
                'branch_pincode'       => '450881',
                'gstin_number'         => '23BBZPS9887C1ZO',
                'active_status'        => '1'
            ],
            [
                'id'                   => 3,
                'branch_name'          => 'SAI AUTOMOBILE',
                'branch_email'         => 'saihondasingot@gmail.com',
                'branch_phone'         => '9111489794',
                'branch_phone2'        => null,
                'branch_address_line'  => 'NEAR BUS STAND, SINGOT',
                'branch_county'        => '1',
                'branch_state'         => '1',
                'branch_district'      => '1',
                'branch_city'          => '1',
                'branch_pincode'       => '450881',
                'gstin_number'         => '23HLLPS4119F1ZY',
                'active_status'        => '1'
            ]
        );
        foreach ($branches as $i => $branch) {
            Branch::updateOrCreate(['id' => $branch['id']], $branch);
        }
    }
}
