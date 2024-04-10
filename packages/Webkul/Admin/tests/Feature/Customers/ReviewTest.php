<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Webkul\Customer\Models\Customer;
use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\Product\Models\ProductReview;
use Webkul\Product\Models\ProductReviewAttachment;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

it('should returns the review page', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.customers.customers.review.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.customers.reviews.index.title'));
});

it('should return the edit page of the review', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
        ],

        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
        ],
    ]))
        ->getSimpleProductFactory()
        ->create();

    $customer = Customer::factory()->create();

    $productReview = ProductReview::factory()->create([
        'product_id'  => $product->id,
        'customer_id' => $customer->id,
        'name'        => $customer->name,
    ]);

    $attachment = UploadedFile::fake()->image('test.png');

    $fileType = explode('/', $attachment->getMimeType());

    $productReviewAttachment = ProductReviewAttachment::factory()->create([
        'path'      => $attachment->store('review/'.$productReview->id),
        'review_id' => $productReview->id,
        'type'      => $fileType[0],
        'mime_type' => $fileType[1],
    ]);

    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.customers.customers.review.edit', $productReview->id))
        ->assertOk()
        ->assertJsonPath('data.id', $productReview->id)
        ->assertJsonPath('data.title', $productReview->title)
        ->assertJsonPath('data.comment', $productReview->comment);

    $this->assertModelWise([
        ProductReviewAttachment::class => [
            [
                'review_id' => $productReview->id,
                'path'      => $productReviewAttachment->path,
                'type'      => $productReviewAttachment->type,
                'mime_type' => $productReviewAttachment->mime_type,
            ],
        ],

        ProductReview::class => [
            [
                'name'        => $productReview->name,
                'title'       => $productReview->title,
                'rating'      => $productReview->rating,
                'comment'     => $productReview->comment,
                'status'      => $productReview->status,
                'product_id'  => $productReview->product_id,
                'customer_id' => $productReview->customer_id,
            ],
        ],
    ]);

    Storage::assertExists($productReviewAttachment->path);
});

it('should fail the validation with errors for status for review update', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
        ],

        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
        ],
    ]))
        ->getSimpleProductFactory()
        ->create();

    $customer = Customer::factory()->create();

    $productReview = ProductReview::factory()->create([
        'product_id'  => $product->id,
        'customer_id' => $customer->id,
        'name'        => $customer->name,
    ]);

    $attachment = UploadedFile::fake()->image('test.png');

    $fileType = explode('/', $attachment->getMimeType());

    $productReviewAttachment = ProductReviewAttachment::factory()->create([
        'path'      => $attachment->store('review/'.$productReview->id),
        'review_id' => $productReview->id,
        'type'      => $fileType[0],
        'mime_type' => $fileType[1],
    ]);

    // Act and Assert.
    $this->loginAsAdmin();

    putJson(route('admin.customers.customers.review.update', $productReview->id))
        ->assertJsonValidationErrorFor('status')
        ->assertUnprocessable();

    $this->assertModelWise([
        ProductReviewAttachment::class => [
            [
                'review_id' => $productReview->id,
                'path'      => $productReviewAttachment->path,
                'type'      => $productReviewAttachment->type,
                'mime_type' => $productReviewAttachment->mime_type,
            ],
        ],

        ProductReview::class => [
            [
                'name'        => $productReview->name,
                'title'       => $productReview->title,
                'rating'      => $productReview->rating,
                'comment'     => $productReview->comment,
                'status'      => $productReview->status,
                'product_id'  => $productReview->product_id,
                'customer_id' => $productReview->customer_id,
            ],
        ],
    ]);

    Storage::assertExists($productReviewAttachment->path);
});

