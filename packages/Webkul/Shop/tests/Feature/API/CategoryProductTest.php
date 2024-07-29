<?php

use Webkul\Faker\Helpers\Category as CategoryFaker;
use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\Product\Helpers\Toolbar;

use function Pest\Laravel\getJson;

it('returns paginated category products', function () {
    // Arrange.
    $productsCount = 50;

    $specifiedCategory = (new CategoryFaker)->factory()->create();

    (new ProductFaker)
        ->getSimpleProductFactory()
        ->hasAttached($specifiedCategory)
        ->count($productsCount)
        ->create();

    $availableLimits = (new Toolbar)->getAvailableLimits();

    // Act and Assert.
    $availableLimits->each(function ($limit) use ($specifiedCategory, $productsCount) {
        getJson(route('shop.api.products.index', ['category_id' => $specifiedCategory->id, 'limit' => $limit]))
            ->assertOk()
            ->assertJsonCount($limit, 'data')
            ->assertJsonPath('meta.total', $productsCount);
    });
});

it('returns category products sorted by name descending', function () {
    // Arrange.
    $specifiedCategory = (new CategoryFaker)->factory()->create();

    $products = (new ProductFaker)
        ->getSimpleProductFactory()
        ->hasAttached($specifiedCategory)
        ->count(3)
        ->create();

    $expectedNamesInDescOrder = $products
        ->map(fn ($product) => $product->name)
        ->sortDesc()
        ->toArray();

    // Act and Assert.
    getJson(route('shop.api.products.index', ['category_id' => $specifiedCategory->id, 'sort' => 'name-desc']))
        ->assertOk()
        ->assertSeeTextInOrder($expectedNamesInDescOrder);
});

it('returns category products sorted by name ascending', function () {
    // Arrange.
    $specifiedCategory = (new CategoryFaker)->factory()->create();

    $products = (new ProductFaker)
        ->getSimpleProductFactory()
        ->hasAttached($specifiedCategory)
        ->count(3)
        ->create();

    $expectedNamesInAscOrder = $products
        ->map(fn ($product) => $product->name)
        ->sort()
        ->toArray();

    // Act and Assert.
    getJson(route('shop.api.products.index', ['category_id' => $specifiedCategory->id, 'sort' => 'name-asc']))
        ->assertOk()
        ->assertSeeTextInOrder($expectedNamesInAscOrder);
});

it('returns category products sorted by created_at descending', function () {
    // Arrange.
    $specifiedCategory = (new CategoryFaker)->factory()->create();

    $simpleProductFactory = (new ProductFaker)
        ->getSimpleProductFactory()
        ->hasAttached($specifiedCategory);

    $firstProduct = $simpleProductFactory->create([
        'created_at' => now()->subYear(),
    ]);

    $secondProduct = $simpleProductFactory->create([
        'created_at' => now()->subMonth(),
    ]);

    $lastProduct = $simpleProductFactory->create([
        'created_at' => now(),
    ]);

    // Act and Assert.
    getJson(route('shop.api.products.index', ['category_id' => $specifiedCategory->id, 'sort' => 'created_at-desc']))
        ->assertOk()
        ->assertSeeTextInOrder([
            $lastProduct->id,
            $secondProduct->id,
            $firstProduct->id,
        ]);
});

it('returns category products sorted by created_at ascending', function () {
    // Arrange.
    $specifiedCategory = (new CategoryFaker)->factory()->create();

    $simpleProductFactory = (new ProductFaker)
        ->getSimpleProductFactory()
        ->hasAttached($specifiedCategory);

    $firstProduct = $simpleProductFactory->create([
        'created_at' => now()->subYear(),
    ]);

    $secondProduct = $simpleProductFactory->create([
        'created_at' => now()->subMonth(),
    ]);

    $lastProduct = $simpleProductFactory->create([
        'created_at' => now(),
    ]);

    // Act and Assert.
    getJson(route('shop.api.products.index', ['category_id' => $specifiedCategory->id, 'sort' => 'created_at-asc']))
        ->assertOk()
        ->assertSeeTextInOrder([
            $firstProduct->id,
            $secondProduct->id,
            $lastProduct->id,
        ]);
});

it('returns category products sorted by price descending', function () {
    // Arrange.
    $specifiedCategory = (new CategoryFaker)->factory()->create();

    $products = (new ProductFaker)
        ->getSimpleProductFactory()
        ->hasAttached($specifiedCategory)
        ->count(3)
        ->create();

    $expectedPricesInDescOrder = $products
        ->map(fn ($product) => $product->getTypeInstance()->getMinimalPrice())
        ->sortDesc()
        ->map(fn ($price) =>  core()->formatPrice($price))
        ->toArray();

    // Act and Assert.
    getJson(route('shop.api.products.index', ['category_id' => $specifiedCategory->id, 'sort' => 'price-desc']))
        ->assertOk()
        ->assertSeeTextInOrder($expectedPricesInDescOrder);
});

it('returns category products sorted by price ascending', function () {
    // Arrange.
    $specifiedCategory = (new CategoryFaker)->factory()->create();

    $products = (new ProductFaker)
        ->getSimpleProductFactory()
        ->hasAttached($specifiedCategory)
        ->count(3)
        ->create();

    $expectedPricesInAscOrder = $products
        ->map(fn ($product) => $product->getTypeInstance()->getMinimalPrice())
        ->sort()
        ->map(fn ($price) =>  core()->formatPrice($price))
        ->toArray();

    // Act and Assert.
    getJson(route('shop.api.products.index', ['category_id' => $specifiedCategory->id, 'sort' => 'price-asc']))
        ->assertOk()
        ->assertSeeTextInOrder($expectedPricesInAscOrder);
});
