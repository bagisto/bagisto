<?php

it('should charge a downloadable product at its special price only while the special price is in force', function (?int $fromInDays, ?int $toInDays, float $expectedPrice) {
    $product = $this->createDownloadableProduct(['price' => ['float_value' => 1000]], [0]);

    $this->setSpecialPriceInForce($product, 800, $fromInDays, $toInDays);

    $response = $this->addDownloadableProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, $expectedPrice);
})->with('special price windows');
