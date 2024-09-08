<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Represents an appliance channel model.
 * @property int $id The unique identifier of the appliance channel.
 * @property int $appliance_id fk to the appliances table.
 * @property string $channel_name The name of the channel.
 * @property int $channel_number The number of the channel.
 */
class ApplianceChannels extends Model
{
    use CrudTrait;
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'appliance_channels';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    protected $fillable = ['appliance_id', 'channel_name', 'channel_number'];
    // protected $hidden = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function applianceId()
    {
        return $this->hasOne(Appliance::class, 'id', 'appliance_id');
    }
    public function appliance()
    {
        return $this->belongsTo(Appliance::class, 'appliance_id', 'id');
    }
    public function device()
    {
        return $this->hasOneThrough(Device::class, Appliance::class, 'id', 'id', 'appliance_id', 'device_id');
    }
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
