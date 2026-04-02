<?php

use Webkul\Customer\Models\Customer;
use Webkul\GDPR\Models\GDPRDataRequest;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\putJson;

// ============================================================================
// Index
// ============================================================================

it('should return the GDPR requests index page', function () {
    $this->loginAsAdmin();

    get(route('admin.customers.gdpr.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.customers.gdpr.index.title'));
});

it('should deny guest access to the GDPR requests index page', function () {
    get(route('admin.customers.gdpr.index'))
        ->assertRedirect(route('admin.session.create'));
});

// ============================================================================
// Edit
// ============================================================================

it('should return GDPR request details', function () {
    $customer = Customer::factory()->create();

    $gdprRequest = GDPRDataRequest::create([
        'customer_id' => $customer->id,
        'email' => $customer->email,
        'status' => 'pending',
        'type' => 'delete',
        'message' => 'Please delete my data.',
    ]);

    $this->loginAsAdmin();

    get(route('admin.customers.gdpr.edit', $gdprRequest->id))
        ->assertOk()
        ->assertJsonPath('data.id', $gdprRequest->id)
        ->assertJsonPath('data.status', 'pending')
        ->assertJsonPath('data.type', 'delete');
});

it('should return 500 for a non-existent GDPR request', function () {
    $this->loginAsAdmin();

    get(route('admin.customers.gdpr.edit', 99999))
        ->assertServerError();
});

// ============================================================================
// Update
// ============================================================================

it('should update a GDPR request status', function () {
    $customer = Customer::factory()->create();

    $gdprRequest = GDPRDataRequest::create([
        'customer_id' => $customer->id,
        'email' => $customer->email,
        'status' => 'pending',
        'type' => 'delete',
        'message' => 'Delete my account.',
    ]);

    $this->loginAsAdmin();

    putJson(route('admin.customers.gdpr.update', $gdprRequest->id), [
        'status' => 'completed',
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.customers.gdpr.index.update-success'));

    $this->assertDatabaseHas('gdpr_data_request', [
        'id' => $gdprRequest->id,
        'status' => 'completed',
    ]);
});

// ============================================================================
// Delete
// ============================================================================

it('should delete a GDPR request', function () {
    $customer = Customer::factory()->create();

    $gdprRequest = GDPRDataRequest::create([
        'customer_id' => $customer->id,
        'email' => $customer->email,
        'status' => 'pending',
        'type' => 'update',
        'message' => 'Update my data.',
    ]);

    $this->loginAsAdmin();

    deleteJson(route('admin.customers.gdpr.delete', $gdprRequest->id))
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.customers.gdpr.index.delete-success'));

    $this->assertDatabaseMissing('gdpr_data_request', ['id' => $gdprRequest->id]);
});

it('should return 500 when deleting a non-existent GDPR request', function () {
    $this->loginAsAdmin();

    deleteJson(route('admin.customers.gdpr.delete', 99999))
        ->assertServerError();
});
