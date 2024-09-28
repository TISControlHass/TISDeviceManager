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
            "light" => [
                "Output Channel" => "1",
            ],
            "rgbw" => [
                "Output Channel" => "1",
                "Red Channel" => "1",
                "Green Channel" => "1",
                "Blue Channel" => "1",
                "White Channel" => "1",
            ],
            "rgb" => [
                "Output Channel" => "1",
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
            "cover" => [
                "Up Channel" => "1",
                "Down Channel" => "1",
            ],
            "cover_with_position" => [
                "Output Channel" => "1",
            ],
            "binary_sensor" => [
                "Input Channel" => "1",
            ],
            "select" => [
                "Input Channel" => "1",
            ],
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
