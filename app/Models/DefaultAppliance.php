<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * DefaultAppliance Model
 *
 * @property int $device_type
 * @property int $appliance_type
 * @property string $appliance_identifier
 * @method function defaultAppliances()
 */
class DefaultAppliance extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $table = 'default_appliances';
    protected $fillable = [
        "device_type",
        "appliance_type",
        "appliance_identifier",
    ];

    // TODO: implement relations and boot
    public function deviceType()
    {
        return $this->hasOne('App\Models\DeviceType', 'id', 'device_type');
    }
    public function applianceType()
    {
        return $this->hasOne('App\Models\ApplianceType', 'id', 'appliance_type');
    }
}
