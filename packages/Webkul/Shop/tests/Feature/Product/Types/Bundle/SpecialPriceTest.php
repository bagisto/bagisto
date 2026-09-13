<?php

it('should charge an option product of a bundle product at its special price only while the special price is in force', function (?int $fromInDays, ?int $toInDays, float $expectedPrice) {
    $product = $this->createBundleProduct([1000]);

    $this->setSpecialPriceInForce($product->bundle_options->first()->bundle_option_products->first()->product, 800, $fromInDays, $toInDays);

    $response = $this->addBundleProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, $expectedPrice);
})->with('special price windows');

it('should list a bundle product at the special price of its cheapest option product', function () {
    $product = $this->createBundleProduct([1000, 1200]);

    $this->setSpecialPriceOnProduct($product->bundle_options->first()->bundle_option_products->first()->product, 750);

    expect($this->listedPrice($product))->toBePrice(750);
});
