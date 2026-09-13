<?php

it('should charge a configurable variant at its special price only while the special price is in force', function (?int $fromInDays, ?int $toInDays, float $expectedPrice) {
    $product = $this->createConfigurableProduct([1000]);

    $this->setSpecialPriceInForce($product->variants->first(), 800, $fromInDays, $toInDays);

    $response = $this->addConfigurableProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, $expectedPrice);
})->with('special price windows');

it('should list a configurable product at the special price of its cheapest variant', function () {
    $product = $this->createConfigurableProduct([1000, 1200]);

    $this->setSpecialPriceOnProduct($product->variants->first(), 750);

    expect($this->listedPrice($product))->toBePrice(750);
});
