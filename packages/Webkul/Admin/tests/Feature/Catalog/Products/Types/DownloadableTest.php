<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductFlat;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

// ============================================================================
// Store
// ============================================================================

it('should store a downloadable product and redirect to edit', function () {
    $this->loginAsAdmin();

    $sku = fake()->uuid();

    postJson(route('admin.catalog.products.store'), [
        'type' => 'downloadable',
        'attribute_family_id' => 1,
        'sku' => $sku,
    ])
        ->assertOk()
        ->assertJsonStructure(['data' => ['redirect_url']]);

    $product = Product::where('sku', $sku)->first();

    expect($product)->not->toBeNull();
    expect($product->type)->toBe('downloadable');
});

// ============================================================================
// Edit
// ============================================================================

it('should return the edit page of a downloadable product', function () {
    $this->loginAsAdmin();

    $product = $this->createDownloadableProduct();

    get(route('admin.catalog.products.edit', $product->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.catalog.products.edit.title'))
        ->assertSeeText($product->name);
});

// ============================================================================
// Product Flat — Via Real Store + Update Flow
// ============================================================================

it('should populate product_flat after store and update', function () {
    $product = $this->storeAndUpdateDownloadableProduct();

    $flat = ProductFlat::where('product_id', $product->id)->first();

    expect($flat)->not->toBeNull();

    // Core fields
    expect($flat->sku)->toBe($product->sku);
    expect($flat->type)->toBe('downloadable');
    expect($flat->name)->toBe('Test Downloadable Product');
    expect((float) $flat->price)->toBe(49.99);

    // Downloadable products skip weight.
    expect($flat->weight)->toBeNull();

    // Boolean fields
    expect($flat->status)->toBeTruthy();
    expect($flat->visible_individually)->toBeTruthy();
});

// ============================================================================
// Downloadable Links and Samples — Via Real Store + Update Flow
// ============================================================================

it('should create downloadable links after store and update', function () {
    $product = $this->storeAndUpdateDownloadableProduct();

    expect($product->downloadable_links)->toHaveCount(2);

    $linkOne = $product->downloadable_links->firstWhere('sort_order', 0);
    expect($linkOne)->not->toBeNull();
    expect($linkOne->url)->toBe('https://example.com/file1.pdf');
    expect((float) $linkOne->price)->toBe(10.0);
    expect($linkOne->downloads)->toBe(5);
    expect($linkOne->type)->toBe('url');
});

it('should create downloadable samples after store and update', function () {
    $product = $this->storeAndUpdateDownloadableProduct();

    expect($product->downloadable_samples)->toHaveCount(1);

    $sample = $product->downloadable_samples->first();
    expect($sample->url)->toBe('https://example.com/sample.pdf');
    expect($sample->type)->toBe('url');
});

it('should not create inventory for a downloadable product', function () {
    $product = $this->storeAndUpdateDownloadableProduct();

    // Downloadable products are not stockable.
    $this->assertDatabaseMissing('product_inventories', [
        'product_id' => $product->id,
    ]);
});

// ============================================================================
// File Upload
// ============================================================================

it('should upload a downloadable link file', function () {
    $product = $this->storeAndUpdateDownloadableProduct();

    $response = postJson(route('admin.catalog.products.upload_link', $product->id), [
        'file' => $file = UploadedFile::fake()->create('document.pdf', 100),
    ])
        ->assertOk()
        ->assertJsonPath('file_name', $file->getClientOriginalName());

    // Clean up uploaded file.
    if (Storage::disk('private')->exists($response['file'])) {
        Storage::disk('private')->delete($response['file']);
    }
});

it('should upload a downloadable sample file', function () {
    $product = $this->storeAndUpdateDownloadableProduct();

    $response = postJson(route('admin.catalog.products.upload_sample', $product->id), [
        'file' => $file = UploadedFile::fake()->create('sample.pdf', 100),
    ])
        ->assertOk()
        ->assertJsonPath('file_name', $file->name);

    // Clean up uploaded file.
    if (Storage::disk('public')->exists($response['file'])) {
        Storage::disk('public')->delete($response['file']);
    }
});

// ============================================================================
// Update Validation
// ============================================================================

it('should fail validation when required fields are missing on downloadable product update', function () {
    $this->loginAsAdmin();

    $product = $this->createDownloadableProduct();

    // Downloadable products do not require weight.
    putJson(route('admin.catalog.products.update', $product->id))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('sku')
        ->assertJsonValidationErrorFor('url_key')
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('price')
        ->assertJsonValidationErrorFor('short_description')
        ->assertJsonValidationErrorFor('description');
});

// ============================================================================
// Delete
// ============================================================================

it('should delete a downloadable product and clean up all related tables', function () {
    $product = $this->storeAndUpdateDownloadableProduct();
    $productId = $product->id;

    deleteJson(route('admin.catalog.products.delete', $productId))
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.catalog.products.delete-success'));

    $this->assertDatabaseMissing('products', ['id' => $productId]);
    $this->assertDatabaseMissing('product_flat', ['product_id' => $productId]);
    $this->assertDatabaseMissing('product_attribute_values', ['product_id' => $productId]);
    $this->assertDatabaseMissing('product_downloadable_links', ['product_id' => $productId]);
    $this->assertDatabaseMissing('product_downloadable_samples', ['product_id' => $productId]);
});
