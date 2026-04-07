<?php

// ============================================================================
// Active Special Price
// ============================================================================

it('should apply special price when within date range', function () {
    $product = $this->createSimpleProduct([
        'price' => ['float_value' => 1000],
        'special_price' => ['float_value' => 800],
        'special_price_from' => ['date_value' => now()->subDay()->format('Y-m-d'), 'channel' => core()->getCurrentChannelCode()],
        'special_price_to' => ['date_value' => now()->addMonth()->format('Y-m-d'), 'channel' => core()->getCurrentChannelCode()],
    ]);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartItemPrice($response, 800);
});

it('should use regular price when special price has expired', function () {
    $product = $this->createSimpleProduct([
        'price' => ['float_value' => 1000],
        'special_price' => ['float_value' => 800],
        'special_price_from' => ['date_value' => now()->subMonth()->format('Y-m-d'), 'channel' => core()->getCurrentChannelCode()],
        'special_price_to' => ['date_value' => now()->subDay()->format('Y-m-d'), 'channel' => core()->getCurrentChannelCode()],
    ]);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartItemPrice($response, 1000);
});

it('should use regular price when special price has not started yet', function () {
    $product = $this->createSimpleProduct([
        'price' => ['float_value' => 1000],
        'special_price' => ['float_value' => 800],
        'special_price_from' => ['date_value' => now()->addDay()->format('Y-m-d'), 'channel' => core()->getCurrentChannelCode()],
        'special_price_to' => ['date_value' => now()->addMonth()->format('Y-m-d'), 'channel' => core()->getCurrentChannelCode()],
    ]);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartItemPrice($response, 1000);
});

it('should apply special price when no date range is set', function () {
    $product = $this->createSimpleProduct([
        'price' => ['float_value' => 1000],
        'special_price' => ['float_value' => 750],
    ]);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartItemPrice($response, 750);
});
