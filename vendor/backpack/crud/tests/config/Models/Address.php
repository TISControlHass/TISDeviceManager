<?php

namespace Backpack\CRUD\Tests\Config\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use CrudTrait;

    protected $table = 'addresses';
    protected $fillable = ['city', 'street', 'number'];

    /**
     * Get the author for the article.
     */
    public function accountDetails()
    {
        return $this->belongsTo('Backpack\CRUD\Tests\config\Models\AccountDetails', 'account_details_id');
    }

    public function bang()
    {
        return $this->belongsTo('Backpack\CRUD\Tests\config\Models\Bang', 'city');
    }
}
