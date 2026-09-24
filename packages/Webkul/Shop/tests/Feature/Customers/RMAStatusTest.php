<?php

use Webkul\Customer\Models\Customer;
use Webkul\RMA\Enums\DefaultRMAStatusEnum;
use Webkul\RMA\Models\RMA;
use Webkul\RMA\Models\RMAItem;
use Webkul\Sales\Models\Order;

use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\postJson;

/**
 * Create an RMA owned by the given customer in the given status.
 */
function createRmaForCustomer(Customer $customer, DefaultRMAStatusEnum $status, string $orderStatus = Order::STATUS_PENDING): RMA
{
    $order = test()->createOrder(['status' => $orderStatus], customer: $customer);

    return RMA::create([
        'order_id' => $order->id,
        'rma_status_id' => $status->value,
    ]);
}

// ============================================================================
// Closing
// ============================================================================

it('should close a pending rma request', function () {
    $customer = Customer::factory()->create();

    $rma = createRmaForCustomer($customer, DefaultRMAStatusEnum::PENDING);

    $this->loginAsCustomer($customer);

    post(route('shop.customers.account.rma.update_status', $rma->id), [
        'close_rma' => 1,
    ])->assertRedirect();

    expect($rma->refresh()->rma_status_id)->toBe(DefaultRMAStatusEnum::SOLVED->value);
});

it('should not close an rma request that is already solved', function () {
    $customer = Customer::factory()->create();

    $rma = createRmaForCustomer($customer, DefaultRMAStatusEnum::SOLVED);

    $this->loginAsCustomer($customer);

    post(route('shop.customers.account.rma.update_status', $rma->id), [
        'close_rma' => 1,
    ])->assertSessionHas('error', trans('shop::app.rma.response.close-not-allowed'));

    expect($rma->refresh()->rma_status_id)->toBe(DefaultRMAStatusEnum::SOLVED->value);
});

it('should not close an rma request whose package has been received', function () {
    $customer = Customer::factory()->create();

    $rma = createRmaForCustomer($customer, DefaultRMAStatusEnum::RECEIVED_PACKAGE);

    $this->loginAsCustomer($customer);

    post(route('shop.customers.account.rma.update_status', $rma->id), [
        'close_rma' => 1,
    ])->assertSessionHas('error', trans('shop::app.rma.response.close-not-allowed'));

    expect($rma->refresh()->rma_status_id)->toBe(DefaultRMAStatusEnum::RECEIVED_PACKAGE->value);
});

it('should not close an rma request that has been declined', function () {
    $customer = Customer::factory()->create();

    $rma = createRmaForCustomer($customer, DefaultRMAStatusEnum::DECLINED);

    $this->loginAsCustomer($customer);

    post(route('shop.customers.account.rma.update_status', $rma->id), [
        'close_rma' => 1,
    ])->assertSessionHas('error', trans('shop::app.rma.response.close-not-allowed'));

    expect($rma->refresh()->rma_status_id)->toBe(DefaultRMAStatusEnum::DECLINED->value);
});

it('should not close an rma request belonging to a canceled order', function () {
    $customer = Customer::factory()->create();

    $rma = createRmaForCustomer($customer, DefaultRMAStatusEnum::PENDING, Order::STATUS_CANCELED);

    $this->loginAsCustomer($customer);

    post(route('shop.customers.account.rma.update_status', $rma->id), [
        'close_rma' => 1,
    ])->assertSessionHas('error', trans('shop::app.rma.response.close-not-allowed'));

    expect($rma->refresh()->rma_status_id)->toBe(DefaultRMAStatusEnum::PENDING->value);
});

it('should not close an rma request belonging to another customer', function () {
    $rma = createRmaForCustomer(Customer::factory()->create(), DefaultRMAStatusEnum::PENDING);

    $this->loginAsCustomer();

    post(route('shop.customers.account.rma.update_status', $rma->id), [
        'close_rma' => 1,
    ])->assertNotFound();

    expect($rma->refresh()->rma_status_id)->toBe(DefaultRMAStatusEnum::PENDING->value);
});

// ============================================================================
// Cancelling
// ============================================================================

it('should cancel a pending rma request', function () {
    $customer = Customer::factory()->create();

    $rma = createRmaForCustomer($customer, DefaultRMAStatusEnum::PENDING);

    $this->loginAsCustomer($customer);

    postJson(route('shop.customers.account.rma.cancel', $rma->id))
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.rma.response.cancel-success'));

    expect($rma->refresh()->rma_status_id)->toBe(DefaultRMAStatusEnum::CANCELED->value);
});

it('should not cancel an rma request that is already solved', function () {
    $customer = Customer::factory()->create();

    $rma = createRmaForCustomer($customer, DefaultRMAStatusEnum::SOLVED);

    $this->loginAsCustomer($customer);

    postJson(route('shop.customers.account.rma.cancel', $rma->id))
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.rma.response.cancel-not-allowed'));

    expect($rma->refresh()->rma_status_id)->toBe(DefaultRMAStatusEnum::SOLVED->value);
});

// ============================================================================
// Listing Actions
// ============================================================================

it('should offer the cancel action for a pending rma request', function () {
    $customer = Customer::factory()->create();

    $rma = createRmaForCustomer($customer, DefaultRMAStatusEnum::PENDING);

    RMAItem::create([
        'rma_id' => $rma->id,
        'quantity' => 1,
    ]);

    $this->loginAsCustomer($customer);

    $response = get(route('shop.customers.account.rma.index'), [
        'X-Requested-With' => 'XMLHttpRequest',
    ])->assertOk();

    $record = collect($response->json('records'))->firstWhere('id', $rma->id);

    expect(collect($record['actions'])->pluck('url'))
        ->toContain(route('shop.customers.account.rma.cancel', $rma->id));
});

it('should not offer the cancel action for a solved rma request', function () {
    $customer = Customer::factory()->create();

    $rma = createRmaForCustomer($customer, DefaultRMAStatusEnum::SOLVED);

    RMAItem::create([
        'rma_id' => $rma->id,
        'quantity' => 1,
    ]);

    $this->loginAsCustomer($customer);

    $response = get(route('shop.customers.account.rma.index'), [
        'X-Requested-With' => 'XMLHttpRequest',
    ])->assertOk();

    $record = collect($response->json('records'))->firstWhere('id', $rma->id);

    expect(collect($record['actions'])->pluck('url'))
        ->not->toContain(route('shop.customers.account.rma.cancel', $rma->id));
});
