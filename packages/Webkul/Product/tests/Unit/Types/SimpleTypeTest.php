<?php

use Illuminate\Support\Facades\Event;
use Webkul\Attribute\Models\Attribute;
use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Models\Cart as CartModel;
use Webkul\Checkout\Models\CartItem;
use Webkul\Product\Exceptions\InsufficientProductInventoryException;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductCustomerGroupPrice;

/**
 * Change the price of a product and reindex it.
 */
function repriceProduct(Product $product, float $price): Product
{
    $product->attribute_values()
        ->where('attribute_id', Attribute::query()->where('code', 'price')->value('id'))
        ->update(['float_value' => $price]);

    Event::dispatch('catalog.product.update.after', $product);

    return $product->refresh();
}

// ============================================================================
// Saleability
// ============================================================================

it('should be for sale while active and in stock', function () {
    $product = $this->setProductStock($this->createSimpleProduct(), 5);

    expect($product->getTypeInstance()->isSaleable())->toBeTrue();
});

it('should not be for sale once out of stock', function () {
    $product = $this->setProductStock($this->createSimpleProduct(), 0);

    expect($product->getTypeInstance()->isSaleable())->toBeFalse();
});

it('should be for sale out of stock when back orders are allowed', function () {
    $this->setConfig('catalog.inventory.stock_options.back_orders', '1');

    $product = $this->setProductStock($this->createSimpleProduct(), 0);

    expect($product->getTypeInstance()->isSaleable())->toBeTrue();
});

it('should not be for sale while inactive', function () {
    $product = $this->createSimpleProduct([
        'status' => ['boolean_value' => false, 'channel' => core()->getCurrentChannelCode()],
    ]);

    expect($product->getTypeInstance()->isSaleable())->toBeFalse();
});

it('should have enough of any quantity when its stock is not managed', function () {
    $product = $this->createSimpleProduct([
        'manage_stock' => ['boolean_value' => false, 'channel' => core()->getCurrentChannelCode()],
    ]);

    $this->setProductStock($product, 0);

    expect($product->getTypeInstance()->haveSufficientQuantity(1000))->toBeTrue();
});

it('should only be available on the channels it is assigned to', function () {
    $product = $this->createSimpleProduct();

    expect($product->isAvailableInChannel())->toBeTrue()
        ->and($product->isAvailableInChannel(core()->getCurrentChannel()->id + 1000))->toBeFalse();
});

// ============================================================================
// Cart Lines
// ============================================================================

it('should price a cart line from the final price and the quantity', function () {
    $product = $this->setProductStock($this->createSimpleProduct(['price' => ['float_value' => 100]]), 10);

    Cart::setCart(CartModel::factory()->create());

    [$line] = $product->getTypeInstance()->prepareForCart([
        'product_id' => $product->id,
        'quantity' => 3,
    ]);

    expect($line)
        ->product_id->toBe($product->id)
        ->sku->toBe($product->sku)
        ->type->toBe('simple')
        ->quantity->toBe(3)
        ->and((float) $line['base_price'])->toBePrice(100)
        ->and((float) $line['base_total'])->toBePrice(300);
});

it('should refuse a cart line beyond the stock', function () {
    $product = $this->setProductStock($this->createSimpleProduct(), 2);

    Cart::setCart(CartModel::factory()->create());

    expect(fn () => $product->getTypeInstance()->prepareForCart(['product_id' => $product->id, 'quantity' => 5]))
        ->toThrow(InsufficientProductInventoryException::class);
});

it('should treat a missing quantity as one', function () {
    $product = $this->createSimpleProduct();

    expect($product->getTypeInstance()->handleQuantity(0))->toBe(1)
        ->and($product->getTypeInstance()->handleQuantity(4))->toBe(4);
});

it('should recognise a cart line for the same product with the same customizable options', function () {
    $product = $this->createSimpleProduct();

    $type = $product->getTypeInstance();

    expect($type->compareOptions(['product_id' => $product->id], ['product_id' => $product->id]))->toBeTrue()
        ->and($type->compareOptions(['product_id' => $product->id], ['product_id' => $product->id + 1]))->toBeFalse()
        ->and($type->compareOptions(['product_id' => $product->id, 'customizable_options' => [1 => 'red']], ['product_id' => $product->id, 'customizable_options' => [1 => 'red']]))->toBeTrue()
        ->and($type->compareOptions(['product_id' => $product->id, 'customizable_options' => [1 => 'red']], ['product_id' => $product->id, 'customizable_options' => [1 => 'blue']]))->toBeFalse()
        ->and($type->compareOptions(['product_id' => $product->id, 'customizable_options' => [1 => 'red']], ['product_id' => $product->id]))->toBeFalse();
});

it('should reprice a cart item once the price of its product changes', function () {
    $product = $this->setProductStock($this->createSimpleProduct(['price' => ['float_value' => 100]]), 10);

    $cart = CartModel::factory()->create();

    $item = CartItem::factory()->create([
        'cart_id' => $cart->id,
        'product_id' => $product->id,
        'sku' => $product->sku,
        'name' => $product->name,
        'type' => 'simple',
        'quantity' => 2,
        'price' => 100,
        'base_price' => 100,
        'price_incl_tax' => 100,
        'base_price_incl_tax' => 100,
        'total' => 200,
        'base_total' => 200,
        'total_incl_tax' => 200,
        'base_total_incl_tax' => 200,
        'additional' => ['product_id' => $product->id, 'quantity' => 2],
    ]);

    $product = repriceProduct($product, 80);

    $result = $product->getTypeInstance()->validateCartItem($item);

    expect($result->isItemInactive())->toBeFalse()
        ->and((float) $item->refresh()->base_price)->toBePrice(80)
        ->and((float) $item->base_total)->toBePrice(160);
});

it('should flag a cart item whose product went inactive', function () {
    $product = $this->createSimpleProduct();

    $cart = CartModel::factory()->create();

    $item = CartItem::factory()->adjustProduct()->create([
        'cart_id' => $cart->id,
        'product_id' => $product->id,
        'sku' => $product->sku,
        'name' => $product->name,
        'type' => 'simple',
        'quantity' => 1,
        'additional' => ['product_id' => $product->id, 'quantity' => 1],
    ]);

    $product->attribute_values()
        ->where('attribute_id', Attribute::query()->where('code', 'status')->value('id'))
        ->update(['boolean_value' => false]);

    expect($product->refresh()->getTypeInstance()->validateCartItem($item->refresh())->isItemInactive())->toBeTrue();
});

// ============================================================================
// Customer Group Prices
// ============================================================================

it('should apply the customer group price of the quantity tier reached', function () {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 100]]);

    ProductCustomerGroupPrice::factory()->create([
        'product_id' => $product->id,
        'customer_group_id' => core()->getGuestCustomerGroup()->id,
        'qty' => 5,
        'value_type' => 'fixed',
        'value' => 80,
    ]);

    $type = $product->getTypeInstance();

    expect((float) $type->getCustomerGroupPrice($product, 1))->toBePrice(100)
        ->and((float) $type->getCustomerGroupPrice($product, 5))->toBePrice(80)
        ->and((float) $type->getCustomerGroupPrice($product, 9))->toBePrice(80);
});

it('should apply a percentage customer group discount', function () {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 200]]);

    ProductCustomerGroupPrice::factory()->create([
        'product_id' => $product->id,
        'customer_group_id' => core()->getGuestCustomerGroup()->id,
        'qty' => 1,
        'value_type' => 'discount',
        'value' => 25,
    ]);

    expect((float) $product->getTypeInstance()->getCustomerGroupPrice($product, 1))->toBePrice(150);
});
