<?php

namespace Backpack\Pro\Http\Controllers\Operations;

use Backpack\Pro\Jobs\PurgeTemporaryFiles;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade;
use Backpack\CRUD\app\Library\Validation\Rules\BackpackCustomRule;
use Backpack\Pro\Uploads\AjaxUploader;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

trait DropzoneOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param  string  $segment  Name of the current entity (singular). Used as first URL segment.
     * @param  string  $routeName  Prefix of the route name.
     * @param  string  $controller  Name of the current CrudController.
     */
    protected function setupDropzoneRoutes($segment, $routeName, $controller)
    {
        Route::post($segment.'/dropzone/upload', [
            'as'        => $segment.'-dropzone-upload',
            'uses'      => $controller.'@dropzoneUpload',
            'operation' => 'dropzone',
        ]);

        Route::post($segment.'/dropzone/delete', [
            'as'        => $segment.'-dropzone-delete',
            'uses'      => $controller.'@dropzoneDelete',
            'operation' => 'dropzone',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupDropzoneDefaults()
    {
        $this->crud->operation('dropzone', function () {
            $this->crud->loadDefaultOperationSettingsFromConfig();
        });
        $this->crud->set('dropzone.formOperation', $this->crud->getCurrentOperation());
        $this->crud->set('dropzone.temporary_disk', config('backpack.operations.dropzone.temporary_disk', 'public'));
        $this->crud->set('dropzone.temporary_folder', config('backpack.operations.dropzone.temporary_folder', 'backpack/temp'));
    }

    /**
     * Store temporary uploaded files in disk and return the paths.
     */
    public function dropzoneUpload()
    {
        $temporaryFolder = CrudPanelFacade::getOperationSetting('temporary_folder');
        $temporaryDisk = CrudPanelFacade::getOperationSetting('temporary_disk');

        PurgeTemporaryFiles::dispatchAfterResponse();

        $operation = CrudPanelFacade::getRequest()->input('operation');

        if (method_exists($this, 'setup'.ucfirst($operation).'Operation')) {
            $this->{'setup'.ucfirst($operation).'Operation'}();
        }

        $requestInputName = CrudPanelFacade::getRequest()->input('fieldName');
        $fieldName = $this->getDropzoneFieldName($requestInputName);
        $uploadedFiles = CrudPanelFacade::getRequest()->file();      

        $this->validateDropzoneAjaxEndpoint($requestInputName, $uploadedFiles);
        
        $uploaderConfiguration = $this->crud->fields()[$fieldName]['withFiles'] ?? $this->crud->fields()[$fieldName]['withMedia'] ?? [];
        $uploaderConfiguration = !is_array($uploaderConfiguration) ? [] : $uploaderConfiguration;

        $files = [];
        $uploader = $this->getDropzoneOperationUploaderClass();
        $uploader = new $uploader(['name' => $fieldName], $uploaderConfiguration);

        foreach ($uploadedFiles as $fileArray) {
            foreach ($fileArray as $file) {
                $fileName = $uploader->getFileName($file);
                $path = Storage::disk($temporaryDisk)->putFileAs($temporaryFolder, $file, $fileName);

                $files[] = $path;
            }
        }

        return response()->json([
            'files'         => $files,
            'success'       => true,
        ]);
    }

    /**
     * Delete temporary files from disk
     */
    public function dropzoneDelete()
    {
        $temporaryDisk = CrudPanelFacade::getOperationSetting('temporary_disk');

        $file = request()->file;

        if (! is_string($file)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid file path.',
            ], 400);
        }

        if (Storage::disk($temporaryDisk)->exists($file)) {
            try {
                Storage::disk($temporaryDisk)->delete($file);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete file.',
                ], 500);
            }
        }

        return response()->json([
            'success' => true,
        ]);
    }

    private function isValidDropzoneField($fieldName)
    {
        if (Str::contains($fieldName, '#')) {
            $container = Str::before($fieldName, '#');
            $fieldName = Str::after($fieldName, '#');
            $field = array_filter($this->crud->fields()[$container]['subfields'] ?? [], function ($item) use ($fieldName) {
                return $item['name'] === $fieldName && $item['type'] === 'dropzone';
            });

            return ! empty($field);
        }

        return isset($this->crud->fields()[$fieldName]) && $this->crud->fields()[$fieldName]['type'] === 'dropzone';
    }

    private function getDropzoneFieldName(string $requestInputName)
    {
        if (! $this->isValidDropzoneField($requestInputName)) {
            abort(500, 'Cannot parse field name.');
        }

        return Str::after($requestInputName, '#');
    }

    private function getDropzoneValidationKey($requestInputName)
    {
        return Str::contains($requestInputName, '#') ? Str::before($requestInputName, '#').'.*.'.Str::after($requestInputName, '#') : $requestInputName;
    }

    protected function validateDropzoneAjaxEndpoint($requestInputName, $uploadedFiles)
    {
        $validationKey = $this->getDropzoneValidationKey($requestInputName);

        $previousUploadedFiles = json_decode(CrudPanelFacade::getRequest()->input('previousUploadedFiles'), true) ?? [];

        $formRequest = $this->crud->getFormRequest();

        [$formRequest, $extendedRules, $extendedMessages] = $formRequest ?
                $this->crud->mergeRequestAndFieldRules($formRequest) :
                [null, $this->crud->getOperationSetting('validationRules'), $this->crud->getOperationSetting('validationMessages')];

        $fieldRules = $extendedRules[$validationKey] ?? [];
        $fieldRules = is_array($fieldRules) ? $fieldRules : (is_string($fieldRules) ? explode('|', $fieldRules) : [$fieldRules]);

        $validDropzoneValidationRule = array_filter($fieldRules, function ($rule) {
            return is_a($rule, BackpackCustomRule::class, true);
        })[0] ?? null;

        if (! empty($validDropzoneValidationRule)) {
            $filesToValidate = (function () use ($validationKey, $uploadedFiles, $requestInputName, $previousUploadedFiles) {
                // build the proper repeatable validation data
                if (Str::contains($validationKey, '.*.')) {
                    return [Str::before($validationKey, '.*') => [0 => [Str::after($validationKey, '*.') => array_merge($uploadedFiles[$requestInputName], $previousUploadedFiles)]]];
                }

                return array_merge($uploadedFiles[$requestInputName], $previousUploadedFiles);
            })();

            Validator::make($filesToValidate, [$validationKey => $validDropzoneValidationRule], $extendedMessages ?? [], $extendedAttributes ?? [])->validate();
        }
    }

    protected function getDropzoneOperationUploaderClass(): string
    {
        return AjaxUploader::class;
    }
}
