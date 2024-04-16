<?php

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Webkul\Admin\Mail\Customer\NewCustomerNotification;
use Webkul\Core\Models\CoreConfig;
use Webkul\Customer\Models\Customer;
use Webkul\Customer\Models\CustomerNote;
use Webkul\Faker\Helpers\Customer as CustomerFaker;
use Webkul\Shop\Mail\Customer\NoteNotification;

use function Pest\Laravel\get;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

it('should returns the customers page', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.customers.customers.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.customers.customers.index.title'))
        ->assertSeeText(trans('admin::app.customers.customers.index.create.create-btn'));
});

it('should return listing items of customers', function () {
    // Arrange.
    $customer = (new CustomerFaker())->factory()->create([
        'password' => Hash::make('admin123'),
    ]);

    // Act and Assert.
    $this->loginAsAdmin();

    getJson(route('admin.customers.customers.index'), [
        'X-Requested-With' => 'XMLHttpRequest',
    ])
        ->assertOk()
        ->assertJsonPath('records.0.customer_id', $customer->id)
        ->assertJsonPath('records.0.email', $customer->email)
        ->assertJsonPath('records.0.full_name', $customer->name);
});

it('should return the view page of customer', function () {
    // Arrange.
    $customer = (new CustomerFaker())->factory()->create([
        'password' => Hash::make('admin123'),
    ]);

    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.customers.customers.view', $customer->id))
        ->assertOk()
        ->assertSeeText($customer->first_name)
        ->assertSeeText($customer->last_name)
        ->assertSeeText($customer->gender)
        ->assertSeeText($customer->email)
        ->assertSeeText($customer->phone)
        ->assertSeeText(trans('admin::app.customers.customers.view.title'));
});

it('should fail the validation with errors when certain inputs are not provided when store in customer', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.customers.customers.store'))
        ->assertJsonValidationErrorFor('first_name')
        ->assertJsonValidationErrorFor('last_name')
        ->assertJsonValidationErrorFor('gender')
        ->assertJsonValidationErrorFor('email')
        ->assertUnprocessable();
});

it('should create a new customer', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.customers.customers.store'), $data = [
        'first_name' => fake()->firstName(),
        'last_name'  => fake()->lastName(),
        'gender'     => fake()->randomElement(['male', 'female', 'other']),
        'email'      => fake()->email(),
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.customers.customers.index.create.create-success'));

    $this->assertModelWise([
        Customer::class => [
            [
                'first_name' => $data['first_name'],
                'last_name'  => $data['last_name'],
                'gender'     => $data['gender'],
                'email'      => $data['email'],
            ],
        ],
    ]);
});

it('should create a new customer and send notification to the customer', function () {
    // Arrange.
    Mail::fake();

    CoreConfig::factory()->create([
        'code'  => 'emails.general.notifications.emails.general.notifications.customer',
        'value' => 1,
    ]);

    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.customers.customers.store'), $data = [
        'first_name' => fake()->firstName(),
        'last_name'  => fake()->lastName(),
        'gender'     => fake()->randomElement(['male', 'female', 'other']),
        'email'      => fake()->email(),
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.customers.customers.index.create.create-success'));

    $this->assertModelWise([
        Customer::class => [
            [
                'first_name' => $data['first_name'],
                'last_name'  => $data['last_name'],
                'gender'     => $data['gender'],
                'email'      => $data['email'],
            ],
        ],
    ]);

    Mail::assertQueued(NewCustomerNotification::class);
});

it('should search the customers for mega search', function () {
    // Arrange.
    $customer = (new CustomerFaker())->factory()->create([
        'password' => Hash::make('admin123'),
    ]);

    // Act and Assert.
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
    // Arrange.
    $customer = (new CustomerFaker())->factory()->create([
        'password' => Hash::make('admin123'),
    ]);

    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.customers.customers.login_as_customer', $customer->id))
        ->assertRedirect(route('shop.customers.account.profile.index'))
        ->isRedirection();
});

it('should fail the validation with errors for notes', function () {
    // Arrange.
    $customer = (new CustomerFaker())->factory()->create([
        'password' => Hash::make('admin123'),
    ]);

    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.customer.note.store', $customer->id))
        ->assertJsonValidationErrorFor('note')
        ->assertUnprocessable();
});

it('should store the notes for the customer', function () {
    // Arrange.
    $customer = (new CustomerFaker())->factory()->create([
        'password' => Hash::make('admin123'),
    ]);

    // Act and Assert.
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

it('should store the notes for the customer and send email to the customer', function () {
    // Arrange.
    Mail::fake();

    $customer = (new CustomerFaker())->factory()->create([
        'password' => Hash::make('admin123'),
    ]);

    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.customer.note.store', $customer->id), [
        'note'              => $note = substr(fake()->paragraph(), 0, 50),
        'customer_notified' => 1,
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

    Mail::assertQueued(NoteNotification::class);

    Mail::assertQueuedCount(1);
});

it('should fail the validation with errors when certain inputs are not provided when update in customer', function () {
    // Arrange.
    $customer = (new CustomerFaker())->factory()->create([
        'password' => Hash::make('admin123'),
    ]);

    // Act and Assert.
    $this->loginAsAdmin();

    putJson(route('admin.customers.customers.update', $customer->id))
        ->assertJsonValidationErrorFor('first_name')
        ->assertJsonValidationErrorFor('last_name')
        ->assertJsonValidationErrorFor('gender')
        ->assertJsonValidationErrorFor('email')
        ->assertUnprocessable();
});

it('should update the the existing customer', function () {
    // Arrange.
    $customer = (new CustomerFaker())->factory()->create([
        'password' => Hash::make('admin123'),
    ]);

    // Act and Assert.
    $this->loginAsAdmin();

    putJson(route('admin.customers.customers.update', $customer->id), $data = [
        'first_name' => fake()->firstName(),
        'last_name'  => $customer->last_name,
        'gender'     => $customer->gender,
        'email'      => fake()->email(),
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.customers.customers.update-success'));

    $this->assertModelWise([
        Customer::class => [
            [
                'first_name' => $data['first_name'],
                'last_name'  => $customer->last_name,
                'gender'     => $customer->gender,
                'email'      => $data['email'],
            ],
        ],
    ]);
});

it('should mass delete the customers', function () {
    // Arrange.
    $customers = (new CustomerFaker())->factory()->count(2)->create([
        'password' => Hash::make('admin123'),
    ]);

    // Act and Assert.
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
    // Arrange.
    $customers = (new CustomerFaker())->factory()->count(2)->create([
        'password' => Hash::make('admin123'),
    ]);

    // Act and Assert.
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
    // Arrange.
    $customer = (new CustomerFaker())->factory()->create([
        'password' => Hash::make('admin123'),
    ]);

    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.customers.customers.delete', $customer->id))
        ->assertRedirect(route('admin.customers.customers.index'))
        ->isRedirection();

    $this->assertDatabaseMissing('customers', [
        'id' => $customer->id,
    ]);
});
