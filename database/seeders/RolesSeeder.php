<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        return $this->createRoles();
    }


    /**
     * Callback function for create roles
     */
    public function createRoles()
    {
        $roles = array(
            ['id' => 1, 'name' => 'admin'],
            ['id' => 2, 'name' => 'user']
        );
        foreach ($roles as $role) {
            Role::updateOrCreate($role, $role);
        }
        return true;
    }
}
