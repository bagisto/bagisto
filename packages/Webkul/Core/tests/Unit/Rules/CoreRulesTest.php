<?php

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;
use Webkul\Core\Rules\Address;
use Webkul\Core\Rules\Code;
use Webkul\Core\Rules\CommaSeparatedInteger;
use Webkul\Core\Rules\Decimal;
use Webkul\Core\Rules\PhoneNumber;
use Webkul\Core\Rules\PostCode;
use Webkul\Core\Rules\Regex;
use Webkul\Core\Rules\Slug;

/**
 * Whether the given value passes the given rule.
 */
function passesRule(ValidationRule $rule, mixed $value): bool
{
    return Validator::make(['value' => $value], ['value' => [$rule]])->passes();
}

it('accepts a well formed code', function (string $value) {
    expect(passesRule(new Code, $value))->toBeTrue();
})->with([
    'letters only' => 'color',
    'letters and digits' => 'size2',
    'letters and underscores' => 'shoe_size',
]);

it('rejects a malformed code', function (string $value) {
    expect(passesRule(new Code, $value))->toBeFalse();
})->with([
    'starting with a digit' => '2size',
    'starting with an underscore' => '_size',
    'with a hyphen' => 'shoe-size',
    'with a space' => 'shoe size',
    'a single letter' => 'a',
]);

it('accepts a decimal with up to four decimal places', function (string $value) {
    expect(passesRule(new Decimal, $value))->toBeTrue();
})->with([
    'an integer' => '10',
    'one decimal place' => '10.5',
    'four decimal places' => '10.1234',
]);

it('rejects a value that is not a decimal', function (string $value) {
    expect(passesRule(new Decimal, $value))->toBeFalse();
})->with([
    'five decimal places' => '10.12345',
    'a comma separator' => '10,5',
    'letters' => 'ten',
    'a negative sign' => '-10',
]);

it('accepts a phone number made of digits with an optional leading plus', function (string $value) {
    expect(passesRule(new PhoneNumber, $value))->toBeTrue();
})->with([
    'digits only' => '9876543210',
    'leading plus' => '+919876543210',
]);

it('rejects a phone number with anything but digits', function (string $value) {
    expect(passesRule(new PhoneNumber, $value))->toBeFalse();
})->with([
    'spaces' => '98765 43210',
    'hyphens' => '987-654-3210',
    'parentheses' => '(987) 6543210',
    'letters' => 'CALL-ME',
    'a plus in the middle' => '98+76543210',
]);

it('accepts a post code of letters and digits, with spaces or hyphens inside', function (string $value) {
    expect(passesRule(new PostCode, $value))->toBeTrue();
})->with([
    'digits' => '110001',
    'letters and digits' => 'SW1A1AA',
    'inner space' => 'SW1A 1AA',
    'inner hyphen' => '12345-6789',
]);

it('rejects a post code that starts or ends with a separator or holds other characters', function (string $value) {
    expect(passesRule(new PostCode, $value))->toBeFalse();
})->with([
    'leading space' => ' 110001',
    'trailing hyphen' => '110001-',
    'a dot' => '110.001',
    'a single character' => '1',
]);

it('accepts a slug of unicode letters and digits joined by single hyphens', function (string $value) {
    expect(passesRule(new Slug, $value))->toBeTrue();
})->with([
    'ascii words' => 'running-shoes',
    'digits' => 'shoes-2026',
    'accented letters' => 'chaussures-été',
    'devanagari letters' => 'जूते',
]);

it('rejects a slug with a separator in the wrong place or an unsupported character', function (string $value) {
    expect(passesRule(new Slug, $value))->toBeFalse();
})->with([
    'leading hyphen' => '-shoes',
    'trailing hyphen' => 'shoes-',
    'double hyphen' => 'running--shoes',
    'a space' => 'running shoes',
    'an underscore' => 'running_shoes',
    'a slash' => 'running/shoes',
]);

it('accepts a street address in any supported script', function (string $value) {
    expect(passesRule(new Address, $value))->toBeTrue();
})->with([
    'latin' => '221B Baker Street, Marylebone',
    'with parentheses' => 'Flat 4 (rear entrance)',
    'devanagari' => 'नई दिल्ली 110001',
    'arabic' => 'شارع الملك فهد',
    'han' => '北京市朝阳区',
]);

it('rejects a street address that is too long or holds unsupported characters', function (string $value) {
    expect(passesRule(new Address, $value))->toBeFalse();
})->with([
    'sixty-one characters' => str_repeat('a', 61),
    'an at sign' => 'Baker Street @ Marylebone',
    'angle brackets' => '<script>alert(1)</script>',
]);

it('accepts a comma separated list of integers', function (string $value) {
    expect(passesRule(new CommaSeparatedInteger, $value))->toBeTrue();
})->with([
    'a single integer' => '12',
    'a list' => '12,24,36',
    'a list with spaces after the commas' => '12, 24, 36',
]);

it('rejects a comma separated list holding anything but integers', function (string $value) {
    expect(passesRule(new CommaSeparatedInteger, $value))->toBeFalse();
})->with([
    'a decimal' => '12,24.5',
    'a word' => '12,twenty-four',
    'a trailing comma' => '12,24,',
    'a negative integer' => '12,-24',
]);

it('accepts a regular expression both the server and the browser can compile', function (string $value) {
    expect(passesRule(new Regex, $value))->toBeTrue()
        ->and(Regex::isUsable($value))->toBeTrue();
})->with([
    'no modifiers' => '/^[a-z]+$/',
    'shared modifiers' => '/^[a-z]+$/imsu',
]);

it('rejects a regular expression the browser could not compile', function (mixed $value) {
    expect(passesRule(new Regex, $value))->toBeFalse()
        ->and(Regex::isUsable($value))->toBeFalse();
})->with([
    'a hash delimiter' => '#^[a-z]+$#',
    'no delimiters' => '^[a-z]+$',
    'a pcre only modifier' => '/^[a-z]+$/x',
    'an unbalanced group' => '/^([a-z]+$/',
    'not a string' => [['/a/']],
]);
