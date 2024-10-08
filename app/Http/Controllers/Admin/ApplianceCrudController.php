<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ApplianceRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ApplianceCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ApplianceCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\BulkDeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Appliance::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/appliance');
        CRUD::setEntityNameStrings('appliance', 'appliances');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::setFromDb(); // set columns from db columns.
        CRUD::addButtonFromModelFunction('top', 'publish', 'publish');
        CRUD::column('device_id')->type('select2')->entity('deviceId')->attribute("device_name")->model('App\Models\Device');
        CRUD::column('appliance_type')->type('select2')->entity('applianceType')->attribute("appliance_type_name")->model('App\Models\ApplianceType');
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setFromDb(); // set fields from db columns.
        CRUD::addField([
            'name' => 'appliance_type',
            'type' => 'select2',
            'label' => 'Appliance Type',
            'attribute' => 'appliance_type_name',
            'model' => \App\Models\ApplianceType::class,
        ]);
        CRUD::addField([
            'name' => 'device_id',
            'type' => 'select2',
            'label' => 'Device',
            'attribute' => 'device_name',
            'model' => \App\Models\Device::class,
        ]);
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
