<?php

namespace Backpack\CRUD\app\Library\Uploaders\Support;

use Backpack\CRUD\app\Library\Uploaders\Support\Interfaces\FileNameGeneratorInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\File\File;

class FileNameGenerator implements FileNameGeneratorInterface
{
    public function getName(string|UploadedFile|File $file): string
    {
        if (is_object($file) && get_class($file) === File::class) {
            return $file->getFileName();
        }

        return $this->getFileName($file).'.'.$this->getExtensionFromFile($file);
    }

    private function getExtensionFromFile(string|UploadedFile $file): string
    {
        return is_a($file, UploadedFile::class, true) ? $file->extension() : Str::after(mime_content_type($file), '/');
    }

    private function getFileName(string|UploadedFile $file): string
    {
        if (is_file($file)) {
            return Str::of($file->getClientOriginalName())->beforeLast('.')->slug()->append('-'.Str::random(4));
        }

        return Str::random(40);
    }
}
