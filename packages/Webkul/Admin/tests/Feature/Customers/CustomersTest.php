<?php

use Illuminate\Support\Facades\Mail;
use Webkul\Admin\Mail\Customer\NewCustomerNotification;
use Webkul\Core\Models\CoreConfig;
use Webkul\Customer\Models\Customer;
use Webkul\Shop\Mail\Customer\NoteNotification;

use function Pest\Laravel\get;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

// ============================================================================
// Index
// ============================================================================

it('should return the customers index page', function () {
    $this->loginAsAdmin();

    get(route('admin.customers.customers.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.customers.customers.index.title'))
        ->assertSeeText(trans('admin::app.customers.customers.index.create.create-btn'));
});

it('should return customer listing via datagrid', function () {
    $customer = Customer::factory()->create();

    $this->loginAsAdmin();

    getJson(route('admin.customers.customers.index'), [
        'X-Requested-With' => 'XMLHttpRequest',
    ])
        ->assertOk()
        ->assertJsonPath('records.0.customer_id', $customer->id)
        ->assertJsonPath('records.0.email', $customer->email);
});

it('should deny guest access to the customers index page', function () {
    get(route('admin.customers.customers.index'))
        ->assertRedirect(route('admin.session.create'));
});

// ============================================================================
// View
// ============================================================================

it('should return the customer view page', function () {
    $customer = Customer::factory()->create();

    $this->loginAsAdmin();

    get(route('admin.customers.customers.view', $customer->id))
        ->assertOk()
        ->assertSeeText($customer->first_name)
        ->assertSeeText($customer->last_name)
        ->assertSeeText($customer->email)
        ->assertSeeText(trans('admin::app.customers.customers.view.title'));
});

it('should return 404 for a non-existent customer view page', function () {
    $this->loginAsAdmin();

    get(route('admin.customers.customers.view', 99999))
        ->assertNotFound();
});

// ============================================================================
// Store
// ============================================================================

