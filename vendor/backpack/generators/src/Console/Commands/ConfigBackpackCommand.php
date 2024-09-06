<?php

namespace Backpack\Generators\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class ConfigBackpackCommand extends GeneratorCommand
{
    use \Backpack\Generators\Console\Commands\Traits\PublishableStubTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'backpack:config';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backpack:config {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a backpack templated config';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Config';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return $this->getStubPath('config');
    }

    /**
     * Execute the console command.
     *
     * @return bool|null
     */
    public function handle()
    {
        $name = $this->getNameInput();

        $path = $this->getPath($name);

        if ($this->alreadyExists($this->getNameInput())) {
            $this->error($this->type.' already existed!');

            return false;
        }

        $this->makeDirectory($path);

        $this->files->put($path, $this->buildClass($name));

        $this->info($this->type.' created successfully.');
    }

    /**
     * Determine if the class already exists.
     *
     * @param  string  $name
     * @return bool
     */
    protected function alreadyExists($name)
    {
        return $this->files->exists($this->getPath($name));
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        return $this->laravel['path'].'/../config/'.str_replace('\\', '/', $name).'.php';
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        return $this->files->get($this->getStub());
    }
}
