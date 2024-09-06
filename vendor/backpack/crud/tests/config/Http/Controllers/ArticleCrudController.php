<?php

namespace Backpack\CRUD\Tests\Config\Http\Controllers;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\Tests\config\Models\Article;

class ArticleCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel(Article::class);
        $this->crud->setRoute('articles');
    }

    public function setupUpdateOperation()
    {
    }

    protected function create()
    {
        return response('create');
    }

    protected function detail()
    {
        return response('detail');
    }
}
