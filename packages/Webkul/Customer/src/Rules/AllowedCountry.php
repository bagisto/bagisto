<?php

namespace Webkul\Customer\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Webkul\Customer\AddressSettings;

class AllowedCountry implements ValidationRule
{
    /**
     * Run the validation rule, passing every country while the storefront is not limited to some.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $allowed = app(AddressSettings::class)->allowedCountryCodes();

        if (
            ! $allowed
            || blank($value)
        ) {
            return;
        }

        if (
            ! is_string($value)
            || ! in_array(mb_strtoupper(trim($value)), array_map('mb_strtoupper', $allowed), true)
        ) {
            $fail('customer::app.validations.allowed-country.not-allowed')->translate();
        }
    }
}
