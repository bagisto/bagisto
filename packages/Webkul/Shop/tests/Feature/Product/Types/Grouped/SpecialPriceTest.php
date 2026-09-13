<?php

it('should charge an associated product of a grouped product at its special price only while the special price is in force', function (?int $fromInDays, ?int $toInDays, float $expectedPrice) {
    $product = $this->createGroupedProduct([1000, 500]);

    $this->setSpecialPriceInForce($product->grouped_products->first()->associated_product, 800, $fromInDays, $toInDays);

    $response = $this->addGroupedProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, $expectedPrice, 0)
        ->assertCartItemPrice($response, 500, 1);
})->with('special price windows');

it('should list a grouped product at the lowest special price among its associated products', function () {
    $product = $this->createGroupedProduct([1000, 500]);

    $this->setSpecialPriceOnProduct($product->grouped_products->last()->associated_product, 350);

    expect($this->listedPrice($product))->toBePrice(350);
});
