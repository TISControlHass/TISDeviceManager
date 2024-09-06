<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Represents an appliance type model.
 * @property int $id The unique identifier of the appliance type.
 * @property string $appliance_type_name The name of the appliance type.
 * @property bool $is_protected Whether the appliance type is protected.
 */
class ApplianceType extends Model
{
    use CrudTrait;
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'appliance_types';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    protected $fillable = ['appliance_type_name', 'is_protected'];
    // protected $hidden = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public static function boot()
    {
        parent::boot();

        static::created(function ($applianceType) {
            $applianceType->updateAppliancesProtection();
        });

        static::updated(function ($applianceType) {
            $applianceType->updateAppliancesProtection();
        });

        static::deleted(function ($applianceType) {
            $applianceType->appliances()->delete();
            $applianceType->defaultappliancechannels()->delete();
        });
    }

    protected function updateAppliancesProtection()
    {
        foreach ($this->appliances as $appliance) {
            $appliance->update(['is_protected' => $this->is_protected]);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function appliances()
    {
        return $this->hasMany(Appliance::class, 'appliance_type');
    }

    public function defaultappliancechannels()
    {
        return $this->hasMany(DefaultApplianceChannel::class, 'appliance_type_id');
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
