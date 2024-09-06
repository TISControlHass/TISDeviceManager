<?php

namespace Backpack\Generators\Console\Commands\Views;

class FieldBackpackCommand extends PublishOrCreateViewBackpackCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'backpack:field';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backpack:field {name?} {--from=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a Backpack field';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Field';

    /**
     * View Namespace.
     *
     * @var string
     */
    protected $viewNamespace = 'fields';

    /**
     * Stub file name.
     *
     * @var string
     */
    protected $stub = 'field.stub';
}
