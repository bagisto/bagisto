<?php

use Illuminate\Support\Facades\Validator;
use Webkul\Core\Rules\StateBelongsToCountry;

/**
 * Whether the given state passes the rule for the given country.
 */
function stateBelongsToCountry(mixed $country, mixed $state): bool
{
    return Validator::make(['state' => $state], ['state' => [new StateBelongsToCountry($country)]])->passes();
}

it('accepts a state code the country has', function () {
    expect(stateBelongsToCountry('US', 'CA'))->toBeTrue();
});

it('matches the state code without regard to case or surrounding spaces', function (string $state) {
    expect(stateBelongsToCountry('us', $state))->toBeTrue();
})->with([
    'lower case' => 'ca',
    'padded' => ' CA ',
]);

it('rejects a state code belonging to another country', function () {
    expect(stateBelongsToCountry('IN', 'CA'))->toBeFalse();
});

it('rejects a state code the country does not have', function () {
    expect(stateBelongsToCountry('IN', 'XX'))->toBeFalse();
});

it('accepts any state for a country without a state list', function () {
    expect(stateBelongsToCountry('GB', 'Greater London'))->toBeTrue();
});

it('leaves a blank country or a blank state alone', function (mixed $country, mixed $state) {
    expect(stateBelongsToCountry($country, $state))->toBeTrue();
})->with([
    'blank country' => ['', 'CA'],
    'null country' => [null, 'CA'],
    'blank state' => ['US', ''],
    'null state' => ['US', null],
]);

it('rejects a state that is not a string for a country with a state list', function () {
    expect(stateBelongsToCountry('US', ['CA']))->toBeFalse();
});
