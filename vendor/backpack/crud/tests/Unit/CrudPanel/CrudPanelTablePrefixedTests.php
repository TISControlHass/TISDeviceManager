<?php

namespace Backpack\CRUD\Tests\Unit\CrudPanel;

use Backpack\CRUD\Tests\config\Models\User;

/**
 * @covers Backpack\CRUD\app\Library\CrudPanel\CrudPanel
 */
class CrudPanelTablePrefixedTests extends \Backpack\CRUD\Tests\config\CrudPanel\BasePrefixedDBCrudPanel
{
    public function testGetColumnTypeFromColumnNameWithPrefixedDatabase()
    {
        $model = new User();

        $this->assertEquals('string', $model->getColumnType('name'));
    }

    public function testGetTableNamePrefixed()
    {
        $model = new User();
        $this->assertEquals('test_users', $model->getTableWithPrefix());
        $this->assertEquals('users', $model->getTable());
    }
}
