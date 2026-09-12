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
     * Run the validation rule, matching codes in PHP so every database compares them without regard to case.
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

        $codes = app(CountryStateRepository::class)
            ->findByField('country_code', mb_strtoupper(trim($this->countryCode)))
            ->pluck('code');

        if ($codes->isEmpty()) {
            return;
        }

        if (
            ! is_string($value)
            || ! $codes->contains(fn ($code) => $this->normalize($code) === $this->normalize($value))
        ) {
            $fail('validation.in')->translate();
        }
    }

    /**
     * Reduce a state code to the form it is compared in.
     */
    protected function normalize(string $code): string
    {
        return mb_strtolower(trim($code));
    }
}
