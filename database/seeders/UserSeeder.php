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
    public function createUsers() {
        $users = array(
            ['id' => 1,'name' => 'Admin','email' => 'admin@aakashauto.com'],
            ['id' => 2,'name' => 'Branch1','email' => 'branch1@aakashauto.com'],
            ['id' => 3,'name' => 'Branch2','email' => 'branch2@aakashauto.com'],
            ['id' => 4,'name' => 'Branch3','email' => 'branch3@aakashauto.com'],
        );

        foreach($users as $user) {
            $user['password'] = Hash::make('123456');
            $user['email_verified_at'] = date('Y-m-d H:i:s');
            User::updateOrCreate(
                ['id' => $user['id'],'email' => $user['email']],
                $user
            );
        }
        return true;
    }
}
