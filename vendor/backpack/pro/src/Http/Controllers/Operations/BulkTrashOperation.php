<?php

namespace Backpack\Pro\Http\Controllers\Operations;

use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\Pro\Http\Controllers\Operations\Traits\TrashFilter;
use Illuminate\Support\Facades\Route;

trait BulkTrashOperation
{
    use TrashFilter;

    /**
     * Define which routes are needed for this operation.
     *
     * @param  string  $segment  Name of the current entity (singular). Used as first URL segment.
     * @param  string  $routeName  Prefix of the route name.
     * @param  string  $controller  Name of the current CrudController.
     */
    protected function setupbulkTrashRoutes($segment, $routeName, $controller)
    {
        Route::post($segment.'/bulk-trash', [
            'as'        => $routeName.'.bulkTrash',
            'uses'      => $controller.'@bulkTrash',
            'operation' => 'bulkTrash',
        ]);
        Route::post($segment.'/bulk-restore', [
            'as'        => $routeName.'.bulkRestore',
            'uses'      => $controller.'@bulkRestore',
            'operation' => 'bulkTrash',
        ]);
        Route::post($segment.'/bulk-destroy', [
            'as'        => $routeName.'.bulkDestroy',
            'uses'      => $controller.'@bulkDestroy',
            'operation' => 'bulkTrash',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupbulkTrashDefaults()
    {
        CRUD::allowAccess(['bulkTrash', 'bulkDestroy', 'bulkRestore']);

        CRUD::operation(['list'], function () {
            //setup Default Behaviour
            CRUD::setOperationSetting('canDestroyNonTrashedItems', CRUD::getOperationSetting('canDestroyNonTrashedItems') ?? false);
            CRUD::setOperationSetting('withTrashFilter', CRUD::getOperationSetting('withTrashFilter') ?? true);

            // fetch new configration if exists
            if (method_exists($this, 'setupTrashOperation')) {
                $this->setupTrashOperation();
            }
            //add buttons and enable bulk actions
            CRUD::enableBulkActions();

            CRUD::addButton('bottom', 'bulk_trash', 'view', 'crud::buttons.bulk_trash');
            CRUD::addButton('bottom', 'bulk_restore', 'view', 'crud::buttons.bulk_restore');
            CRUD::addButton('bottom', 'bulk_destroy', 'view', 'crud::buttons.bulk_destroy');

            // add filter in list view if enabled and BulkTrashOperation is not used
            if (CRUD::getOperationSetting('withTrashFilter') && CRUD::getOperation() == 'list') {
                $this->setupTrashFilter();
            } else {
                // if filter is not enabled on page show all entries
                $this->crud->query->withTrashed();
            }
        });
    }

    /**
     * Trash multiple entries in one go.
     *
     * @return string
     */
    public function bulkTrash()
    {
        CRUD::hasAccessOrFail('bulkTrash');

        $entries = CRUD::getModel()->whereIn(CRUD::getModel()->getKeyName(), request()->input('entries', []))->get();
        $trashedEntries = [];

        foreach ($entries as $entry) {
            if (CRUD::hasAccess('bulkTrash', $entry)) {
                $trashedEntries[] = $entry->delete();
            }
        }

        return $trashedEntries;
    }

    /**
     * Restore multiple entries in one go.
     *
     * @return string
     */
    public function bulkRestore()
    {
        CRUD::hasAccessOrFail('bulkRestore');

        $entries = CRUD::getModel()->whereIn(CRUD::getModel()->getKeyName(), request()->input('entries', []))->onlyTrashed()->get();
        $restoredEntries = [];

        foreach ($entries as $entry) {
            $restoredEntries[] = $entry->restore();
        }

        return $restoredEntries;
    }

    /**
     * Delete multiple entries in one go.
     *
     * @return string
     */
    public function bulkDestroy()
    {
        CRUD::hasAccessOrFail('bulkDestroy');

        $entries = CRUD::getModel()->whereIn(CRUD::getModel()->getKeyName(), request()->input('entries', []))->withTrashed()->get();
        $deletedEntries = [];

        foreach ($entries as $entry) {
            $deletedEntries[] = $entry->forceDelete();
        }

        return $deletedEntries;
    }
}
