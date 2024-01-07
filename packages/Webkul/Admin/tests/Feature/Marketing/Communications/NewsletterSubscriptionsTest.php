<?php

use Webkul\Core\Models\SubscribersList;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\putJson;

afterEach(function () {
    /**
     * Cleaning up rows which are created.
     */
    SubscribersList::query()->delete();
});

it('should return the subscription index page', function () {
    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.marketing.communications.subscribers.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.communications.subscribers.index.title'));
});

it('should show the edit page of compaign', function () {
    // Arrange
    $subscriber = SubscribersList::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.marketing.communications.subscribers.edit', $subscriber->id))
        ->assertOk()
        ->assertJsonPath('data.id', $subscriber->id)
        ->assertJsonPath('data.email', $subscriber->email)
        ->assertJsonPath('data.is_subscribed', $subscriber->is_subscribed);
});

it('should update the subscriber', function () {
    // Arrange
    $subscriber = SubscribersList::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    putJson(route('admin.marketing.communications.subscribers.update'), [
        'id'            => $subscriber->id,
        'is_subscribed' => $isSubscribed = fake()->boolean,
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.communications.subscribers.index.edit.success'));

    $this->assertDatabaseHas('subscribers_list', [
        'id'            => $subscriber->id,
        'is_subscribed' => $isSubscribed,
    ]);
});

it('should delete the specific subscriber', function () {
    // Arrange
    $subscriber = SubscribersList::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    deleteJson(route('admin.marketing.communications.subscribers.delete', $subscriber->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.communications.subscribers.delete-success'));
});
