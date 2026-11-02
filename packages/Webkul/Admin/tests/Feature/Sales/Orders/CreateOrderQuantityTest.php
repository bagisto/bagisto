<?php

use Webkul\Admin\Http\Resources\ProductResource;
use Webkul\Faker\Helpers\Product as ProductFaker;

it('should let the admin choose a quantity only for a product that has a quantity box', function (string $factory, bool $canChangeQty) {
    // Arrange
    $product = (new ProductFaker)->{$factory}()->create();

    // Act
    $resource = (new ProductResource($product))->toArray(request());

    // Assert
    expect($resource['can_change_qty'])->toBe($canChangeQty);
})->with([
    'simple product' => ['getSimpleProductFactory', true],
    'downloadable product' => ['getDownloadableProductFactory', false],
]);
