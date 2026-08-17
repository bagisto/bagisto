<?php

use Webkul\Customer\Models\Customer as ModelsCustomer;
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
function createRmaForCustomer(ModelsCustomer $customer, DefaultRMAStatusEnum $status, string $orderStatus = 'pending'): RMA
{
    $order = Order::factory()->create([
        'customer_id' => $customer->id,
        'customer_email' => $customer->email,
        'customer_first_name' => $customer->first_name,
        'customer_last_name' => $customer->last_name,
        'status' => $orderStatus,
    ]);

    return RMA::create([
        'order_id' => $order->id,
        'rma_status_id' => $status->value,
    ]);
}

it('should close a pending rma request', function () {
    // Arrange.
    $customer = ModelsCustomer::factory()->create();

    $rma = createRmaForCustomer($customer, DefaultRMAStatusEnum::PENDING);

    // Act.
    $this->loginAsCustomer($customer);

    post(route('shop.customers.account.rma.update-status', $rma->id), [
        'close_rma' => 1,
    ])->assertRedirect();

    // Assert.
    expect($rma->refresh()->rma_status_id)->toBe(DefaultRMAStatusEnum::SOLVED->value);
});

it('should not close an rma request that is already solved', function () {
    // Arrange.
    $customer = ModelsCustomer::factory()->create();

    $rma = createRmaForCustomer($customer, DefaultRMAStatusEnum::SOLVED);

    // Act.
    $this->loginAsCustomer($customer);

    post(route('shop.customers.account.rma.update-status', $rma->id), [
        'close_rma' => 1,
    ])->assertSessionHas('error', trans('shop::app.rma.response.close-not-allowed'));

    // Assert.
    expect($rma->refresh()->rma_status_id)->toBe(DefaultRMAStatusEnum::SOLVED->value);
});

it('should not close an rma request whose package has been received', function () {
    // Arrange.
    $customer = ModelsCustomer::factory()->create();

    $rma = createRmaForCustomer($customer, DefaultRMAStatusEnum::RECEIVED_PACKAGE);

    // Act.
    $this->loginAsCustomer($customer);

    post(route('shop.customers.account.rma.update-status', $rma->id), [
        'close_rma' => 1,
    ])->assertSessionHas('error', trans('shop::app.rma.response.close-not-allowed'));

    // Assert.
    expect($rma->refresh()->rma_status_id)->toBe(DefaultRMAStatusEnum::RECEIVED_PACKAGE->value);
});

it('should not close an rma request that has been declined', function () {
    // Arrange.
    $customer = ModelsCustomer::factory()->create();

    $rma = createRmaForCustomer($customer, DefaultRMAStatusEnum::DECLINED);

    // Act.
    $this->loginAsCustomer($customer);

    post(route('shop.customers.account.rma.update-status', $rma->id), [
        'close_rma' => 1,
    ])->assertSessionHas('error', trans('shop::app.rma.response.close-not-allowed'));

    // Assert.
    expect($rma->refresh()->rma_status_id)->toBe(DefaultRMAStatusEnum::DECLINED->value);
});

it('should not close an rma request belonging to a canceled order', function () {
    // Arrange.
    $customer = ModelsCustomer::factory()->create();

    $rma = createRmaForCustomer($customer, DefaultRMAStatusEnum::PENDING, Order::STATUS_CANCELED);

    // Act.
    $this->loginAsCustomer($customer);

    post(route('shop.customers.account.rma.update-status', $rma->id), [
        'close_rma' => 1,
    ])->assertSessionHas('error', trans('shop::app.rma.response.close-not-allowed'));

    // Assert.
    expect($rma->refresh()->rma_status_id)->toBe(DefaultRMAStatusEnum::PENDING->value);
});

it('should not close an rma request belonging to another customer', function () {
    // Arrange.
    $rma = createRmaForCustomer(ModelsCustomer::factory()->create(), DefaultRMAStatusEnum::PENDING);

    // Act.
    $this->loginAsCustomer();

    post(route('shop.customers.account.rma.update-status', $rma->id), [
        'close_rma' => 1,
    ])->assertNotFound();

    // Assert.
    expect($rma->refresh()->rma_status_id)->toBe(DefaultRMAStatusEnum::PENDING->value);
});

it('should cancel a pending rma request', function () {
    // Arrange.
    $customer = ModelsCustomer::factory()->create();

    $rma = createRmaForCustomer($customer, DefaultRMAStatusEnum::PENDING);

    // Act.
    $this->loginAsCustomer($customer);

    postJson(route('shop.customers.account.rma.cancel', $rma->id))
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.rma.response.cancel-success'));

    // Assert.
    expect($rma->refresh()->rma_status_id)->toBe(DefaultRMAStatusEnum::CANCELED->value);
});

it('should not cancel an rma request that is already solved', function () {
    // Arrange.
    $customer = ModelsCustomer::factory()->create();

    $rma = createRmaForCustomer($customer, DefaultRMAStatusEnum::SOLVED);

    // Act.
    $this->loginAsCustomer($customer);

    postJson(route('shop.customers.account.rma.cancel', $rma->id))
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.rma.response.cancel-not-allowed'));

    // Assert.
    expect($rma->refresh()->rma_status_id)->toBe(DefaultRMAStatusEnum::SOLVED->value);
});

it('should offer the cancel action for a pending rma request', function () {
    // Arrange.
    $customer = ModelsCustomer::factory()->create();

    $rma = createRmaForCustomer($customer, DefaultRMAStatusEnum::PENDING);

    RMAItem::create([
        'rma_id' => $rma->id,
        'quantity' => 1,
    ]);

    // Act.
    $this->loginAsCustomer($customer);

    $response = get(route('shop.customers.account.rma.index'), [
        'X-Requested-With' => 'XMLHttpRequest',
    ])->assertOk();

    // Assert.
    $record = collect($response->json('records'))->firstWhere('id', $rma->id);

    expect(collect($record['actions'])->pluck('url'))
        ->toContain(route('shop.customers.account.rma.cancel', $rma->id));
});

it('should not offer the cancel action for a solved rma request', function () {
    // Arrange.
    $customer = ModelsCustomer::factory()->create();

    $rma = createRmaForCustomer($customer, DefaultRMAStatusEnum::SOLVED);

    RMAItem::create([
        'rma_id' => $rma->id,
        'quantity' => 1,
    ]);

    // Act.
    $this->loginAsCustomer($customer);

    $response = get(route('shop.customers.account.rma.index'), [
        'X-Requested-With' => 'XMLHttpRequest',
    ])->assertOk();

    // Assert.
    $record = collect($response->json('records'))->firstWhere('id', $rma->id);

    expect(collect($record['actions'])->pluck('url'))
        ->not->toContain(route('shop.customers.account.rma.cancel', $rma->id));
});
