<?php

namespace Webkul\Customer\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Webkul\Customer\AddressSettings;

class AllowedCountry implements ValidationRule
{
    /**
     * Create a rule for the countries of the given channel, the requested one by default.
     */
    public function __construct(protected ?string $channelCode = null) {}

    /**
     * Run the validation rule, passing every country while the channel is not limited to some.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $allowed = app(AddressSettings::class)->allowedCountryCodes($this->channelCode);

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
