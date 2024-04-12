<?php

use Webkul\Core\Models\SubscribersList;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\putJson;

it('should return the subscription index page', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.marketing.communications.subscribers.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.communications.subscribers.index.title'));
});

it('should show the edit page of campaign', function () {
    // Arrange.
    $subscriber = SubscribersList::factory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.marketing.communications.subscribers.edit', $subscriber->id))
        ->assertOk()
        ->assertJsonFragment($subscriber->toArray());
});

it('should fail the validation with errors when certain inputs are not provided when update in news letter subscription', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    putJson(route('admin.marketing.communications.subscribers.update'))
        ->assertJsonValidationErrorFor('id')
        ->assertJsonValidationErrorFor('is_subscribed')
        ->assertUnprocessable();
});

it('should fail the validation with errors when is subscribed not passed in update in news letter subscription', function () {
    // Arrange.
    $subscriber = SubscribersList::factory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    putJson(route('admin.marketing.communications.subscribers.update'), [
        'id' => $subscriber->id,
    ])
        ->assertJsonValidationErrorFor('is_subscribed')
        ->assertUnprocessable();
});

it('should fail the validation with errors when id is not passed in update in news letter subscription', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    putJson(route('admin.marketing.communications.subscribers.update'), [
        'is_subscribed' => 1,
    ])
        ->assertJsonValidationErrorFor('id')
        ->assertUnprocessable();
});

it('should update the subscriber', function () {
    // Arrange.
    $subscriber = SubscribersList::factory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    putJson(route('admin.marketing.communications.subscribers.update'), [
        'id'            => $subscriber->id,
        'is_subscribed' => $isSubscribed = rand(0, 1),
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.communications.subscribers.index.edit.success'));

    $this->assertModelWise([
        SubscribersList::class => [
            [
                'id'            => $subscriber->id,
                'is_subscribed' => $isSubscribed,
            ],
        ],
    ]);
});

it('should delete the specific subscriber', function () {
    // Arrange.
    $subscriber = SubscribersList::factory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    deleteJson(route('admin.marketing.communications.subscribers.delete', $subscriber->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.communications.subscribers.delete-success'));
});
