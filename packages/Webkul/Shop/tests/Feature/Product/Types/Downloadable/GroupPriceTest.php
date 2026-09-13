<?php

it('should apply a fixed customer group price to a downloadable product', function (array $priceGroups, ?int $customerGroupId) {
    $product = $this->createDownloadableProduct(['price' => ['float_value' => 1000]], [0]);

    $this->setCustomerGroupPrice($product, $priceGroups, 'fixed', 700);

    $this->actAsCustomerGroup($customerGroupId);

    $response = $this->addDownloadableProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, 700);
})->with('customer groups');

it('should apply a percentage customer group discount to a downloadable product', function (array $priceGroups, ?int $customerGroupId) {
    $product = $this->createDownloadableProduct(['price' => ['float_value' => 1000]], [0]);

    $this->setCustomerGroupPrice($product, $priceGroups, 'discount', 20);

    $this->actAsCustomerGroup($customerGroupId);

    $response = $this->addDownloadableProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, 800);
})->with('customer groups');

it('should not apply a customer group price set for another group to a downloadable product', function () {
    $product = $this->createDownloadableProduct(['price' => ['float_value' => 1000]], [0]);

    $this->setCustomerGroupPrice($product, 3, 'fixed', 700);

    $this->actAsCustomerGroup(2);

    $response = $this->addDownloadableProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, 1000);
});
