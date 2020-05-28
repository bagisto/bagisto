<?php

namespace Tests\Functional\CartRule;

use FunctionalTester;
use Webkul\CartRule\Models\CartRule;

class CartRuleCopyCest
{

    public function testCartRuleCopy(FunctionalTester $I)
    {
        $I->loginAsAdmin();

        $original = $I->have(CartRule::class, [
            'status' => 1,
        ]);

        $count = count(cartRule::all());

        $I->amOnAdminRoute('admin.cart-rules.copy', ['id' => $original->id]);

        $I->seeRecord(CartRule::class, [
            'id'     => $original->id + 1,
            'status' => 0,
            'name'   => $original->name,
        ]);

        $I->assertCount($count + 1, CartRule::all());

        $I->seeResponseCodeIsSuccessful();

    }
}
