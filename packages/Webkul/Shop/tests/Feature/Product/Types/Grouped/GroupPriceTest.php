<?php

it('should apply a fixed customer group price to an associated product of a grouped product', function (array $priceGroups, ?int $customerGroupId) {
    $product = $this->createGroupedProduct([1000, 500]);

    $this->setCustomerGroupPrice($product->grouped_products->first()->associated_product, $priceGroups, 'fixed', 700);

    $this->actAsCustomerGroup($customerGroupId);

    $response = $this->addGroupedProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, 700, 0)
        ->assertCartItemPrice($response, 500, 1);
})->with('customer groups');

it('should apply a percentage customer group discount to an associated product of a grouped product', function (array $priceGroups, ?int $customerGroupId) {
    $product = $this->createGroupedProduct([1000, 500]);

    $this->setCustomerGroupPrice($product->grouped_products->first()->associated_product, $priceGroups, 'discount', 20);

    $this->actAsCustomerGroup($customerGroupId);

    $response = $this->addGroupedProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, 800, 0)
        ->assertCartItemPrice($response, 500, 1);
})->with('customer groups');

it('should not apply a customer group price set for another group to an associated product of a grouped product', function () {
    $product = $this->createGroupedProduct([1000, 500]);

    $this->setCustomerGroupPrice($product->grouped_products->first()->associated_product, 3, 'fixed', 700);

    $this->actAsCustomerGroup(2);

    $response = $this->addGroupedProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, 1000, 0);
});
