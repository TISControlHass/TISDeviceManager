<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ApplianceChannelsRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\VirtualDevice;
use App\Models\Device;

/**
 * Class ApplianceChannelsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ApplianceChannelsCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    protected $all_devices; // Declare the $all_devices property
    protected $channel_types = ['input' => 'Input', 'output' => 'Output', 'ac' => 'AC', 'floor_heating' => 'Floor Heating', 'virtual_input' => 'Virtual Input', 'virtual_output' => 'Virtual Output'];


    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\ApplianceChannels::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/appliance-channels');
        CRUD::setEntityNameStrings('appliance channels', 'appliance channels');
        $devices = Device::all();
        $virtual_devices = VirtualDevice::all();
        $all_devices = [];

        foreach ($devices as $device) {
            $all_devices[$device->id] = $device->device_name;
        }

        foreach ($virtual_devices as $virtual_device) {
            $all_devices[$virtual_device->id] = "offline - " . $virtual_device->device_name;
        }

        $this->all_devices = $all_devices; // Assign the $all_devices value to the property
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
        CRUD::removeButton('create');
        CRUD::removeButton('delete');
        CRUD::column("appliance_id")->type("select2")->label("Appliance")->entity('applianceId')->attribute("appliance_name")->model("App\Models\Appliance");
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {

        CRUD::setValidation(ApplianceChannelsRequest::class);
        CRUD::setFromDb(); // set fields from db columns.
        CRUD::field("appliance_id")->type("select2")->label("Appliance")->attribute("appliance_name")->model("App\Models\Appliance");
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
