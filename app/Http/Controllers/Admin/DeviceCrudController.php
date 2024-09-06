<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Validation\Rule;

use App\Http\Requests\DeviceRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Library\Widget;

/**
 * Class DeviceCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class DeviceCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Device::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/device');
        CRUD::setEntityNameStrings('device', 'devices');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::addButtonFromModelFunction('top', 'scan_devices', 'scan_devices');
        CRUD::addButtonFromModelFunction('top', 'auto_create_appliances', 'auto_create_appliances');
        // CRUD::removeButton('create');
        CRUD::setFromDb(); // set columns from db columns.
        CRUD::column('device_type')->type('select2')->entity('deviceType')->attribute("device_type_name")->model('App\Models\DeviceType');
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
        CRUD::setValidation(DeviceRequest::class);
        CRUD::setFromDb(); // set fields from db columns.
        CRUD::addField([
            'name' => 'device_type',
            'label' => 'Device Type',
            'type' => 'select2',
            'entity' => 'deviceType',
            'attribute' => "device_type_name",
            'model' => 'App\Models\DeviceType',
        ]);
        Widget::add()->type('script')->content('assets/js/autofill_virtualdevices.js');


        // validation rules
        $rules = [
            'device_type' => 'required',
            'device_name' => [
                'required',
                'min:1',
                'unique:devices,device_name',
            ],
            'device_address' => [
                'required',
                'min:3',
                'unique:devices,device_address',
            ],

        ];
        $messages = [
            'device_type.required' => 'The device type field is required.',
            'device_name.required' => 'The device name field is required.',
            'device_name.min' => 'Use more than 1 character.',
            'device_name.unique' => 'This device name already exists.',
            'device_address.required' => 'The device address field is required.',
            'device_address.min' => 'Use more than 3 characters.',
            'device_address.unique' => 'This device address already exists.',
        ];
        $this->crud->setValidation($rules, $messages);
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
        $rules = [
            'device_type' => 'required',
            'device_name' => [
                'required',
                'min:1',
                Rule::unique('devices', 'device_name')->ignore($this->crud->getCurrentEntryId()),
            ],
            'device_address' => [
                'required',
                'min:3',
                Rule::unique('devices', 'device_address')->ignore($this->crud->getCurrentEntryId()),
            ],
        ];
        $messages = [
            'device_type.required' => 'The device type field is required.',
            'device_name.required' => 'The device name field is required.',
            'device_name.min' => 'Use more than 1 character.',
            'device_name.unique' => 'This device name already exists.',
            'device_address.required' => 'The device address field is required.',
            'device_address.min' => 'Use more than 3 characters.',
            'device_address.unique' => 'This device address already exists.',
        ];
        $this->crud->setValidation($rules, $messages);
    }
}
