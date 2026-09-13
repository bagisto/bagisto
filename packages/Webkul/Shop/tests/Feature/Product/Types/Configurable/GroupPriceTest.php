<?php

it('should apply a fixed customer group price to a configurable variant', function (array $priceGroups, ?int $customerGroupId) {
    $product = $this->createConfigurableProduct([1000]);

    $this->setCustomerGroupPrice($product->variants->first(), $priceGroups, 'fixed', 700);

    $this->actAsCustomerGroup($customerGroupId);

    $response = $this->addConfigurableProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, 700);
})->with('customer groups');

it('should apply a percentage customer group discount to a configurable variant', function (array $priceGroups, ?int $customerGroupId) {
    $product = $this->createConfigurableProduct([1000]);

    $this->setCustomerGroupPrice($product->variants->first(), $priceGroups, 'discount', 20);

    $this->actAsCustomerGroup($customerGroupId);

    $response = $this->addConfigurableProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, 800);
})->with('customer groups');

it('should not apply a customer group price set for another group to a configurable variant', function () {
    $product = $this->createConfigurableProduct([1000]);

    $this->setCustomerGroupPrice($product->variants->first(), 3, 'fixed', 700);

    $this->actAsCustomerGroup(2);

    $response = $this->addConfigurableProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, 1000);
});
