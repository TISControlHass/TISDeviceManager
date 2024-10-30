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
        $TIS_APPLIANCES = [
            "switch",
            "dimmer",
            "rgbw",
            "rgb",
            "ac",
            "floor_heating",
            "shutter",
            "motor",
            "binary_sensor",
            "security",
            "noise_sensor",
            "eco2_sensor",
            "tvoc_sensor",
            "humidity_sensor",
            "co_sensor",
            "lux_sensor",
            "temperature_sensor",
            "weather",
        ];

        // first empty the table
        ApplianceType::truncate();

        // now populate it
        foreach ($TIS_APPLIANCES as $appliance) {
            ApplianceType::create([
                'appliance_type_name' => $appliance,
                'is_protected' => false,
            ]);
        }
    }
}
