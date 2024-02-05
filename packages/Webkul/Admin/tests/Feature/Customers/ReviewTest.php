<?php

use Illuminate\Support\Arr;
use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\Product\Models\ProductReview;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

it('should returns the review page', function () {
    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.customers.customers.review.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.customers.reviews.index.title'));
});

it('should return the edit page of the review', function () {
    // Arrange
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

    $review = ProductReview::factory()->create([
        'product_id' => $product->id,
    ]);

    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.customers.customers.review.edit', $review->id))
        ->assertOk()
        ->assertJsonPath('data.id', $review->id)
        ->assertJsonPath('data.title', $review->title)
        ->assertJsonPath('data.comment', $review->comment);
});

it('should fail the validation with errors for status for review update', function () {
    // Arrange
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

    $review = ProductReview::factory()->create([
        'product_id' => $product->id,
    ]);

    // Act and Assert
    $this->loginAsAdmin();

    putJson(route('admin.customers.customers.review.update', $review->id))
        ->assertJsonValidationErrorFor('status')
        ->assertUnprocessable();
});

it('should update the status of the review', function () {
    // Arrange
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

    $review = ProductReview::factory()->create([
        'product_id' => $product->id,
    ]);

    // Act and Assert
    $this->loginAsAdmin();

    putJson(route('admin.customers.customers.review.update', $review->id), [
        'status' => $status = Arr::random(['approved', 'disapproved', 'pending']),
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.customers.reviews.update-success'));

    $this->assertModelWise([
        ProductReview::class => [
            [
                'id'     => $review->id,
                'status' => $status,
            ],
        ],
    ]);
});

it('should delete the review', function () {
    // Arrange
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

    $review = ProductReview::factory()->create([
        'product_id' => $product->id,
    ]);

    // Act and assert
    $this->loginAsAdmin();

    deleteJson(route('admin.customers.customers.review.delete', $review->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.customers.reviews.index.datagrid.delete-success'));

    $this->assertDatabaseMissing('product_reviews', [
        'id' => $review->id,
    ]);
});

it('should mass delete the product review', function () {
    // Arrange
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

    $reviews = ProductReview::factory()->count(2)->create([
        'product_id' => $product->id,
    ]);

    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.customers.customers.review.mass_delete', [
        'indices' => $reviews->pluck('id')->toArray(),
    ]))
        ->assertOk()
        ->assertSeeText(trans('admin::app.customers.reviews.index.datagrid.mass-delete-success'));

    foreach ($reviews as $review) {
        $this->assertDatabaseMissing('product_reviews', [
            'id' => $review->id,
        ]);
    }
});

it('should mass update the product review', function () {
    // Arrange
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

    $reviews = ProductReview::factory()->count(2)->create([
        'status'     => $status,
        'product_id' => $product->id,
    ]);

    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.customers.customers.review.mass_update', [
        'indices' => $reviews->pluck('id')->toArray(),
        'value'   => $status,
    ]))
        ->assertOk()
        ->assertSeeText(trans('admin::app.customers.reviews.index.datagrid.mass-update-success'));

    foreach ($reviews as $review) {
        $this->assertModelWise([
            ProductReview::class => [
                [
                    'id'     => $review->id,
                    'status' => $review->status,
                ],
            ],
        ]);
    }
});
