<?php

namespace Backpack\CRUD\Tests\Config\CrudTrait;

use Backpack\CRUD\Tests\BaseTestClass;

abstract class BaseCrudTrait extends BaseTestClass
{
    protected function getPackageAliases($app)
    {
        return [
            '\App' => \Illuminate\Support\Facades\App::class,
            '\Request' => \Illuminate\Support\Facades\Request::class,
        ];
    }
}
