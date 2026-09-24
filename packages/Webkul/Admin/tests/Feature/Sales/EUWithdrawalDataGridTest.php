<?php

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Testing\TestResponse;
use Webkul\EUWithdrawal\Enums\WithdrawalStatus;
use Webkul\EUWithdrawal\Models\Withdrawal;

use function Pest\Laravel\getJson;

/**
 * An email address no other withdrawal or order in the database carries.
 */
function uniqueWithdrawalEmail(): string
{
    return Str::lower(Str::random(16)).'@example.test';
}

/**
 * Create a withdrawal request for a fresh guest order, with the given attributes overriding the defaults.
 */
function makeWithdrawal(array $attributes = []): Withdrawal
{
    $order = test()->createGuestOrder(array_merge([
        'customer_email' => uniqueWithdrawalEmail(),
        'status' => 'pending',
    ], $attributes['order'] ?? []));

    return Withdrawal::query()->create(array_merge([
        'uuid' => (string) Str::uuid(),
        'order_id' => $order->id,
        'customer_id' => null,
        'is_guest' => true,
        'customer_email' => uniqueWithdrawalEmail(),
        'channel_id' => $order->channel_id,
        'locale' => 'en',
        'received_at' => now(),
        'status' => WithdrawalStatus::RECEIVED,
    ], Arr::except($attributes, ['order'])));
}

/**
 * Request the eu withdrawals listing narrowed by the given filters.
 */
function euWithdrawalsListing(array $filters): TestResponse
{
    return getJson(route('admin.sales.eu_withdrawals.index', ['filters' => $filters]), [
        'X-Requested-With' => 'XMLHttpRequest',
    ]);
}

// ============================================================================
// Filtering
// ============================================================================

it('should filter the eu withdrawals listing by customer email', function () {
    $email = uniqueWithdrawalEmail();

    $wanted = makeWithdrawal(['customer_email' => $email]);

    makeWithdrawal();

    $this->loginAsAdmin();

    euWithdrawalsListing(['customer_email' => [$email]])
        ->assertOk()
        ->assertJsonPath('meta.total', 1)
        ->assertJsonPath('records.0.uuid', $wanted->uuid);
});

it('should filter the eu withdrawals listing by status', function () {
    $email = uniqueWithdrawalEmail();

    $refunded = makeWithdrawal([
        'customer_email' => $email,
        'status' => WithdrawalStatus::REFUNDED,
    ]);

    makeWithdrawal([
        'customer_email' => $email,
        'status' => WithdrawalStatus::RECEIVED,
    ]);

    $this->loginAsAdmin();

    euWithdrawalsListing([
        'customer_email' => [$email],
        'status' => [WithdrawalStatus::REFUNDED],
    ])
        ->assertOk()
        ->assertJsonPath('meta.total', 1)
        ->assertJsonPath('records.0.uuid', $refunded->uuid);
});

it('should search the eu withdrawals listing', function () {
    $token = Str::lower(Str::random(16));

    $wanted = makeWithdrawal(['customer_email' => "{$token}@example.test"]);

    makeWithdrawal();

    $this->loginAsAdmin();

    euWithdrawalsListing(['all' => [$token]])
        ->assertOk()
        ->assertJsonPath('meta.total', 1)
        ->assertJsonPath('records.0.uuid', $wanted->uuid);
});

it('should match the withdrawal customer email rather than the one on its order', function () {
    $orderEmail = uniqueWithdrawalEmail();

    makeWithdrawal([
        'customer_email' => uniqueWithdrawalEmail(),
        'order' => ['customer_email' => $orderEmail],
    ]);

    $this->loginAsAdmin();

    euWithdrawalsListing(['customer_email' => [$orderEmail]])
        ->assertOk()
        ->assertJsonPath('meta.total', 0);
});

it('should match the withdrawal status rather than the one on its order', function () {
    $email = uniqueWithdrawalEmail();

    makeWithdrawal([
        'customer_email' => $email,
        'status' => WithdrawalStatus::REFUNDED,
        'order' => ['status' => 'pending'],
    ]);

    $this->loginAsAdmin();

    euWithdrawalsListing([
        'customer_email' => [$email],
        'status' => ['pending'],
    ])
        ->assertOk()
        ->assertJsonPath('meta.total', 0);
});
