<?php

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Webkul\EUWithdrawal\Enums\WithdrawalStatus;
use Webkul\EUWithdrawal\Models\Withdrawal;
use Webkul\Sales\Models\Order;
use Webkul\Shop\Mail\Customer\EUWithdrawal\GuestWithdrawalLink;
use Webkul\Shop\Mail\Customer\EUWithdrawal\WithdrawalConfirmation;
use Webkul\User\Models\Admin;

use function Pest\Laravel\get;
use function Pest\Laravel\post;

/**
 * Turn the EU withdrawal feature on for the current channel.
 */
function enableEuWithdrawal(): void
{
    test()->setConfig('sales.eu_withdrawal.general.enabled', '1');
}

/**
 * Turn the EU withdrawal feature off for the current channel.
 */
function disableEuWithdrawal(): void
{
    test()->setConfig('sales.eu_withdrawal.general.enabled', '0');
}

/**
 * Record a received withdrawal for a customer order, declared in the given locale.
 */
function createWithdrawalRecord(Order $order, string $locale = 'en'): Withdrawal
{
    return Withdrawal::query()->create([
        'uuid' => (string) Str::uuid(),
        'order_id' => $order->id,
        'customer_id' => $order->customer_id,
        'is_guest' => false,
        'customer_email' => $order->customer_email,
        'channel_id' => $order->channel_id,
        'locale' => $locale,
        'received_at' => now(),
        'status' => WithdrawalStatus::RECEIVED,
    ]);
}

// ============================================================================
// Observer (Append-Only)
// ============================================================================

it('should reject the deletion of a withdrawal record', function () {
    $withdrawal = createWithdrawalRecord($this->createOrder());

    expect(fn () => $withdrawal->delete())->toThrow(RuntimeException::class);
});

it('should reject a change to an evidence column after insert', function () {
    $withdrawal = createWithdrawalRecord($this->createOrder());

    expect(fn () => $withdrawal->update(['customer_email' => 'changed@example.test']))
        ->toThrow(RuntimeException::class);
});

it('should allow a change to an operational column', function () {
    $withdrawal = createWithdrawalRecord($this->createOrder());

    $withdrawal->update([
        'status' => WithdrawalStatus::REFUNDED,
        'refunded_at' => now(),
        'refund_note' => 'Refund #123',
    ]);

    expect($withdrawal->fresh()->status)->toBe(WithdrawalStatus::REFUNDED);
});

// ============================================================================
// Auth Flow
// ============================================================================

it('should answer not found on the create route when the channel toggle is off', function () {
    disableEuWithdrawal();

    $order = $this->createOrder(customer: $this->loginAsCustomer());

    get(route('shop.customers.account.eu-withdrawal.create', $order->id))
        ->assertNotFound();
});

it('should create a withdrawal record and send the confirmation email for a signed-in customer', function () {
    Mail::fake();

    enableEuWithdrawal();

    $customer = $this->loginAsCustomer();

    $order = $this->createOrder(customer: $customer);

    $response = post(route('shop.customers.account.eu-withdrawal.store', $order->id), [
        'reason_text' => 'Changed my mind.',
    ]);

    $withdrawal = Withdrawal::query()->where('order_id', $order->id)->first();

    expect($withdrawal)->not->toBeNull()
        ->and($withdrawal->status)->toBe(WithdrawalStatus::RECEIVED)
        ->and($withdrawal->customer_email)->toBe($customer->email)
        ->and($withdrawal->reason_text)->toBe('Changed my mind.')
        ->and($withdrawal->received_at)->not->toBeNull();

    $response->assertRedirectToRoute('shop.customers.account.eu-withdrawal.show', $withdrawal->uuid);

    Mail::assertSent(WithdrawalConfirmation::class);
});

it('should accept a withdrawal without a reason, since a reason is legally optional', function () {
    Mail::fake();

    enableEuWithdrawal();

    $order = $this->createOrder(customer: $this->loginAsCustomer());

    post(route('shop.customers.account.eu-withdrawal.store', $order->id), [])
        ->assertRedirect();

    expect(Withdrawal::query()->where('order_id', $order->id)->first()->reason_text)
        ->toBeNull();
});

