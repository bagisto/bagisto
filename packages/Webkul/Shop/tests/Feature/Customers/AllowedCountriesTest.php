<?php

use Webkul\Core\Core;
use Webkul\Core\Models\Channel;
use Webkul\Core\Models\CoreConfig;
use Webkul\Core\Repositories\CountryRepository;
use Webkul\Customer\AddressSettings;
use Webkul\Customer\Models\CustomerAddress;
use Webkul\Customer\Repositories\CustomerAddressRepository;
use Webkul\Customer\Rules\AllowedCountry;
use Webkul\Shop\Http\Controllers\API\AddressController;

use function Pest\Laravel\get;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

// ============================================================================
// Country List
// ============================================================================

it('should offer every country while the storefront is not limited to some', function () {
    $codes = collect(getJson(route('shop.api.customers.addresses.allowed_countries'))->assertOk()->json('data'))->pluck('code');

    expect($codes)->toContain('IN', 'US', 'GB');
});

it('should offer only the allowed countries', function () {
    $this->setConfig('customer.address.countries.allowed', 'IN,US');

    $codes = collect(getJson(route('shop.api.customers.addresses.allowed_countries'))->assertOk()->json('data'))->pluck('code');

    expect($codes->all())->toEqualCanonicalizing(['IN', 'US']);
});

it('should offer the states of the allowed countries only', function () {
    $this->setConfig('customer.address.countries.allowed', 'IN');

    $states = getJson(route('shop.api.customers.addresses.allowed_states'))->assertOk()->json('data');

    expect(array_keys($states))->toBe(['IN']);
});

it('should keep listing every country and its states on the core endpoints while the storefront is limited', function () {
    $this->setConfig('customer.address.countries.allowed', 'IN');

    $codes = collect(getJson(route('shop.api.core.countries'))->assertOk()->json('data'))->pluck('code');

    $states = getJson(route('shop.api.core.states'))->assertOk()->json('data');

    expect($codes)->toContain('IN', 'US', 'GB')
        ->and($states)->toHaveKeys(['IN', 'US']);
});

it('should not limit the storefront to the countries allowed on another channel', function () {
    CoreConfig::query()->where('code', 'customer.address.countries.allowed')->delete();

    CoreConfig::factory()->create([
        'code' => 'customer.address.countries.allowed',
        'value' => 'IN',
        'channel_code' => Channel::factory()->create()->code,
    ]);

    expect(app(AddressSettings::class)->allowedCountryCodes())->toBe([]);
});

it('should ignore the blanks and spaces of a stored list', function () {
    $this->setConfig('customer.address.countries.allowed', ',IN, US');

    expect(app(AddressSettings::class)->allowedCountryCodes())->toBe(['IN', 'US']);
});

it('should list the countries as options of the configuration field', function () {
    $options = app(CountryRepository::class)->getConfigOptions();

    expect($options)->toContain(['title' => 'India', 'value' => 'IN']);
});

// ============================================================================
// Customer Address
// ============================================================================

it('should offer only the allowed countries on the address forms', function () {
    $customer = $this->loginAsCustomer();

    $address = CustomerAddress::factory()->create([
        'customer_id' => $customer->id,
        'country' => 'IN',
        'state' => 'UP',
    ]);

    $this->setConfig('customer.address.countries.allowed', 'IN');

    get(route('shop.customers.account.addresses.create'))
        ->assertOk()
        ->assertSee('value="IN"', false)
        ->assertDontSee('value="US"', false);

    get(route('shop.customers.account.addresses.edit', $address->id))
        ->assertOk()
        ->assertSee('value="IN"', false)
        ->assertDontSee('value="US"', false);
});

it('should fail the validation when the country is not allowed when storing the customer address', function () {
    $this->setConfig('customer.address.countries.allowed', 'IN');

    $this->loginAsCustomer();

    postJson(route('shop.customers.account.addresses.store'), $this->storefrontAddress([
        'country' => 'US',
        'state' => 'CA',
    ]))
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['country' => trans('customer::app.validations.allowed-country.not-allowed')]);
});

it('should refuse to keep a saved address in a country that is no longer allowed', function () {
    $customer = $this->loginAsCustomer();

    $address = CustomerAddress::factory()->create([
        'customer_id' => $customer->id,
        'country' => 'US',
        'state' => 'CA',
    ]);

    $this->setConfig('customer.address.countries.allowed', 'IN');

    putJson(route('shop.customers.account.addresses.update', $address->id), $this->storefrontAddress([
        'country' => 'US',
        'state' => 'CA',
    ]))
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['country' => trans('customer::app.validations.allowed-country.not-allowed')]);
});

