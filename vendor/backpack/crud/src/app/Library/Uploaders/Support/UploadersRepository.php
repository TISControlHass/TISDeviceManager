<?php

namespace Backpack\CRUD\app\Library\Uploaders\Support;

use Backpack\CRUD\app\Library\Uploaders\Support\Interfaces\UploaderInterface;

final class UploadersRepository
{
    /**
     * The array of uploaders classes for field types.
     */
    private array $uploaderClasses;

    /**
     * Uploaders registered in a repeatable group.
     */
    private array $repeatableUploaders = [];

    /**
     * Uploaders that have already been handled (events registered) for each field/column instance.
     */
    private array $handledUploaders = [];

    public function __construct()
    {
        $this->uploaderClasses = config('backpack.crud.uploaders');
    }

    /**
     * Mark the given uploader as handled.
     */
    public function markAsHandled(string $objectName): void
    {
        if (! in_array($objectName, $this->handledUploaders)) {
            $this->handledUploaders[] = $objectName;
        }
    }

    /**
     * Check if the given uploader for field/column have been handled.
     */
    public function isUploadHandled(string $objectName): bool
    {
        return in_array($objectName, $this->handledUploaders);
    }

    /**
     * Check if there are uploads for the give object(field/column) type.
     */
    public function hasUploadFor(string $objectType, string $group): bool
    {
        return array_key_exists($objectType, $this->uploaderClasses[$group]);
    }

    /**
     * Return the uploader for the given object type.
     */
    public function getUploadFor(string $objectType, string $group): string
    {
        return $this->uploaderClasses[$group][$objectType];
    }

    /**
     * Register new uploaders or override existing ones.
     */
    public function addUploaderClasses(array $uploaders, string $group): void
    {
        $this->uploaderClasses[$group] = array_merge($this->getGroupUploadersClasses($group), $uploaders);
    }

    /**
     * Return the uploaders classes for the given group.
     */
    private function getGroupUploadersClasses(string $group): array
    {
        return $this->uploaderClasses[$group] ?? [];
    }

    /**
     * Register the specified uploader for the given upload name.
     */
    public function registerRepeatableUploader(string $uploadName, UploaderInterface $uploader): void
    {
        if (! array_key_exists($uploadName, $this->repeatableUploaders) || ! in_array($uploader, $this->repeatableUploaders[$uploadName])) {
            $this->repeatableUploaders[$uploadName][] = $uploader;
        }
    }

    /**
     * Check if there are uploaders registered for the given upload name.
     */
    public function hasRepeatableUploadersFor(string $uploadName): bool
    {
        return array_key_exists($uploadName, $this->repeatableUploaders);
    }

    /**
     * Get the repeatable uploaders for the given upload name.
     */
    public function getRepeatableUploadersFor(string $uploadName): array
    {
        return $this->repeatableUploaders[$uploadName] ?? [];
    }

    /**
     * Check if the specified upload is registered for the given repeatable uploads.
     */
    public function isUploadRegistered(string $uploadName, UploaderInterface $upload): bool
    {
        return $this->hasRepeatableUploadersFor($uploadName) && in_array($upload->getName(), $this->getRegisteredUploadNames($uploadName));
    }

    /**
     * Return the registered uploaders names for the given repeatable upload name.
     */
    public function getRegisteredUploadNames(string $uploadName): array
    {
        return array_map(function ($uploader) {
            return $uploader->getName();
        }, $this->getRepeatableUploadersFor($uploadName));
    }
}
