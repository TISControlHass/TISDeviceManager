<?php

namespace Backpack\CRUD\app\Library\Uploaders\Support\Interfaces;

use Illuminate\Http\UploadedFile;

interface FileNameGeneratorInterface
{
    public function getName(string|UploadedFile $file): string;
}
