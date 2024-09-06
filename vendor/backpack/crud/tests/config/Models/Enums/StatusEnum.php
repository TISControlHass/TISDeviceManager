<?php

namespace Backpack\CRUD\Tests\config\Models\Enums;

enum StatusEnum: string
{
    case DRAFT = 'drafted';
    case PUBLISHED = 'publish';
}