it('should keep the first withdrawal record when the declaration is submitted twice', function () {
    Mail::fake();

    enableEuWithdrawal();

    $order = $this->createOrder(customer: $this->loginAsCustomer());

    post(route('shop.customers.account.eu-withdrawal.store', $order->id), ['reason_text' => 'first']);
    post(route('shop.customers.account.eu-withdrawal.store', $order->id), ['reason_text' => 'second']);

    expect(Withdrawal::query()->where('order_id', $order->id)->count())->toBe(1)
        ->and(Withdrawal::query()->where('order_id', $order->id)->first()->reason_text)->toBe('first');
});

// ============================================================================
// Guest Flow
// ============================================================================

it('should email a magic link when a guest lookup matches an order', function () {
    Mail::fake();

    enableEuWithdrawal();

    $order = $this->createGuestOrder(['customer_email' => fake()->unique()->safeEmail()]);

    $found = Order::query()
        ->where('increment_id', $order->increment_id)
        ->where('customer_email', $order->customer_email)
        ->where('is_guest', true)
        ->first();

    expect($found)->not->toBeNull('order should match before HTTP lookup is exercised')
        ->and($found->channel)->not->toBeNull('order must morphTo a channel')
        ->and((bool) core()->getConfigData('sales.eu_withdrawal.general.enabled', $found->channel->code))
        ->toBeTrue('config flag must be enabled for the order channel');

    post(route('shop.eu-withdrawal.guest.lookup.submit'), [
        'order_increment_id' => (string) $order->increment_id,
        'email' => $order->customer_email,
    ])->assertRedirect()->assertSessionHas('lookup_sent', true);

    Mail::assertQueued(GuestWithdrawalLink::class, fn ($mail) => $mail->toEmail === $order->customer_email);
});

it('should not reveal whether a guest order exists when a lookup misses', function () {
    Mail::fake();

    enableEuWithdrawal();

    post(route('shop.eu-withdrawal.guest.lookup.submit'), [
        'order_increment_id' => 'NON-EXISTENT',
        'email' => 'no-such@example.test',
    ])->assertRedirect()->assertSessionHas('lookup_sent', true);

    Mail::assertNothingSent();
});

it('should complete a guest withdrawal through a signed url', function () {
    Mail::fake();

    enableEuWithdrawal();

    $order = $this->createGuestOrder(['customer_email' => fake()->unique()->safeEmail()]);

    $signedStoreUrl = URL::temporarySignedRoute(
        'shop.eu-withdrawal.guest.store',
        now()->addHours(24),
        ['orderId' => $order->id],
    );

    post($signedStoreUrl, ['reason_text' => 'No longer needed.'])
        ->assertRedirect();

    $withdrawal = Withdrawal::query()->where('order_id', $order->id)->first();

    expect($withdrawal)->not->toBeNull()
        ->and($withdrawal->is_guest)->toBeTrue()
        ->and($withdrawal->customer_id)->toBeNull()
        ->and($withdrawal->customer_email)->toBe($order->customer_email);

    Mail::assertSent(WithdrawalConfirmation::class);
});

it('should reject the guest store endpoint without a valid signature', function () {
    enableEuWithdrawal();

    $order = $this->createGuestOrder();

    post(route('shop.eu-withdrawal.guest.store', $order->id), [])
        ->assertForbidden();
});

// ============================================================================
// Admin
// ============================================================================

it('should tell the admin the confirmation was resent in the admin\'s own language', function () {
    Mail::fake();

    $withdrawal = createWithdrawalRecord($this->createOrder(), 'ar');

    app()->setLocale('en');

    $this->actingAs(Admin::factory()->create(), 'admin');

    post(route('admin.sales.eu-withdrawals.resend_confirmation', $withdrawal->id));

    expect(app()->getLocale())->toBe('en')
        ->and(session('success'))->toBe(trans('admin::app.eu_withdrawal.flash.confirmation_resent', [], 'en'))
        ->and(session('success'))->not->toBe(trans('admin::app.eu_withdrawal.flash.confirmation_resent', [], 'ar'));

    Mail::assertSent(WithdrawalConfirmation::class);
});
