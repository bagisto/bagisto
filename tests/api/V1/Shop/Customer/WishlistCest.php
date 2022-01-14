<?php

namespace Tests\API\V1\Shop\Customer;

use ApiTester;
use Webkul\Customer\Models\Wishlist;

class WishlistCest extends CustomerCest
{
    public function testForFetchingAllTheCustomerWishlists(ApiTester $I)
    {
        $customer = $I->amSanctumAuthenticatedCustomer();

        $product = $I->haveSimpleProduct();

        $wishlist = $I->have(Wishlist::class, [
            'channel_id'  => core()->getCurrentChannel()->id,
            'customer_id' => $customer->id,
            'product_id'  => $product->id,
        ]);

        $I->sendGet($this->getVersionRoute('customer/wishlist'));

        $I->seeAllNecessarySuccessResponse();
    }
}
