<?php

use Webkul\Core\Models\SubscribersList;
use Webkul\Customer\Models\Customer;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\putJson;

// ============================================================================
// Index
// ============================================================================

it('should return the newsletter subscription index page', function () {
    $this->loginAsAdmin();

    get(route('admin.marketing.communications.subscribers.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.communications.subscribers.index.title'));
});

it('should deny guest access to the newsletter subscription index page', function () {
    get(route('admin.marketing.communications.subscribers.index'))
        ->assertRedirect(route('admin.session.create'));
});

// ============================================================================
// Edit
// ============================================================================

it('should return subscriber details as JSON', function () {
    $subscriber = SubscribersList::factory()->create();

    $this->loginAsAdmin();

    get(route('admin.marketing.communications.subscribers.edit', $subscriber->id))
        ->assertOk()
        ->assertJsonPath('data.id', $subscriber->id)
        ->assertJsonPath('data.email', $subscriber->email);
});

// ============================================================================
// Update
// ============================================================================

it('should update a subscriber status', function () {
    $subscriber = SubscribersList::factory()->create(['is_subscribed' => true]);

    $this->loginAsAdmin();

    putJson(route('admin.marketing.communications.subscribers.update'), [
        'id' => $subscriber->id,
        'is_subscribed' => 0,
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.communications.subscribers.index.edit.success'));

    $this->assertDatabaseHas('subscribers_list', [
        'id' => $subscriber->id,
        'is_subscribed' => false,
    ]);
});

it('should sync subscription status with the customer record', function () {
    $customer = Customer::factory()->create(['subscribed_to_news_letter' => true]);

    $subscriber = SubscribersList::factory()->create([
        'customer_id' => $customer->id,
        'email' => $customer->email,
        'is_subscribed' => true,
    ]);

    $this->loginAsAdmin();

    putJson(route('admin.marketing.communications.subscribers.update'), [
        'id' => $subscriber->id,
        'is_subscribed' => 0,
    ])
        ->assertOk();

    $this->assertDatabaseHas('customers', [
        'id' => $customer->id,
        'subscribed_to_news_letter' => false,
    ]);
});

it('should fail validation when required fields are missing on update', function () {
    $this->loginAsAdmin();

    putJson(route('admin.marketing.communications.subscribers.update'))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('id')
        ->assertJsonValidationErrorFor('is_subscribed');
});

it('should fail validation when id is missing on update', function () {
    $this->loginAsAdmin();

    putJson(route('admin.marketing.communications.subscribers.update'), [
        'is_subscribed' => 1,
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('id');
});

it('should fail validation when is_subscribed is missing on update', function () {
    $subscriber = SubscribersList::factory()->create();

    $this->loginAsAdmin();

    putJson(route('admin.marketing.communications.subscribers.update'), [
        'id' => $subscriber->id,
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('is_subscribed');
});

// ============================================================================
// Delete
// ============================================================================

it('should delete a subscriber', function () {
    $subscriber = SubscribersList::factory()->create();

    $this->loginAsAdmin();

    deleteJson(route('admin.marketing.communications.subscribers.delete', $subscriber->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.communications.subscribers.delete-success'));

    $this->assertDatabaseMissing('subscribers_list', ['id' => $subscriber->id]);
});

it('should unset the customer newsletter flag on delete', function () {
    $customer = Customer::factory()->create(['subscribed_to_news_letter' => true]);

    $subscriber = SubscribersList::factory()->create([
        'customer_id' => $customer->id,
        'email' => $customer->email,
        'is_subscribed' => true,
    ]);

    $this->loginAsAdmin();

    deleteJson(route('admin.marketing.communications.subscribers.delete', $subscriber->id))
        ->assertOk();

    $this->assertDatabaseHas('customers', [
        'id' => $customer->id,
        'subscribed_to_news_letter' => false,
    ]);
});
