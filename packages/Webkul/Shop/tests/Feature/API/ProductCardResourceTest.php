<?php

use Webkul\Customer\Models\Wishlist;
use Webkul\Product\Models\Product;
use Webkul\Shop\Http\Resources\ProductResource;
use Webkul\User\Models\Admin;

use function Pest\Laravel\getJson;

/**
 * The product card the storefront products api lists for the given product.
 */
function listedProductCard(Product $product): ?array
{
    return collect(getJson(route('shop.api.products.index', ['sort' => 'created_at-desc']))->assertOk()->json('data'))
        ->firstWhere('id', $product->id);
}

// ============================================================================
// Product Card
// ============================================================================

it('should omit the heavy description and gallery fields from the products listing', function () {
    expect(listedProductCard($this->createSimpleProduct()))
        ->not->toHaveKey('description')
        ->not->toHaveKey('images');
});

it('should read the wishlist from the customer guard, whichever guard the request uses by default', function () {
    $product = $this->createSimpleProduct();

    $customer = $this->loginAsCustomer();

    Wishlist::factory()->create([
        'channel_id' => core()->getCurrentChannel()->id,
        'product_id' => $product->id,
        'customer_id' => $customer->id,
    ]);

    $this->actingAs(Admin::factory()->create(), 'admin');

    expect(listedProductCard($product)['is_wishlist'])->toBeTrue()
        ->and((new ProductResource($product))->resolve()['is_wishlist'])->toBeTrue();
});

it('should keep the fields the product card needs in the listing', function () {
    expect(listedProductCard($this->createSimpleProduct()))
        ->toHaveKeys([
            'id',
            'name',
            'url_key',
            'base_image',
            'is_new',
            'is_saleable',
            'is_wishlist',
            'price_html',
            'ratings',
            'reviews',
        ]);
});
