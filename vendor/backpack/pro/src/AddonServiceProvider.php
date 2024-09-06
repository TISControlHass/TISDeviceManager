<?php

namespace Backpack\Pro;

use Backpack\Pro\Console\Commands\PurgeTemporaryFolder;
use Illuminate\Support\ServiceProvider;

class AddonServiceProvider extends ServiceProvider
{
    use AutomaticServiceProvider { bootForConsole as traitBootForConsole; }

    protected $vendorName = 'backpack';

    protected $packageName = 'pro';

    protected $commands = [
        PurgeTemporaryFolder::class,
    ];

    public function boot()
    {
        $this->autoboot();
        app('UploadersRepository')->addUploaderClasses([
            'dropzone' => \Backpack\Pro\Uploads\AjaxUploader::class,
        ], 'withFiles');
    }

    public function register()
    {
        $this->autoRegister();
        $this->mergeConfigFrom(__DIR__.'/../config/operations/dropzone.php', 'backpack.operations.dropzone');
    }

    protected function bootForConsole(): void
    {
        $this->traitBootForConsole();

        $this->publishes([
            __DIR__.'/../config/operations/dropzone.php' => config_path('vendor/backpack/operations/dropzone.php'),
        ], 'dropzone-config');
    }
}