it('should create a new customer', function () {
    $this->loginAsAdmin();

    postJson(route('admin.customers.customers.store'), $data = [
        'first_name' => fake()->firstName(),
        'last_name' => fake()->lastName(),
        'gender' => fake()->randomElement(['male', 'female', 'other']),
        'email' => fake()->safeEmail(),
        'channel_id' => 1,
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.customers.customers.index.create.create-success'));

    $this->assertDatabaseHas('customers', [
        'first_name' => $data['first_name'],
        'last_name' => $data['last_name'],
        'gender' => $data['gender'],
        'email' => $data['email'],
    ]);
});

it('should create a customer and send notification email', function () {
    Mail::fake();

    CoreConfig::factory()->create([
        'code' => 'emails.general.notifications.emails.general.notifications.customer_account_credentials',
        'value' => 1,
    ]);

    $this->loginAsAdmin();

    postJson(route('admin.customers.customers.store'), [
        'first_name' => fake()->firstName(),
        'last_name' => fake()->lastName(),
        'gender' => 'male',
        'email' => fake()->safeEmail(),
        'channel_id' => 1,
    ])
        ->assertOk();

    Mail::assertQueued(NewCustomerNotification::class);
});

it('should fail validation when required fields are missing on store', function () {
    $this->loginAsAdmin();

    postJson(route('admin.customers.customers.store'))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('first_name')
        ->assertJsonValidationErrorFor('last_name')
        ->assertJsonValidationErrorFor('gender')
        ->assertJsonValidationErrorFor('email');
});

it('should fail validation when email already exists on store', function () {
    $existing = Customer::factory()->create();

    $this->loginAsAdmin();

    postJson(route('admin.customers.customers.store'), [
        'first_name' => fake()->firstName(),
        'last_name' => fake()->lastName(),
        'gender' => 'male',
        'email' => $existing->email,
        'channel_id' => $existing->channel_id,
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('email');
});

// ============================================================================
// Update
// ============================================================================

it('should update an existing customer', function () {
    $customer = Customer::factory()->create();

    $this->loginAsAdmin();

    putJson(route('admin.customers.customers.update', $customer->id), $data = [
        'first_name' => 'Updated First',
        'last_name' => 'Updated Last',
        'gender' => $customer->gender,
        'email' => fake()->safeEmail(),
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.customers.customers.update-success'));

    $this->assertDatabaseHas('customers', [
        'id' => $customer->id,
        'first_name' => 'Updated First',
        'last_name' => 'Updated Last',
        'email' => $data['email'],
    ]);
});

it('should fail validation when required fields are missing on update', function () {
    $customer = Customer::factory()->create();

    $this->loginAsAdmin();

    putJson(route('admin.customers.customers.update', $customer->id))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('first_name')
        ->assertJsonValidationErrorFor('last_name')
        ->assertJsonValidationErrorFor('gender')
        ->assertJsonValidationErrorFor('email');
});

// ============================================================================
// Search
// ============================================================================

it('should search customers by name', function () {
    $customer = Customer::factory()->create();

    $this->loginAsAdmin();

    getJson(route('admin.customers.customers.search', [
        'query' => $customer->first_name,
    ]))
        ->assertOk()
        ->assertJsonPath('data.0.id', $customer->id)
        ->assertJsonPath('data.0.email', $customer->email);
});

// ============================================================================
// Login as Customer
// ============================================================================

it('should login as customer from the admin panel', function () {
    $customer = Customer::factory()->create();

    $this->loginAsAdmin();

    get(route('admin.customers.customers.login_as_customer', $customer->id))
        ->assertRedirect(route('shop.customers.account.profile.index'));
});

// ============================================================================
// Notes
// ============================================================================

it('should store a note for a customer', function () {
    $customer = Customer::factory()->create();

    $this->loginAsAdmin();

    postJson(route('admin.customer.note.store', $customer->id), [
        'note' => $note = 'This is a test admin note.',
    ])
        ->assertRedirect(route('admin.customers.customers.view', $customer->id));

    $this->assertDatabaseHas('customer_notes', [
        'customer_id' => $customer->id,
        'note' => $note,
    ]);
});

it('should store a note and send email notification to the customer', function () {
    Mail::fake();

    $customer = Customer::factory()->create();

    $this->loginAsAdmin();

    postJson(route('admin.customer.note.store', $customer->id), [
        'note' => 'Note with notification.',
        'customer_notified' => 1,
    ])
        ->assertRedirect(route('admin.customers.customers.view', $customer->id));

    Mail::assertQueued(NoteNotification::class);
});

it('should fail validation when note is missing', function () {
    $customer = Customer::factory()->create();

    $this->loginAsAdmin();

    postJson(route('admin.customer.note.store', $customer->id))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('note');
});

// ============================================================================
// Delete
// ============================================================================

it('should delete a specific customer', function () {
    $customer = Customer::factory()->create();

    $this->loginAsAdmin();

    postJson(route('admin.customers.customers.delete', $customer->id))
        ->assertRedirect(route('admin.customers.customers.index'));

    $this->assertDatabaseMissing('customers', ['id' => $customer->id]);
});

// ============================================================================
// Mass Update
// ============================================================================

it('should mass update customer status to active', function () {
    $customers = Customer::factory()->count(2)->create(['status' => 0]);

    $this->loginAsAdmin();

    postJson(route('admin.customers.customers.mass_update'), [
        'indices' => $customers->pluck('id')->toArray(),
        'value' => 1,
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.customers.customers.index.datagrid.update-success'));

    foreach ($customers as $customer) {
        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'status' => 1,
        ]);
    }
});

it('should mass update customer status to inactive', function () {
    $customers = Customer::factory()->count(2)->create(['status' => 1]);

    $this->loginAsAdmin();

    postJson(route('admin.customers.customers.mass_update'), [
        'indices' => $customers->pluck('id')->toArray(),
        'value' => 0,
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.customers.customers.index.datagrid.update-success'));

    foreach ($customers as $customer) {
        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'status' => 0,
        ]);
    }
});

// ============================================================================
// Mass Delete
// ============================================================================

it('should mass delete customers', function () {
    $customers = Customer::factory()->count(2)->create();

    $this->loginAsAdmin();

    postJson(route('admin.customers.customers.mass_delete'), [
        'indices' => $customers->pluck('id')->toArray(),
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.customers.customers.index.datagrid.delete-success'));

    foreach ($customers as $customer) {
        $this->assertDatabaseMissing('customers', ['id' => $customer->id]);
    }
});
