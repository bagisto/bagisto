<?php

namespace Tests\Functional\CartRule;

use FunctionalTester;
use Illuminate\Support\Facades\DB;
use Webkul\CartRule\Models\CartRule;

class CartRuleCopyCest
{
    public function testCartRuleCopy(FunctionalTester $I)
    {
        $I->loginAsAdmin();

        $original = $I->have(CartRule::class, [
            'status' => 1,
        ]);

        DB::table('cart_rule_channels')->insert([
            'cart_rule_id' => $original->id,
            'channel_id'   => 1,
        ]);

        DB::table('cart_rule_customer_groups')->insert([
            'cart_rule_id'      => $original->id,
            'customer_group_id' => 1,
        ]);

        $count = count(CartRule::all());

        $I->amOnAdminRoute('admin.cart-rules.copy', ['id' => $original->id]);

        $I->seeRecord(CartRule::class, [
            'id'     => $original->id + 1,
            'status' => 0,
            'name'   => 'Copy of ' . $original->name,
        ]);

        $I->assertCount($count + 1, CartRule::all());

        $I->assertEquals(
            DB::table('cart_rule_channels')
                ->pluck('cart_rule_id', 'channel_id')
                ->toArray(),
            [1 => $original->id + 1]
        );

        $I->assertEquals(
            DB::table('cart_rule_customer_groups')
                ->pluck('cart_rule_id', 'customer_group_id')
                ->toArray(),
            [1 => $original->id + 1]
        );

        $I->seeResponseCodeIsSuccessful();

        $I->seeCurrentRouteIs('admin.cart-rules.copy');
    }
}
