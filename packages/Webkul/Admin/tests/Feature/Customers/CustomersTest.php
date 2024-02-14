<?php

use Illuminate\Support\Facades\Hash;
use Webkul\Customer\Models\Customer;
use Webkul\Customer\Models\CustomerNote;
use Webkul\Faker\Helpers\Customer as CustomerFaker;

use function Pest\Laravel\get;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

it('should returns the customers page', function () {
    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.customers.customers.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.customers.customers.index.title'))
        ->assertSeeText(trans('admin::app.customers.customers.index.create.create-btn'));
});

it('should return listing items of customers', function () {
    // Arrange
    $customer = (new CustomerFaker())->factory()->create([
        'password' => Hash::make('admin123'),
    ]);

    // Act & Assert
    $this->loginAsAdmin();

    getJson(route('admin.customers.customers.index'), [
        'X-Requested-With' => 'XMLHttpRequest',
    ])
        ->assertOk()
        ->assertJsonPath('records.0.customer_id', $customer->id)
        ->assertJsonPath('records.0.email', $customer->email)
        ->assertJsonPath('records.0.full_name', $customer->name)
        ->assertJsonPath('meta.total', 1);
});

it('should return the view page of customer', function () {
    // Arrange
    $customer = (new CustomerFaker())->factory()->create([
        'password' => Hash::make('admin123'),
    ]);

    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.customers.customers.view', $customer->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.customers.customers.view.title'))
        ->assertSeeText($customer->name);
});

it('should fail the validation with errors when certain inputs are not provided when store in customer', function () {
    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.customers.customers.store'))
        ->assertJsonValidationErrorFor('first_name')
        ->assertJsonValidationErrorFor('last_name')
        ->assertJsonValidationErrorFor('gender')
        ->assertJsonValidationErrorFor('email')
        ->assertUnprocessable();
});

it('should create a new customer', function () {
    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.customers.customers.store'), [
        'first_name' => $fistName = fake()->firstName(),
        'last_name'  => $lastName = fake()->lastName(),
        'gender'     => $gender = fake()->randomElement(['male', 'female', 'other']),
        'email'      => $email = fake()->email(),
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.customers.customers.index.create.create-success'));

    $this->assertModelWise([
        Customer::class => [
            [
                'first_name' => $fistName,
                'last_name'  => $lastName,
                'gender'     => $gender,
                'email'      => $email,
            ],
        ],
    ]);
});

it('should search the customers for mega search', function () {
    // Arrange
    $customer = (new CustomerFaker())->factory()->create([
        'password' => Hash::make('admin123'),
    ]);

    // Act and Assert
    $this->loginAsAdmin();

    getJson(route('admin.customers.customers.search'), [
        'query' => $customer->name,
    ])
        ->assertOk()
        ->assertJsonPath('data.0.id', $customer->id)
        ->assertJsonPath('data.0.first_name', $customer->first_name)
        ->assertJsonPath('data.0.email', $customer->email);
});

it('should login the customer from the admin panel', function () {
    // Arrange
    $customer = (new CustomerFaker())->factory()->create([
        'password' => Hash::make('admin123'),
    ]);

    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.customers.customers.login_as_customer', $customer->id))
        ->assertRedirect(route('shop.customers.account.profile.index'))
        ->isRedirection();
});

it('should fail the validation with errors for notes', function () {
    // Arrange
    $customer = (new CustomerFaker())->factory()->create([
        'password' => Hash::make('admin123'),
    ]);

    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.customer.note.store', $customer->id))
        ->assertJsonValidationErrorFor('note')
        ->assertUnprocessable();
});

it('should store the notes for the customer', function () {
    // Arrange
    $customer = (new CustomerFaker())->factory()->create([
        'password' => Hash::make('admin123'),
    ]);

    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.customer.note.store', $customer->id), [
        'note' => $note = substr(fake()->paragraph(), 0, 50),
    ])
        ->assertRedirect(route('admin.customers.customers.view', $customer->id))
        ->isRedirection();

    $this->assertModelWise([
        CustomerNote::class => [
            [
                'note' => $note,
            ],
        ],
    ]);
});

it('should fail the validation with errors when certain inputs are not provided when update in customer', function () {
    // Arrange
    $customer = (new CustomerFaker())->factory()->create([
        'password' => Hash::make('admin123'),
    ]);

    // Act and Assert
    $this->loginAsAdmin();

    putJson(route('admin.customers.customers.update', $customer->id))
        ->assertJsonValidationErrorFor('first_name')
        ->assertJsonValidationErrorFor('last_name')
        ->assertJsonValidationErrorFor('gender')
        ->assertJsonValidationErrorFor('email')
        ->assertUnprocessable();
});

it('should update the the existing customer', function () {
    // Arrange
    $customer = (new CustomerFaker())->factory()->create([
        'password' => Hash::make('admin123'),
    ]);

    // Act and Assert
    $this->loginAsAdmin();

    putJson(route('admin.customers.customers.update', $customer->id), [
        'first_name' => $fistName = fake()->firstName(),
        'last_name'  => $customer->last_name,
        'gender'     => $customer->gender,
        'email'      => $email = fake()->email(),
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.customers.customers.update-success'));

    $this->assertModelWise([
        Customer::class => [
            [
                'first_name' => $fistName,
                'last_name'  => $customer->last_name,
                'gender'     => $customer->gender,
                'email'      => $email,
            ],
        ],
    ]);
});

it('should mass delete the customers', function () {
    // Arrange
    $customers = (new CustomerFaker())->factory()->count(2)->create([
        'password' => Hash::make('admin123'),
    ]);

    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.customers.customers.mass_delete'), [
        'indices' => $customers->pluck('id')->toArray(),
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.customers.customers.index.datagrid.delete-success'));

    foreach ($customers as $customer) {
        $this->assertDatabaseMissing('customers', [
            'id' => $customer->id,
        ]);
    }
});

it('should mass update the customers', function () {
    // Arrange
    $customers = (new CustomerFaker())->factory()->count(2)->create([
        'password' => Hash::make('admin123'),
    ]);

    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.customers.customers.mass_update'), [
        'indices' => $customers->pluck('id')->toArray(),
        'value'   => 1,
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.customers.customers.index.datagrid.update-success'));

    foreach ($customers as $customer) {
        $this->assertModelWise([
            Customer::class => [
                [
                    'id'     => $customer->id,
                    'status' => 1,
                ],
            ],
        ]);
    }
});

it('should delete a specific customer', function () {
    // Arrange
    $customer = (new CustomerFaker())->factory()->create([
        'password' => Hash::make('admin123'),
    ]);

    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.customers.customers.delete', $customer->id))
        ->assertRedirect(route('admin.customers.customers.index'))
        ->isRedirection();

    $this->assertDatabaseMissing('customers', [
        'id' => $customer->id,
    ]);
});
