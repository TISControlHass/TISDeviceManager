<?php

namespace Backpack\CRUD\app\Console\Commands\Themes;

use Illuminate\Console\Command;

class RequireThemeCoreuiv2 extends Command
{
    use InstallsTheme;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backpack:require:theme-coreuiv2
                                {--debug} : Show process output or not. Useful for debugging.';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the CoreUIv2 theme';

    /**
     * Backpack addons install attribute.
     *
     * @var array
     */
    public static $addon = [
        'name' => 'CoreUIv2',
        'description' => [
            'UI provided by CoreUIv2, a Boostrap 4 template. Considered legacy, but useful for IE support.',
            '<fg=blue>https://github.com/laravel-backpack/theme-coreuiv2/</>',
        ],
        'repo' => 'backpack/theme-coreuiv2',
        'path' => 'vendor/backpack/theme-coreuiv2',
        'command' => 'backpack:require:theme-coreuiv2',
        'view_namespace' => 'backpack.theme-coreuiv2::',
        'publish_tag' => 'theme-coreuiv2-config',
        'provider' => '\Backpack\ThemeCoreuiv2\AddonServiceProvider',
    ];

    /**
     * Execute the console command.
     *
     * @return mixed Command-line output
     */
    public function handle()
    {
        $this->installTheme();
    }
}
