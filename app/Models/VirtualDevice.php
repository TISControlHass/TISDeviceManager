<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;



/**
 * Represents a device model.
 * @property int $id The unique identifier of the device.
 * @property string $device_type fk to the devices_types table.
 * @property string $device_name The name of the device.
 */
class VirtualDevice extends Model
{
    use CrudTrait;
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'virtual_devices';

    protected $guarded = ['id'];
    protected $fillable = ["device_type", "device_name", "is_mapped"];
    // protected $hidden = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($device) {
            // Get the device type
            $deviceType = $device->deviceType;
            // Create the output channels
            if ($deviceType->output_channels > 0) {
                // Create or update the output channels
                try {
                    for ($i = 0; $i < intval($deviceType->output_channels); $i++) {
                        $outputChannel = Channel::updateOrCreate(
                            [
                                'device_id' => $device->id,
                                'channel_number' => $i + 1,
                                'channel_type' => 'virtual_output',
                            ],
                            [
                                'device_id' => $device->id,
                                'channel_number' => $i + 1,
                                'channel_value' => 0,
                                'channel_type' => 'virtual_output',
                                'channel_description' => 'virtual Output Channel ' . ($i + 1),

                            ]
                        );
                    };
                } catch (\Throwable $th) {
                    dd($th);
                }
            }

            // Create the input channels
            if ($deviceType->input_channels > 0) {
                // Create or update the input channels
                for ($i = 0; $i < $deviceType->input_channels; $i++) {
                    Channel::updateOrCreate(
                        [
                            'device_id' => $device->id,
                            'channel_number' => $i + 1,
                            'channel_type' => 'virtual_input',
                        ],
                        [
                            'device_id' => $device->id,
                            'channel_number' => $i + 1,
                            'channel_value' => 0,
                            'channel_type' => 'virtual_input',
                            'channel_description' => 'virtual Input Channel ' . ($i + 1),
                        ]
                    );
                }
            }
        });

        static::deleting(function ($device) {
            $device->outputChannels()->delete();
            $device->inputChannels()->delete();
            $device->applianceChannels()->whereIn('channel_type', ['virtual_input', 'virtual_output'])->update(['device_id' => null, 'channel_number' => null]);
        });
    }

    public function map($device_id)
    {
        $real_device = Device::find($device_id);
        if (!$real_device) {
            Log::error("Device not found");
            return false;
        }
        if ($real_device->device_type != $this->device_type) {
            Log::error("Device type mismatch");
            return false;
        }
        // update channel type and update the device id using for lopp
        foreach ($this->applianceChannels as $channel) {
            $channel->device_id = $device_id;
            $channel->channel_type = $channel->channel_type == "virtual_input" ? "input" : "output";
            $channel->save();
        }

        $this->is_mapped = true;
        $this->save();
        Log::info("Device mapped");
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function outputChannels()
    {
        return $this->hasMany(Channel::class, 'device_id', 'id');
    }

    public function inputChannels()
    {
        return $this->hasMany(Channel::class, 'device_id', 'id');
    }

    public function applianceChannels()
    {
        return $this->hasMany(ApplianceChannels::class, 'device_id', 'id');
    }

    public function deviceType()
    {
        return $this->belongsTo(DeviceType::class, "device_type");
    }
}
