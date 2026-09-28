<?php

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Webkul\Customer\Models\Customer;
use Webkul\RMA\Enums\DefaultRMAStatusEnum;
use Webkul\RMA\Models\RMA;
use Webkul\RMA\Models\RMAImage;
use Webkul\RMA\Repositories\RMAImageRepository;
use Webkul\Sales\Models\Order;

/**
 * A pending return on a fresh order, the state an upload is attached to.
 */
function returnAwaitingPhotos(): RMA
{
    $order = test()->createOrder(['status' => Order::STATUS_PENDING], customer: Customer::factory()->create());

    return RMA::create([
        'order_id' => $order->id,
        'rma_status_id' => DefaultRMAStatusEnum::PENDING->value,
    ]);
}

/**
 * Run the photos of a return through the repository the way a multipart request does.
 */
function uploadPhotos(RMA $rma, array $files, array $input = []): void
{
    app()->instance('request', Request::create('/', 'POST', ['images' => $input], [], ['images' => $files]));

    app(RMAImageRepository::class)->manageImages(array_merge($input, $files), $rma);
}

// ============================================================================
// Storing An Uploaded Photo
// ============================================================================

it('should store an uploaded photo where the row it writes points', function () {
    Storage::fake('private');

    $rma = returnAwaitingPhotos();

    uploadPhotos($rma, [UploadedFile::fake()->image('damage.png')]);

    $images = RMAImage::query()->where('rma_id', $rma->id)->get();

    expect($images)->toHaveCount(1)
        ->and(Storage::disk('private')->exists($images->first()->path))->toBeTrue();
});

it('should keep no row whose file it never wrote', function () {
    Storage::fake('private');

    $rma = returnAwaitingPhotos();

    uploadPhotos($rma, [UploadedFile::fake()->image('damage.png')]);

    $paths = RMAImage::query()->where('rma_id', $rma->id)->pluck('path');

    foreach ($paths as $path) {
        expect(Storage::disk('private')->exists($path))->toBeTrue();
    }
});

it('should delete the stored file of a photo it replaces', function () {
    Storage::fake('private');

    $rma = returnAwaitingPhotos();

    uploadPhotos($rma, [UploadedFile::fake()->image('first.png')]);

    $replaced = RMAImage::query()->where('rma_id', $rma->id)->value('path');

    uploadPhotos($rma, [UploadedFile::fake()->image('second.png')]);

    expect(Storage::disk('private')->exists($replaced))->toBeFalse();
});

it('should store the photo when the form also posts the empty field the picker renders', function () {
    Storage::fake('private');

    $rma = returnAwaitingPhotos();

    uploadPhotos($rma, [UploadedFile::fake()->image('damage.png')], ['']);

    $images = RMAImage::query()->where('rma_id', $rma->id)->get();

    expect($images)->toHaveCount(1)
        ->and(Storage::disk('private')->exists($images->first()->path))->toBeTrue();
});
