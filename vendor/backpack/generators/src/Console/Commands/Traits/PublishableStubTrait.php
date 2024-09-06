<?php

namespace Backpack\Generators\Console\Commands\Traits;

use Illuminate\Support\Str;

trait PublishableStubTrait
{
    /**
     * Return the path to the stub.
     */
    public function getStubPath(string $path): string
    {
        $path = Str::finish($path, '.stub');

        if (file_exists(base_path("stubs/backpack/generators/{$path}"))) {
            return base_path("stubs/backpack/generators/{$path}");
        }

        return __DIR__."/../../stubs/{$path}";
    }
}
