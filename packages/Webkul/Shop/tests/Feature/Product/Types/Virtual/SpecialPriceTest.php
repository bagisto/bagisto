<?php

it('should charge a virtual product at its special price only while the special price is in force', function (?int $fromInDays, ?int $toInDays, float $expectedPrice) {
    $product = $this->createVirtualProduct(['price' => ['float_value' => 1000]]);

    $this->setSpecialPriceInForce($product, 800, $fromInDays, $toInDays);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartItemPrice($response, $expectedPrice);
})->with('special price windows');
