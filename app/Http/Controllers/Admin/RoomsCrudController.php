<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\RoomsRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class RoomsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class RoomsCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Rooms::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/rooms');
        CRUD::setEntityNameStrings('room', 'rooms');
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
        CRUD::column('floor_id')->type('select2')->entity('floorId')->model('App\Models\Floors')->label('Floor');

        /**
         * Columns can be defined using the fluent syntax:
         * - CRUD::column('price')->type('number');
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        // CRUD::setValidation(RoomsRequest::class);
        CRUD::setFromDb(); // set fields from db columns.
        CRUD::field('floor_id')->type('select2')->entity('floorId')->attribute('floor_name')->model('App\Models\Floors')->label('Floor');

        // validation rules
        $rules = [
            'room_name' => 'required|unique:rooms,room_name,NULL,id,floor_id,' . request()->floor_id,
            'floor_id' => 'required',
        ];

        $messages = [
            'room_name.required' => 'The room name field is required.',
            'floor_id.required' => 'The floor field is required.',
            'room_name.unique' => 'This room already exists in the selected floor.'
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
    }
}
