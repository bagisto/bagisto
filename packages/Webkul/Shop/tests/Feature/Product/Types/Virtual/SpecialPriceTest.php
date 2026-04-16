<?php

// ============================================================================
// Active Special Price
// ============================================================================

it('should apply special price to virtual product when within date range', function () {
    $product = $this->createVirtualProduct([
        'price' => ['float_value' => 500],
        'special_price' => ['float_value' => 350],
        'special_price_from' => ['date_value' => now()->subDay()->format('Y-m-d'), 'channel' => core()->getCurrentChannelCode()],
        'special_price_to' => ['date_value' => now()->addMonth()->format('Y-m-d'), 'channel' => core()->getCurrentChannelCode()],
    ]);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartItemPrice($response, 350);
});

it('should use regular price when virtual product special price has expired', function () {
    $product = $this->createVirtualProduct([
        'price' => ['float_value' => 500],
        'special_price' => ['float_value' => 350],
        'special_price_from' => ['date_value' => now()->subMonth()->format('Y-m-d'), 'channel' => core()->getCurrentChannelCode()],
        'special_price_to' => ['date_value' => now()->subDay()->format('Y-m-d'), 'channel' => core()->getCurrentChannelCode()],
    ]);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartItemPrice($response, 500);
});

it('should use regular price when virtual product special price has not started', function () {
    $product = $this->createVirtualProduct([
        'price' => ['float_value' => 500],
        'special_price' => ['float_value' => 350],
        'special_price_from' => ['date_value' => now()->addDay()->format('Y-m-d'), 'channel' => core()->getCurrentChannelCode()],
        'special_price_to' => ['date_value' => now()->addMonth()->format('Y-m-d'), 'channel' => core()->getCurrentChannelCode()],
    ]);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartItemPrice($response, 500);
});

it('should apply special price to virtual product when no date range is set', function () {
    $product = $this->createVirtualProduct([
        'price' => ['float_value' => 500],
        'special_price' => ['float_value' => 375],
    ]);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartItemPrice($response, 375);
});
