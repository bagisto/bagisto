<?php

use Webkul\Admin\Notifications\AdminNotifications;
use Webkul\Customer\Models\Customer;
use Webkul\Notification\Models\Notification;
use Webkul\Notification\Repositories\NotificationRepository;
use Webkul\User\Models\Admin;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

/**
 * Raise the notification a placed order raises.
 */
function notifyOfPlacedOrder(): Notification
{
    return notifier()->notify('order.placed', test()->createOrder())->first();
}

/**
 * Raise the notification a registered customer raises.
 */
function notifyOfRegisteredCustomer(): Notification
{
    return notifier()->notify('customer.registered', Customer::factory()->create())->first();
}

/**
 * The notification with the given id in a feed response, if it is listed.
 */
function listedNotification(array $feed, Notification $notification): ?array
{
    return collect($feed)->firstWhere('id', $notification->id);
}

/**
 * How many notifications the signed in admin has not read, in one group or in all of them, read without a request.
 */
function feedUnreadCount(?string $group = null): int
{
    $adminNotifications = app(AdminNotifications::class);

    $types = $group ? $adminNotifications->typesIn($group) : $adminNotifications->types();

    return array_sum(app(NotificationRepository::class)->unreadCountsFor(
        auth()->guard('admin')->user(),
        AdminNotifications::AUDIENCE,
        $types
    ));
}

/**
 * Whether the admin has read the notification.
 */
function adminHasRead(Notification $notification, Admin $admin): bool
{
    return $notification->reads()
        ->where('reader_type', $admin->getMorphClass())
        ->where('reader_id', $admin->id)
        ->exists();
}

// ============================================================================
// The Feed
// ============================================================================

it('should list a notification with its title, message, link and unread state', function () {
    $this->loginAsAdmin();

    $unread = feedUnreadCount();

    $notification = notifyOfPlacedOrder();

    $data = $notification->data;

    $response = getJson(route('admin.notification.list'))->assertOk();

    expect(listedNotification($response->json('data'), $notification))->toMatchArray([
        'title' => trans('admin::app.notifications.types.order-placed.title', ['order' => $data['increment_id']]),
        'message' => trans('admin::app.notifications.types.order.message', [
            'total' => core()->formatPrice($data['base_grand_total'], $data['base_currency_code']),
            'customer' => $data['customer_name'],
        ]),
        'icon' => 'icon-cart',
        'tone' => 'blue',
        'url' => route('admin.notification.open', $notification->id),
        'read' => false,
        'day_label' => trans('admin::app.notifications.today'),
    ])
        ->and($response->json('unread'))->toBe($unread + 1);
});

it('should list only the types the admin role may see', function () {
    $order = notifyOfPlacedOrder();

    $customer = notifyOfRegisteredCustomer();

    $this->loginAsAdminWithPermissions(['dashboard', 'customers', 'customers.customers']);

    $response = getJson(route('admin.notification.list'))->assertOk();

    expect(listedNotification($response->json('data'), $customer))->not->toBeNull()
        ->and(listedNotification($response->json('data'), $order))->toBeNull()
        ->and(collect($response->json('groups'))->pluck('key')->all())->toBe(['customers']);
});

it('should list nothing to an admin whose role sees no notification type', function () {
    notifyOfPlacedOrder();

    $this->loginAsAdminWithPermissions(['dashboard']);

    $response = getJson(route('admin.notification.list'))->assertOk();

    expect($response->json('data'))->toBeEmpty()
        ->and($response->json('groups'))->toBeEmpty()
        ->and($response->json('unread'))->toBe(0);
});

it('should narrow the feed to a group', function () {
    $order = notifyOfPlacedOrder();

    $customer = notifyOfRegisteredCustomer();

    $this->loginAsAdmin();

    $feed = getJson(route('admin.notification.list', ['group' => 'customers']))->assertOk()->json('data');

    expect(listedNotification($feed, $customer))->not->toBeNull()
        ->and(listedNotification($feed, $order))->toBeNull();
});

it('should refuse a group the admin role may not see', function () {
    $this->loginAsAdminWithPermissions(['dashboard', 'customers', 'customers.customers']);

    getJson(route('admin.notification.list', ['group' => 'orders']))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('group');
});

it('should narrow the feed to the notifications the admin has not read', function () {
    $read = notifyOfPlacedOrder();

    $unread = notifyOfPlacedOrder();

    $admin = $this->loginAsAdmin();

    app(NotificationRepository::class)->markRead($read->id, $admin);

    $feed = getJson(route('admin.notification.list', ['unread' => 1]))->assertOk()->json('data');

    expect(listedNotification($feed, $unread))->not->toBeNull()
        ->and(listedNotification($feed, $read))->toBeNull();
});

