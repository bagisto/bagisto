<?php

use Illuminate\Support\Facades\Event;
use Webkul\Customer\Models\Customer;
use Webkul\Customer\Models\CustomerGroup;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

// ============================================================================
// Index
// ============================================================================

it('should return the customer groups index page', function () {
    $this->loginAsAdmin();

    get(route('admin.customers.groups.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.customers.groups.index.title'))
        ->assertSeeText(trans('admin::app.customers.groups.index.create.create-btn'));
});

it('should return customer groups listing via datagrid', function () {
    $group = CustomerGroup::factory()->create();

    $this->loginAsAdmin();

    getJson(route('admin.customers.groups.index'), [
        'X-Requested-With' => 'XMLHttpRequest',
    ])
        ->assertOk()
        ->assertJsonPath('records.0.id', $group->id)
        ->assertJsonPath('records.0.code', $group->code);
});

it('should deny guest access to the customer groups index page', function () {
    get(route('admin.customers.groups.index'))
        ->assertRedirect(route('admin.session.create'));
});

// ============================================================================
// Store
// ============================================================================

it('should store a new customer group', function () {
    $this->loginAsAdmin();

    postJson(route('admin.customers.groups.store'), [
        'code' => $code = fake()->unique()->lexify('group_??????????'),
        'name' => $name = fake()->name(),
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.customers.groups.index.create.success'));

    $this->assertDatabaseHas('customer_groups', [
        'code' => $code,
        'name' => $name,
        'is_user_defined' => 1,
    ]);
});

it('should fail validation when required fields are missing on store', function () {
    $this->loginAsAdmin();

    postJson(route('admin.customers.groups.store'))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('code')
        ->assertJsonValidationErrorFor('name');
});

it('should fail validation when code starts with a number', function () {
    $this->loginAsAdmin();

    postJson(route('admin.customers.groups.store'), [
        'code' => '123invalid',
        'name' => 'Invalid Code Group',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('code');
});

it('should fail validation when code already exists on store', function () {
    $existing = CustomerGroup::factory()->create();

    $this->loginAsAdmin();

    postJson(route('admin.customers.groups.store'), [
        'code' => $existing->code,
        'name' => 'Duplicate Code',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('code');
});

it('should dispatch events when storing a customer group', function () {
    Event::fake();

    $this->loginAsAdmin();

    postJson(route('admin.customers.groups.store'), [
        'code' => fake()->unique()->lexify('group_??????????'),
        'name' => fake()->name(),
    ])
        ->assertOk();

    Event::assertDispatched('customer.customer_group.create.before');
    Event::assertDispatched('customer.customer_group.create.after');
});

// ============================================================================
// Update
// ============================================================================

it('should update an existing customer group', function () {
    $group = CustomerGroup::factory()->create();

    $this->loginAsAdmin();

    putJson(route('admin.customers.groups.update'), [
        'id' => $group->id,
        'code' => $group->code,
        'name' => 'Updated Group Name',
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.customers.groups.index.edit.success'));

    $this->assertDatabaseHas('customer_groups', [
        'id' => $group->id,
        'name' => 'Updated Group Name',
    ]);
});

it('should fail validation when required fields are missing on update', function () {
    $group = CustomerGroup::factory()->create();

    $this->loginAsAdmin();

    putJson(route('admin.customers.groups.update'), [
        'id' => $group->id,
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('code')
        ->assertJsonValidationErrorFor('name');
});

it('should allow updating a group with its own code', function () {
    $group = CustomerGroup::factory()->create();

    $this->loginAsAdmin();

    putJson(route('admin.customers.groups.update'), [
        'id' => $group->id,
        'code' => $group->code,
        'name' => $group->name,
    ])
        ->assertOk();
});

it('should fail validation when code conflicts with another group on update', function () {
    $groupA = CustomerGroup::factory()->create();
    $groupB = CustomerGroup::factory()->create();

    $this->loginAsAdmin();

    putJson(route('admin.customers.groups.update'), [
        'id' => $groupB->id,
        'code' => $groupA->code,
        'name' => 'Conflict',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('code');
});

it('should dispatch events when updating a customer group', function () {
    Event::fake();

    $group = CustomerGroup::factory()->create();

    $this->loginAsAdmin();

    putJson(route('admin.customers.groups.update'), [
        'id' => $group->id,
        'code' => $group->code,
        'name' => 'Updated',
    ])
        ->assertOk();

    Event::assertDispatched('customer.customer_group.update.before');
    Event::assertDispatched('customer.customer_group.update.after');
});

// ============================================================================
// Destroy
// ============================================================================

it('should delete a user-defined customer group', function () {
    $group = CustomerGroup::factory()->create(['is_user_defined' => true]);

    $this->loginAsAdmin();

    deleteJson(route('admin.customers.groups.delete', $group->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.customers.groups.index.edit.delete-success'));

    $this->assertDatabaseMissing('customer_groups', ['id' => $group->id]);
});

it('should not delete a default customer group', function () {
    $group = CustomerGroup::factory()->create(['is_user_defined' => false]);

    $this->loginAsAdmin();

    deleteJson(route('admin.customers.groups.delete', $group->id))
        ->assertBadRequest()
        ->assertSeeText(trans('admin::app.customers.groups.index.edit.group-default'));

    $this->assertDatabaseHas('customer_groups', ['id' => $group->id]);
});

it('should not delete a customer group with associated customers', function () {
    $group = CustomerGroup::factory()->create(['is_user_defined' => true]);

    // Associate a customer with this group.
    Customer::factory()->create(['customer_group_id' => $group->id]);

    $this->loginAsAdmin();

    deleteJson(route('admin.customers.groups.delete', $group->id))
        ->assertBadRequest()
        ->assertSeeText(trans('admin::app.customers.groups.customer-associate'));

    $this->assertDatabaseHas('customer_groups', ['id' => $group->id]);
});

it('should dispatch events when deleting a customer group', function () {
    Event::fake();

    $group = CustomerGroup::factory()->create(['is_user_defined' => true]);

    $this->loginAsAdmin();

    deleteJson(route('admin.customers.groups.delete', $group->id))
        ->assertOk();

    Event::assertDispatched('customer.customer_group.delete.before');
    Event::assertDispatched('customer.customer_group.delete.after');
});
