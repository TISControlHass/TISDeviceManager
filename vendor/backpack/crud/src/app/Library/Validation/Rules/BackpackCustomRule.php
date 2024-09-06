<?php

namespace Backpack\CRUD\app\Library\Validation\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\ValidatorAwareRule;
use Illuminate\Support\Facades\Validator;

/**
 * @method static static itemRules()
 */
abstract class BackpackCustomRule implements ValidationRule, DataAwareRule, ValidatorAwareRule
{
    /**
     * @var \Illuminate\Contracts\Validation\Validator
     */
    protected $validator;

    protected array $data;

    public array $fieldRules = [];

    public bool $implicit = true;

    public static function field(string|array|ValidationRule|Rule $rules = []): self
    {
        $instance = new static();
        $instance->fieldRules = self::getRulesAsArray($rules);

        return $instance;
    }

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
        // is the extending class reponsability the implementation of the validation logic
    }

    /**
     * Set the performing validator.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return $this
     */
    public function setValidator($validator)
    {
        $this->validator = $validator;

        return $this;
    }

    /**
     * Set the data under validation.
     *
     * @param  array  $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    public function getFieldRules(): array
    {
        return tap($this->fieldRules, function ($rule) {
            if (is_a($rule, BackpackCustomRule::class, true)) {
                $rule = $rule->getFieldRules();
            }

            return $rule;
        });
    }

    protected static function getRulesAsArray($rules)
    {
        if (is_string($rules)) {
            $rules = explode('|', $rules);
        }

        if (! is_array($rules)) {
            $rules = [$rules];
        }

        return $rules;
    }

    /**
     * Implementation.
     */
    public function validateFieldRules(string $attribute, mixed $value, Closure $fail): void
    {
        $validator = Validator::make([$attribute => $value], [
            $attribute => $this->getFieldRules(),
        ], $this->validator->customMessages, $this->validator->customAttributes);

        if ($validator->fails()) {
            foreach ($validator->errors()->messages()[$attribute] as $message) {
                $fail($message)->translate();
            }
        }
    }
}
