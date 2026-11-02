<?php

use Webkul\Admin\Http\Resources\ProductResource;
use Webkul\Core\Models\Currency;
use Webkul\Faker\Helpers\Product as ProductFaker;

it('should price a product for the admin in the base currency rather than the channel one', function () {
    // Arrange
    $baseCurrency = Currency::factory()->create([
        'code' => 'XBA',
        'symbol' => 'B#',
        'currency_position' => null,
    ]);

    config()->set('app.currency', $baseCurrency->code);

    $product = (new ProductFaker)->getSimpleProductFactory()->create();

    // Act
    $formattedPrice = (new ProductResource($product))->toArray(request())['formatted_price'];

    // Assert
    expect($formattedPrice)->toBe(core()->formatBasePrice($product->price))
        ->toContain('B#')
        ->not->toContain(core()->getChannelBaseCurrency()->symbol);
});
