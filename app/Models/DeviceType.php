<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * DeviceType Model
 *
 * @property string $device_type_name
 * @property string $device_model_number
 * @property string $device_type_description
 * @method function defaultAppliances()
 */
class DeviceType extends Model
{
    use CrudTrait;
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'devices_types';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $fillable = [
        "device_type_name",
        "device_model_number",
        "device_description",
    ];
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
    public function devices()
    {
        return $this->hasMany('App\Models\Device', 'device_type', 'id');
    }
    public function defaultAppliances()
    {
        return $this->hasMany('App\Models\DefaultAppliance', 'device_type', 'id');
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
