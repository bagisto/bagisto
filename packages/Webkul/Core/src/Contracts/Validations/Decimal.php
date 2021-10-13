<?php

namespace Webkul\Core\Contracts\Validations;

use Illuminate\Contracts\Validation\Rule;

class Decimal implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        return preg_match('/^\d*(\.\d{1,4})?$/', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return trans('core::validation.decimal');
    }
}