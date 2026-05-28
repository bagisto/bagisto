<?php

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Webkul\Core\Models\CoreConfig;
use Webkul\Customer\Models\Customer;
use Webkul\EUWithdrawal\Enums\WithdrawalStatus;
use Webkul\EUWithdrawal\Models\Withdrawal;
use Webkul\Sales\Models\Order;
use Webkul\Shop\Mail\Customer\EUWithdrawal\GuestWithdrawalLink;
use Webkul\Shop\Mail\Customer\EUWithdrawal\WithdrawalConfirmation;

use function Pest\Laravel\post;

/* -----------------------------------------------------------------------
 |  HELPERS
 | -----------------------------------------------------------------------*/

function enableEuWithdrawal(string $channelCode = 'default'): void
{
    CoreConfig::query()->updateOrCreate(
        ['code' => 'sales.eu_withdrawal.general.enabled', 'channel_code' => $channelCode, 'locale_code' => null],
        ['value' => '1']
    );
}

function disableEuWithdrawal(string $channelCode = 'default'): void
{
    CoreConfig::query()->updateOrCreate(
        ['code' => 'sales.eu_withdrawal.general.enabled', 'channel_code' => $channelCode, 'locale_code' => null],
        ['value' => '0']
    );
}

function makeOrderForCustomer(Customer $customer): Order
{
    return Order::factory()->create([
        'customer_id' => $customer->id,
        'customer_email' => $customer->email,
        'is_guest' => 0,
    ]);
}

function makeGuestOrder(string $email = 'guest@example.test'): Order
{
    return Order::factory()->create([
        'customer_id' => null,
        'customer_email' => $email,
        'is_guest' => 1,
    ]);
}

/* -----------------------------------------------------------------------
 |  OBSERVER (append-only)
 | -----------------------------------------------------------------------*/

it('rejects deletion of a withdrawal record', function () {
    enableEuWithdrawal();
    $customer = $this->loginAsCustomer();
    $order = makeOrderForCustomer($customer);

    $withdrawal = Withdrawal::create([
        'uuid' => (string) Str::uuid(),
        'order_id' => $order->id,
        'customer_id' => $customer->id,
        'is_guest' => false,
        'customer_email' => $customer->email,
        'channel_id' => $order->channel_id,
        'locale' => 'en',
        'received_at' => now(),
        'status' => WithdrawalStatus::RECEIVED,
    ]);

    expect(fn () => $withdrawal->delete())->toThrow(RuntimeException::class);
});

it('rejects mutation of evidence columns after insert', function () {
    enableEuWithdrawal();
    $customer = $this->loginAsCustomer();
    $order = makeOrderForCustomer($customer);

    $withdrawal = Withdrawal::create([
        'uuid' => (string) Str::uuid(),
        'order_id' => $order->id,
        'customer_id' => $customer->id,
        'is_guest' => false,
        'customer_email' => $customer->email,
        'channel_id' => $order->channel_id,
        'locale' => 'en',
        'received_at' => now(),
        'status' => WithdrawalStatus::RECEIVED,
    ]);

    expect(fn () => $withdrawal->update(['customer_email' => 'changed@example.test']))
        ->toThrow(RuntimeException::class);
});

it('allows mutation of operational columns', function () {
    enableEuWithdrawal();
    $customer = $this->loginAsCustomer();
    $order = makeOrderForCustomer($customer);

    $withdrawal = Withdrawal::create([
        'uuid' => (string) Str::uuid(),
        'order_id' => $order->id,
        'customer_id' => $customer->id,
        'is_guest' => false,
        'customer_email' => $customer->email,
        'channel_id' => $order->channel_id,
        'locale' => 'en',
        'received_at' => now(),
        'status' => WithdrawalStatus::RECEIVED,
    ]);

    $withdrawal->update([
        'status' => WithdrawalStatus::REFUNDED,
        'refunded_at' => now(),
        'refund_note' => 'Refund #123',
    ]);

    expect($withdrawal->fresh()->status)->toBe(WithdrawalStatus::REFUNDED);
});

/* -----------------------------------------------------------------------
 |  AUTH FLOW
 | -----------------------------------------------------------------------*/

it('returns 404 on the create route when the channel toggle is off', function () {
    disableEuWithdrawal();
    $customer = $this->loginAsCustomer();
    $order = makeOrderForCustomer($customer);

    $this->get(route('shop.customers.account.eu-withdrawal.create', $order->id))
        ->assertNotFound();
});

