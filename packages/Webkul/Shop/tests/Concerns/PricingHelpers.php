<?php

namespace Webkul\Shop\Tests\Concerns;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Webkul\CartRule\Models\CartRule;
use Webkul\CartRule\Models\CartRuleCoupon;
use Webkul\CatalogRule\Models\CatalogRule;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductCustomerGroupPrice;

trait PricingHelpers
{
    /**
     * Set a special price on a product and re-index.
     *
     * Used for composite types (configurable variants, grouped associated
     * products, bundle option products) where the special price must be
     * set on the child product after creation.
     */
    public function setSpecialPriceOnProduct(Product $product, float $price, ?string $from = null, ?string $to = null): void
    {
        $attributes = $this->getAttributeMap();

        $product->attribute_values()
            ->where('attribute_id', $attributes['special_price']->id)
            ->update(['float_value' => $price]);

        if ($from !== null) {
            $product->attribute_values()
                ->where('attribute_id', $attributes['special_price_from']->id)
                ->update(['date_value' => $from]);
        }

        if ($to !== null) {
            $product->attribute_values()
                ->where('attribute_id', $attributes['special_price_to']->id)
                ->update(['date_value' => $to]);
        }

        Event::dispatch('catalog.product.update.after', $product);
    }

    /**
     * Set a customer group price on a product and re-index.
     */
    public function setCustomerGroupPrice(Product $product, int $customerGroupId, string $valueType, float $value, int $qty = 1): void
    {
        ProductCustomerGroupPrice::factory()->create([
            'qty' => $qty,
            'value_type' => $valueType,
            'value' => $value,
            'product_id' => $product->id,
            'customer_group_id' => $customerGroupId,
        ]);

        Event::dispatch('catalog.product.update.after', $product);
    }

    /**
     * Create and apply a catalog rule with index.
     */
    public function createCatalogRuleForPricing(array $overrides = [], array $customerGroups = [1, 2, 3]): CatalogRule
    {
        $channelId = core()->getCurrentChannel()->id;

        return CatalogRule::factory()
            ->withIndex([$channelId], $customerGroups)
            ->create(array_merge([
                'name' => 'test-catalog-rule-'.Str::uuid(),
                'status' => 1,
                'action_type' => 'by_percent',
                'discount_amount' => 20,
            ], $overrides));
    }

    /**
     * Create a cart rule with channel and customer group sync.
     */
    public function createCartRuleForPricing(array $overrides = [], array $customerGroups = [1, 2, 3]): CartRule
    {
        $channelId = core()->getCurrentChannel()->id;

        return CartRule::factory()->afterCreating(function (CartRule $rule) use ($customerGroups, $channelId) {
            $rule->cart_rule_customer_groups()->sync($customerGroups);
            $rule->cart_rule_channels()->sync([$channelId]);
        })->create(array_merge([
            'name' => 'test-rule-'.Str::uuid(),
            'status' => 1,
            'action_type' => 'by_fixed',
            'discount_amount' => 50,
            'coupon_type' => 0,
        ], $overrides));
    }

    /**
     * Create a cart rule with a specific coupon code.
     */
    public function createCouponCartRule(string $code, array $overrides = [], array $customerGroups = [1, 2, 3]): CartRule
    {
        $cartRule = $this->createCartRuleForPricing(array_merge([
            'coupon_type' => 1,
            'use_auto_generation' => 0,
        ], $overrides), $customerGroups);

        CartRuleCoupon::factory()->create([
            'cart_rule_id' => $cartRule->id,
            'code' => $code,
        ]);

        return $cartRule;
    }
}
