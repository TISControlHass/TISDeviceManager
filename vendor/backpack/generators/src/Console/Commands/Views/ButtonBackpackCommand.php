<?php

namespace Backpack\Generators\Console\Commands\Views;

class ButtonBackpackCommand extends PublishOrCreateViewBackpackCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'backpack:button';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backpack:button {name?} {--from=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a Backpack button';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Button';

    /**
     * View Namespace.
     *
     * @var string
     */
    protected $viewNamespace = 'buttons';

    /**
     * Stub file name.
     *
     * @var string
     */
    protected $stub = 'button.stub';
}
