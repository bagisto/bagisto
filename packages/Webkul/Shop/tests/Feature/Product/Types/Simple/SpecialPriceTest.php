<?php

it('should charge a simple product at its special price only while the special price is in force', function (?int $fromInDays, ?int $toInDays, float $expectedPrice) {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 1000]]);

    $this->setSpecialPriceInForce($product, 800, $fromInDays, $toInDays);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartItemPrice($response, $expectedPrice);
})->with('special price windows');

it('should list a simple product at its special price as soon as it is set', function () {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 1000]]);

    $this->setSpecialPriceOnProduct($product, 750);

    expect($this->listedPrice($product))->toBePrice(750);
});
