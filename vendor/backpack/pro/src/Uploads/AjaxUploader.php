<?php

namespace Backpack\Pro\Uploads;

use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Library\Uploaders\Support\Interfaces\UploaderInterface;
use Backpack\CRUD\app\Library\Uploaders\Uploader;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Prologue\Alerts\Facades\Alert;
use Illuminate\Support\Str;

class AjaxUploader extends Uploader
{
    public static function for(array $field, $configuration): UploaderInterface
    {
        return (new self($field, $configuration))->multiple();
    }

    public function uploadFiles(Model $entry, $value = null)
    {
        $temporaryFolder = CRUD::get('dropzone.temporary_folder');
        $temporaryDisk = CRUD::get('dropzone.temporary_disk');

        $uploads = $value ?? CRUD::getRequest()->input($this->getName()) ?? [];

        $uploads = is_array($uploads) ? $uploads : (json_decode($uploads, true) ?? []);

        $uploadedFiles = array_filter($uploads, function ($value) use ($temporaryFolder) {
            return strpos($value, $temporaryFolder) !== false;
        });

        $previousSentFiles = array_filter($uploads, function ($value) use ($temporaryFolder) {
            return strpos($value, $temporaryFolder) === false;
        });

        $previousDatabaseFiles = $this->getPreviousFiles($entry) ?? [];

        $previousDatabaseFiles = is_array($previousDatabaseFiles) ? $previousDatabaseFiles : (json_decode($previousDatabaseFiles, true) ?? []);

        $filesToDelete = array_diff($previousDatabaseFiles, $previousSentFiles);

        foreach ($filesToDelete as $key => $value) {
            Storage::disk($this->getDisk())->delete(Str::start($value, $this->getPath()));
        }

        foreach ($uploadedFiles as $key => $value) {
            try {
                $name = substr($value, strrpos($value, '/') + 1);
                $move = Storage::disk($this->getDisk())->put($this->getPath().$name, Storage::disk($temporaryDisk)->get($value));

                if ($move) {
                    Storage::disk($temporaryDisk)->delete($value);
                    $value = str_replace(Str::finish($temporaryFolder, '/'), $this->getPath(), $value);
                    $previousSentFiles[] = $value;
                    continue;
                }

                Log::error('Unable to move file from '.$value.' to '.Storage::disk($this->getDisk())->path($this->getPath().$name).'.');
                Alert::error('An error occured uploading files. Check log files.')->flash();
            } catch (\Throwable $th) {
                Log::error($th->getMessage());
                Alert::error('An error occured uploading files. Check log files.')->flash();
            }
        }

        if(empty($previousSentFiles)) {
            return null;
        }

        return isset($entry->getCasts()[$this->getName()]) ? $previousSentFiles : json_encode($previousSentFiles);
    }

    public function uploadRepeatableFiles($values, $previousValues, $entry = null)
    {
        $temporaryFolder = CRUD::get('dropzone.temporary_folder');
        $temporaryDisk = CRUD::get('dropzone.temporary_disk');

        $values = array_map(function ($value) {
            return is_array($value) ? $value : (json_decode($value, true) ?? []);
        }, $values);

        foreach ($values as $row => $files) {
            $files = is_array($files) ? $files : (json_decode($files, true) ?? []);

            $uploadedFiles = array_filter($files, function ($value) use ($temporaryFolder) {
                return strpos($value, $temporaryFolder) !== false;
            });

            $previousSentFiles = array_filter($files, function ($value) use ($temporaryFolder) {
                return strpos($value, $temporaryFolder) === false;
            });

            foreach ($uploadedFiles ?? [] as $key => $file) {
                $name = substr($file, strrpos($file, '/') + 1);

                $temporaryFile = Storage::disk($temporaryDisk)->get($file);

                Storage::disk($this->getDisk())->put($this->getPath().$name, $temporaryFile);

                Storage::disk($temporaryDisk)->delete($file);

                $file = str_replace($temporaryFolder, $this->getPath(), $file);

                $values[$row][$key] = $file;
            }

            $filesToDelete = array_diff($previousValues[$row] ?? [], $previousSentFiles);
            foreach ($filesToDelete as $key => $value) {
                Storage::disk($this->getDisk())->delete($this->getPath().$value);
            }
        }

        foreach($values as $row => $value) {
            if(empty($value)) {
                unset($values[$row]);
            }
        }
        return $values;
    }
}
