<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Webkul\Attribute\Models\Attribute;
use Webkul\Product\Models\ProductAttributeValue;

use function Pest\Laravel\put;
use function Pest\Laravel\putJson;

/**
 * The path currently stored for one of the product's media attributes.
 */
function storedMediaPath(string $code): ?string
{
    return ProductAttributeValue::query()
        ->where('product_id', test()->product->id)
        ->where('attribute_id', test()->mediaAttributes[$code]->id)
        ->value('text_value');
}

beforeEach(function () {
    $this->product = $this->createSimpleProduct();

    $group = $this->product->attribute_family->attribute_groups()->first();

    $this->mediaAttributes = [
        'qa_image_attribute' => Attribute::factory()->create([
            'code' => 'qa_image_attribute',
            'type' => 'image',
        ]),

        'qa_file_attribute' => Attribute::factory()->create([
            'code' => 'qa_file_attribute',
            'type' => 'file',
        ]),
    ];

    $this->storedPaths = [
        'qa_image_attribute' => 'products/'.$this->product->id.'/existing.png',
        'qa_file_attribute' => 'products/'.$this->product->id.'/existing.pdf',
    ];

    $position = 0;

    foreach ($this->mediaAttributes as $code => $attribute) {
        DB::table('attribute_group_mappings')->insert([
            'attribute_id' => $attribute->id,
            'attribute_group_id' => $group->id,
            'position' => ++$position,
        ]);

        ProductAttributeValue::create([
            'product_id' => $this->product->id,
            'attribute_id' => $attribute->id,
            'text_value' => $this->storedPaths[$code],
            'unique_id' => $this->product->id.'|'.$attribute->id,
        ]);
    }

    $this->payload = [
        'sku' => $this->product->sku,
        'url_key' => $this->product->url_key,
        'name' => 'Unchanged Name',
        'short_description' => 'Unchanged short.',
        'description' => 'Unchanged description.',
        'price' => 49.99,
        'weight' => 5,
        'channel' => core()->getDefaultChannelCode(),
        'locale' => app()->getLocale(),
        'status' => 1,
        'visible_individually' => 1,
        'new' => 0,
        'featured' => 0,
        'guest_checkout' => 1,
    ];

    $this->loginAsAdmin();
});

// ============================================================================
// Stored Media Kept
// ============================================================================

it('should keep the stored files when the form sends nothing for them', function () {
    putJson(route('admin.catalog.products.update', $this->product->id), $this->payload)
        ->assertRedirect(route('admin.catalog.products.index'));

    expect(storedMediaPath('qa_image_attribute'))->toBe($this->storedPaths['qa_image_attribute'])
        ->and(storedMediaPath('qa_file_attribute'))->toBe($this->storedPaths['qa_file_attribute']);
});

it('should ignore a path posted for a media attribute rather than storing it', function () {
    putJson(route('admin.catalog.products.update', $this->product->id), array_merge($this->payload, [
        'qa_image_attribute' => 'products/1/somebody-elses.png',
    ]))->assertRedirect(route('admin.catalog.products.index'));

    expect(storedMediaPath('qa_image_attribute'))->toBe($this->storedPaths['qa_image_attribute']);
});

// ============================================================================
// Fresh Uploads
// ============================================================================

it('should replace the stored file when a fresh image is uploaded', function () {
    Storage::fake();

    put(route('admin.catalog.products.update', $this->product->id), array_merge($this->payload, [
        'qa_image_attribute' => UploadedFile::fake()->image('fresh.png'),
    ]))->assertRedirect(route('admin.catalog.products.index'));

    $stored = storedMediaPath('qa_image_attribute');

    expect($stored)
        ->not->toBe($this->storedPaths['qa_image_attribute'])
        ->toStartWith('products/'.$this->product->id.'/');

    Storage::assertExists($stored);
});

it('should replace the stored file when a fresh file is uploaded', function () {
    Storage::fake();

    put(route('admin.catalog.products.update', $this->product->id), array_merge($this->payload, [
        'qa_file_attribute' => UploadedFile::fake()->create('fresh.pdf', 10, 'application/pdf'),
    ]))->assertRedirect(route('admin.catalog.products.index'));

    $stored = storedMediaPath('qa_file_attribute');

    expect($stored)
        ->not->toBe($this->storedPaths['qa_file_attribute'])
        ->toStartWith('products/'.$this->product->id.'/');

    Storage::assertExists($stored);
});

// ============================================================================
// Removal
// ============================================================================

it('should clear the stored file when the attribute is removed', function () {
    putJson(route('admin.catalog.products.update', $this->product->id), array_merge($this->payload, [
        'qa_image_attribute' => ['delete' => 1],
        'qa_file_attribute' => ['delete' => 1],
    ]))->assertRedirect(route('admin.catalog.products.index'));

    expect(storedMediaPath('qa_image_attribute'))->toBeNull()
        ->and(storedMediaPath('qa_file_attribute'))->toBeNull();
});

// ============================================================================
// Rejected Uploads
// ============================================================================

it('should reject an image attribute uploaded with a disallowed type', function () {
    put(route('admin.catalog.products.update', $this->product->id), array_merge($this->payload, [
        'qa_image_attribute' => UploadedFile::fake()->create('payload.php', 10, 'text/x-php'),
    ]))->assertSessionHasErrors('qa_image_attribute');

    expect(storedMediaPath('qa_image_attribute'))->toBe($this->storedPaths['qa_image_attribute']);
});

it('should reject a file attribute larger than the allowed upload size', function () {
    put(route('admin.catalog.products.update', $this->product->id), array_merge($this->payload, [
        'qa_file_attribute' => UploadedFile::fake()->create('too-big.pdf', 999999, 'application/pdf'),
    ]))->assertSessionHasErrors('qa_file_attribute');

    expect(storedMediaPath('qa_file_attribute'))->toBe($this->storedPaths['qa_file_attribute']);
});
