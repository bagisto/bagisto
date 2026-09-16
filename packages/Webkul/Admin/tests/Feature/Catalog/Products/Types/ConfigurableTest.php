<?php

use Webkul\Attribute\Models\Attribute;
use Webkul\Attribute\Models\AttributeFamily;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductFlat;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

/**
 * A configurable attribute looked up by its code rather than by a seeded id.
 */
function configurableAttribute(string $code): Attribute
{
    return Attribute::query()->where('code', $code)->firstOrFail();
}

// ============================================================================
// Store
// ============================================================================

it('should return configurable attributes when storing without super_attributes', function () {
    $this->loginAsAdmin();

    $attributes = AttributeFamily::query()->findOrFail(1)->configurable_attributes;

    $response = postJson(route('admin.catalog.products.store'), [
        'type' => 'configurable',
        'attribute_family_id' => 1,
        'sku' => fake()->uuid(),
    ])->assertOk();

    foreach ($attributes as $key => $attribute) {
        $response
            ->assertJsonPath("data.attributes.{$key}.id", $attribute->id)
            ->assertJsonPath("data.attributes.{$key}.code", $attribute->code);

        foreach ($attribute->options as $optionKey => $option) {
            $response
                ->assertJsonPath("data.attributes.{$key}.options.{$optionKey}.id", $option->id)
                ->assertJsonPath("data.attributes.{$key}.options.{$optionKey}.name", $option->admin_name);
        }
    }
});

it('should create a configurable product with variants when super_attributes are provided', function () {
    $this->loginAsAdmin();

    $color = configurableAttribute('color');

    $size = configurableAttribute('size');

    $sku = fake()->uuid();

    postJson(route('admin.catalog.products.store'), [
        'type' => 'configurable',
        'attribute_family_id' => 1,
        'sku' => $sku,
        'super_attributes' => [
            'color' => $color->options->take(2)->pluck('id')->all(),
            'size' => $size->options->take(2)->pluck('id')->all(),
        ],
    ])->assertOk();

    $product = Product::query()->where('sku', $sku)->first();

    expect($product)->not->toBeNull()
        ->and($product->type)->toBe('configurable')
        ->and($product->variants)->toHaveCount(4);

    $this->assertDatabaseHas('product_super_attributes', [
        'product_id' => $product->id,
        'attribute_id' => $color->id,
    ]);

    $this->assertDatabaseHas('product_super_attributes', [
        'product_id' => $product->id,
        'attribute_id' => $size->id,
    ]);

    foreach ($product->variants as $variant) {
        expect($variant->type)->toBe('simple')
            ->and($variant->parent_id)->toBe($product->id);
    }
});

// ============================================================================
// Edit
// ============================================================================

