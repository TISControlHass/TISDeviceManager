<?php

namespace Backpack\Generators\Console\Commands;

use Backpack\Generators\Services\BackpackCommand;
use Illuminate\Support\Str;

class CrudBackpackCommand extends BackpackCommand
{
    use \Backpack\CRUD\app\Console\Commands\Traits\PrettyCommandOutput;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backpack:crud {name}
        {--validation= : Validation type, must be request, array or field}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a CRUD interface: Controller, Model, Request';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->getNameInput();
        $nameTitle = $this->buildCamelName($name);
        $nameKebab = $this->buildKebabName($nameTitle);
        $fullNameWithSpaces = $this->buildNameWithSpaces($nameTitle);

        // Validate if the name is reserved
        if ($this->isReservedName($nameTitle)) {
            $this->errorBlock("The name '$nameTitle' is reserved by PHP.");

            return false;
        }

        $this->infoBlock("Creating CRUD for the <fg=blue>$nameTitle</> model:");

        // Validate validation option
        $validation = $this->handleValidationOption();
        if (! $validation) {
            return false;
        }

        // Create the CRUD Model and show output
        $this->call('backpack:crud-model', ['name' => $name]);

        // Create the CRUD Controller and show output
        $this->call('backpack:crud-controller', ['name' => $name, '--validation' => $validation]);

        // Create the CRUD Request and show output
        if ($validation === 'request') {
            $this->call('backpack:crud-request', ['name' => $name]);
        }

        // Create the CRUD route
        $this->call('backpack:add-custom-route', [
            'code' => "Route::crud('$nameKebab', '{$this->convertSlashesForNamespace($nameTitle)}CrudController');",
        ]);

        // Create the menu item
        $this->call('backpack:add-menu-content', [
            'code' => '<x-backpack::menu-item title="'.$fullNameWithSpaces."\" icon=\"la la-question\" :link=\"backpack_url('".$nameKebab."')\" />",
        ]);

        // if the application uses cached routes, we should rebuild the cache so the previous added route will
        // be acessible without manually clearing the route cache.
        if (app()->routesAreCached()) {
            $this->call('route:cache');
        }

        $url = Str::of(config('app.url'))->finish('/')->append('admin/')->append($nameKebab);

        $this->newLine();
        $this->line("  Done! Go to <fg=blue>$url</> to see the CRUD in action.");
        $this->newLine();
    }

    /**
     * Handle validation Option.
     *
     * @return string
     */
    private function handleValidationOption()
    {
        $options = ['request', 'array', 'field'];

        // Validate validation option
        $validation = $this->option('validation');

        if (! $validation) {
            $validation = $this->askHint(
                'How would you like to define your validation rules, for the Create and Update operations?', [
                    'More info at <fg=blue>https://backpackforlaravel.com/docs/5.x/crud-operation-create#validation</>',
                    'Valid options are <fg=blue>request</>, <fg=blue>array</> or <fg=blue>field</>',
                ], $options[0]);

            if (! $this->option('no-interaction')) {
                $this->deleteLines(5);
            }
        }

        if (! in_array($validation, $options)) {
            $this->errorBlock("The validation must be request, array or field. '$validation' is not valid.");

            return false;
        }

        return $validation;
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return false;
    }
}
