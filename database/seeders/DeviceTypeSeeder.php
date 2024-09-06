<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DeviceType;

class DeviceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // "device_type_name",   "device_model_number",        "device_description"
        $known_types = [
            "RCU-8OUT-8IN" => ["27,186", "8 channel RCU"],
            "DIM-6CH-2A" => ["2,88", "6 channel dimmer"]
        ];

        // Truncate the device types table
        DeviceType::truncate();
        foreach ($known_types as $typeName => $details) {
            DeviceType::create([
                'device_type_name' => $typeName,
                'device_model_number' => $details[0],
                'device_description' => $details[1] ?? 'No description available.',
            ]);
        }
    }
}