it('should return the edit page of a configurable product', function () {
    $this->loginAsAdmin();

    $product = $this->createConfigurableProduct();

    get(route('admin.catalog.products.edit', $product->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.catalog.products.edit.title'))
        ->assertSee($product->sku)
        ->assertSee($product->variants->first()->sku);
});

// ============================================================================
// Product Flat — Via Real Store + Update Flow
// ============================================================================

it('should populate parent product_flat after store and update', function () {
    $product = $this->storeAndUpdateConfigurableProduct();

    $flat = ProductFlat::query()->where('product_id', $product->id)->first();

    expect($flat)->not->toBeNull()
        ->and($flat->sku)->toBe($product->sku)
        ->and($flat->type)->toBe('configurable')
        ->and($flat->attribute_family_id)->toBe(1)
        ->and($flat->name)->toBe('Test Configurable Product')
        ->and($flat->short_description)->toBe('A short description for the configurable product.')
        ->and($flat->description)->toBe('A full description for the configurable product.')
        ->and($flat->url_key)->not->toBeEmpty()
        ->and($flat->price)->toBeNull()
        ->and($flat->weight)->toBeNull()
        ->and($flat->status)->toBeTruthy()
        ->and($flat->visible_individually)->toBeTruthy()
        ->and($flat->locale)->toBe(app()->getLocale())
        ->and($flat->channel)->toBe(core()->getDefaultChannelCode());
});

it('should populate variant product_flat entries after store and update', function () {
    $product = $this->storeAndUpdateConfigurableProduct();

    foreach ($product->variants as $variant) {
        $flat = ProductFlat::query()->where('product_id', $variant->id)->first();

        expect($flat)->not->toBeNull("product_flat for variant {$variant->id} should exist.")
            ->and($flat->type)->toBe('simple')
            ->and($flat->sku)->toBe($variant->sku)
            ->and($flat->name)->not->toBeEmpty()
            ->and((float) $flat->price)->toBeGreaterThan(0)
            ->and((float) $flat->weight)->toBeGreaterThan(0)
            ->and($variant->parent_id)->toBe($product->id);
    }
});

// ============================================================================
// Variant Attribute Values
// ============================================================================

it('should store super attribute values on each variant', function () {
    $product = $this->storeAndUpdateConfigurableProduct();

    $colorId = configurableAttribute('color')->id;

    $sizeId = configurableAttribute('size')->id;

    foreach ($product->variants as $variant) {
        $colorAttr = $variant->attribute_values
            ->first(fn ($av) => $av->attribute_id === $colorId);

        $sizeAttr = $variant->attribute_values
            ->first(fn ($av) => $av->attribute_id === $sizeId);

        expect($colorAttr)->not->toBeNull("Variant {$variant->id} should have a color attribute value.")
            ->and($colorAttr->integer_value)->not->toBeNull()
            ->and($sizeAttr)->not->toBeNull("Variant {$variant->id} should have a size attribute value.")
            ->and($sizeAttr->integer_value)->not->toBeNull();
    }
});

// ============================================================================
// Inventory — Variant Level
// ============================================================================

it('should create inventory for each variant after update', function () {
    $product = $this->storeAndUpdateConfigurableProduct();

    foreach ($product->variants as $variant) {
        $this->assertDatabaseHas('product_inventories', [
            'product_id' => $variant->id,
            'inventory_source_id' => 1,
        ]);
    }

    $this->assertDatabaseMissing('product_inventories', [
        'product_id' => $product->id,
    ]);
});

// ============================================================================
// Channel Assignment
// ============================================================================

it('should assign the configurable product and variants to the current channel', function () {
    $product = $this->storeAndUpdateConfigurableProduct();

    $channelId = core()->getDefaultChannel()->id;

    $this->assertDatabaseHas('product_channels', [
        'product_id' => $product->id,
        'channel_id' => $channelId,
    ]);

    foreach ($product->variants as $variant) {
        $this->assertDatabaseHas('product_channels', [
            'product_id' => $variant->id,
            'channel_id' => $channelId,
        ]);
    }
});

// ============================================================================
// Update — Change Variant Values and Verify
// ============================================================================

it('should update variant values and reflect changes in product_flat', function () {
    $product = $this->storeAndUpdateConfigurableProduct();

    $variant = $product->variants->first();

    $variants = [
        $variant->id => [
            'sku' => $variant->sku,
            'name' => 'Updated Variant Name',
            'price' => 79.99,
            'weight' => 3,
            'status' => 1,
            'inventories' => [1 => 250],
        ],
    ];

    foreach ($product->variants->skip(1) as $otherVariant) {
        $variants[$otherVariant->id] = [
            'sku' => $otherVariant->sku,
            'name' => $otherVariant->name ?? 'Variant',
            'price' => 50,
            'weight' => 2,
            'status' => 1,
            'inventories' => [1 => 50],
        ];
    }

    putJson(route('admin.catalog.products.update', $product->id), [
        'sku' => $product->sku,
        'url_key' => $product->url_key,
        'name' => $product->name,
        'short_description' => $product->short_description,
        'description' => $product->description,
        'channel' => core()->getDefaultChannelCode(),
        'locale' => app()->getLocale(),
        'status' => 1,
        'visible_individually' => 1,
        'guest_checkout' => 1,
        'variants' => $variants,
    ])->assertRedirect(route('admin.catalog.products.index'));

    $flat = ProductFlat::query()->where('product_id', $variant->id)->first();

    expect($flat->name)->toBe('Updated Variant Name')
        ->and((float) $flat->price)->toBe(79.99)
        ->and((float) $flat->weight)->toBe(3.0);
});

// ============================================================================
// Update Validation
// ============================================================================

it('should fail validation when required fields are missing on configurable product update', function () {
    $this->loginAsAdmin();

    $product = $this->createConfigurableProduct();

    putJson(route('admin.catalog.products.update', $product->id))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('sku')
        ->assertJsonValidationErrorFor('url_key')
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('short_description')
        ->assertJsonValidationErrorFor('description');
});

it('should fail validation when boolean fields have invalid values on configurable product update', function () {
    $this->loginAsAdmin();

    $product = $this->createConfigurableProduct();

    putJson(route('admin.catalog.products.update', $product->id), [
        'visible_individually' => 'invalid',
        'status' => 'invalid',
        'guest_checkout' => 'invalid',
        'new' => 'invalid',
        'featured' => 'invalid',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('visible_individually')
        ->assertJsonValidationErrorFor('status')
        ->assertJsonValidationErrorFor('guest_checkout')
        ->assertJsonValidationErrorFor('new')
        ->assertJsonValidationErrorFor('featured');
});

// ============================================================================
// Delete
// ============================================================================

it('should delete a configurable product and all its variants', function () {
    $product = $this->storeAndUpdateConfigurableProduct();

    $productId = $product->id;

    $variantIds = $product->variants->pluck('id')->toArray();

    deleteJson(route('admin.catalog.products.delete', $productId))
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.catalog.products.delete-success'));

    $this->assertDatabaseMissing('products', ['id' => $productId]);

    $this->assertDatabaseMissing('product_flat', ['product_id' => $productId]);

    $this->assertDatabaseMissing('product_attribute_values', ['product_id' => $productId]);

    $this->assertDatabaseMissing('product_super_attributes', ['product_id' => $productId]);

    foreach ($variantIds as $variantId) {
        $this->assertDatabaseMissing('products', ['id' => $variantId]);

        $this->assertDatabaseMissing('product_flat', ['product_id' => $variantId]);

        $this->assertDatabaseMissing('product_attribute_values', ['product_id' => $variantId]);

        $this->assertDatabaseMissing('product_inventories', ['product_id' => $variantId]);
    }
});

// ============================================================================
// Variations
// ============================================================================

it('should give the admin panel the variations of a configurable product without the storefront image urls', function () {
    $product = $this->storeAndUpdateConfigurableProduct();

    $this->loginAsAdmin();

    $response = getJson(route('admin.catalog.products.configurable.options', $product->id))
        ->assertOk()
        ->assertJsonStructure(['data' => ['attributes' => [['id', 'code', 'label', 'swatch_type', 'options']], 'index']])
        ->assertJsonMissingPath('data.variant_images')
        ->assertJsonMissingPath('data.variant_videos')
        ->assertJsonMissingPath('data.variant_prices');

    expect($response->getContent())->not->toContain('cache/');
});