it('should count the unread notifications of each group', function () {
    $this->loginAsAdmin();

    $orders = feedUnreadCount('orders');

    $customers = feedUnreadCount('customers');

    notifyOfPlacedOrder();

    notifyOfPlacedOrder();

    notifyOfRegisteredCustomer();

    $groups = collect(getJson(route('admin.notification.list'))->assertOk()->json('groups'))->keyBy('key');

    expect($groups['orders']['unread'])->toBe($orders + 2)
        ->and($groups['customers']['unread'])->toBe($customers + 1)
        ->and($groups['orders']['name'])->toBe(trans('admin::app.notifications.groups.orders'));
});

it('should leave out a notification whose type is no longer registered', function () {
    $this->loginAsAdmin();

    $unread = feedUnreadCount();

    $orphan = Notification::query()->create(['type' => 'removed.type', 'audience' => AdminNotifications::AUDIENCE, 'data' => []]);

    $response = getJson(route('admin.notification.list'))->assertOk();

    expect(listedNotification($response->json('data'), $orphan))->toBeNull()
        ->and($response->json('unread'))->toBe($unread);

    get(route('admin.notification.open', $orphan->id))->assertNotFound();
});

// ============================================================================
// Read State
// ============================================================================

it('should keep each admin read state their own', function () {
    $notification = notifyOfPlacedOrder();

    $colleague = Admin::factory()->create();

    $this->loginAsAdmin();

    $unread = feedUnreadCount();

    postJson(route('admin.notification.mark_read', $notification->id))
        ->assertOk()
        ->assertJsonPath('unread', $unread - 1);

    $this->loginAsAdmin($colleague);

    $feed = getJson(route('admin.notification.list'))->assertOk()->json('data');

    expect(listedNotification($feed, $notification)['read'])->toBeFalse();
});

it('should mark a read notification unread again', function () {
    $notification = notifyOfPlacedOrder();

    $admin = $this->loginAsAdmin();

    app(NotificationRepository::class)->markRead($notification->id, $admin);

    $unread = feedUnreadCount();

    deleteJson(route('admin.notification.mark_unread', $notification->id))
        ->assertOk()
        ->assertJsonPath('unread', $unread + 1);

    expect(adminHasRead($notification, $admin))->toBeFalse();
});

it('should take a second read of a notification in its stride', function () {
    $notification = notifyOfPlacedOrder();

    $this->loginAsAdmin();

    $unread = postJson(route('admin.notification.mark_read', $notification->id))->assertOk()->json('unread');

    postJson(route('admin.notification.mark_read', $notification->id))
        ->assertOk()
        ->assertJsonPath('unread', $unread);
});

it('should mark every notification read for the admin', function () {
    $order = notifyOfPlacedOrder();

    $customer = notifyOfRegisteredCustomer();

    $admin = $this->loginAsAdmin();

    postJson(route('admin.notification.read_all'))
        ->assertOk()
        ->assertJsonPath('unread', 0)
        ->assertJsonPath('message', trans('admin::app.notifications.marked-all-read'));

    expect(adminHasRead($order, $admin))->toBeTrue()
        ->and(adminHasRead($customer, $admin))->toBeTrue();
});

it('should mark only the chosen group read', function () {
    $order = notifyOfPlacedOrder();

    notifyOfRegisteredCustomer();

    $admin = $this->loginAsAdmin();

    $orders = feedUnreadCount('orders');

    postJson(route('admin.notification.read_all'), ['group' => 'customers'])->assertOk();

    expect(feedUnreadCount('customers'))->toBe(0)
        ->and(feedUnreadCount('orders'))->toBe($orders)
        ->and(adminHasRead($order, $admin))->toBeFalse();
});

it('should refuse to mark a notification of a type the admin role may not see', function () {
    $notification = notifyOfPlacedOrder();

    $admin = $this->loginAsAdminWithPermissions(['dashboard', 'customers', 'customers.customers']);

    postJson(route('admin.notification.mark_read', $notification->id))->assertNotFound();

    expect(adminHasRead($notification, $admin))->toBeFalse();
});

// ============================================================================
// Opening A Notification
// ============================================================================

it('should open what the notification is about and mark it read', function () {
    $notification = notifyOfPlacedOrder();

    $admin = $this->loginAsAdmin();

    get(route('admin.notification.open', $notification->id))
        ->assertRedirect(route('admin.sales.orders.view', $notification->data['order_id']));

    expect(adminHasRead($notification, $admin))->toBeTrue();
});

it('should refuse to open a notification of a type the admin role may not see', function () {
    $notification = notifyOfPlacedOrder();

    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.notification.open', $notification->id))->assertNotFound();
});

// ============================================================================
// The Screen And The Bell
// ============================================================================

it('should show the notification screen to every admin', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.notification.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.notifications.description'));
});

it('should show the notification bell to every admin', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.dashboard.index'))
        ->assertOk()
        ->assertSee('<v-notifications', false);
});
