<?php

use Webkul\Product\Helpers\DiscountBadge;

/**
 * Pruebas de regresión del badge dinámico "-X% OFF" (HU-11).
 *
 * Estas pruebas no requieren base de datos: validan la lógica pura
 * del cálculo de descuento usada por el storefront.
 */
it('returns the correct percentage for a valid discount', function () {
    expect(DiscountBadge::calculatePercentage(100, 75))->toBe(25);

    expect(DiscountBadge::calculatePercentage(200, 150))->toBe(25);

    expect(DiscountBadge::calculatePercentage(49.99, 24.99))->toBe(50);

    expect(DiscountBadge::calculatePercentage('100', '90'))->toBe(10);
});

it('returns null when there is no discount', function () {
    expect(DiscountBadge::calculatePercentage(100, 100))->toBeNull();

    expect(DiscountBadge::calculatePercentage(100, 120))->toBeNull();
});

it('returns null when prices are invalid', function () {
    expect(DiscountBadge::calculatePercentage(0, 0))->toBeNull();

    expect(DiscountBadge::calculatePercentage(-10, 5))->toBeNull();

    expect(DiscountBadge::calculatePercentage(100, -5))->toBeNull();

    expect(DiscountBadge::calculatePercentage(null, null))->toBeNull();
});

it('returns null when the discount is below the minimum threshold', function () {
    expect(DiscountBadge::calculatePercentage(100, 97))->toBeNull();

    expect(DiscountBadge::calculatePercentage(100, 96))->toBeNull();

    expect(DiscountBadge::calculatePercentage(100, 95))->toBe(5);
});

it('respects a custom minimum threshold', function () {
    expect(DiscountBadge::calculatePercentage(100, 92, 10))->toBeNull();

    expect(DiscountBadge::calculatePercentage(100, 89, 10))->toBe(11);
});

it('rounds percentages correctly', function () {
    expect(DiscountBadge::calculatePercentage(3, 2))->toBe(33);

    expect(DiscountBadge::calculatePercentage(3, 1))->toBe(67);

    expect(DiscountBadge::calculatePercentage(100, 0.5))->toBe(100);
});

it('calculates the discount from the bagisto prices array', function () {
    $prices = [
        'regular' => [
            'price'           => 100.0,
            'formatted_price' => '$100.00',
        ],

        'final' => [
            'price'           => 50.0,
            'formatted_price' => '$50.00',
        ],
    ];

    expect(DiscountBadge::fromPrices($prices))->toBe(50);
});

it('returns null when the prices array is incomplete', function () {
    expect(DiscountBadge::fromPrices([]))->toBeNull();

    expect(DiscountBadge::fromPrices(['regular' => ['price' => 100]]))->toBeNull();
});
