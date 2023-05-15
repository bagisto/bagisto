<?php

namespace Webkul\Core\Contracts\Validations;

use Illuminate\Contracts\Validation\Rule;

class PhoneNumber implements Rule
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
        /**
         * This regular expression allows phone numbers with the following conditions:
         * - The phone number can start with an optional "+" sign.
         * - After the "+" sign, there should be one or more digits.
         *
         * This validation is sufficient for global-level phone number validation. If
         * someone wants to customize it, they can override this rule.
         */
        return preg_match('/^\+?\d+$/', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('core::validation.phone-number');
    }
}
