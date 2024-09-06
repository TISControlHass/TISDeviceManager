<?php

namespace Backpack\Pro\Http\Controllers\Operations;

use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\Pro\Http\Controllers\Operations\Traits\TrashFilter;
use Illuminate\Support\Facades\Route;

trait TrashOperation
{
    use TrashFilter;

    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupTrashRoutes($segment, $routeName, $controller)
    {
        Route::delete($segment.'/{id}/trash', [
            'as'        => $routeName.'.trash',
            'uses'      => $controller.'@trash',
            'operation' => 'trash',
        ]);
        Route::delete($segment.'/{id}/destroy', [
            'as'        => $routeName.'.destroy',
            'uses'      => $controller.'@destroy',
            'operation' => 'trash',
        ]);
        Route::put($segment.'/{id}/restore', [
            'as'        => $routeName.'.restore',
            'uses'      => $controller.'@restore',
            'operation' => 'trash',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupTrashDefaults()
    {
        CRUD::allowAccess(['trash', 'destroy', 'restore']);

        CRUD::operation(['list', 'show'], function () {
            //setup Default Behaviour
            CRUD::setOperationSetting('canDestroyNonTrashedItems', CRUD::getOperationSetting('canDestroyNonTrashedItems') ?? false);
            CRUD::setOperationSetting('withTrashFilter', CRUD::getOperationSetting('withTrashFilter') ?? true);

            // fetch new configration if exists
            if (method_exists($this, 'setupTrashOperation')) {
                $this->setupTrashOperation();
            }

            //add buttons
            CRUD::addButton('line', 'trash', 'view', 'crud::buttons.trash');
            CRUD::addButton('line', 'restore', 'view', 'crud::buttons.restore');
            CRUD::addButton('line', 'destroy', 'view', 'crud::buttons.destroy');

            // add filter in list view if enabled
            if (CRUD::getOperationSetting('withTrashFilter') && CRUD::getOperation() == 'list') {
                $this->setupTrashFilter();
            } else {
                // if filter is not enabled on page show all entries
                $this->crud->query->withTrashed();
            }
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return string
     */
    public function destroy($id)
    {
        CRUD::hasAccessOrFail('destroy');

        $id = CRUD::getCurrentEntryId() ?? $id;

        return $this->crud->query->withTrashed()->find($id)->forceDelete();
    }

    /**
     * Trash the specified resource from storage.
     *
     * @param  int  $id
     * @return string
     */
    public function trash($id)
    {
        CRUD::hasAccessOrFail('trash');

        $id = CRUD::getCurrentEntryId() ?? $id;

        return CRUD::delete($id);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  int  $id
     * @return string
     */
    public function restore($id)
    {
        CRUD::hasAccessOrFail('restore');

        $id = CRUD::getCurrentEntryId() ?? $id;

        return $this->crud->query->onlyTrashed()->find($id)->restore();
    }
}
