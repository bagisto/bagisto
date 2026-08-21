<?php

use Illuminate\Support\Facades\DB;
use Webkul\Shop\Http\Requests\CartAddressRequest;

class TestCartAddressRequest extends CartAddressRequest
{
    public function prepareForTest(): void
    {
        $this->prepareForValidation();
    }
}

function prepareCartAddressRequest(array $payload): TestCartAddressRequest
{
    $request = TestCartAddressRequest::create('/', 'POST', $payload);

    $request->prepareForTest();

    return $request;
}

beforeEach(function () {
    DB::table('country_states')->insert([
        [
            'country_id' => null,
            'country_code' => 'T1',
            'code' => 'T1-A',
            'default_name' => 'Test State A',
        ],
        [
            'country_id' => null,
            'country_code' => 'T2',
            'code' => 'T2-B',
            'default_name' => 'Test State B',
        ],
    ]);
});

afterEach(function () {
    DB::table('country_states')
        ->whereIn('country_code', ['T1', 'T2'])
        ->delete();
});

it('clears a known state code that belongs to another country', function (string $addressType) {
    $request = prepareCartAddressRequest([
        $addressType => [
            'country' => 'T2',
            'state' => 'T1-A',
        ],
    ]);

    expect($request->input("{$addressType}.state"))->toBeNull();
})->with(['billing', 'shipping']);

it('keeps a known state code that belongs to the selected country', function () {
    $request = prepareCartAddressRequest([
        'billing' => [
            'country' => 'T2',
            'state' => 'T2-B',
        ],
    ]);

    expect($request->input('billing.state'))->toBe('T2-B');
});

it('keeps legacy free-form state values', function () {
    $request = prepareCartAddressRequest([
        'billing' => [
            'country' => 'T2',
            'state' => 'Legacy Province',
        ],
    ]);

    expect($request->input('billing.state'))->toBe('Legacy Province');
});
