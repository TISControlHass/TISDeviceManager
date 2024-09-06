<?php

namespace Backpack\Generators\Console\Commands\Views;

class ColumnBackpackCommand extends PublishOrCreateViewBackpackCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'backpack:column';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backpack:column {name?} {--from=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a Backpack column';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Column';

    /**
     * View Namespace.
     *
     * @var string
     */
    protected $viewNamespace = 'columns';

    /**
     * Stub file name.
     *
     * @var string
     */
    protected $stub = 'column.stub';
}
