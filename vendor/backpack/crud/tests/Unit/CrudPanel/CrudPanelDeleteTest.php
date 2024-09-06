<?php

namespace Backpack\CRUD\Tests\Unit\CrudPanel;

use Backpack\CRUD\Tests\config\Models\Article;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

/**
 * @covers Backpack\CRUD\app\Library\CrudPanel\Traits\Delete
 */
class CrudPanelDeleteTest extends \Backpack\CRUD\Tests\config\CrudPanel\BaseDBCrudPanel
{
    public function testDelete()
    {
        $this->markTestIncomplete('Not correctly implemented');

        $this->crudPanel->setModel(Article::class);
        $article = Article::find(1);

        $wasDeleted = $this->crudPanel->delete($article->id);

        // TODO: the delete method should not convert the returned result to a string
        $deletedArticle = Article::find(1);
        $this->assertTrue($wasDeleted);
        $this->assertNull($deletedArticle);
    }

    public function testDeleteUnknown()
    {
        $this->expectException(ModelNotFoundException::class);

        $this->crudPanel->setModel(Article::class);
        $unknownId = DB::getPdo()->lastInsertId() + 1;

        $this->crudPanel->delete($unknownId);
    }

    public function testItAddsTheBulkDeleteButton()
    {
        $this->crudPanel->addBulkDeleteButton();
        $this->assertCount(1, $this->crudPanel->buttons());
    }
}
