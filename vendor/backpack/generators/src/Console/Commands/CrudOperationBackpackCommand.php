<?php

namespace Backpack\Generators\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class CrudOperationBackpackCommand extends GeneratorCommand
{
    use \Backpack\Generators\Console\Commands\Traits\PublishableStubTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'backpack:crud-operation';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backpack:crud-operation {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a Backpack CRUD operation trait';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Trait';

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        $name = str_replace($this->laravel->getNamespace(), '', $name);

        return $this->laravel['path'].'/'.str_replace('\\', '/', $name).'Operation.php';
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return $this->getStubPath('crud-operation');
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Http\Controllers\Admin\Operations';
    }

    /**
     * Replace the table name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceNameStrings(&$stub, $name)
    {
        $name = Str::of($name)->afterLast('\\');

        $stub = str_replace('DummyClass', $name->studly(), $stub);
        $stub = str_replace('dummyClass', $name->lcfirst(), $stub);
        $stub = str_replace('Dummy Class', $name->snake()->replace('_', ' ')->title(), $stub);
        $stub = str_replace('dummy-class', $name->snake('-'), $stub);
        $stub = str_replace('dummy_class', $name->snake(), $stub);

        return $this;
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        return $this
            ->replaceNamespace($stub, $name)
            ->replaceNameStrings($stub, $name)
            ->replaceClass($stub, $name);
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInput()
    {
        return Str::of($this->argument('name'))
            ->trim()
            ->studly();
    }
}
