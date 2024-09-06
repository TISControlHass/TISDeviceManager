<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\DeviceTypeRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

use Illuminate\Validation\Rule;

/**
 * Class DeviceTypeCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class DeviceTypeCrudController extends CrudController
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
        CRUD::setModel(\App\Models\DeviceType::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/device-type');
        CRUD::setEntityNameStrings('device type', 'device types');
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
        CRUD::column('created_at')->type('datetime');
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(DeviceTypeRequest::class);
        CRUD::setFromDb(); // set fields from db columns.

        // validation rules
        $rules = [
            'device_type_name' => 'required|unique:devices_types,device_type_name|min:1',
            'device_model_number' => 'required|unique:devices_types,device_model_number|min:1',
        ];
        $messages = [
            'device_type_name.required' => 'Device type name is required.',
            'device_type_name.min' => 'Device type name must have at least 1 character.',
            'device_model_number.required' => 'Device model number is required.',
            'device_model_number.unique' => 'Device model number already exists.',
            'device_model_number.min' => 'Device model number must have at least 1 character.',
        ];
        [];
        $this->crud->setValidation($rules, $messages);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();

        // validation rules
        $rules = [
            'device_type_name' => [
                'required',
                'min:1',
                Rule::unique('devices_types', 'device_type_name')->ignore($this->crud->getCurrentEntryId()),
            ],
            'device_model_number' => [
                'required',
                'min:1',
                Rule::unique('devices_types', 'device_model_number')->ignore($this->crud->getCurrentEntryId()),
            ]
        ];

        $this->crud->setValidation($rules);
    }
}
