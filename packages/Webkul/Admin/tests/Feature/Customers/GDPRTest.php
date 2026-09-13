<?php

use Webkul\Customer\Models\Customer;
use Webkul\GDPR\Models\GDPRDataRequest;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\getJson;
use function Pest\Laravel\putJson;

/**
 * A pending GDPR request of the given type, raised by a fresh customer.
 */
function pendingGdprRequest(string $type = 'delete'): GDPRDataRequest
{
    $customer = Customer::factory()->create();

    return GDPRDataRequest::create([
        'customer_id' => $customer->id,
        'email' => $customer->email,
        'status' => 'pending',
        'type' => $type,
        'message' => 'Please '.$type.' my data.',
    ]);
}

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
    $gdprRequest = pendingGdprRequest();

    $this->loginAsAdmin();

    getJson(route('admin.customers.gdpr.edit', $gdprRequest->id))
        ->assertOk()
        ->assertJsonPath('data.id', $gdprRequest->id)
        ->assertJsonPath('data.status', 'pending')
        ->assertJsonPath('data.type', 'delete');
});

it('should return 404 for a GDPR request that does not exist', function () {
    $this->loginAsAdmin();

    getJson(route('admin.customers.gdpr.edit', 999999))
        ->assertNotFound();
});

// ============================================================================
// Update
// ============================================================================

it('should update a GDPR request status', function () {
    $gdprRequest = pendingGdprRequest();

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
    $gdprRequest = pendingGdprRequest('update');

    $this->loginAsAdmin();

    deleteJson(route('admin.customers.gdpr.delete', $gdprRequest->id))
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.customers.gdpr.index.delete-success'));

    $this->assertDatabaseMissing('gdpr_data_request', ['id' => $gdprRequest->id]);
});

it('should return 404 when deleting a GDPR request that does not exist', function () {
    $this->loginAsAdmin();

    deleteJson(route('admin.customers.gdpr.delete', 999999))
        ->assertNotFound();
});
