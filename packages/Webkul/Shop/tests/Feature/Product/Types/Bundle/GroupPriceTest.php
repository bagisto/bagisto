<?php

it('should apply a fixed customer group price to an option product of a bundle product', function (array $priceGroups, ?int $customerGroupId) {
    $product = $this->createBundleProduct([1000]);

    $this->setCustomerGroupPrice($product->bundle_options->first()->bundle_option_products->first()->product, $priceGroups, 'fixed', 700);

    $this->actAsCustomerGroup($customerGroupId);

    $response = $this->addBundleProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, 700);
})->with('customer groups');

it('should apply a percentage customer group discount to an option product of a bundle product', function (array $priceGroups, ?int $customerGroupId) {
    $product = $this->createBundleProduct([1000]);

    $this->setCustomerGroupPrice($product->bundle_options->first()->bundle_option_products->first()->product, $priceGroups, 'discount', 20);

    $this->actAsCustomerGroup($customerGroupId);

    $response = $this->addBundleProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, 800);
})->with('customer groups');

it('should not apply a customer group price set for another group to an option product of a bundle product', function () {
    $product = $this->createBundleProduct([1000]);

    $this->setCustomerGroupPrice($product->bundle_options->first()->bundle_option_products->first()->product, 3, 'fixed', 700);

    $this->actAsCustomerGroup(2);

    $response = $this->addBundleProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, 1000);
});
