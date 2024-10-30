<?php

namespace Database\Seeders;

use App\Models\Appliance;
use App\Models\ApplianceType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DefaultApplianceChannel;

class DefaultApplianceChannelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $default_channels = [
            "switch" => [
                "Output Channel" => "1",
            ],
            "dimmer" => [
                "Output Channel" => "1",
            ],
            "rgbw" => [
                "Red Channel" => "1",
                "Green Channel" => "1",
                "Blue Channel" => "1",
                "White Channel" => "1",
            ],
            "rgb" => [
                "Red Channel" => "1",
                "Green Channel" => "1",
                "Blue Channel" => "1",
            ],
            "ac" => [
                "AC" => "1",
            ],
            "floor_heating" => [
                "Floor Heating" => "1",
            ],
            "shutter" => [
                "Up Channel" => "1",
                "Down Channel" => "1",
            ],
            "motor" => [
                "Output Channel" => "1",
            ],
            "binary_sensor" => [
                "Input Channel" => "1",
            ],
            "security" => [
                "Input Channel" => "1",
            ],

            "noise_sensor" => ["Input Channel" => "1"],
            "eco2_sensor" => ["Input Channel" => "1"],
            "tvoc_sensor" => ["Input Channel" => "1"],
            "humidity_sensor" => ["Input Channel" => "1"],
            "co_sensor" => ["Input Channel" => "1"],
            "lux_sensor" => ["Input Channel" => "1"],
            "temperature_sensor" => ["Input Channel" => "1"],
            "weather" => ["Input Channel" => "1"],
        ];
        // truncate the table
        DefaultApplianceChannel::truncate();
        // populate the table
        foreach ($default_channels as $appliance_type => $channels) {
            foreach ($channels as $channel_name => $channel_number) {
                DefaultApplianceChannel::create([
                    'appliance_type_id' => ApplianceType::where('appliance_type_name', $appliance_type)->first()->id,
                    'channel_name' => $channel_name,
                ]);
            }
        }
    }
}
