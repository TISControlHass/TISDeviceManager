<?php

namespace Backpack\CRUD\Tests\Config\Models;

class UserWithReturnTypes extends \Backpack\CRUD\Tests\config\Models\User
{
    public function isAnAttribute(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return false;
    }

    public function isARelation(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->bang();
    }
}
