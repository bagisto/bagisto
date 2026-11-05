<?php

namespace Webkul\Core\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AddressLines implements ValidationRule
{
    /**
     * The most characters the street lines may take once joined, the width of the address column.
     */
    public const MAX_LENGTH = 255;

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_array($value)) {
            return;
        }

        if (mb_strlen($this->join($value)) > self::MAX_LENGTH) {
            $fail('validation.max.string')->translate(['max' => self::MAX_LENGTH]);
        }
    }

    /**
     * Join the street lines one per line, as the address column stores them.
     */
    protected function join(array $lines): string
    {
        return implode(PHP_EOL, array_map(fn ($line) => is_scalar($line) ? (string) $line : '', $lines));
    }
}
