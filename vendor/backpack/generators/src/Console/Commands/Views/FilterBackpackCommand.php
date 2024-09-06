<?php

namespace Backpack\Generators\Console\Commands\Views;

class FilterBackpackCommand extends PublishOrCreateViewBackpackCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'backpack:filter';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backpack:filter {name?} {--from=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a Backpack filter';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Filter';

    /**
     * View Namespace.
     *
     * @var string
     */
    protected $viewNamespace = 'filters';

    /**
     * Stub file name.
     *
     * @var string
     */
    protected $stub = 'filter.stub';
}
