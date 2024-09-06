<?php

namespace Backpack\CRUD\app\Library\CrudPanel\Traits\Support;

use Illuminate\Support\Traits\Macroable;

trait MacroableWithAttributes
{
    use Macroable {
        __call as macroCall;
    }

    /**
     * The registered string macros.
     *
     * @var array
     */
    protected static $macros = [];

    /**
     * Get the registered macros.
     *
     * @var array
     */
    public function getMacros()
    {
        return static::$macros;
    }

    /**
     * Call the macros registered for the given macroable attributes.
     */
    public function callRegisteredAttributeMacros(): self
    {
        $macros = $this->getMacros();
        $attributes = $this->getAttributes();

        foreach (array_keys($macros) as $macro) {
            if (isset($attributes[$macro])) {
                $this->{$macro}($attributes[$macro]);
            }
            if (isset($attributes['subfields'])) {
                $subfieldsWithMacros = collect($attributes['subfields'])
                                        ->filter(fn ($item) => isset($item[$macro]));

                $subfieldsWithMacros->each(
                    function ($item) use ($subfieldsWithMacros, $macro) {
                        if ($subfieldsWithMacros->last() === $item) {
                            $this->{$macro}($item[$macro], $item);
                        } else {
                            $this->{$macro}($item[$macro], $item, false);
                        }
                    }
                );
            }
        }

        return $this;
    }
}
