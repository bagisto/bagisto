<?php

use Webkul\Product\Models\ProductAttributeValue;
use Webkul\Product\Models\ProductFlat;

use Webkul\CartRule\Models\CartRule;
use Haendlerbund\SalesforceApi\Models\HbCartRule;

class CartRuleCest
{

    public function testCartRuleCreation(FunctionalTester $I)
    {
        $I->loginAsAdmin();

        $I->amOnAdminRoute('admin.cart-rules.index');

        // we are dealing with vue.js so we can not do classical form filling
        $I->sendAjaxPostRequest(route('admin.cart-rules.store'), [
            '_token' => csrf_token(),
            'name' => 'Demo Cart Rule',

            // the following fields are important to send with the POST request:
            'starts_from'           => '',
            'ends_till'             => '',

            'use_auto_generation' => 0,
            'coupon_type'         => 0, // no coupon
            'action_type'         => 'by_percent',
            'coupon_code'         => 'coupon',
            'discount_amount'     => '10',

            'channels' => [
                'default',
            ],

            'customer_groups' => [
                'guest',
            ],
        ]);

        $cartRule = $I->grabRecord(CartRule::class, [
            'name' => 'Demo Cart Rule',
        ]);

        $I->seeResponseCodeIsSuccessful();

    }
}
