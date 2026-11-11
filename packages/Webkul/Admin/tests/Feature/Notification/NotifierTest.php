<?php

use Illuminate\Support\Facades\Event;
use Webkul\Admin\Events\NotificationCreated;
use Webkul\Admin\Notifications\AdminNotifications;
use Webkul\Customer\Models\Customer;
use Webkul\DataTransfer\Models\Import;
use Webkul\GDPR\Models\GDPRDataRequest;
use Webkul\Notification\Models\Notification;
use Webkul\Notification\Notifier;
use Webkul\Notification\Types\AbstractType;
use Webkul\Product\Models\ProductReview;
use Webkul\RMA\Models\RMA;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\Refund;
use Webkul\User\Models\Admin;

use function Pest\Laravel\get;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

/**
 * How many notifications of the type were raised about the order.
 */
function notificationsAboutOrder(string $type, Order $order): int
{
    return Notification::query()->where('type', $type)->get()->filter(fn ($notification) => $notification->data['order_id'] === $order->id)->count();
}

/**
 * Whether the admin has read the notification.
 */
function notificationReadBy(Notification $notification, Admin $admin): bool
{
    return $notification->reads()
        ->where('reader_type', $admin->getMorphClass())
        ->where('reader_id', $admin->id)
        ->exists();
}

/**
 * Register a test type, listed under a group of its own, from the given definition.
 */
function registerTestNotificationType(string $type, AbstractType $class, array $definition): void
{
    app()->instance("test.notification.{$type}", $class);

    config([
        'notification_groups' => array_merge(config('notification_groups'), [
            'testing' => ['name' => 'Testing', 'icon' => 'icon-promotion', 'sort' => 99],
        ]),
        'notification_types' => array_merge(config('notification_types'), [
            $type => array_merge(['group' => 'testing', 'class' => "test.notification.{$type}"], $definition),
        ]),
    ]);
}

/**
 * A test type titled with the subject's name, addressed to the admins the subject lists.
 */
function addressedTestType(): AbstractType
{
    return new class extends AbstractType
    {
        public function data(mixed $subject): array
        {
            return ['name' => $subject['name']];
        }

        public function title(array $data): string
        {
            return "Report {$data['name']} ready";
        }

        public function recipients(mixed $subject): ?iterable
        {
            return $subject['admins'] ?? null;
        }
    };
}

// ============================================================================
// Raising A Notification
// ============================================================================

it('should keep the data its type builds from the subject', function () {
    $order = $this->createOrder();

    $notification = notifier()->notify('order.placed', $order)->first();

    expect($notification->type)->toBe('order.placed')
        ->and($notification->audience)->toBe(AdminNotifications::AUDIENCE)
        ->and($notification->notifiable_id)->toBeNull()
        ->and($notification->data)->toEqual([
            'order_id' => $order->id,
            'increment_id' => $order->increment_id,
            'customer_name' => trim($order->customer_first_name.' '.$order->customer_last_name),
            'base_grand_total' => (float) $order->base_grand_total,
            'base_currency_code' => $order->base_currency_code,
        ]);
});

it('should refuse a type that is not registered', function () {
    notifier()->notify('missing.type', []);
})->throws(InvalidArgumentException::class);

it('should skip a subject its type passes on', function () {
    $order = $this->createOrder(['status' => Order::STATUS_PROCESSING]);

    expect(notifier()->notify('order.canceled', $order))->toBeEmpty()
        ->and(notificationsAboutOrder('order.canceled', $order))->toBe(0);
});

it('should mark a notification read for the admin whose own action raised it, and only for them', function () {
    $colleague = Admin::factory()->create();

    $admin = $this->loginAsAdmin();

    postJson(route('admin.customers.customers.store'), [
        'first_name' => fake()->firstName(),
        'last_name' => fake()->lastName(),
        'gender' => 'other',
        'email' => $email = fake()->unique()->safeEmail(),
        'channel_id' => core()->getDefaultChannel()->id,
    ])->assertOk();

    $notification = Notification::query()->where('type', 'customer.registered')->latest('id')->firstOrFail();

    expect($notification->data['email'])->toBe($email)
        ->and(notificationReadBy($notification, $admin))->toBeTrue()
        ->and(notificationReadBy($notification, $colleague))->toBeFalse();
});

it('should leave a notification raised by the storefront unread for a signed in admin', function () {
    $admin = $this->loginAsAdmin();

    $notification = notifier()->notify('customer.registered', Customer::factory()->create())->first();

    expect(notificationReadBy($notification, $admin))->toBeFalse();
});

it('should broadcast a new notification on the admin channel', function () {
    Event::fake([NotificationCreated::class]);

    $notification = notifier()->notify('order.placed', $this->createOrder())->first();

    Event::assertDispatched(NotificationCreated::class, fn (NotificationCreated $event) => $event->id === $notification->id
        && $event->type === 'order.placed'
        && $event->broadcastAs() === 'notification-created');
});

// ============================================================================
// The Events That Raise Each Type
// ============================================================================

