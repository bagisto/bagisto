<?php

namespace Webkul\Core\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Address implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! preg_match("/^[a-zA-Z0-9\s'\p{Arabic}\p{Bengali}\p{Hebrew}\p{Latin}\p{Sinhala}\p{Cyrillic}\p{Devanagari}p{Hiragana}\p{Katakana}\p{Han}\-,\(\)]{1,60}$/iu", $value)) {
            $fail('core::validation.address')->translate();
        }
    }
}
