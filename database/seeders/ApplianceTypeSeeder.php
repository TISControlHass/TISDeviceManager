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
            "light",
            "rgbw",
            "rgb",
            "ac",
            "floor_heating",
            "shutter",
            "shutter_with_position",
            "sensor",
            "binary_sensor",
            "select",
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
