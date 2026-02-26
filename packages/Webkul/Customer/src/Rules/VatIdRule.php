<?php

namespace Webkul\Customer\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class VatIdRule implements ValidationRule
{
    /**
     * The country code from the input form.
     *
     * @var string
     */
    private $country;

    /**
     * Run the validation rule.
     *
     * The rules are borrowed from:
     *
     * @see https://raw.githubusercontent.com/danielebarbaro/laravel-vat-eu-validator/master/src/VatValidator.php
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $validator = new VatValidator;

        if (! empty($value) && ! $validator->validate($value, $this->country)) {
            $fail('customer::app.validations.vat-id.invalid-format')->translate();
        }
    }

    /**
     * Set the country code.
     *
     * @param  string  $country
     */
    public function setCountry($country): self
    {
        $this->country = $country;

        return $this;
    }
}
