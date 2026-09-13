<?php

it('should apply a percentage catalog rule to a virtual product', function (array $ruleGroups, ?int $customerGroupId) {
    $product = $this->createVirtualProduct(['price' => ['float_value' => 500]]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 20], $ruleGroups);

    $this->actAsCustomerGroup($customerGroupId);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartItemPrice($response, 400);
})->with('customer groups');

it('should apply a fixed catalog rule to a virtual product', function (array $ruleGroups, ?int $customerGroupId) {
    $product = $this->createVirtualProduct(['price' => ['float_value' => 500]]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_fixed', 'discount_amount' => 150], $ruleGroups);

    $this->actAsCustomerGroup($customerGroupId);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartItemPrice($response, 350);
})->with('customer groups');

it('should not apply a catalog rule limited to another customer group to a virtual product', function () {
    $product = $this->createVirtualProduct(['price' => ['float_value' => 500]]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 20], [3]);

    $this->actAsCustomerGroup(2);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartItemPrice($response, 500);
});

it('should list a virtual product at the catalog rule price as soon as the rule is saved', function () {
    $product = $this->createVirtualProduct(['price' => ['float_value' => 500]]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 20]);

    expect($this->listedPrice($product))->toBePrice(400);
});
