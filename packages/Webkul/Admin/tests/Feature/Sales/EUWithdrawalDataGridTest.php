<?php

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Webkul\EUWithdrawal\Enums\WithdrawalStatus;
use Webkul\EUWithdrawal\Models\Withdrawal;
use Webkul\Sales\Models\Order;

use function Pest\Laravel\getJson;

$ajax = ['X-Requested-With' => 'XMLHttpRequest'];

function makeWithdrawal(array $attributes = []): Withdrawal
{
    $order = Order::factory()->create(array_merge([
        'customer_email' => 'order-owner@example.test',
        'status' => 'pending',
        'is_guest' => 1,
    ], $attributes['order'] ?? []));

    return Withdrawal::create(array_merge([
        'uuid' => (string) Str::uuid(),
        'order_id' => $order->id,
        'customer_id' => null,
        'is_guest' => true,
        'customer_email' => 'shopper@example.test',
        'channel_id' => $order->channel_id,
        'locale' => 'en',
        'received_at' => now(),
        'status' => WithdrawalStatus::RECEIVED,
    ], Arr::except($attributes, ['order'])));
}

it('should filter the eu withdrawals listing by customer email', function () use ($ajax) {
    $wanted = makeWithdrawal(['customer_email' => 'wanted@example.test']);

    makeWithdrawal(['customer_email' => 'other@example.test']);

    $this->loginAsAdmin();

    getJson(route('admin.sales.eu-withdrawals.index', [
        'filters' => ['customer_email' => ['wanted@example.test']],
    ]), $ajax)
        ->assertOk()
        ->assertJsonPath('meta.total', 1)
        ->assertJsonPath('records.0.uuid', $wanted->uuid);
});

it('should filter the eu withdrawals listing by status', function () use ($ajax) {
    $refunded = makeWithdrawal(['status' => WithdrawalStatus::REFUNDED]);

    makeWithdrawal(['status' => WithdrawalStatus::RECEIVED]);

    $this->loginAsAdmin();

    getJson(route('admin.sales.eu-withdrawals.index', [
        'filters' => ['status' => [WithdrawalStatus::REFUNDED]],
    ]), $ajax)
        ->assertOk()
        ->assertJsonPath('meta.total', 1)
        ->assertJsonPath('records.0.uuid', $refunded->uuid);
});

it('should search the eu withdrawals listing', function () use ($ajax) {
    $wanted = makeWithdrawal(['customer_email' => 'searchable@example.test']);

    makeWithdrawal(['customer_email' => 'elsewhere@example.test']);

    $this->loginAsAdmin();

    getJson(route('admin.sales.eu-withdrawals.index', [
        'filters' => ['all' => ['searchable']],
    ]), $ajax)
        ->assertOk()
        ->assertJsonPath('meta.total', 1)
        ->assertJsonPath('records.0.uuid', $wanted->uuid);
});

it('should match the withdrawal customer email rather than the one on its order', function () use ($ajax) {
    makeWithdrawal([
        'customer_email' => 'shopper@example.test',
        'order' => ['customer_email' => 'order-owner@example.test'],
    ]);

    $this->loginAsAdmin();

    getJson(route('admin.sales.eu-withdrawals.index', [
        'filters' => ['customer_email' => ['order-owner@example.test']],
    ]), $ajax)
        ->assertOk()
        ->assertJsonPath('meta.total', 0);
});

it('should match the withdrawal status rather than the one on its order', function () use ($ajax) {
    makeWithdrawal([
        'status' => WithdrawalStatus::REFUNDED,
        'order' => ['status' => 'pending'],
    ]);

    $this->loginAsAdmin();

    getJson(route('admin.sales.eu-withdrawals.index', [
        'filters' => ['status' => ['pending']],
    ]), $ajax)
        ->assertOk()
        ->assertJsonPath('meta.total', 0);
});
