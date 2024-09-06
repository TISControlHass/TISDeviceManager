<?php

namespace Backpack\Generators\Services;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

abstract class BackpackCommand extends GeneratorCommand
{
    private const STR_CAMEL = 'camel';

    private const STR_KEBAB = 'kebab';

    public function buildCamelName(string $name): string
    {
        return self::buildName($name, self::STR_CAMEL);
    }

    public function buildKebabName(string $name): string
    {
        return self::buildName($name, self::STR_KEBAB);
    }

    public function buildName(string $name, string $type): string
    {
        $nameTitles = explode('/', $name);
        $nameTitle = '';

        $applyCasing = fn (string $title) => $type === self::STR_CAMEL ? ucfirst($title) : strtolower($title);

        foreach ($nameTitles as $key => $title) {
            $nameTitle .= $key > 0 ? '/' : '';
            $nameTitle .= $applyCasing(Str::$type($title));
        }

        return $nameTitle;
    }

    public function buildPluralName(string $nameKebab)
    {
        return Str::plural(str_replace('-', ' ', Arr::last(explode('/', $nameKebab))));
    }

    public function buildNameWithSpaces(string $nameTitle): string
    {
        $words = preg_split('/(?=[A-Z])/', str_replace('/', '', $nameTitle));

        // Transform last word into plural
        $lastWord = Arr::last($words);
        array_pop($words);
        $words[] = Str::plural($lastWord);

        $name = [];

        foreach ($words as $word) {
            if ($word === '') {
                continue;
            }
            $name[] = count($name) === 0 ? ucfirst($word) : strtolower($word);
        }

        return implode(' ', $name);
    }

    public function buildSingularName(string $nameKebab)
    {
        return str_replace('-', ' ', Arr::last(explode('/', $nameKebab)));
    }

    public function buildRelativePath(string $name): string
    {
        return lcfirst(Str::of("$name.php")->replace('\\', '/'));
    }

    public function buildClassName(string $name): string
    {
        return ucfirst(Arr::last(explode('/', $name)));
    }

    public function convertSlashesForNamespace(string $name): string
    {
        return Str::replace('/', '\\', $name);
    }
}
