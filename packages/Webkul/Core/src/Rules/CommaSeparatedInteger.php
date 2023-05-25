<?php

namespace Webkul\Core\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CommaSeparatedInteger implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $this->isCommaSeparatedInteger($attribute, $value)) {
            $fail('core::validation.comma-separated-integer')->translate();
        }
    }

    /**
     * Determine if the value is comma separated integer.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function isCommaSeparatedInteger($attribute, $value)
    {
        $integerValues = explode(',', $value);

        foreach ($integerValues as $integerValue) {
            if (! preg_match('/^[0-9]+$/', $integerValue)) {
                return false;
            }
        }

        return true;
    }
}
