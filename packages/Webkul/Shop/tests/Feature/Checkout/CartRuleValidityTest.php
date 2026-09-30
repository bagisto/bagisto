<?php

use Webkul\CartRule\Helpers\CartRule as CartRuleHelper;
use Webkul\CartRule\Models\CartRule;

/**
 * Create an active cart rule with the given validity window, for the default channel and every
 * customer group.
 */
function createCartRuleValidBetween(?string $startsFrom, ?string $endsTill): CartRule
{
    $cartRule = CartRule::factory()->create([
        'starts_from' => $startsFrom,
        'ends_till' => $endsTill,
        'status' => 1,
    ]);

    $cartRule->cart_rule_channels()->attach([core()->getCurrentChannel()->id]);

    $cartRule->cart_rule_customer_groups()->attach([1, 2, 3]);

    return $cartRule;
}

/**
 * The ids of the cart rules that apply right now.
 */
function applicableCartRuleIds(): array
{
    return app(CartRuleHelper::class)
        ->getCartRuleQuery()
        ->pluck('cart_rules.id')
        ->all();
}

it('should leave out a cart rule that ended earlier in the current hour', function () {
    $this->travelTo('2026-01-15 10:45:00');

    $cartRule = createCartRuleValidBetween('2026-01-15 09:00:00', '2026-01-15 10:30:00');

    expect(applicableCartRuleIds())->not->toContain($cartRule->id);
});

it('should leave out a cart rule that starts later in the current hour', function () {
    $this->travelTo('2026-11-15 10:05:00');

    $cartRule = createCartRuleValidBetween('2026-11-15 10:10:00', null);

    expect(applicableCartRuleIds())->not->toContain($cartRule->id);
});

it('should apply a cart rule within its start and end times', function () {
    $this->travelTo('2026-01-15 10:45:00');

    $cartRule = createCartRuleValidBetween('2026-01-15 10:40:00', '2026-01-15 10:50:00');

    expect(applicableCartRuleIds())->toContain($cartRule->id);
});
