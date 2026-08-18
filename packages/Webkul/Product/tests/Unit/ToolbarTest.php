<?php

use Webkul\Core\Models\CoreConfig;
use Webkul\Product\Helpers\Toolbar;

it('falls back to the default limits when the products per page config is non-numeric', function () {
    // Arrange
    CoreConfig::create([
        'code' => 'catalog.products.storefront.products_per_page',
        'value' => 'ten, twenty',
    ]);

    // Act
    $limits = app(Toolbar::class)->getAvailableLimits();

    // Assert
    expect($limits->toArray())->toBe([12, 24, 36, 48]);
});

it('filters out non-numeric values but keeps the valid ones from the products per page config', function () {
    // Arrange
    CoreConfig::create([
        'code' => 'catalog.products.storefront.products_per_page',
        'value' => '10, twenty, 30',
    ]);

    // Act
    $limits = app(Toolbar::class)->getAvailableLimits();

    // Assert
    expect($limits->toArray())->toBe([10, 30]);
});

it('returns the configured numeric limits as integers', function () {
    // Arrange
    CoreConfig::create([
        'code' => 'catalog.products.storefront.products_per_page',
        'value' => '10, 20, 30',
    ]);

    // Act
    $limits = app(Toolbar::class)->getAvailableLimits();

    // Assert
    expect($limits->toArray())->toBe([10, 20, 30]);
});

it('does not crash and falls back to the default limit when the limit param is non-numeric', function () {
    // Arrange
    CoreConfig::create([
        'code' => 'catalog.products.storefront.products_per_page',
        'value' => 'ten, twenty',
    ]);

    // Act
    $limit = app(Toolbar::class)->getLimit(['limit' => 'ten']);

    // Assert
    expect($limit)->toBe(12);
});
