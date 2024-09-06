<?php

namespace Backpack\CRUD\app\Library\Validation\Rules\Support;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\ValidationRule;

trait HasFiles
{
    public array $fileRules;

    public function file(string|array|ValidationRule|Rule $rules): self
    {
        $this->fileRules = self::getRulesAsArray($rules);

        return $this;
    }

    protected function getFileRules(): array
    {
        return $this->fileRules ?? [];
    }
}
