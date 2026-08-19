<?php

use Webkul\BookingProduct\Models\BookingProduct;
use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\Product\Contracts\ProductFlat;
use Webkul\Product\Models\Product;

use function Pest\Laravel\get;
use function Pest\Laravel\postJson;

it('should return the product index page', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.catalog.products.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.catalog.products.index.title'))
        ->assertSeeText(trans('admin::app.catalog.products.index.create-btn'));
});

it('should copy the existing product', function () {
    // Arrange.
    $product = (new ProductFaker)->getSimpleProductFactory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.catalog.products.copy', $product->id))
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.catalog.products.product-copied'));

    // Get the newly created product (last one).
    $copiedProduct = Product::latest('id')->first();

    // Assert the copied product has a different ID.
    expect($copiedProduct->id)->not->toBe($product->id);

    // Assert the copied product has a temporary SKU (as per copy functionality).
    expect($copiedProduct->sku)->toStartWith('temporary-sku-');

    // Assert the copied product exists in `product_flat`.
    $this->assertDatabaseHas('product_flat', [
        'product_id' => $copiedProduct->id,
        'sku' => $copiedProduct->sku,
    ]);
});

it('should perform the mass action from update status for products', function () {
    // Arrange.
    $products = (new ProductFaker)->getSimpleProductFactory()->count(2)->create();

    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.catalog.products.mass_update'), [
        'indices' => $products->pluck('id')->toArray(),
        'value' => 1,
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.catalog.products.index.datagrid.mass-update-success'));

    foreach ($products as $product) {
        $this->assertModelWise([
            ProductFlat::class => [
                [
                    'product_id' => $product->id,
                    'sku' => $product->sku,
                    'status' => 1,
                ],
            ],
        ]);
    }
});

it('should perform the mass action for delete for products', function () {
    // Arrange.
    $products = (new ProductFaker)->getSimpleProductFactory()->count(2)->create();

    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.catalog.products.mass_delete'), [
        'indices' => $products->pluck('id')->toArray(),
        'value' => 1,
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.catalog.products.index.datagrid.mass-delete-success'));

    foreach ($products as $product) {
        $this->assertDatabaseMissing('product_flat', [
            'status' => 1,
            'product_id' => $product->id,
        ]);
    }
});

it('should search the product', function () {
    // Arrange.
    $product = (new ProductFaker)->getSimpleProductFactory()->count(2)->create();

    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.catalog.products.search', [
        'query' => $product[0]->name,
    ]))
        ->assertOk()
        ->assertJsonPath('data.0.id', $product[0]->id)
        ->assertJsonPath('data.0.name', $product[0]->name)
        ->assertJsonPath('data.0.sku', $product[0]->sku);
});

it('should copy the existing product with customizable options', function () {
    // Arrange.
    $product = (new ProductFaker)->getSimpleProductFactory()->create();

    // Create a customizable option for the product
    $customizableOption = $product->customizable_options()->create([
        'type' => 'select',
        'is_required' => 1,
        'sort_order' => 1,
        'label' => 'Test Option Label',
    ]);

    // Create a price/value for the customizable option
    $customizableOption->customizable_option_prices()->create([
        'label' => 'Test Value Label',
        'price' => 10.00,
        'sort_order' => 1,
    ]);

    // Act.
    $this->loginAsAdmin();

    postJson(route('admin.catalog.products.copy', $product->id))
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.catalog.products.product-copied'));

    // Get the newly created product (last one).
    $copiedProduct = Product::latest('id')->first();

    // Assert the copied product has customizable options cloned
    expect($copiedProduct->customizable_options)->toHaveCount(1);

    $copiedCustomizableOption = $copiedProduct->customizable_options->first();
    expect($copiedCustomizableOption->type)->toBe('select');
    expect($copiedCustomizableOption->is_required)->toBe(1);
    expect($copiedCustomizableOption->label)->toBe('Test Option Label');

    // Assert the customizable option price/value is cloned
    expect($copiedCustomizableOption->customizable_option_prices)->toHaveCount(1);

    $copiedPrice = $copiedCustomizableOption->customizable_option_prices->first();
    expect($copiedPrice->label)->toBe('Test Value Label');
    expect($copiedPrice->price)->toEqual(10.00);
});

it('should copy the download links and samples of a downloadable product', function () {
    // Arrange.
    $product = (new ProductFaker)->getDownloadableProductFactory()->create();

    $product->downloadable_samples()->create([
        'type' => 'url',
        'url' => 'https://example.com/sample.pdf',
        'sort_order' => 1,
    ]);

    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.catalog.products.copy', $product->id))->assertOk();

    $copiedProduct = Product::latest('id')->first();

    expect($copiedProduct->downloadable_links()->count())->toBe($product->downloadable_links()->count())
        ->and($copiedProduct->downloadable_samples()->count())->toBe($product->downloadable_samples()->count());

    expect($copiedProduct->downloadable_links->pluck('title')->all())
        ->toBe($product->downloadable_links->pluck('title')->all());
});

it('should copy the booking settings of a booking product', function () {
    // Arrange.
    $product = (new ProductFaker)->getSimpleProductFactory()->create();

    Product::query()->where('id', $product->id)->update(['type' => 'booking']);

    $product->refresh();

    $bookingProduct = BookingProduct::query()->create([
        'type' => 'default',
        'qty' => 5,
        'location' => 'Studio One',
        'show_location' => 1,
        'product_id' => $product->id,
    ]);

    $bookingProduct->default_slot()->create([
        'booking_type' => 'many',
        'duration' => 60,
        'break_time' => 15,
        'slots' => [['day' => 0, 'from' => '09:00', 'to' => '17:00']],
    ]);

    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.catalog.products.copy', $product->id))->assertOk();

    $copiedBooking = Product::latest('id')->first()->booking_products()->first();

    expect($copiedBooking)->not->toBeNull()
        ->and($copiedBooking->type)->toBe('default')
        ->and($copiedBooking->location)->toBe('Studio One')
        ->and($copiedBooking->default_slot)->not->toBeNull()
        ->and($copiedBooking->default_slot->duration)->toBe(60)
        ->and($copiedBooking->default_slot->slots)->toBe($bookingProduct->default_slot->slots);
});
