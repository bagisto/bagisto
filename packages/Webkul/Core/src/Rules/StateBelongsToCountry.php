<?php

namespace Webkul\Core\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Webkul\Core\Repositories\CountryStateRepository;

class StateBelongsToCountry implements ValidationRule
{
    /**
     * Create a new rule instance.
     */
    public function __construct(protected mixed $countryCode) {}

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (
            ! is_string($this->countryCode)
            || blank($this->countryCode)
            || blank($value)
        ) {
            return;
        }

        $countryStateRepository = app(CountryStateRepository::class);

        if (! $countryStateRepository->count(['country_code' => $this->countryCode])) {
            return;
        }

        if (
            ! is_string($value)
            || ! $countryStateRepository->count([
                'country_code' => $this->countryCode,
                'code' => $value,
            ])
        ) {
            $fail('validation.in')->translate();
        }
    }
}
