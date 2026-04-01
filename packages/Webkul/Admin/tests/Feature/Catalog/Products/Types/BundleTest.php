<?php

use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductFlat;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

// ============================================================================
// Store
// ============================================================================

it('should store a bundle product and redirect to edit', function () {
    $this->loginAsAdmin();

    $sku = fake()->uuid();

    postJson(route('admin.catalog.products.store'), [
        'type' => 'bundle',
        'attribute_family_id' => 1,
        'sku' => $sku,
    ])
        ->assertOk()
        ->assertJsonStructure(['data' => ['redirect_url']]);

    $product = Product::where('sku', $sku)->first();

    expect($product)->not->toBeNull();
    expect($product->type)->toBe('bundle');
});

// ============================================================================
// Edit
// ============================================================================

it('should return the edit page of a bundle product', function () {
    $product = $this->storeAndUpdateBundleProduct();

    get(route('admin.catalog.products.edit', $product->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.catalog.products.edit.title'))
        ->assertSeeText($product->name);
});

// ============================================================================
// Product Flat — Via Real Store + Update Flow
// ============================================================================

it('should populate product_flat after store and update', function () {
    $product = $this->storeAndUpdateBundleProduct();

    $flat = ProductFlat::where('product_id', $product->id)->first();

    expect($flat)->not->toBeNull();

    // Core fields
    expect($flat->sku)->toBe($product->sku);
    expect($flat->type)->toBe('bundle');
    expect($flat->name)->toBe('Test Bundle Product');

    // Bundle products skip price and weight.
    expect($flat->price)->toBeNull();
    expect($flat->weight)->toBeNull();

    // Boolean fields
    expect($flat->status)->toBeTruthy();
    expect($flat->visible_individually)->toBeTruthy();
});

// ============================================================================
// Bundle Options — Via Real Store + Update Flow
// ============================================================================

it('should create bundle options with products after store and update', function () {
    $product = $this->storeAndUpdateBundleProduct();

    expect($product->bundle_options)->toHaveCount(1);

    $option = $product->bundle_options->first();
    expect($option->type)->toBe('select');
    expect($option->is_required)->toBeTruthy();
    expect($option->bundle_option_products)->toHaveCount(2);

    // Verify the option label translation.
    $this->assertDatabaseHas('product_bundle_option_translations', [
        'product_bundle_option_id' => $option->id,
        'locale' => app()->getLocale(),
        'label' => 'Select Color',
    ]);
});

it('should update bundle option products', function () {
    $product = $this->storeAndUpdateBundleProduct();
    $option = $product->bundle_options->first();
    $locale = app()->getLocale();

    // Update with changed label and product qty.
    $products = [];

    foreach ($option->bundle_option_products as $key => $optionProduct) {
        $products[$optionProduct->id] = [
            'product_id' => $optionProduct->product_id,
            'sort_order' => $key,
            'qty' => 5,
            'is_default' => $key === 0 ? 1 : 0,
        ];
    }

    putJson(route('admin.catalog.products.update', $product->id), [
        'sku' => $product->sku,
        'url_key' => $product->url_key,
        'name' => $product->name,
        'short_description' => $product->short_description,
        'description' => $product->description,
        'channel' => core()->getCurrentChannelCode(),
        'locale' => $locale,
        'status' => 1,
        'visible_individually' => 1,
        'guest_checkout' => 1,
        'bundle_options' => [
            $option->id => [
                $locale => ['label' => 'Updated Option Label'],
                'type' => 'radio',
                'is_required' => '1',
                'sort_order' => 0,
                'products' => $products,
            ],
        ],
    ])->assertRedirect(route('admin.catalog.products.index'));

    // Verify updated option.
    $this->assertDatabaseHas('product_bundle_option_translations', [
        'product_bundle_option_id' => $option->id,
        'label' => 'Updated Option Label',
    ]);

    // Verify all products have updated qty.
    foreach ($products as $id => $productData) {
        $this->assertDatabaseHas('product_bundle_option_products', [
            'id' => $id,
            'qty' => 5,
        ]);
    }
});

it('should not create inventory for a bundle product', function () {
    $product = $this->storeAndUpdateBundleProduct();

    // Bundle products themselves don't have inventory.
    $this->assertDatabaseMissing('product_inventories', [
        'product_id' => $product->id,
    ]);
});

// ============================================================================
// Update Validation
// ============================================================================

it('should fail validation when required fields are missing on bundle product update', function () {
    $product = $this->storeAndUpdateBundleProduct();

    // Bundle products do not require price or weight.
    putJson(route('admin.catalog.products.update', $product->id))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('sku')
        ->assertJsonValidationErrorFor('url_key')
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('short_description')
        ->assertJsonValidationErrorFor('description');
});

// ============================================================================
// Delete
// ============================================================================

it('should delete a bundle product and clean up all related tables', function () {
    $product = $this->storeAndUpdateBundleProduct();
    $productId = $product->id;
    $optionIds = $product->bundle_options->pluck('id')->toArray();

    deleteJson(route('admin.catalog.products.delete', $productId))
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.catalog.products.delete-success'));

    $this->assertDatabaseMissing('products', ['id' => $productId]);
    $this->assertDatabaseMissing('product_flat', ['product_id' => $productId]);
    $this->assertDatabaseMissing('product_attribute_values', ['product_id' => $productId]);
    $this->assertDatabaseMissing('product_bundle_options', ['product_id' => $productId]);

    foreach ($optionIds as $optionId) {
        $this->assertDatabaseMissing('product_bundle_option_products', [
            'product_bundle_option_id' => $optionId,
        ]);
    }
});
