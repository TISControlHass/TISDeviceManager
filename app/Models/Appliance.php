<?php

namespace App\Models;


use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\ApplianceChannels;
use PhpMqtt\Client\Facades\MQTT;
use Illuminate\Support\Facades\Log;

/**
 * Represents an appliance model.
 * @property int $id The unique identifier of the appliance.
 * @property string $appliance_name The name of the appliance.
 * @property string $appliance_type fk to the appliance_types table.
 * @property string $device_id fk to the devices table.
 * @property string $appliance_class The class of the appliance.
 * @property bool $is_protected Whether the appliance is protected.

 */
class Appliance extends Model
{
    use CrudTrait;
    // Define the table associated with the model
    protected $table = 'appliances';

    // Define the primary key column name
    protected $primaryKey = 'id';

    // Define the fillable columns
    protected $fillable = [
        'device_id',
        'appliance_name',
        'appliance_type',
        'appliance_class',
        'is_protected'
    ];
    // Define any relationships or additional methods here
    // boot function
    protected static function boot()
    {
        parent::boot();

        static::created(
            function ($appliance) {
                // Get the appliance type
                $applianceType = $appliance->applianceType;
                // check protection
                // TODO: implement protection

                $channelsData = $applianceType->defaultApplianceChannels->map(function ($defaultApplianceChannel) use ($appliance) {
                    return [
                        'appliance_id' => $appliance->id,
                        'channel_name' => $defaultApplianceChannel->channel_name,
                        'channel_number' => null, // Assuming you need to explicitly set this
                    ];
                })->toArray();

                ApplianceChannels::insert($channelsData);
            }
            // }
        );

        static::updated(function ($appliance) {
            // remove all appliance channels
            $appliance->applianceChannels->delete();
            // create new channels
            $applianceType = $appliance->applianceType;
            $channelsData = $applianceType->defaultApplianceChannels->map(function ($defaultApplianceChannel) use ($appliance) {
                return [
                    'appliance_id' => $appliance->id,
                    'channel_name' => $defaultApplianceChannel->channel_name,
                    'channel_number' => null, // Assuming you need to explicitly set this
                ];
            })->toArray();

            ApplianceChannels::insert($channelsData);
        });

        static::deleting(function ($appliance) {
            // remove all appliance channels
            try {
                $appliance->applianceChannels()->get();
                $appliance->applianceChannels()->delete();
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                dd($e->getMessage());
            }
            // delete all channels
        });

        // check protection
    }

    public function applianceType()
    {
        return $this->hasOne(ApplianceType::class, 'id', 'appliance_type');
    }

    public function deviceId()
    {
        return $this->hasOne(Device::class, 'id', 'device_id');
    }

    public function applianceChannels()
    {
        return $this->hasMany(ApplianceChannels::class, 'appliance_id');
    }

    public function publish()
    {
        return '<a class="btn btn-primary" href="' . route('appliances.publish') . '" data-toggle="tooltip" title="Publish appliances to homeassistant."><i class="la la-home"> </i> Publish to HomeAssistant</a>';
    }

    public function defaultApplianceChannels()
    {
        return $this->hasMany(DefaultApplianceChannel::class, 'appliance_type', 'appliance_type');
    }
}
