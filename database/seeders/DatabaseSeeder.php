<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            BranchesSeeder::class,
            RolesSeeder::class,
            UserSeeder::class,
            //Address
            CountrySeeder::class,
            StateSeeder::class,
            DistrictSeeder::class,
            CitySeeder::class,
            //Brands
            BrandSeeder::class,
            ModelSeeder::class,
            ColorSeeder::class,
            GstRateSeeder::class,
            RtoGstRateSeeder::class,

            RtoAgentSeeder::class,
            FinancerSeeder::class,
            DealerSeeder::class,
            BrokerSeeder::class,

            TyreBrandSeeder::class,
            BatteryBrandSeeder::class
        ]);
    }
}