it('should raise a notification of the type an event is registered for', function (string $type, string $event, Closure $subject, string $key) {
    $subject = $subject->call($this);

    Event::dispatch($event, $subject);

    $notification = Notification::query()->where('type', $type)->latest('id')->first();

    expect($notification)->not->toBeNull()
        ->and($notification->data[$key])->toBe($subject->id);
})->with([
    'a placed order' => ['order.placed', 'checkout.order.save.after', fn () => $this->createOrder(), 'order_id'],
    'a canceled order' => ['order.canceled', 'sales.order.cancel.after', fn () => $this->createOrder(['status' => Order::STATUS_CANCELED]), 'order_id'],
    'a refund' => ['order.refunded', 'sales.refund.save.after', fn () => Refund::factory()->create([
        'order_id' => $this->createOrder()->id,
        'base_grand_total' => 49.5,
        'base_currency_code' => 'USD',
    ]), 'refund_id'],
    'a registered customer' => ['customer.registered', 'customer.registration.after', fn () => Customer::factory()->create(), 'customer_id'],
    'a new review' => ['review.created', 'customer.review.create.after', fn () => ProductReview::factory()->create([
        'product_id' => $this->createSimpleProduct()->id,
        'name' => 'Ava Patel',
    ]), 'review_id'],
    'a return request' => ['return.requested', 'customer.rma.request.create.after', fn () => RMA::query()->create([
        'order_id' => $this->createOrder()->id,
    ]), 'rma_id'],
    'a GDPR request' => ['gdpr.requested', 'customer.gdpr-request.create.after', fn () => GDPRDataRequest::query()->create([
        'customer_id' => ($customer = Customer::factory()->create())->id,
        'email' => $customer->email,
        'status' => 'pending',
        'type' => 'delete',
        'message' => 'Please delete my data.',
    ]), 'request_id'],
    'a finished import' => ['import.completed', 'data_transfer.imports.completed', fn () => Import::query()->create([
        'type' => 'products',
        'action' => 'append',
        'validation_strategy' => 'stop-on-errors',
        'field_separator' => ',',
        'file_path' => 'imports/products.csv',
        'processed_rows_count' => 10,
        'invalid_rows_count' => 2,
    ]), 'import_id'],
]);

it('should not notify about a cancellation that leaves the order open', function () {
    $order = $this->createOrder(['status' => Order::STATUS_PROCESSING]);

    Event::dispatch('sales.order.cancel.after', $order);

    expect(notificationsAboutOrder('order.canceled', $order))->toBe(0);
});

it('should warn when an import skipped rows, and read as done when it skipped none', function () {
    $clean = notifier()->notify('import.completed', Import::query()->create([
        'type' => 'products',
        'action' => 'append',
        'validation_strategy' => 'stop-on-errors',
        'field_separator' => ',',
        'file_path' => 'imports/clean.csv',
        'processed_rows_count' => 10,
    ]))->first();

    $skipped = notifier()->notify('import.completed', Import::query()->create([
        'type' => 'products',
        'action' => 'append',
        'validation_strategy' => 'skip-errors',
        'field_separator' => ',',
        'file_path' => 'imports/skipped.csv',
        'processed_rows_count' => 10,
        'invalid_rows_count' => 3,
    ]))->first();

    $this->loginAsAdmin();

    $feed = collect(getJson(route('admin.notification.list'))->assertOk()->json('data'))->keyBy('id');

    expect($feed[$clean->id]['tone'])->toBe('green')
        ->and($feed[$clean->id]['message'])->toBe(trans('admin::app.notifications.types.import-completed.message', ['processed' => 10]))
        ->and($feed[$skipped->id]['tone'])->toBe('amber')
        ->and($feed[$skipped->id]['message'])->toBe(trans('admin::app.notifications.types.import-completed.message-skipped', [
            'processed' => 10,
            'skipped' => 3,
        ]));
});

// ============================================================================
// A Type Registered By Another Package
// ============================================================================

it('should raise and list a type another package registers, under its own group', function () {
    registerTestNotificationType('marketing.campaign-sent', addressedTestType(), [
        'audience' => AdminNotifications::AUDIENCE,
        'events' => ['test.campaign.sent'],
    ]);

    app(Notifier::class)->listen();

    Event::dispatch('test.campaign.sent', [['name' => 'Autumn Sale']]);

    $this->loginAsAdminWithPermissions(['dashboard']);

    $response = getJson(route('admin.notification.list', ['group' => 'testing']))->assertOk();

    expect($response->json('data.0'))->toMatchArray([
        'title' => 'Report Autumn Sale ready',
        'read' => false,
    ])
        ->and(collect($response->json('groups'))->pluck('key')->all())->toBe(['testing']);
});

it('should show a notification addressed to some admins to those admins only', function () {
    registerTestNotificationType('reports.ready', addressedTestType(), ['audience' => AdminNotifications::AUDIENCE]);

    $addressed = Admin::factory()->create();

    $other = Admin::factory()->create();

    $notifications = notifier()->notify('reports.ready', ['name' => 'Sales', 'admins' => [$addressed]]);

    $this->loginAsAdmin($addressed);

    $listed = collect(getJson(route('admin.notification.list', ['group' => 'testing']))->assertOk()->json('data'))->pluck('id');

    $this->loginAsAdmin($other);

    $hidden = collect(getJson(route('admin.notification.list', ['group' => 'testing']))->json('data'))->pluck('id');

    expect($notifications)->toHaveCount(1)
        ->and($notifications->first()->notifiable_id)->toBe($addressed->id)
        ->and($listed->all())->toContain($notifications->first()->id)
        ->and($hidden->all())->not->toContain($notifications->first()->id);

    get(route('admin.notification.open', $notifications->first()->id))->assertNotFound();
});

it('should keep a notification for another audience out of the admin feed', function () {
    registerTestNotificationType('seller.payout-sent', addressedTestType(), ['audience' => 'seller']);

    $notification = notifier()->notify('seller.payout-sent', ['name' => 'May'])->first();

    $this->loginAsAdmin();

    expect($notification->audience)->toBe('seller')
        ->and(collect(getJson(route('admin.notification.list'))->assertOk()->json('data'))->pluck('id')->all())
        ->not->toContain($notification->id);

    get(route('admin.notification.open', $notification->id))->assertNotFound();
});
