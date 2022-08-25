<?php

namespace Tests\API\V1\Shop\Customer;

use ApiTester;

class CheckoutCest extends CustomerCest
{
    public function testForPlacingOrder(ApiTester $I)
    {
        $customer = $I->amSanctumAuthenticatedCustomer();

        $token = $I->amCreatingTokenForSanctumAuthenticatedCustomer($customer);

        $I->haveAllNecessaryHeaders($token);

        $this->generateCartForCustomer($I, $customer);

        $this->saveAddress($I);

        $this->saveShippingMethod($I);

        $this->savePaymentMethod($I);

        $this->saveOrder($I);
    }

    private function generateCartForCustomer(ApiTester $I, $customer)
    {
        $product = $I->haveSimpleProduct();

        $cart = $I->haveCart([
            'customer_id' => $customer->id,
        ]);

        $cartItem = $I->haveCartItems([
            'cart_id'    => $cart->id,
            'product_id' => $product->id,
        ]);
    }

    private function saveAddress(ApiTester $I)
    {
        $fields = $I->cleanAllFields([
            'first_name'   => $I->fake()->firstName,
            'last_name'    => $I->fake()->lastName,
            'email'        => $I->fake()->safeEmail(),
            'address1'     => [$I->fake()->streetAddress],
            'company_name' => $I->fake()->company,
            'country'      => $I->fake()->countryCode,
            'state'        => $I->fake()->word,
            'city'         => $I->fake()->city,
            'postcode'     => $I->fake()->postcode,
            'phone'        => $I->fake()->phoneNumber,
        ]);

        $I->sendPost($this->getVersionRoute('customer/checkout/save-address'), [
            'billing' => array_merge($fields, ['use_for_shipping' => false]),

            'shipping' => $fields,
        ]);

        $I->seeAllNecessarySuccessResponse();
    }

    private function saveShippingMethod(ApiTester $I)
    {
        $I->sendPost($this->getVersionRoute('customer/checkout/save-shipping'), [
            'shipping_method' => 'flatrate_flatrate',
        ]);

        $I->seeAllNecessarySuccessResponse();
    }

    private function savePaymentMethod(ApiTester $I)
    {
        $I->sendPost($this->getVersionRoute('customer/checkout/save-payment'), [
            'payment' => [
                'method' => 'cashondelivery',
            ],
        ]);

        $I->seeAllNecessarySuccessResponse();
    }

    private function saveOrder(ApiTester $I)
    {
        $I->sendPost($this->getVersionRoute('customer/checkout/save-order'));

        $I->seeAllNecessarySuccessResponse();
    }
}
