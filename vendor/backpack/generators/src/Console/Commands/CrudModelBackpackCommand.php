<?php

namespace Backpack\Generators\Console\Commands;

use Backpack\Generators\Services\BackpackCommand;
use Illuminate\Support\Str;

class CrudModelBackpackCommand extends BackpackCommand
{
    use \Backpack\CRUD\app\Console\Commands\Traits\PrettyCommandOutput;
    use \Backpack\Generators\Console\Commands\Traits\PublishableStubTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'backpack:crud-model';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backpack:crud-model {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a Backpack CRUD model';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Model';

    /**
     * The trait that allows a model to have an admin panel.
     *
     * @var string
     */
    protected $crudTrait = 'Backpack\CRUD\app\Models\Traits\CrudTrait';

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
        $namespaceApp = $this->qualifyClass($nameTitle);
        $namespaceModels = $this->qualifyClass('/Models/'.$nameTitle);
        $relativePath = $this->buildRelativePath($namespaceModels);

        $this->progressBlock("Creating Model <fg=blue>$relativePath</>");

        // Check if exists on app or models
        $existsOnApp = $this->alreadyExists($namespaceApp);
        $existsOnModels = $this->alreadyExists($namespaceModels);

        // If no model was found, we will generate the path to the location where this class file
        // should be written. Then, we will build the class and make the proper replacements on
        // the stub files so that it gets the correctly formatted namespace and class name.
        if (! $existsOnApp && ! $existsOnModels) {
            $this->makeDirectory($this->getPath($namespaceModels));

            $this->files->put($this->getPath($namespaceModels), $this->sortImports($this->buildClass($nameTitle)));

            $this->closeProgressBlock();

            return false;
        }

        // Model exists
        $this->closeProgressBlock('Already existed', 'yellow');

        // If it was found on both namespaces, we'll ask user to pick one of them
        if ($existsOnApp && $existsOnModels) {
            $result = $this->choice('Multiple models with this name were found, which one do you want to use?', [
                1 => "Use $namespaceApp",
                2 => "Use $namespaceModels",
            ]);

            // Disable the namespace not selected
            $existsOnApp = $result === 1;
            $existsOnModels = $result === 2;
        }

        $name = $existsOnApp ? $namespaceApp : $namespaceModels;
        $path = $this->getPath($name);

        // As the class already exists, we don't want to create the class and overwrite the
        // user's code. We just make sure it uses CrudTrait. We add that one line.
        if (! $this->hasOption('force') || ! $this->option('force')) {
            $this->progressBlock('Adding CrudTrait to the Model');

            $content = Str::of($this->files->get($path));

            // check if it already uses CrudTrait
            // if it does, do nothing
            if ($content->contains($this->crudTrait)) {
                $this->closeProgressBlock('Already existed', 'yellow');

                return false;
            } else {
                $modifiedContent = Str::of($content->before('namespace'))
                                    ->append('namespace'.$content->after('namespace')->before(';'))
                                    ->append(';'.PHP_EOL.PHP_EOL.'use Backpack\CRUD\app\Models\Traits\CrudTrait;');

                $content = $content->after('namespace')->after(';');

                while (str_starts_with($content, PHP_EOL) || str_starts_with($content, "\n")) {
                    $content = substr($content, 1);
                }

                $modifiedContent = $modifiedContent->append(PHP_EOL.$content);

                // use the CrudTrait on the class
                $modifiedContent = $modifiedContent->replaceFirst('{', '{'.PHP_EOL.'    use CrudTrait;');

                // save the file
                $this->files->put($path, $modifiedContent);
                // let the user know what we've done
                $this->closeProgressBlock();

                return true;
            }
            // In case we couldn't add the CrudTrait
            $this->errorProgressBlock();
            $this->note("Model already existed on '$name' and we couldn't add CrudTrait. Please add it manually.", 'red');
        }
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return $this->getStubPath('crud-model');
    }

    /**
     * Replace the table name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceTable(&$stub, $name)
    {
        $name = str_replace('/', '', $this->buildCamelName($name));
        $name = ltrim(strtolower(preg_replace('/[A-Z]/', '_$0', $name)), '_');

        $table = Str::snake(Str::plural($name));

        $stub = str_replace('DummyTable', $table, $stub);

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

        return $this->replaceNamespace($stub, $this->qualifyClass('/Models/'.$name))->replaceTable($stub, $name)->replaceClass($stub, $this->buildClassName($name));
    }
}
