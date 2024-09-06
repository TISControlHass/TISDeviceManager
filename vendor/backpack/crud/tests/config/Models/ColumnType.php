<?php

namespace Backpack\CRUD\Tests\Config\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class ColumnType extends Model
{
    use CrudTrait;

    protected $table = 'column_types';
}
