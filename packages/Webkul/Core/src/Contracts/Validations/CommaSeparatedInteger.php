<?php

namespace Webkul\Core\Contracts\Validations;

use Illuminate\Contracts\Validation\Rule;

class CommaSeparatedInteger implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $integerValues = explode(',', $value);

        foreach ($integerValues as $integerValue) {
            if (! preg_match('/^[0-9]+$/', $integerValue)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('core::validation.comma-separated-integer');
    }
}
