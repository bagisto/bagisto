<?php

use Webkul\Notification\Models\Notification;

use function Pest\Laravel\get;
use function Pest\Laravel\getJson;
use function Pest\Laravel\post;

/**
 * Record the only order notification there is, as placing an order does.
 */
function notifyOfOrder(): Notification
{
    Notification::query()->delete();

    $order = test()->createOrder();

    return Notification::create([
        'type' => 'order',
        'order_id' => $order->id,
        'read' => 0,
    ]);
}

// ============================================================================
// The Notification Feed
// ============================================================================

it('should not hand order notifications to an admin who may not view orders', function () {
    notifyOfOrder();

    $this->loginAsAdminWithPermissions(['dashboard']);

    $response = getJson(route('admin.notification.get_notification'))->assertOk();

    expect($response->json('search_results.data'))->toBeEmpty()
        ->and($response->json('total_unread'))->toBe(0)
        ->and($response->json('status_count'))->toBeEmpty();
});

it('should hand order notifications to an admin who may view orders', function () {
    notifyOfOrder();

    $this->loginAsAdminWithPermissions(['dashboard', 'sales', 'sales.orders']);

    $response = getJson(route('admin.notification.get_notification'))->assertOk();

    expect($response->json('search_results.data'))->toHaveCount(1)
        ->and($response->json('total_unread'))->toBe(1);
});

it('should hand order notifications to an admin whose role grants everything', function () {
    notifyOfOrder();

    $this->loginAsAdmin();

    $response = getJson(route('admin.notification.get_notification'))->assertOk();

    expect($response->json('search_results.data'))->toHaveCount(1)
        ->and($response->json('total_unread'))->toBe(1);
});

// ============================================================================
// The Notification Screen And Its Actions
// ============================================================================

it('should refuse the notification screen to an admin who may not view orders', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.notification.index'))->assertUnauthorized();
});

it('should refuse to mark notifications read for an admin who may not view orders', function () {
    notifyOfOrder();

    $this->loginAsAdminWithPermissions(['dashboard']);

    post(route('admin.notification.read_all'))->assertUnauthorized();

    expect(Notification::where('read', 0)->count())->toBe(1);
});

it('should refuse to open an order through a notification for an admin who may not view orders', function () {
    $notification = notifyOfOrder();

    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.notification.viewed_notification', $notification->order_id))->assertUnauthorized();

    expect((bool) $notification->refresh()->read)->toBeFalse();
});

// ============================================================================
// The Header Bell
// ============================================================================

it('should leave the notification bell out of the header for an admin who may not view orders', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.dashboard.index'))
        ->assertOk()
        ->assertDontSee('<v-notifications', false);
});