it('should move a saved address to an allowed country', function () {
    $customer = $this->loginAsCustomer();

    $address = CustomerAddress::factory()->create([
        'customer_id' => $customer->id,
        'country' => 'US',
        'state' => 'CA',
    ]);

    $this->setConfig('customer.address.countries.allowed', 'IN');

    putJson(route('shop.customers.account.addresses.update', $address->id), $this->storefrontAddress([
        'country' => 'IN',
        'state' => 'UP',
    ]))
        ->assertRedirect(route('shop.customers.account.addresses.index'));

    $this->assertDatabaseHas('addresses', [
        'id' => $address->id,
        'country' => 'IN',
    ]);
});

it('should accept an allowed country whatever the case it is sent in', function () {
    $this->setConfig('customer.address.countries.allowed', 'IN');

    expect(validator(['country' => 'in'], ['country' => [new AllowedCountry]])->passes())->toBeTrue()
        ->and(validator(['country' => 'us'], ['country' => [new AllowedCountry]])->passes())->toBeFalse();
});

it('should store the customer address when the country is allowed', function () {
    $this->setConfig('customer.address.countries.allowed', 'IN,US');

    $customer = $this->loginAsCustomer();

    postJson(route('shop.customers.account.addresses.store'), $this->storefrontAddress([
        'country' => 'US',
        'state' => 'CA',
    ]))
        ->assertRedirect(route('shop.customers.account.addresses.index'));

    $this->assertDatabaseHas('addresses', [
        'customer_id' => $customer->id,
        'country' => 'US',
    ]);
});

it('should store the customer address in any country while the storefront is not limited to some', function () {
    $customer = $this->loginAsCustomer();

    postJson(route('shop.customers.account.addresses.store'), $this->storefrontAddress([
        'country' => 'GB',
        'state' => 'Greater London',
    ]))
        ->assertRedirect(route('shop.customers.account.addresses.index'));

    $this->assertDatabaseHas('addresses', [
        'customer_id' => $customer->id,
        'country' => 'GB',
    ]);
});

// ============================================================================
// Checkout
// ============================================================================

it('should refuse a checkout address in a country that is not allowed', function () {
    $this->setConfig('customer.address.countries.allowed', 'IN');

    $this->addProductToCart($this->createSimpleProduct()->id);

    postJson(route('shop.checkout.onepage.addresses.store'), [
        'billing' => array_merge($this->storefrontAddress(), ['use_for_shipping' => true]),
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['billing.country' => trans('customer::app.validations.allowed-country.not-allowed')]);
});

it('should accept a checkout address in an allowed country', function () {
    $this->setConfig('customer.address.countries.allowed', 'IN,US');

    $this->addProductToCart($this->createSimpleProduct()->id);

    postJson(route('shop.checkout.onepage.addresses.store'), [
        'billing' => array_merge($this->storefrontAddress(), ['use_for_shipping' => true]),
    ])
        ->assertOk();
});

it('should refuse a shipping estimate for a country that is not allowed', function () {
    $this->setConfig('customer.address.countries.allowed', 'IN');

    $this->addProductToCart($this->createSimpleProduct()->id);

    postJson(route('shop.api.checkout.cart.estimate_shipping'), [
        'country' => 'US',
        'state' => 'CA',
        'postcode' => '90001',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['country' => trans('customer::app.validations.allowed-country.not-allowed')]);
});

// ============================================================================
// Deprecated Core Helpers
// ============================================================================

it('should keep answering the address requirements through the deprecated core helpers', function () {
    $this->setConfig([
        'customer.address.requirements.country' => 1,
        'customer.address.requirements.state' => 0,
        'customer.address.requirements.postcode' => 1,
    ]);

    expect(core()->isCountryRequired())->toBeTrue()
        ->and(core()->isStateRequired())->toBeFalse()
        ->and(core()->isPostCodeRequired())->toBeTrue();
});

// ============================================================================
// Compatibility With v2.5.1
// ============================================================================

it('should build the address controller for a subclass that passes only the repository', function () {
    $controller = new class(app(CustomerAddressRepository::class)) extends AddressController
    {
        /**
         * Create the controller the way a subclass written for v2.5.1 does.
         */
        public function __construct(CustomerAddressRepository $customerAddressRepository)
        {
            parent::__construct($customerAddressRepository);
        }
    };

    expect($controller->allowedCountries()->resolve())->not->toBeEmpty();
});
