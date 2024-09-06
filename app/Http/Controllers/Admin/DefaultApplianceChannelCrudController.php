<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\DefaultApplianceChannelRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class DefaultApplianceChannelCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class DefaultApplianceChannelCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\DefaultApplianceChannel::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/default-appliance-channel');
        CRUD::setEntityNameStrings('default appliance channel', 'default appliance channels');
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
        CRUD::column('appliance_type_id')->type('select2')->entity('applianceType')->attribute("appliance_type_name")->model('App\Models\ApplianceType');
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
            'name' => 'appliance_type_id',
            'type' => 'select2',
            'label' => 'Appliance Type',
            'attribute' => 'appliance_type_name',
            'model' => 'App\Models\ApplianceType',
        ]);

        // validation rules
        $rules = [
            'appliance_type_id' => 'required',
            'channel_name' => 'required|min:1',
        ];
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
