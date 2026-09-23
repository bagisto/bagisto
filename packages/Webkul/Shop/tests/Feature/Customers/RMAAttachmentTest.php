<?php

use Illuminate\Support\Facades\Storage;
use Webkul\Customer\Models\Customer;
use Webkul\RMA\Enums\DefaultRMAStatusEnum;
use Webkul\RMA\Models\RMA;
use Webkul\RMA\Models\RMAImage;
use Webkul\RMA\Models\RMAMessage;
use Webkul\Sales\Models\Order;

use function Pest\Laravel\get;

/**
 * A conversation message carrying an attachment on a return the given customer owns.
 */
function messageWithAttachment(Customer $customer, string $disk = 'private'): RMAMessage
{
    $order = test()->createOrder(['status' => Order::STATUS_PENDING], customer: $customer);

    $rma = RMA::create([
        'order_id' => $order->id,
        'rma_status_id' => DefaultRMAStatusEnum::PENDING->value,
    ]);

    $path = 'rma-conversation/1/'.Str::random(40).'.png';

    Storage::disk($disk)->put($path, 'attachment contents');

    return RMAMessage::create([
        'rma_id' => $rma->id,
        'message' => 'Here is the damage.',
        'attachment_path' => $path,
        'attachment' => 'damage.png',
    ]);
}

/**
 * A photo on a return the given customer owns, stored the way an upload stores it.
 */
function imageOnReturn(Customer $customer): RMAImage
{
    $order = test()->createOrder(['status' => Order::STATUS_PENDING], customer: $customer);

    $rma = RMA::create([
        'order_id' => $order->id,
        'rma_status_id' => DefaultRMAStatusEnum::PENDING->value,
    ]);

    $path = 'rma/'.$rma->id.'/images/'.Str::random(40).'.png';

    Storage::disk('private')->put($path, 'photo contents');

    return RMAImage::create(['rma_id' => $rma->id, 'path' => $path]);
}

// ============================================================================
// Attachment Access
// ============================================================================

it('should give a customer the attachment of their own return', function () {
    $customer = Customer::factory()->create();

    $message = messageWithAttachment($customer);

    $this->loginAsCustomer($customer);

    get(route('shop.customers.account.rma.attachment', $message->id))
        ->assertOk()
        ->assertDownload('damage.png');
});

it('should not give a customer the attachment of another customer', function () {
    $message = messageWithAttachment(Customer::factory()->create());

    $this->loginAsCustomer(Customer::factory()->create());

    get(route('shop.customers.account.rma.attachment', $message->id))
        ->assertNotFound();
});

it('should send a guest to the login page rather than an attachment', function () {
    $message = messageWithAttachment(Customer::factory()->create());

    get(route('shop.customers.account.rma.attachment', $message->id))
        ->assertRedirect(route('shop.customer.session.index'));
});

it('should still serve an attachment stored before attachments moved off the public disk', function () {
    $customer = Customer::factory()->create();

    $message = messageWithAttachment($customer, config('filesystems.default'));

    $this->loginAsCustomer($customer);

    get(route('shop.customers.account.rma.attachment', $message->id))
        ->assertOk()
        ->assertDownload('damage.png');
});

// ============================================================================
// Return Photos
// ============================================================================

it('should show a customer a photo on their own return', function () {
    $customer = Customer::factory()->create();

    $image = imageOnReturn($customer);

    $this->loginAsCustomer($customer);

    get(route('shop.customers.account.rma.image', $image->id))->assertOk();
});

it('should not show a customer a photo on another return', function () {
    $image = imageOnReturn(Customer::factory()->create());

    $this->loginAsCustomer(Customer::factory()->create());

    get(route('shop.customers.account.rma.image', $image->id))->assertNotFound();
});
