<?php

namespace Backpack\Generators\Console\Commands;

use Backpack\Generators\Services\BackpackCommand;
use Illuminate\Support\Str;

class CrudControllerBackpackCommand extends BackpackCommand
{
    use \Backpack\CRUD\app\Console\Commands\Traits\PrettyCommandOutput;
    use \Backpack\Generators\Console\Commands\Traits\PublishableStubTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'backpack:crud-controller';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backpack:crud-controller {name}
        {--validation=request : Validation type, must be request, array or field}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a Backpack CRUD controller';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Controller';

    /**
     * Execute the console command.
     *
     * @return bool|null
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        $name = $this->getNameInput();
        $nameTitle = $this->buildCamelName($name);
        $qualifiedClassName = $this->qualifyClass($nameTitle);
        $path = $this->getPath($qualifiedClassName);
        $relativePath = Str::of($path)->after(base_path())->trim('\\/');

        $this->progressBlock("Creating Controller <fg=blue>$relativePath</>");

        // Next, We will check to see if the class already exists. If it does, we don't want
        // to create the class and overwrite the user's code. So, we will bail out so the
        // code is untouched. Otherwise, we will continue generating this class' files.
        if ((! $this->hasOption('force') || ! $this->option('force')) && $this->alreadyExists($this->getNameInput())) {
            $this->closeProgressBlock('Already existed', 'yellow');

            return false;
        }

        // Next, we will generate the path to the location where this class' file should get
        // written. Then, we will build the class and make the proper replacements on the
        // stub files so that it gets the correctly formatted namespace and class name.
        $this->makeDirectory($path);

        $this->files->put($path, $this->sortImports($this->buildClass($nameTitle)));

        $this->closeProgressBlock();
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        $name = str_replace($this->laravel->getNamespace(), '', $name);

        return $this->laravel['path'].'/'.str_replace('\\', '/', $name).'CrudController.php';
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return $this->getStubPath('crud-controller');
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Http\Controllers\Admin';
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
        $nameTitle = Str::afterLast($name, '\\');
        $nameKebab = $this->buildKebabName($name);
        $nameSingular = $this->buildSingularName($nameKebab);
        $namePlural = $this->buildPluralName($nameSingular);

        $stub = str_replace('DummyModelClass', $this->convertSlashesForNamespace($nameTitle), $stub);
        $stub = str_replace('DummyClass', $this->buildClassName($nameTitle), $stub);
        $stub = str_replace('dummy-class', $nameKebab, $stub);
        $stub = str_replace('dummy singular', $nameSingular, $stub);
        $stub = str_replace('dummy plural', $namePlural, $stub);

        return $this;
    }

    protected function getAttributes($model)
    {
        $attributes = [];
        $model = new $model();

        // if fillable was defined, use that as the attributes
        if (count($model->getFillable())) {
            $attributes = $model->getFillable();
        } else {
            // otherwise, if guarded is used, just pick up the columns straight from the bd table
            $attributes = \Schema::getColumnListing($model->getTable());
        }

        return $attributes;
    }

    /**
     * Replace the table name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceSetFromDb(&$stub, $name)
    {
        $class = Str::afterLast($name, '\\');
        $model = "App\\Models\\$class";

        if (! class_exists($model)) {
            return $this;
        }

        $attributes = $this->getAttributes($model);
        $fields = array_diff($attributes, ['id', 'created_at', 'updated_at', 'deleted_at']);
        $columns = array_diff($attributes, ['id']);
        $glue = PHP_EOL.'        ';

        // create an array with the needed code for defining fields
        $fields = collect($fields)
            ->map(function ($field) {
                return "CRUD::field('$field');";
            })
            ->join($glue);

        // create an array with the needed code for defining columns
        $columns = collect($columns)
            ->map(function ($column) {
                return "CRUD::column('$column');";
            })
            ->join($glue);

        // replace setFromDb with actual fields and columns
        $stub = str_replace('CRUD::setFromDb(); // fields', $fields, $stub);
        $stub = str_replace('CRUD::setFromDb(); // columns', $columns, $stub);

        return $this;
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceModel(&$stub, $name)
    {
        $class = str_replace($this->getNamespace($name).'\\', '', $name);
        $stub = str_replace(['DummyClass', '{{ class }}', '{{class}}'], $this->buildClassName($name), $stub);

        return $this;
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceRequest(&$stub)
    {
        $validation = $this->option('validation');

        // replace request class when validation is array
        if ($validation === 'array') {
            $stub = str_replace('DummyClassRequest::class', "[\n            // 'name' => 'required|min:2',\n        ]", $stub);
        }

        // remove the validation class when validation is field
        if ($validation === 'field') {
            $stub = str_replace("        CRUD::setValidation(DummyClassRequest::class);\n\n", '', $stub);
        }

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

        $this->replaceNamespace($stub, $this->qualifyClass($name))
            ->replaceRequest($stub)
            ->replaceNameStrings($stub, $this->buildCamelName($name))
            ->replaceModel($stub, $this->buildCamelName($name))
            ->replaceSetFromDb($stub, $this->buildCamelName($name));

        return $stub;
    }
}
