<?php

use Illuminate\Pagination\Cursor;
use Webkul\Attribute\Models\Attribute;
use Webkul\Core\Helpers\CacheGeneration;
use Webkul\Core\Models\Channel;
use Webkul\Core\Models\Locale;
use Webkul\Core\Repositories\ChannelRepository;
use Webkul\Product\Helpers\Indexers\Flat as FlatIndexer;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductFlat;

beforeEach(function () {
    $defaultChannel = core()->getDefaultChannel();

    $this->channelLocale = Locale::factory()->create();

    $this->channel = Channel::factory()->create([
        'root_category_id' => $defaultChannel->root_category_id,
        'default_locale_id' => $this->channelLocale->id,
        'base_currency_id' => $defaultChannel->base_currency_id,
    ]);

    $this->channel->locales()->sync([$this->channelLocale->id]);

    CacheGeneration::bump(ChannelRepository::class);
});

/**
 * Write a product's name in the given locale without reindexing it.
 */
function nameProductIn(Product $product, string $localeCode, string $name): void
{
    $attributeId = Attribute::query()->where('code', 'name')->value('id');

    $product->attribute_values()->create([
        'attribute_id' => $attributeId,
        'locale' => $localeCode,
        'text_value' => $name,
        'unique_id' => implode('|', [$localeCode, $product->id, $attributeId]),
    ]);
}

/**
 * Put a product on the given channels and rewrite its flat rows.
 */
function reindexFlatOn(Product $product, array $channelIds): void
{
    $product->channels()->sync($channelIds);

    app(FlatIndexer::class)->refresh($product->fresh());
}

/**
 * The flat row of a product on a channel in a locale.
 */
function flatRowOn(int $productId, string $channelCode, string $localeCode): ?ProductFlat
{
    return ProductFlat::query()
        ->where('product_id', $productId)
        ->where('channel', $channelCode)
        ->where('locale', $localeCode)
        ->first();
}

// ============================================================================
// Channel Without The Admin Locale
// ============================================================================

it('should write the flat row of a channel in its own locale from the values saved in that locale', function () {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 100]]);

    nameProductIn($product, $this->channelLocale->code, $name = fake()->words(3, true));

    reindexFlatOn($product, [core()->getDefaultChannel()->id, $this->channel->id]);

    $row = flatRowOn($product->id, $this->channel->code, $this->channelLocale->code);

    expect($row)->not->toBeNull()
        ->and($row->name)->toBe($name)
        ->and($row->sku)->toBe($product->sku)
        ->and((float) $row->price)->toBePrice(100);
});

it('should write the admin locale flat row of a channel that lacks the admin locale from the channel default locale', function () {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 100]]);

    nameProductIn($product, $this->channelLocale->code, $name = fake()->words(3, true));

    reindexFlatOn($product, [$this->channel->id]);

    $row = flatRowOn($product->id, $this->channel->code, app()->getLocale());

    expect($row)->not->toBeNull()
        ->and($row->name)->toBe($name)
        ->and((float) $row->price)->toBePrice(100);
});

it('should keep the admin locale values on a channel that has the admin locale', function () {
    $product = $this->createSimpleProduct([
        'name' => ['text_value' => $adminName = fake()->words(3, true), 'locale' => app()->getLocale()],
    ]);

    nameProductIn($product, $this->channelLocale->code, fake()->words(3, true));

    reindexFlatOn($product, [core()->getDefaultChannel()->id, $this->channel->id]);

    expect(flatRowOn($product->id, core()->getDefaultChannel()->code, app()->getLocale()))
        ->name->toBe($adminName);
});

it('should write only the admin locale flat rows when refreshing the admin locale', function () {
    $product = $this->createSimpleProduct();

    nameProductIn($product, $this->channelLocale->code, $name = fake()->words(3, true));

    $product->channels()->sync([$this->channel->id]);

    app(FlatIndexer::class)->refreshAdminLocale([$product->fresh()]);

    expect(flatRowOn($product->id, $this->channel->code, app()->getLocale()))
        ->not->toBeNull()
        ->name->toBe($name)
        ->and(flatRowOn($product->id, $this->channel->code, $this->channelLocale->code))->toBeNull();
});

// ============================================================================
// Batched Reindexing
// ============================================================================

it('should reindex every product in a full flat reindex when an earlier batched reindex left its cursor in the request', function () {
    $product = $this->createSimpleProduct();

    ProductFlat::query()->where('product_id', $product->id)->delete();

    request()->query->add(['cursor' => (new Cursor(['products.id' => $product->id]))->encode()]);

    app(FlatIndexer::class)->reindexFull();

    expect(flatRowOn($product->id, core()->getDefaultChannel()->code, app()->getLocale()))
        ->not->toBeNull()
        ->sku->toBe($product->sku);
});
