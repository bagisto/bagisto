<?php

use Illuminate\Support\Facades\Validator;
use Webkul\Core\Rules\PhoneNumber;

/**
 * Whether the rule accepts the given number.
 */
function phoneNumberPasses(string $value): bool
{
    return Validator::make(['phone' => $value], ['phone' => new PhoneNumber])->passes();
}

// ============================================================================
// Accepted Numbers
// ============================================================================

it('should accept a phone number written with separators', function (string $value) {
    expect(phoneNumberPasses($value))->toBeTrue();
})->with([
    '0412 345 678',
    '(555) 123-4567',
    '+44 20 7946 0958',
    '06.12.34.56.78',
    '+1-800-555-0199',
    '+49 (0) 30 901820',
]);

it('should accept a phone number written as bare digits', function (string $value) {
    expect(phoneNumberPasses($value))->toBeTrue();
})->with([
    '9876543210',
    '+919876543210',
]);

// ============================================================================
// Rejected Values
// ============================================================================

it('should reject a value that is not a phone number', function (string $value) {
    expect(phoneNumberPasses($value))->toBeFalse();
})->with([
    'abc',
    'call me',
    '12a34',
    '555 ext',
    '+',
    '()',
    '---',
    '+-5',
]);
