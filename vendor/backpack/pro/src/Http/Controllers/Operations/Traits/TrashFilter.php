<?php

namespace Backpack\Pro\Http\Controllers\Operations\Traits;

use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Arr;

trait TrashFilter
{
    protected function setupTrashFilter()
    {
        if (CRUD::hasFilterWhere('name', 'trashed')) {
            return;
        }

        CRUD::addFilter(
            [
                'type'               => 'trashed',
                'name'               => 'trashed',
                'label'              => trans('backpack/pro::trash.trashed'),
                'deleteWithoutTrash' => CRUD::getOperationSetting('canDestroyNonTrashedItems'),
                'hideActionColumn'  => !in_array("Backpack\Pro\Http\Controllers\Operations\TrashOperation", class_uses($this), true)
            ],
            false,
            function () { // if the filter is active
                CRUD::addButton('line', 'restore', 'view', 'crud::buttons.restore');
                CRUD::addBaseClause('onlyTrashed');
                // remove all buttons except trash/bulkTrash operations buttons
                $buttons_to_remove = array_filter(Arr::pluck($this->crud->buttons(), 'name'), function ($item) {
                    return !in_array($item, ['destroy', 'restore', 'trash']);
                });

                CRUD::removeButtons($buttons_to_remove, 'line');
            },
            function () {
                CRUD::addBaseClause('withoutTrashed');
            }
        );
    }
}
