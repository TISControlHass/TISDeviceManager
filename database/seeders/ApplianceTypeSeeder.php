<?php

namespace Database\Seeders;

use App\Models\ApplianceType;
use Illuminate\Database\Seeder;

class ApplianceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $SUPPORTED_PLATFORMS = [
        //     "Light" => [0, 1, 0, 0, false],
        //     "Switch" => [0, 1, 0, 0, false],
        //     // "Fan" => [0, 1, 0, 0, false],
        //     // "Climate" => [0, 0, 1, 0, false],
        //     // "Media Player" => [0, 0, 0, 0, false],
        //     // "Cover" => [0, 2, 0, 0],
        //     "Binary Sensor" => [1, 0, 0, 0, false],
        //     // "Sensor" => [1, 0, 0, 0, false],
        // ];
        // // Truncate the appliance types table
        // ApplianceType::truncate();

        // // Seed the appliance types
        // foreach ($SUPPORTED_PLATFORMS as $platform => $channels) {
        //     ApplianceType::create([
        //         'appliance_type_name' => $platform,
        //         'icon' => 'default',
        //         'payload' => 'default',
        //         'input_channels' => $channels[0],
        //         'output_channels' => $channels[1],
        //         'ac_channels' => $channels[2],
        //         'floor_heating_channels' => $channels[3],
        //         'is_protected' => $channels[4],
        //     ]);
        // }
    }
}
