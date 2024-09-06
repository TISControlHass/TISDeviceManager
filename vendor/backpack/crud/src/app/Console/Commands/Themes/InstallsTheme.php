<?php

namespace Backpack\CRUD\app\Console\Commands\Themes;

trait InstallsTheme
{
    use \Backpack\CRUD\app\Console\Commands\Traits\PrettyCommandOutput;
    use \Backpack\CRUD\app\Console\Commands\Traits\AddonsHelper;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'backpack:require:theme-name {--debug} : Show process output or not. Useful for debugging.';

    /**
     * The console command description.
     *
     * @var string
     */
    // protected $description = 'Install Backpack\'s XXX Theme';

    /**
     * Backpack addons install attribute.
     *
     * @var array
     */
    // public static $addon = [
    // 'name'        => 'CoreUIv2',
    // 'description' => [
        //     'UI provided by CoreUIv2, a Boostrap 4 template.',
        //     '<fg=blue>https://github.com/laravel-backpack/theme-coreuiv2/</>',
    // ],
    // 'repo'    => 'backpack/theme-coreuiv2',
    // 'path'    => 'vendor/backpack/theme-coreuiv2',
    // 'command' => 'backpack:require:theme-coreuiv2',
    // 'view_namespace' => 'backpack.theme-coreuiv2::',
    // 'publish_tag' => 'theme-coreuiv2-config',
    // ];

    /**
     * Run the theme installation process.
     *
     * @return void
     */
    public function installTheme()
    {
        // Check if it is installed
        if ($this->isInstalled()) {
            $this->newLine();
            $this->line(sprintf('  %s was already installed', self::$addon['name']), 'fg=red');
            $this->newLine();

            return;
        }

        $this->newLine();
        $this->progressBlock($this->description);

        // Require package
        try {
            $this->composerRequire(self::$addon['repo']);
            $this->closeProgressBlock();
        } catch (\Throwable $e) {
            $this->errorProgressBlock();
            $this->line('  '.$e->getMessage(), 'fg=red');
            $this->newLine();

            return;
        }

        // Display general error in case it failed
        if (! $this->isInstalled()) {
            $this->errorProgressBlock();
            $this->note('For further information please check the log file.');
            $this->note('You can also follow the manual installation process documented on Github.');
            $this->newLine();

            return;
        }

        // Publish the theme config file
        $this->progressBlock('Publish theme config file');

        // manually include the provider in the run-time
        if (! class_exists(self::$addon['provider'])) {
            include self::$addon['provider_path'] ?? self::$addon['path'].'/src/AddonServiceProvider.php';
            app()->register(self::$addon['provider']);
        }

        $this->executeArtisanProcess('vendor:publish', [
            '--tag' => self::$addon['publish_tag'],
        ]);
        $this->closeProgressBlock();

        // add this theme's view namespace to the ui config file
        $this->progressBlock('Use theme as view namespace in <fg=blue>config/backpack/ui.php</>');
        $this->useViewNamespaceInConfigFile();
        $this->closeProgressBlock();
        $this->newLine();
    }

    public function isInstalled()
    {
        return file_exists(self::$addon['path'].'/composer.json');
    }

    public function useViewNamespaceInConfigFile()
    {
        $config_file = config_path('backpack/ui.php');
        $config_contents = file_get_contents($config_file);
        $config_contents = preg_replace("/'view_namespace' => '.*'/", "'view_namespace' => '".self::$addon['view_namespace']."'", $config_contents);
        $config_contents = preg_replace("/'view_namespace_fallback' => '.*'/", "'view_namespace_fallback' => '".self::$addon['view_namespace']."'", $config_contents);

        file_put_contents($config_file, $config_contents);
    }
}