it('creates a withdrawal record and confirmation email on auth happy path', function () {
    Mail::fake();
    enableEuWithdrawal();
    $customer = $this->loginAsCustomer();
    $order = makeOrderForCustomer($customer);

    $response = post(route('shop.customers.account.eu-withdrawal.store', $order->id), [
        'reason_text' => 'Changed my mind.',
    ]);

    $withdrawal = Withdrawal::query()->where('order_id', $order->id)->first();
    expect($withdrawal)->not->toBeNull();
    expect($withdrawal->status)->toBe(WithdrawalStatus::RECEIVED);
    expect($withdrawal->customer_email)->toBe($customer->email);
    expect($withdrawal->reason_text)->toBe('Changed my mind.');
    expect($withdrawal->received_at)->not->toBeNull();

    $response->assertRedirectToRoute('shop.customers.account.eu-withdrawal.show', $withdrawal->uuid);

    Mail::assertSent(WithdrawalConfirmation::class);
});

it('accepts a withdrawal with no reason (reason is legally optional)', function () {
    Mail::fake();
    enableEuWithdrawal();
    $customer = $this->loginAsCustomer();
    $order = makeOrderForCustomer($customer);

    post(route('shop.customers.account.eu-withdrawal.store', $order->id), [])
        ->assertRedirect();

    expect(Withdrawal::query()->where('order_id', $order->id)->first()->reason_text)
        ->toBeNull();
});

it('is idempotent: double submit returns the same withdrawal record', function () {
    Mail::fake();
    enableEuWithdrawal();
    $customer = $this->loginAsCustomer();
    $order = makeOrderForCustomer($customer);

    post(route('shop.customers.account.eu-withdrawal.store', $order->id), ['reason_text' => 'first']);
    post(route('shop.customers.account.eu-withdrawal.store', $order->id), ['reason_text' => 'second']);

    expect(Withdrawal::query()->where('order_id', $order->id)->count())->toBe(1);

    $withdrawal = Withdrawal::query()->where('order_id', $order->id)->first();
    expect($withdrawal->reason_text)->toBe('first');
});

/* -----------------------------------------------------------------------
 |  GUEST FLOW
 | -----------------------------------------------------------------------*/

it('emails a magic link when guest lookup matches an order', function () {
    Mail::fake();
    enableEuWithdrawal();
    $order = makeGuestOrder('claimant@example.test');

    // Sanity: confirm the order we expect lookup to find actually matches at the DB level.
    $found = Order::query()
        ->where('increment_id', $order->increment_id)
        ->where('customer_email', 'claimant@example.test')
        ->where('is_guest', 1)
        ->first();
    expect($found)->not->toBeNull('order should match before HTTP lookup is exercised');

    // What channel is the order on, and is config enabled for it?
    $channel = $found->channel;
    expect($channel)->not->toBeNull('order must morphTo a channel');
    $cfg = core()->getConfigData('sales.eu_withdrawal.general.enabled', $channel->code);
    expect((bool) $cfg)->toBeTrue('config flag must be enabled for the order channel');

    post(route('shop.eu-withdrawal.guest.lookup.submit'), [
        'order_increment_id' => (string) $order->increment_id,
        'email' => 'claimant@example.test',
    ])->assertRedirect()->assertSessionHas('lookup_sent', true);

    Mail::assertQueued(GuestWithdrawalLink::class, fn ($mail) => $mail->toEmail === 'claimant@example.test');
});

it('does not reveal whether a guest order exists when lookup misses', function () {
    Mail::fake();
    enableEuWithdrawal();

    $response = post(route('shop.eu-withdrawal.guest.lookup.submit'), [
        'order_increment_id' => 'NON-EXISTENT',
        'email' => 'no-such@example.test',
    ]);

    $response->assertRedirect()->assertSessionHas('lookup_sent', true);
    Mail::assertNothingSent();
});

it('completes the guest happy path via a signed URL', function () {
    Mail::fake();
    enableEuWithdrawal();
    $order = makeGuestOrder('claimant@example.test');

    $signedStoreUrl = URL::temporarySignedRoute(
        'shop.eu-withdrawal.guest.store',
        now()->addHours(24),
        ['orderId' => $order->id],
    );

    $this->post($signedStoreUrl, ['reason_text' => 'No longer needed.'])
        ->assertRedirect();

    $withdrawal = Withdrawal::query()->where('order_id', $order->id)->first();
    expect($withdrawal)->not->toBeNull();
    expect($withdrawal->is_guest)->toBeTrue();
    expect($withdrawal->customer_id)->toBeNull();
    expect($withdrawal->customer_email)->toBe('claimant@example.test');

    Mail::assertSent(WithdrawalConfirmation::class);
});

it('rejects the guest store endpoint without a valid signature', function () {
    enableEuWithdrawal();
    $order = makeGuestOrder('claimant@example.test');

    $this->post(route('shop.eu-withdrawal.guest.store', $order->id), [])
        ->assertForbidden();
});
