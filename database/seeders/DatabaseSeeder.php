<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(SettingsSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(ApplianceTypeSeeder::class);
        $this->call(DeviceTypeSeeder::class);
        $this->call(DefaultApplianceChannelsSeeder::class);
        $this->call(DefaultApplianceSeeder::class);
    }
}
