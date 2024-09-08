<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

/**
 * Represents a device model.
 *
 * @property int $id The unique identifier of the device.
 * @property string $device_type fk to the devices_types table.
 * @property string $device_name The name of the device.
 * @property string $address The adress of the device.
 * @property string $gateway The gateway of the device.
 */
class Device extends Model
{
    use CrudTrait;

    // Define the table associated with the model
    protected $table = 'devices';

    // Define the primary key column name
    protected $primaryKey = 'id';

    protected $fillable = [
        'device_name',
        'device_type',
        'device_address',
        'gateway',
    ];

    // model events
    protected static function boot()
    {
        parent::boot();

        static::created(function ($device) {
            // Get the device type
            $deviceType = $device->deviceType;
            // Create the output channels
            // TODO: create default appliances
            // foreach ($deviceType->defaultAppliances as $defaultAppliance) {
            //     $appliance = new Appliance();
            //     $appliance->appliance_type = $defaultAppliance->appliance_type;
            //     $appliance->appliance_name = $device->device_name . "-" . $defaultAppliance->appliance_identifier;
            //     $appliance->device_id = $device->id;
            //     $appliance->appliance_class = null;
            //     $appliance->is_protected = $defaultAppliance->is_protected || false;
            //     $appliance->save();
            // }

            // check if the device has a virtual device and sync
            // TODO: make virtual device work
            // $device->sync_virtual_device();

        });

        static::deleting(function ($device) {
            // TODO: delete all related appliances
            $device->appliances()->each(function ($channel) {
                $channel->delete();
            });
            $virtualDevice = $device->virtualDevice;
            // TODO: make virtual device work
            // if ($virtualDevice) {
            //     foreach ($device->applianceChannels as $applianceChannel) {
            //         $applianceChannel->update([
            //             'device_id' => $virtualDevice->id,
            //             'channel_type' => $applianceChannel->channel_type == 'input' ? 'virtual_input' : 'virtual_output',
            //         ]);
            //     }
            //     $virtualDevice->update(['is_mapped' => false]);
            //     Log::info("Virtual Device unmapped");
            // } else {
            //     Log::info("Virtual Device not found");
            // }
        });

        static::updated(function ($device) {
            Log::info('Device updated... syncing virtual device');
            // TODO: make virtual device work
            // $device->sync_virtual_device();
        });
    }

    // public function sync_virtual_device()
    // {
    //     $device_id = $this->id;
    //     $virtualDevice = $this->virtualDevice;
    //     if ($virtualDevice) {
    //         $virtualDevice->map($device_id);
    //     } else {
    //         Log::info("Virtual Device not found");
    //     }
    // }

    // Relationships
    public function appliances()
    {
        return $this->hasMany(Appliance::class, 'device_id', 'id');
    }

    public function defaultAppliances()
    {
        return $this->hasMany(DefaultAppliance::class, 'device_type', 'device_type');
    }

    public function deviceType()
    {
        return $this->belongsTo(DeviceType::class, 'device_type');
    }

    // TODO: make virtual device work
    // public function virtualDevice()
    // {
    //     return $this->hasOne(VirtualDevice::class, "device_name", "device_name");
    // }

    // BackPack CRUD Buttons
    public function scan_devices($crud = false)
    {
        return '<a class="btn btn-primary" href="'.route('devices.scan').'" data-toggle="tooltip" title="Scan the network for devices."><i class="la la-search "></i> Scan Devices</a>';
    }

    public function auto_create_appliances($crud = false)
    {
        return '<a class="btn btn-primary" href="'.route('appliances.auto-create').'" data-toggle="tooltip" title="Auto create appliances from the devices."><i class="la la-plus "></i> Auto Create Appliances</a>';
    }
}
