<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Settings;

/**
 * A seed class to create the initial settings
 */
class SettingsSeeder extends Seeder
{
    /**
     * Seed the application's database
     * @return void
     */
    public function run(): void
    {
        // Delete all records
        Settings::truncate();
        // Create and save the settings
        $settings = [
            [
                'key' => 'server_address',
                'value' => 'homeassistant.local:8123',
            ],
            [ // Add more settings as needed
                'key' => 'lock_module_password',
                'value' => '1234',
            ]
        ];

        foreach ($settings as $setting) {
            Settings::create($setting);
        }
    }
}
