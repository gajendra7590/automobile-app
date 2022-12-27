<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        return $this->createUsers();
    }

    /**
     * Function for create users
     */
    public function createUsers()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');


        //Create Admin User
        $userModel = User::updateOrCreate(['id' => 1], [
            'name'     => 'ADMIN USER',
            'email'    => 'admin.user@yopmail.com',
            'password' => Hash::make('admin@123456'),
            'email_verified_at' => date('Y-m-d H:i:s'),
            'is_default' => 1,
            'is_admin' => 1,
            'active_status' => 1
        ]);
        //Assign Role
        $userModel->assignRole('admin');

        //Create 3 default branch users
        for ($i = 1; $i <= 3; $i++) {
            $branchDetail = Branch::find($i);
            //Create Admin User
            $userModel2 = User::updateOrCreate(['id' => ($i + 1)], [
                'name'     => $branchDetail->branch_name,
                'email'    => $branchDetail->branch_email,
                'password' => Hash::make('user@123456'),
                'email_verified_at' => date('Y-m-d H:i:s'),
                'branch_id'         => $i,
                'is_default' => 1,
                'is_admin' => 0,
                'active_status' => 1
            ]);
            //Assign Role
            $userModel2->assignRole('user');
        }
        return true;
    }
}
