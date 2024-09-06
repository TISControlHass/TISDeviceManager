<?php

namespace Backpack\CRUD\app\Library\Validation\Rules;

use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade;
use Backpack\CRUD\app\Library\Validation\Rules\Support\HasFiles;
use Closure;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;

class ValidUpload extends BackpackCustomRule
{
    use HasFiles;

    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $entry = CrudPanelFacade::getCurrentEntry();

        if (! array_key_exists($attribute, $this->data) && $entry) {
            return;
        }

        $this->validateFieldRules($attribute, $value, $fail);

        if (! empty($value) && ! empty($this->getFileRules())) {
            $validator = Validator::make([$attribute => $value], [
                $attribute => $this->getFileRules(),
            ], $this->validator->customMessages, $this->validator->customAttributes);

            if ($validator->fails()) {
                foreach ($validator->errors()->messages()[$attribute] as $message) {
                    $fail($message)->translate();
                }
            }
        }
    }

    public static function field(string|array|ValidationRule|Rule $rules = []): self
    {
        return parent::field($rules);
    }
}
