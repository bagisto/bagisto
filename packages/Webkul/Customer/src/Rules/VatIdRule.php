<?php

namespace Webkul\Customer\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * Class VatIdRule
 *
 * @see https://laravel.com/docs/5.8/validation#using-rule-objects
 */
class VatIdRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * The rules are borrowed from:
     *
     * @see https://raw.githubusercontent.com/danielebarbaro/laravel-vat-eu-validator/master/src/VatValidator.php
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $validator = new VatValidator;

        return empty($value) || $validator->validate($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('customer::app.validations.vat-id.invalid-format');
    }
}
