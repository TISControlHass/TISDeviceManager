<?php

namespace Backpack\CRUD\Tests\Unit\CrudPanel;

use Backpack\CRUD\Tests\config\CrudPanel\BaseCrudPanel;

/**
 * @covers Backpack\CRUD\app\Library\CrudPanel\Traits\Autofocus
 */
class CrudPanelAutofocusTest extends BaseCrudPanel
{
    public function testItCanEnableAndDisableAutofocus()
    {
        $this->crudPanel->setOperation('create');
        $this->crudPanel->enableAutoFocus();
        $this->assertTrue($this->crudPanel->getAutoFocusOnFirstField());
        $this->crudPanel->disableAutofocus();
        $this->assertFalse($this->crudPanel->getAutoFocusOnFirstField());
    }
}
