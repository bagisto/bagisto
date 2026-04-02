<?php

// ============================================================================
// Active Special Price
// ============================================================================

it('should apply special price to downloadable product when within date range', function () {
    $product = $this->createDownloadableProduct([
        'price' => ['float_value' => 1000],
        'special_price' => ['float_value' => 800],
        'special_price_from' => ['date_value' => now()->subDay()->format('Y-m-d'), 'channel' => core()->getCurrentChannelCode()],
        'special_price_to' => ['date_value' => now()->addMonth()->format('Y-m-d'), 'channel' => core()->getCurrentChannelCode()],
    ], [0]);

    $response = $this->addDownloadableProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, 800);
});

it('should use regular price when downloadable product special price has expired', function () {
    $product = $this->createDownloadableProduct([
        'price' => ['float_value' => 1000],
        'special_price' => ['float_value' => 800],
        'special_price_from' => ['date_value' => now()->subMonth()->format('Y-m-d'), 'channel' => core()->getCurrentChannelCode()],
        'special_price_to' => ['date_value' => now()->subDay()->format('Y-m-d'), 'channel' => core()->getCurrentChannelCode()],
    ], [0]);

    $response = $this->addDownloadableProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, 1000);
});

it('should use regular price when downloadable product special price has not started', function () {
    $product = $this->createDownloadableProduct([
        'price' => ['float_value' => 1000],
        'special_price' => ['float_value' => 800],
        'special_price_from' => ['date_value' => now()->addDay()->format('Y-m-d'), 'channel' => core()->getCurrentChannelCode()],
        'special_price_to' => ['date_value' => now()->addMonth()->format('Y-m-d'), 'channel' => core()->getCurrentChannelCode()],
    ], [0]);

    $response = $this->addDownloadableProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, 1000);
});

it('should apply special price to downloadable product when no date range is set', function () {
    $product = $this->createDownloadableProduct([
        'price' => ['float_value' => 1000],
        'special_price' => ['float_value' => 750],
    ], [0]);

    $response = $this->addDownloadableProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, 750);
});
