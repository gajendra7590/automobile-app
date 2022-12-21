<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
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
        //Create Admin User
        $userModel = User::updateOrCreate(['id' => 1], [
            'name'     => 'Admin',
            'email'    => 'admin@aakashauto.com',
            'password' => Hash::make('123456'),
            'is_default' => 1,
            'is_admin' => 1,
            'active_status' => 1
        ]);
        //Assign Role
        $userModel->assignRole(1);
        return true;
    }
}
