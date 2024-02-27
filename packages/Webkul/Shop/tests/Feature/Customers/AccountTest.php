<?php

use Illuminate\Support\Facades\Hash;
use Webkul\Customer\Models\Customer;
use Webkul\Customer\Models\CustomerAddress;
use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\Product\Models\ProductReview;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\patchJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

it('should returns the profile page', function () {
    // Act and Assert
    $customer = $this->loginAsCustomer();

    get(route('shop.customers.account.profile.index'))
        ->assertOk()
        ->assertSeeText(trans('shop::app.customers.account.profile.edit'))
        ->assertSeeText(trans('shop::app.customers.account.profile.delete'))
        ->assertSeeText($customer->first_name)
        ->assertSeeText($customer->last_name)
        ->assertSeeText($customer->email);
});

it('should returns the edit page of the customer', function () {
    // Act and Assert
    $customer = $this->loginAsCustomer();

    get(route('shop.customers.account.profile.edit'))
        ->assertOk()
        ->assertSeeText($customer->email)
        ->assertSeeText($customer->first_name)
        ->assertSeeText(trans('shop::app.customers.account.profile.edit-profile'));
});

it('should fails the validations error when certain inputs are not provided when update the customer', function () {
    // Act and Assert
    $this->loginAsCustomer();

    postJson(route('shop.customers.account.profile.store'))
        ->assertJsonValidationErrorFor('first_name')
        ->assertJsonValidationErrorFor('last_name')
        ->assertJsonValidationErrorFor('gender')
        ->assertJsonValidationErrorFor('phone')
        ->assertUnprocessable();
});

it('should update the customer', function () {
    // Act and Assert
    $customer = $this->loginAsCustomer();

    postJson(route('shop.customers.account.profile.store'), [
        'first_name'        => $firstName = fake()->firstName(),
        'last_name'         => $lastName = fake()->lastName(),
        'gender'            => $gender = fake()->randomElement(['Other', 'Male', 'Female']),
        'email'             => $customer->email,
        'status'            => 1,
        'customer_group_id' => 2,
        'phone'             => $phone = fake()->e164PhoneNumber(),
    ])
        ->assertRedirect(route('shop.customers.account.profile.index'));

    $this->assertModelWise([
        Customer::class => [
            [
                'first_name'        => $firstName,
                'last_name'         => $lastName,
                'gender'            => $gender,
                'email'             => $customer->email,
                'status'            => 1,
                'customer_group_id' => 2,
                'phone'             => $phone,
            ],
        ],
    ]);
});

it('should fails the validation error when password is not provided when delete the customer account', function () {
    // Act and Assert
    $this->loginAsCustomer();

    postJson(route('shop.customers.account.profile.destroy'))
        ->assertJsonValidationErrorFor('password')
        ->assertUnprocessable();
});

it('should delete the customer account', function () {
    // Arrange
    $customer = Customer::factory()->create([
        'password' => Hash::make('admin123'),
    ]);

    // Act and Assert
    $this->loginAsCustomer($customer);

    postJson(route('shop.customers.account.profile.destroy'), [
        'password' => 'admin123',
    ])
        ->assertRedirect(route('shop.customer.session.index'));

    $this->assertDatabaseMissing('customers', [
        'id' => $customer->id,
    ]);
});

