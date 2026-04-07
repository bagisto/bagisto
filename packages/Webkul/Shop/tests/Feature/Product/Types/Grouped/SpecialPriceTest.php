<?php

// ============================================================================
// Active Special Price
// ============================================================================

it('should apply special price to grouped associated product when within date range', function () {
    $product = $this->createGroupedProduct([1000, 500]);

    $associatedProduct = $product->grouped_products->first()->associated_product;

    $this->setSpecialPriceOnProduct($associatedProduct, 700, now()->subDay()->format('Y-m-d'), now()->addMonth()->format('Y-m-d'));

    $qty = [];
    foreach ($product->grouped_products as $gp) {
        $qty[$gp->associated_product_id] = 1;
    }

    $response = $this->addProductToCart($product->id, 1, ['qty' => $qty])->assertOk();

    $this->assertCartItemPrice($response, 700, 0);
});

it('should use regular price when grouped associated product special price has expired', function () {
    $product = $this->createGroupedProduct([1000, 500]);

    $associatedProduct = $product->grouped_products->first()->associated_product;

    $this->setSpecialPriceOnProduct($associatedProduct, 700, now()->subMonth()->format('Y-m-d'), now()->subDay()->format('Y-m-d'));

    $qty = [];
    foreach ($product->grouped_products as $gp) {
        $qty[$gp->associated_product_id] = 1;
    }

    $response = $this->addProductToCart($product->id, 1, ['qty' => $qty])->assertOk();

    $this->assertCartItemPrice($response, 1000, 0);
});

it('should use regular price when grouped associated product special price has not started', function () {
    $product = $this->createGroupedProduct([1000, 500]);

    $associatedProduct = $product->grouped_products->first()->associated_product;

    $this->setSpecialPriceOnProduct($associatedProduct, 700, now()->addDay()->format('Y-m-d'), now()->addMonth()->format('Y-m-d'));

    $qty = [];
    foreach ($product->grouped_products as $gp) {
        $qty[$gp->associated_product_id] = 1;
    }

    $response = $this->addProductToCart($product->id, 1, ['qty' => $qty])->assertOk();

    $this->assertCartItemPrice($response, 1000, 0);
});

it('should apply special price to grouped associated product when no date range is set', function () {
    $product = $this->createGroupedProduct([1000, 500]);

    $associatedProduct = $product->grouped_products->first()->associated_product;

    $this->setSpecialPriceOnProduct($associatedProduct, 750);

    $qty = [];
    foreach ($product->grouped_products as $gp) {
        $qty[$gp->associated_product_id] = 1;
    }

    $response = $this->addProductToCart($product->id, 1, ['qty' => $qty])->assertOk();

    $this->assertCartItemPrice($response, 750, 0);
});
