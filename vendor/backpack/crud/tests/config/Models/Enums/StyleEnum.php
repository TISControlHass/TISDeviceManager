<?php

namespace Backpack\CRUD\Tests\config\Models\Enums;

enum StyleEnum
{
    case DRAFT;
    case PUBLISHED;

    public function color(): string
    {
        return match ($this) {
            StyleEnum::DRAFT => 'red',
            StyleEnum::PUBLISHED => 'green',
        };
    }
}
