<?php

namespace Backpack\Generators\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class PageBackpackCommand extends GeneratorCommand
{
    use \Backpack\CRUD\app\Console\Commands\Traits\PrettyCommandOutput;
    use \Backpack\Generators\Console\Commands\Traits\PublishableStubTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'backpack:page';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backpack:page {name}
        {--view-path=admin : Path for the view, after resources/views/}
        {--route= : Path for the route, after admin/}
        {--layout=blank : Base layout for the page}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a Backpack Page';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Resource';

    /**
     * Execute the console command.
     *
     * @return bool|null
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        $input = Str::of($this->getNameInput())
            ->replace('\\', '/')
            ->replace('.', '/')
            ->start('/')
            ->prepend($this->option('view-path'))
            ->replace('\\', '/')
            ->replace('//', '/')
            ->trim('/');

        $name = $input->afterLast('/');
        $nameTitle = $name->snake()->replace('-', ' ')->replace('_', ' ')->title();
        $nameSnake = $nameTitle->snake();

        $path = $input->beforeLast($name)->trim('/\\');
        $filePath = Str::of("$path/$nameSnake")->trim('/\\');
        $fullPath = $this->getPath($filePath);
        $layout = Str::of($this->option('layout'))->replace('\\', '/')->replace('/', '.');
        $route = Str::of($this->option('route') ?? $nameSnake)->replace('\\', '/')->trim('/');

        $this->infoBlock("Creating {$nameTitle} page");

        $this->progressBlock("Creating view <fg=blue>resources/views/{$filePath}.blade.php</>");

        // check if the file already exists
        if ((! $this->hasOption('force') || ! $this->option('force')) && $this->alreadyExists($filePath)) {
            $this->closeProgressBlock('Already existed', 'yellow');

            return false;
        }

        $this->makeDirectory($fullPath);

        // create page view
        $stub = $this->buildClass($filePath);
        $stub = str_replace('layout', $layout, $stub);
        $stub = str_replace('Dummy Name', $nameTitle, $stub);
        $this->files->put($fullPath, $stub);

        $this->closeProgressBlock();

        // create controller
        $this->call('backpack:page-controller', [
            'name' => $nameTitle,
            '--view-path' => $path,
        ]);

        // create route
        $this->call('backpack:add-custom-route', [
            'code' => "Route::get('{$route}', '{$nameTitle->studly()}Controller@index')->name('page.{$nameSnake}.index');",
        ]);

        // create the menu item
        $this->call('backpack:add-menu-content', [
            'code' => '<x-backpack::menu-item title="'.$nameTitle."\" icon=\"la la-question\" :link=\"backpack_url('".$route."')\" />",
        ]);

        $url = backpack_url($route);

        $this->newLine();
        $this->note("Page {$nameTitle} created.");
        $this->note("Go to <fg=blue>{$url}</> to access your new page.");
        $this->newLine();
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return $this->getStubPath('page');
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
        return resource_path("views/$name.blade.php");
    }
}
