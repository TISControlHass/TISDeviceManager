<?php

namespace Backpack\CRUD\Tests\Config\CrudPanel;

use Backpack\CRUD\app\Library\CrudPanel\CrudPanel;
use Backpack\CRUD\Tests\BaseTestClass;
use Backpack\CRUD\Tests\config\Models\TestModel;

abstract class BaseCrudPanel extends BaseTestClass
{
    /**
     * @var CrudPanel
     */
    protected $crudPanel;

    protected $model;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->app->singleton('crud', function ($app) {
            return new CrudPanel($app);
        });
        $this->crudPanel = app('crud');
        $this->crudPanel->setModel(TestModel::class);
        $this->model = $this->crudPanel->getModel();
    }
}
