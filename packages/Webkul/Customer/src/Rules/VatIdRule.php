<?php

namespace Webkul\Customer\Rules;

use Illuminate\Contracts\Validation\Rule;
use Webkul\Customer\Rules\VatValidator;

/**
 * Class VatIdRule
 *
 * @see https://laravel.com/docs/5.8/validation#using-rule-objects
 * @package App\Rules
 */
class VatIdRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * The rules are borrowed from:
     * @see https://raw.githubusercontent.com/danielebarbaro/laravel-vat-eu-validator/master/src/VatValidator.php
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $validator = new VatValidator();

        return $validator->validate($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('shop::app.invalid_vat_format');
    }
}
