<?php

namespace Webkul\Shop\Tests\Concerns;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Illuminate\Testing\TestResponse;
use Webkul\CartRule\Models\CartRule;
use Webkul\CatalogRule\Models\CatalogRule;
use Webkul\Customer\Contracts\Customer as CustomerContract;
use Webkul\Faker\Helpers\Customer as CustomerFaker;
use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductBundleOption;
use Webkul\Product\Models\ProductBundleOptionProduct;
use Webkul\Product\Models\ProductGroupedProduct;

trait ShopTestBench
{
    /**
     * Login as customer.
     */
    public function loginAsCustomer(?CustomerContract $customer = null): CustomerContract
    {
        $customer = $customer ?? (new CustomerFaker)->factory()->create();

        $this->actingAs($customer);

        return $customer;
    }

    /**
     * Create a sellable simple product, with the attribute values given replacing the generated ones.
     */
    public function createSimpleProduct(array $attributeValues = []): Product
    {
        return (new ProductFaker(['attribute_value' => $attributeValues]))
            ->getSimpleProductFactory()
            ->create()
            ->fresh();
    }

    /**
     * Create a configurable product with one simple variant per price given.
     */
    public function createConfigurableProduct(array $variantPrices = [100, 200]): Product
    {
        $parent = Product::factory()->configurable()->create();

        foreach ($variantPrices as $price) {
            $this->createSimpleProduct(['price' => ['float_value' => $price]])
                ->update(['parent_id' => $parent->id]);
        }

        Event::dispatch('catalog.product.update.after', $parent);

        return $parent->fresh()->load('variants');
    }

    /**
     * Create a bundle product with a required select option holding one product per price given.
     */
    public function createBundleProduct(array $optionProductPrices = [100, 200]): Product
    {
        $parent = Product::factory()->bundle()->create();

        $option = ProductBundleOption::factory()->create([
            'product_id' => $parent->id,
            'type' => 'select',
            'is_required' => 1,
            'sort_order' => 0,
            'label' => 'Select Option',
        ]);

        foreach (array_values($optionProductPrices) as $sortOrder => $price) {
            ProductBundleOptionProduct::factory()->create([
                'product_bundle_option_id' => $option->id,
                'product_id' => $this->createSimpleProduct(['price' => ['float_value' => $price]])->id,
                'qty' => 1,
                'sort_order' => $sortOrder,
                'is_default' => $sortOrder === 0 ? 1 : 0,
            ]);
        }

        Event::dispatch('catalog.product.update.after', $parent);

        return $parent->fresh()->load('bundle_options.bundle_option_products');
    }

    /**
     * Create a grouped product associating one product per price given.
     */
    public function createGroupedProduct(array $associatedPrices = [100, 200]): Product
    {
        $parent = Product::factory()->grouped()->create();

        foreach (array_values($associatedPrices) as $sortOrder => $price) {
            ProductGroupedProduct::factory()->create([
                'product_id' => $parent->id,
                'associated_product_id' => $this->createSimpleProduct(['price' => ['float_value' => $price]])->id,
                'qty' => 1,
                'sort_order' => $sortOrder,
            ]);
        }

        Event::dispatch('catalog.product.update.after', $parent);

        return $parent->fresh()->load('grouped_products');
    }

    /**
     * Create an active catalog rule for the current channel and the customer groups given, then index it.
     */
    public function createCatalogRuleForPricing(array $attributes = [], array $customerGroups = [1, 2, 3]): CatalogRule
    {
        return CatalogRule::factory()
            ->afterCreating(function (CatalogRule $catalogRule) use ($customerGroups) {
                $catalogRule->channels()->sync([core()->getCurrentChannel()->id]);

                $catalogRule->customer_groups()->sync($customerGroups);

                Event::dispatch('promotions.catalog_rule.update.after', $catalogRule);
            })
            ->create(array_merge([
                'name' => 'test-catalog-rule-'.Str::uuid(),
                'status' => 1,
                'starts_from' => null,
                'ends_till' => null,
                'action_type' => 'by_percent',
                'discount_amount' => 20,
            ], $attributes));
    }

    /**
     * Create an active cart rule without a coupon for the current channel and the customer groups given.
     */
    public function createCartRuleForPricing(array $attributes = [], array $customerGroups = [1, 2, 3]): CartRule
    {
        return CartRule::factory()
            ->afterCreating(function (CartRule $cartRule) use ($customerGroups) {
                $cartRule->cart_rule_customer_groups()->sync($customerGroups);

                $cartRule->cart_rule_channels()->sync([core()->getCurrentChannel()->id]);
            })
            ->create(array_merge([
                'name' => 'test-rule-'.Str::uuid(),
                'status' => 1,
                'action_type' => 'by_fixed',
                'discount_amount' => 50,
                'coupon_type' => 0,
            ], $attributes));
    }

    /**
     * Add the first variant of a configurable product to the cart.
     */
    public function addConfigurableProductToCart(Product $product, int $quantity = 1): TestResponse
    {
        return $this->postJson(route('shop.api.checkout.cart.store'), [
            'product_id' => $product->id,
            'selected_configurable_option' => $product->loadMissing('variants')->variants->firstOrFail()->id,
            'quantity' => $quantity,
            'is_buy_now' => 0,
        ]);
    }

    /**
     * Assert the discount a cart response carries.
     */
    public function assertCartDiscount(TestResponse $response, float $expectedDiscount): static
    {
        $this->assertPrice($expectedDiscount, $response->json('data.discount_amount'));

        return $this;
    }
}
