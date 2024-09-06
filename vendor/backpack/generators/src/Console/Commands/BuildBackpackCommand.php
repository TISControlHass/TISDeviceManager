<?php

namespace Backpack\Generators\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BuildBackpackCommand extends Command
{
    use \Backpack\CRUD\app\Console\Commands\Traits\PrettyCommandOutput;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backpack:build
        {--validation=request : Validation type, must be request, array or field}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create CRUDs for all Models that don\'t already have one.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // make a list of all models
        $models = $this->getModels(base_path('app'));

        if (! count($models)) {
            $this->errorBlock('No models found.');

            return false;
        }

        foreach ($models as $model) {
            $this->call('backpack:crud', ['name' => $model, '--validation' => $this->option('validation')]);
            $this->line('  <fg=gray>----------</>');
        }

        $this->deleteLines();
    }

    private function getModels(string $path): array
    {
        $out = [];
        $results = scandir($path);

        foreach ($results as $result) {
            $filepath = "$path/$result";

            // ignore `.` (dot) prefixed files
            if ($result[0] === '.') {
                continue;
            }

            if (is_dir($filepath)) {
                $out = array_merge($out, $this->getModels($filepath));
                continue;
            }

            // Try to load it by path as namespace
            $class = (string) Str::of($filepath)
                ->after(base_path())
                ->trim('\\/')
                ->replace('/', '\\')
                ->before('.php')
                ->ucfirst();

            $result = $this->validateModelClass($class);
            if ($result) {
                $out[] = $result;
                continue;
            }

            // Try to load it from file content
            $fileContent = Str::of(file_get_contents($filepath));
            $namespace = $fileContent->match('/namespace (.*);/')->value();
            $classname = $fileContent->match('/class (\w+)/')->value();

            $result = $this->validateModelClass("$namespace\\$classname");
            if ($result) {
                $out[] = $result;
                continue;
            }
        }

        return $out;
    }

    private function validateModelClass(string $class): ?string
    {
        try {
            $reflection = new \ReflectionClass($class);

            if ($reflection->isSubclassOf(Model::class) && ! $reflection->isAbstract()) {
                return Str::of($class)->afterLast('\\');
            }
        } catch (\Throwable$e) {
        }

        return null;
    }
}
