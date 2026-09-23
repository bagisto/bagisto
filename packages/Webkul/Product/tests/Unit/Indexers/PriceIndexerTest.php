<?php

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;
use Webkul\Attribute\Models\Attribute;
use Webkul\Product\Helpers\Indexers\Price as PriceIndexer;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductPriceIndex;

/**
 * A composite product of the given type and one of the products its price is built from.
 */
function compositeProductWithChild(string $type): array
{
    $parent = test()->createProductOfType($type);

    $childId = match ($type) {
        'configurable' => $parent->variants->first()->id,
        'grouped' => $parent->grouped_products->first()->associated_product_id,
        'bundle' => $parent->bundle_options->first()->bundle_option_products->first()->product_id,
    };

    return [$parent, Product::query()->findOrFail($childId)];
}

/**
 * Start a special price on a product today, without reindexing it.
 */
function startSpecialPriceToday(Product $product, float $specialPrice): void
{
    $attributeIds = Attribute::query()->whereIn('code', ['special_price', 'special_price_from'])->pluck('id', 'code');

    $product->attribute_values()
        ->where('attribute_id', $attributeIds['special_price'])
        ->update(['float_value' => $specialPrice]);

    $product->attribute_values()
        ->where('attribute_id', $attributeIds['special_price_from'])
        ->update(['date_value' => Carbon::today()->format('Y-m-d')]);
}

/**
 * The indexed minimum price of a product for guests on the default channel.
 */
function indexedMinPriceOf(Product $product): float
{
    return (float) ProductPriceIndex::query()
        ->where('product_id', $product->id)
        ->where('customer_group_id', core()->getGuestCustomerGroup()->id)
        ->where('channel_id', core()->getDefaultChannel()->id)
        ->value('min_price');
}

// ============================================================================
// Reindexing Given Products
// ============================================================================

it('should reindex every set of products handed to the same indexer', function () {
    $first = $this->createSimpleProduct(['price' => ['float_value' => 100]]);

    $second = $this->createSimpleProduct(['price' => ['float_value' => 100]]);

    startSpecialPriceToday($first, 60);

    startSpecialPriceToday($second, 70);

    $indexer = app(PriceIndexer::class);

    $indexer->reindexProducts([$first->id]);

    $indexer->reindexProducts([$second->id]);

    expect(indexedMinPriceOf($first))->toBePrice(60)
        ->and(indexedMinPriceOf($second))->toBePrice(70);
});

// ============================================================================
// Selective Reindexing
// ============================================================================

it('should reindex the composite parent of a product whose special price starts today', function (string $type) {
    [$parent, $child] = compositeProductWithChild($type);

    startSpecialPriceToday($child, 60);

    $announced = [];

    Event::listen('catalog.product.price.reindex.after', function (array $productIds = []) use (&$announced) {
        $announced = $productIds;
    });

    app(PriceIndexer::class)->reindexSelective();

    expect(indexedMinPriceOf($child))->toBePrice(60)
        ->and(indexedMinPriceOf($parent))->toBePrice(60)
        ->and($announced)->toContain($child->id, $parent->id);
})->with(['configurable', 'grouped', 'bundle']);
