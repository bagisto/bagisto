<?php

namespace Tests\Unit\Checkout\Cart;

use Faker\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use UnitTester;
use Webkul\Core\Helpers\Laravel5Helper;

class CartCest
{
    private $faker;
    private $simpleProduct1;
    private $simpleProduct2;
    private $virtualProduct1;
    private $virtualProduct2;
    private $downloadableProduct1;
    private $downloadableProduct2;

    public function _before(UnitTester $I)
    {
        $this->faker = Factory::create();

        $this->sessionToken = $this->faker->uuid;
        session(['_token' => $this->sessionToken]);

        $this->simpleProduct1 = $I->haveProduct(Laravel5Helper::SIMPLE_PRODUCT);
        cart()->addProduct($this->simpleProduct1->id, [
            '_token'     => session('_token'),
            'product_id' => $this->simpleProduct1->id,
            'quantity'   => 1,
        ]);

        $this->simpleProduct2 = $I->haveProduct(Laravel5Helper::SIMPLE_PRODUCT);
        cart()->addProduct($this->simpleProduct2->id, [
            '_token'     => session('_token'),
            'product_id' => $this->simpleProduct2->id,
            'quantity'   => 1,
        ]);

        $this->virtualProduct1 = $I->haveProduct(Laravel5Helper::VIRTUAL_PRODUCT);
        cart()->addProduct($this->virtualProduct1->id, [
            '_token'     => session('_token'),
            'product_id' => $this->virtualProduct1->id,
            'quantity'   => 1,
        ]);

        $this->virtualProduct2 = $I->haveProduct(Laravel5Helper::VIRTUAL_PRODUCT);
        cart()->addProduct($this->virtualProduct2->id, [
            '_token'     => session('_token'),
            'product_id' => $this->virtualProduct2->id,
            'quantity'   => 1,
        ]);

        $this->downloadableProduct1 = $I->haveProduct(Laravel5Helper::DOWNLOADABLE_PRODUCT);

        $this->downloadableProduct2 = $I->haveProduct(Laravel5Helper::DOWNLOADABLE_PRODUCT);
    }

    public function testCartWithInactiveProducts(UnitTester $I)
    {
        $I->comment('sP1, sP2, vP1 and vP2 in cart');
        $I->assertEquals(4, count(cart()->getCart()->items));

        $I->comment('deactivate sP2');
        DB::table('product_attribute_values')
            ->where([
                'product_id'   => $this->simpleProduct2->id,
                'attribute_id' => 8 // status
            ])
            ->update(['boolean_value' => 0]);

        Event::dispatch('catalog.product.update.after', $this->simpleProduct2->refresh());

        $I->assertFalse(cart()->hasError());
        $I->comment('sP2 is inactive');
        $I->assertEquals(3, count(cart()->getCart()->items));

        $I->comment('add dP2 to cart');
        cart()->addProduct($this->downloadableProduct2->id, [
            '_token'     => session('_token'),
            'product_id' => $this->downloadableProduct2->id,
            'quantity'   => 1,
            'links'      => $this->downloadableProduct2->downloadable_links->pluck('id')->all(),
        ]);

        $I->assertEquals(4, count(cart()->getCart()->items));
        $I->assertFalse(cart()->hasError());

        $I->comment('deactivate dP2');
        DB::table('product_attribute_values')
            ->where([
                'product_id'   => $this->downloadableProduct2->id,
                'attribute_id' => 8 // status
            ])
            ->update(['boolean_value' => 0]);

        Event::dispatch('catalog.product.update.after', $this->downloadableProduct2->refresh());

        $I->comment('add dP1 to cart, dP2 should be removed now');
        cart()->addProduct($this->downloadableProduct1->id, [
            '_token'     => session('_token'),
            'product_id' => $this->downloadableProduct1->id,
            'quantity'   => 1,
            'links'      => $this->downloadableProduct1->downloadable_links->pluck('id')->all(),
        ]);

        $I->assertEquals(4, count(cart()->getCart()->items));

        $I->comment('deactivate vP2');
        DB::table('product_attribute_values')
            ->where([
                'product_id'   => $this->virtualProduct2->id,
                'attribute_id' => 8 // status
            ])
            ->update(['boolean_value' => 0]);

        Event::dispatch('catalog.product.update.after', $this->virtualProduct2->refresh());

        $I->comment('change quantity of vP1, vP2 should be removed now');
        $cartItemId = $this->getCartItemIdFromProduct($this->virtualProduct1->id);
        cart()->updateItems([
            'qty' => [
                $cartItemId => 5
            ],
        ]);
        $I->assertEquals(3, count(cart()->getCart()->items));

        $I->assertEquals(5, cart()->getCart()->items()->find($cartItemId)->quantity);
    }

    /**
     * @param int $productId
     *
     * @return int|null
     */
    private function getCartItemIdFromProduct(int $productId): ?int
    {
        foreach(cart()->getCart()->items as $item) {
            if ($item->product_id === $productId) {
                return $item->id;
            }
        }

        return null;
    }
}