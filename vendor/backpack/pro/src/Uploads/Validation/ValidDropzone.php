<?php

namespace Backpack\Pro\Uploads\Validation;

use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade;
use Backpack\CRUD\app\Library\Validation\Rules\ValidFileArray;
use Closure;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ValidDropzone extends ValidFileArray
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $temporaryFolder = CrudPanelFacade::get('dropzone.temporary_folder');
        $temporaryDisk = CrudPanelFacade::get('dropzone.temporary_disk');
       
        $entry = CrudPanelFacade::getCurrentEntry() !== false ? CrudPanelFacade::getCurrentEntry() : null;
        
        if (! is_array($value)) {
            try {
                $value = json_decode($value, true) ?? [];
            } catch(\Exception $e) {
                $fail('Unable to determine the value type');

                return;
            }
        }
        
        // request wants json when we are uploading files in dropzone
        if (CrudPanelFacade::getRequest()->wantsJson()) {
            $this->validateFileUploadEndpoint($attribute, $value, $fail);

            return;
        }

        // from the array of sent values, get the ones that are new files, aka they are in the temporary folder.
        $newFiles = array_filter($value, function ($file) use ($temporaryDisk, $temporaryFolder) {
            return strpos($file, $temporaryFolder) !== false && Storage::disk($temporaryDisk)->exists($file);
        });

        // previous file paths stored in database
        $previousDabataseFiles = $entry?->{$attribute} ?? [];
        $previousDabataseFiles = is_string($previousDabataseFiles) ? json_decode($previousDabataseFiles, true) : $previousDabataseFiles;

        // the files sent in the request
        $fileSentInRequestArray = array_diff($value, $newFiles);

        // comparison between the sent files and the previous files in database
        $filesDeleted = array_diff($previousDabataseFiles, $fileSentInRequestArray);

        $databaseFilesWithoutDeleted = array_diff($previousDabataseFiles, $filesDeleted);

        $filesToValidate = array_merge($databaseFilesWithoutDeleted, $newFiles);

        if (empty($filesToValidate)) {
            unset($this->data[$attribute]);
        } else {
            $this->data[$attribute] = $filesToValidate;
        }

        $this->validateArrayData($attribute, $fail);
    }

    private function validateFileUploadEndpoint($attribute, $value, $fail)
    {
        // when uploading files the only rules we care about from the "array rules" are the maximum size ones.
        // they can be represented by: max, size, between, lt and lte.
        $customRules = (function () {
            $rules = [];
            foreach ($this->getFieldRules() as $rule) {
                // keep the array rule validation.
                if (Str::startsWith($rule, 'array') || Str::startsWith($rule, 'lt') || Str::startsWith($rule, 'lte') || Str::startsWith($rule, 'max')) {
                    $rules[] = $rule;

                    continue;
                }

                if (Str::startsWith($rule, 'size')) {
                    $rules[] = str_replace('size', 'max', $rule);

                    continue;
                }

                if (Str::startsWith($rule, 'between')) {
                    [$ruleName, $ruleValues] = explode(':', $rule);
                    $max = explode(',', $ruleValues)[1];
                    $rules[] = 'max:'.$max;
                }
            }

            return $rules;
        })();

        $this->validateArrayData($attribute, $fail, $this->data, $customRules);

        $attributeKey = Str::afterLast($attribute, '.');
        $files = [];
       
        foreach($this->data as $sentFiles) {
            if(!is_array($sentFiles)) {
                try {
                    if (is_file($sentFiles)) {
                        $files[] = $sentFiles;
                    }
                    continue;
                }catch(\Exception) {
                    $fail('Unknown datatype, aborting upload process.');
                    $files = [];
                    break;
                }
            }
    
            if (is_multidimensional_array($sentFiles)) {
                foreach ($sentFiles as $key => $value) {
                    foreach ($value[$attributeKey] as $file) {
                        if (is_file($file)) {
                            $files[] = $file;
                        }
                    }
                }
                continue;
            }

            foreach ($sentFiles as $key => $value) {
                if (is_file($value)) {
                    $files[] = $value;
                }
            }
            
        }
        
        $this->validateItems($attribute, $files, $fail);
    }
}
