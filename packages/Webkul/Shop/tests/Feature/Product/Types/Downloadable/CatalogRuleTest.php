<?php

it('should apply a percentage catalog rule to a downloadable product', function (array $ruleGroups, ?int $customerGroupId) {
    $product = $this->createDownloadableProduct(['price' => ['float_value' => 1000]], [0]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 20], $ruleGroups);

    $this->actAsCustomerGroup($customerGroupId);

    $response = $this->addDownloadableProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, 800);
})->with('customer groups');

it('should apply a fixed catalog rule to a downloadable product', function (array $ruleGroups, ?int $customerGroupId) {
    $product = $this->createDownloadableProduct(['price' => ['float_value' => 1000]], [0]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_fixed', 'discount_amount' => 150], $ruleGroups);

    $this->actAsCustomerGroup($customerGroupId);

    $response = $this->addDownloadableProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, 850);
})->with('customer groups');

it('should not apply a catalog rule limited to another customer group to a downloadable product', function () {
    $product = $this->createDownloadableProduct(['price' => ['float_value' => 1000]], [0]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 20], [3]);

    $this->actAsCustomerGroup(2);

    $response = $this->addDownloadableProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, 1000);
});

it('should list a downloadable product at the catalog rule price as soon as the rule is saved', function () {
    $product = $this->createDownloadableProduct(['price' => ['float_value' => 1000]], [0]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 20]);

    expect($this->listedPrice($product))->toBePrice(800);
});
