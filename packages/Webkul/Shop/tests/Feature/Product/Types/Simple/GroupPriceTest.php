<?php

it('should apply a fixed customer group price to a simple product', function (array $priceGroups, ?int $customerGroupId) {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 1000]]);

    $this->setCustomerGroupPrice($product, $priceGroups, 'fixed', 700);

    $this->actAsCustomerGroup($customerGroupId);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartItemPrice($response, 700);
})->with('customer groups');

it('should apply a percentage customer group discount to a simple product', function (array $priceGroups, ?int $customerGroupId) {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 1000]]);

    $this->setCustomerGroupPrice($product, $priceGroups, 'discount', 20);

    $this->actAsCustomerGroup($customerGroupId);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartItemPrice($response, 800);
})->with('customer groups');

it('should not apply a customer group price set for another group to a simple product', function () {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 1000]]);

    $this->setCustomerGroupPrice($product, 3, 'fixed', 700);

    $this->actAsCustomerGroup(2);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartItemPrice($response, 1000);
});
