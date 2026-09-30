<?php

use Webkul\CartRule\Helpers\CartRule as CartRuleHelper;

/**
 * The ids of the cart rules that apply at the current moment.
 */
function applicableCartRuleIds(): array
{
    return app(CartRuleHelper::class)
        ->getCartRuleQuery()
        ->pluck('cart_rules.id')
        ->all();
}

// ============================================================================
// Validity Window
// ============================================================================

it('should leave out a cart rule that ended earlier in the current hour', function () {
    $this->travelTo('2026-01-15 10:45:00');

    $cartRule = $this->createCartRuleForPricing([
        'starts_from' => '2026-01-15 09:00:00',
        'ends_till' => '2026-01-15 10:30:00',
    ]);

    expect(applicableCartRuleIds())->not->toContain($cartRule->id);
});

it('should leave out a cart rule that starts later in the current hour', function () {
    $this->travelTo('2026-11-15 10:05:00');

    $cartRule = $this->createCartRuleForPricing([
        'starts_from' => '2026-11-15 10:10:00',
        'ends_till' => null,
    ]);

    expect(applicableCartRuleIds())->not->toContain($cartRule->id);
});

it('should apply a cart rule within its start and end times', function () {
    $this->travelTo('2026-01-15 10:45:00');

    $cartRule = $this->createCartRuleForPricing([
        'starts_from' => '2026-01-15 10:40:00',
        'ends_till' => '2026-01-15 10:50:00',
    ]);

    expect(applicableCartRuleIds())->toContain($cartRule->id);
});