it('should update the status of the review', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
        ],

        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
        ],
    ]))
        ->getSimpleProductFactory()
        ->create();

    $customer = Customer::factory()->create();

    $productReview = ProductReview::factory()->create([
        'product_id'  => $product->id,
        'customer_id' => $customer->id,
        'name'        => $customer->name,
    ]);

    $attachment = UploadedFile::fake()->image('test.png');

    $fileType = explode('/', $attachment->getMimeType());

    $productReviewAttachment = ProductReviewAttachment::factory()->create([
        'path'      => $attachment->store('review/'.$productReview->id),
        'review_id' => $productReview->id,
        'type'      => $fileType[0],
        'mime_type' => $fileType[1],
    ]);

    // Act and Assert.
    $this->loginAsAdmin();

    putJson(route('admin.customers.customers.review.update', $productReview->id), [
        'status' => $status = Arr::random(['approved', 'disapproved', 'pending']),
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.customers.reviews.update-success'));

    $this->assertModelWise([
        ProductReviewAttachment::class => [
            [
                'review_id' => $productReview->id,
                'path'      => $productReviewAttachment->path,
                'type'      => $productReviewAttachment->type,
                'mime_type' => $productReviewAttachment->mime_type,
            ],
        ],

        ProductReview::class => [
            [
                'name'        => $productReview->name,
                'title'       => $productReview->title,
                'rating'      => $productReview->rating,
                'comment'     => $productReview->comment,
                'status'      => $status,
                'product_id'  => $productReview->product_id,
                'customer_id' => $productReview->customer_id,
            ],
        ],
    ]);

    Storage::assertExists($productReviewAttachment->path);
});

it('should delete the review', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
        ],

        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
        ],
    ]))
        ->getSimpleProductFactory()
        ->create();

    $customer = Customer::factory()->create();

    $productReview = ProductReview::factory()->create([
        'product_id'  => $product->id,
        'customer_id' => $customer->id,
        'name'        => $customer->name,
    ]);

    $attachment = UploadedFile::fake()->image('test.png');

    $fileType = explode('/', $attachment->getMimeType());

    $productReviewAttachment = ProductReviewAttachment::factory()->create([
        'path'      => $attachment->store('review/'.$productReview->id),
        'review_id' => $productReview->id,
        'type'      => $fileType[0],
        'mime_type' => $fileType[1],
    ]);

    // Act and Assert.
    $this->loginAsAdmin();

    deleteJson(route('admin.customers.customers.review.delete', $productReview->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.customers.reviews.index.datagrid.delete-success'));

    $this->assertDatabaseMissing('product_reviews', [
        'id' => $productReview->id,
    ]);

    $this->assertDatabaseMissing('product_review_attachments', [
        'id' => $productReviewAttachment->id,
    ]);

    Storage::assertDirectoryEmpty($productReviewAttachment->path);
});

it('should mass delete the product review', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
        ],

        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
        ],
    ]))
        ->getSimpleProductFactory()
        ->create();

    $customer = Customer::factory()->create();

    $productReviews = ProductReview::factory()->count(5)->create([
        'product_id'  => $product->id,
        'customer_id' => $customer->id,
        'name'        => $customer->name,
    ]);

    $productReviewAttachments = [];

    foreach ($productReviews as $key => $productReview) {
        $attachment = UploadedFile::fake()->image('test_'.$key.'.png');

        $fileType = explode('/', $attachment->getMimeType());

        $productReviewAttachments[] = ProductReviewAttachment::factory()->create([
            'path'      => $attachment->store('review/'.$productReview->id),
            'review_id' => $productReview->id,
            'type'      => $fileType[0],
            'mime_type' => $fileType[1],
        ]);
    }

    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.customers.customers.review.mass_delete', [
        'indices' => $productReviews->pluck('id')->toArray(),
    ]))
        ->assertOk()
        ->assertSeeText(trans('admin::app.customers.reviews.index.datagrid.mass-delete-success'));

    foreach ($productReviews as $productReview) {
        $this->assertDatabaseMissing('product_reviews', [
            'id' => $productReview->id,
        ]);
    }

    foreach ($productReviewAttachments as $productReviewAttachment) {
        $this->assertDatabaseMissing('product_review_attachments', [
            'id' => $productReviewAttachment->id,
        ]);

        Storage::assertDirectoryEmpty($productReviewAttachment->path);
    }
});

it('should mass update the product review', function () {
    // Arrange.
    $status = Arr::random(['approved', 'disapproved', 'pending']);

    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
        ],

        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
        ],
    ]))
        ->getSimpleProductFactory()
        ->create();

    $productReviews = ProductReview::factory()->count(2)->create([
        'status'     => $status,
        'product_id' => $product->id,
    ]);

    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.customers.customers.review.mass_update', [
        'indices' => $productReviews->pluck('id')->toArray(),
        'value'   => $status,
    ]))
        ->assertOk()
        ->assertSeeText(trans('admin::app.customers.reviews.index.datagrid.mass-update-success'));

    foreach ($productReviews as $productReview) {
        $this->assertModelWise([
            ProductReview::class => [
                [
                    'id'     => $productReview->id,
                    'status' => $productReview->status,
                ],
            ],
        ]);
    }
});