it('should shows the reviews of customer', function () {
    // Arrange
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => fake()->randomFloat(2, 1000, 5000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getSimpleProductFactory()->create();

    $customer = Customer::factory()->create();

    $productReview = ProductReview::factory()->create([
        'product_id'  => $product->id,
        'customer_id' => $customer->id,
    ]);

    // Act and Assert
    $customer = $this->loginAsCustomer($customer);

    get(route('shop.customers.account.reviews.index'))
        ->assertOk()
        ->assertSeeText(trans('shop::app.customers.account.reviews.title'))
        ->assertSeeText($productReview->title)
        ->assertSeeText($productReview->comment);
});

it('should returns the address page of the customer', function () {
    // Arrange
    $customer = Customer::factory()->create();

    $customerAddress = CustomerAddress::factory()->create([
        'customer_id' => $customer->id,
    ]);

    // Act and Assert
    $this->loginAsCustomer($customer);

    get(route('shop.customers.account.addresses.index'))
        ->assertOk()
        ->assertSeeText($customerAddress->email)
        ->assertSeeText($customerAddress->fast_name)
        ->assertSeeText($customerAddress->last_name)
        ->assertSeeText($customerAddress->address1)
        ->assertSeeText($customerAddress->city)
        ->assertSeeText($customerAddress->state)
        ->assertSeeText($customerAddress->company_name)
        ->assertSeeText(trans('shop::app.customers.account.addresses.add-address'));
});

it('should returns the create page of address', function () {
    // Act and Assert
    $this->loginAsCustomer();

    get(route('shop.customers.account.addresses.create'))
        ->assertOk()
        ->assertSeeText(trans('shop::app.customers.account.addresses.add-address'))
        ->assertSeeText(trans('shop::app.customers.account.addresses.first-name'))
        ->assertSeeText(trans('shop::app.customers.account.addresses.last-name'))
        ->assertSeeText(trans('shop::app.customers.account.addresses.vat-id'))
        ->assertSeeText(trans('shop::app.customers.account.addresses.street-address'))
        ->assertSeeText(trans('shop::app.customers.account.addresses.company-name'));
});

it('should fails the validation error when certain inputs not provided when store the customer address', function () {
    // Act and Assert
    $this->loginAsCustomer();

    postJson(route('shop.customers.account.addresses.store'))
        ->assertJsonValidationErrorFor('city')
        ->assertJsonValidationErrorFor('phone')
        ->assertJsonValidationErrorFor('state')
        ->assertJsonValidationErrorFor('country')
        ->assertJsonValidationErrorFor('address1')
        ->assertJsonValidationErrorFor('postcode')
        ->assertJsonValidationErrorFor('last_name')
        ->assertJsonValidationErrorFor('first_name')
        ->assertUnprocessable();
});

it('should store the customer address', function () {
    // Act and Assert
    $customer = $this->loginAsCustomer();

    postJson(route('shop.customers.account.addresses.store'), [
        'customer_id'     => $customer->id,
        'company_name'    => $companyName = fake()->word(),
        'first_name'      => $firstName = fake()->firstName(),
        'last_name'       => $lastName = fake()->lastName(),
        'address1'        => [fake()->word()],
        'address'         => fake()->address(),
        'country'         => $countryCode = fake()->countryCode(),
        'state'           => $state = fake()->state(),
        'city'            => $city = fake()->city(),
        'postcode'        => $postCode = rand(11111, 99999),
        'phone'           => $phoneNumber = fake()->e164PhoneNumber(),
        'default_address' => fake()->randomElement([0, 1]),
        'address_type'    => $addressType = CustomerAddress::ADDRESS_TYPE,
    ])
        ->assertRedirect(route('shop.customers.account.addresses.index'));

    $this->assertModelWise([
        CustomerAddress::class => [
            [
                'customer_id'  => $customer->id,
                'company_name' => $companyName,
                'first_name'   => $firstName,
                'last_name'    => $lastName,
                'country'      => $countryCode,
                'state'        => $state,
                'city'         => $city,
                'postcode'     => $postCode,
                'phone'        => $phoneNumber,
                'address_type' => $addressType,
            ],
        ],
    ]);
});

it('should edit the customer address', function () {
    // Arrange
    $customer = Customer::factory()->create();

    $customerAddress = CustomerAddress::factory()->create([
        'customer_id' => $customer->id,
    ]);

    // Act and Assert
    $this->loginAsCustomer($customer);

    get(route('shop.customers.account.addresses.edit', $customerAddress->id))
        ->assertOk()
        ->assertSeeText(trans('shop::app.customers.account.addresses.edit'))
        ->assertSeeText(trans('shop::app.customers.account.addresses.title'))
        ->assertSeeText(trans('shop::app.customers.account.addresses.save'));
});

it('should fails the validation error when certain inputs not provided update the customer address', function () {
    $customer = Customer::factory()->create();

    $customerAddress = CustomerAddress::factory()->create([
        'customer_id' => $customer->id,
    ]);

    // Act and Assert
    $this->loginAsCustomer($customer);

    putJson(route('shop.customers.account.addresses.update', $customerAddress->id))
        ->assertJsonValidationErrorFor('city')
        ->assertJsonValidationErrorFor('phone')
        ->assertJsonValidationErrorFor('state')
        ->assertJsonValidationErrorFor('country')
        ->assertJsonValidationErrorFor('address1')
        ->assertJsonValidationErrorFor('postcode')
        ->assertJsonValidationErrorFor('last_name')
        ->assertJsonValidationErrorFor('first_name')
        ->assertUnprocessable();
});

it('should update the customer address', function () {
    $customer = Customer::factory()->create();

    $customerAddress = CustomerAddress::factory()->create([
        'customer_id' => $customer->id,
    ]);

    // Act and Assert
    $this->loginAsCustomer($customer);

    putJson(route('shop.customers.account.addresses.update', $customerAddress->id), [
        'customer_id'     => $customer->id,
        'company_name'    => $companyName = fake()->word(),
        'first_name'      => $firstName = fake()->firstName(),
        'last_name'       => $lastName = fake()->lastName(),
        'address1'        => [fake()->word()],
        'country'         => $customerAddress->country,
        'state'           => $customerAddress->state,
        'city'            => $customerAddress->city,
        'postcode'        => $postCode = rand(1111, 99999),
        'phone'           => $customerAddress->phone,
        'default_address' => 1,
        'address_type'    => $customerAddress->address_type,
    ])
        ->assertRedirect(route('shop.customers.account.addresses.index'));

    $this->assertModelWise([
        CustomerAddress::class => [
            [
                'customer_id'     => $customer->id,
                'company_name'    => $companyName,
                'first_name'      => $firstName,
                'last_name'       => $lastName,
                'country'         => $customerAddress->country,
                'state'           => $customerAddress->state,
                'city'            => $customerAddress->city,
                'postcode'        => $postCode,
                'phone'           => $customerAddress->phone,
                'default_address' => $customerAddress->default_address,
                'address_type'    => $customerAddress->address_type,
            ],
        ],
    ]);
});

it('should set default address for the customer', function () {
    // Arrange
    $customer = Customer::factory()->create();

    $customerAddresses = CustomerAddress::factory()->create([
        'customer_id'     => $customer->id,
        'default_address' => 0,
    ]);

    // Act and assert
    $this->loginAsCustomer($customer);

    patchJson(route('shop.customers.account.addresses.update.default', $customerAddresses->id))
        ->assertRedirect();

    $this->assertModelWise([
        CustomerAddress::class => [
            [
                'customer_id'     => $customer->id,
                'default_address' => 1,
            ],
        ],
    ]);
});

it('should delete the customer address', function () {
    $customer = Customer::factory()->create();

    $customerAddress = CustomerAddress::factory()->create([
        'customer_id'     => $customer->id,
        'default_address' => 0,
    ]);

    // Act and assert
    $this->loginAsCustomer($customer);

    deleteJson(route('shop.customers.account.addresses.delete', $customerAddress->id))
        ->assertRedirect();

    $this->assertDatabaseMissing('addresses', [
        'customer_id' => $customer->id,
        'id'          => $customerAddress->id,
    ]);
});
