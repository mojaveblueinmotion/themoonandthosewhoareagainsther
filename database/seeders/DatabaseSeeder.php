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
        // Geo
        $this->call(PermissionSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(MasterSeeder::class);
        // $this->call(MenuFlowSeeder::class);
    }
}
