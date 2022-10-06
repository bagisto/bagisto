<?php

namespace Webkul\Product\Helpers\PriceIndexer;

use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Product\Repositories\ProductCustomerGroupPriceRepository;
use Webkul\CatalogRule\Helpers\CatalogRuleProductPrice;

abstract class AbstractPriceIndex
{
    /**
     * Product instance.
     *
     * @var \Webkul\Product\Contracts\Product
     */
    protected $product;

    /**
     * Customer Group instance.
     *
     * @var \Webkul\Customer\Contracts\CustomerGroup
     */
    protected $customerGroup;

    /**
     * Create a new command instance.
     *
     * @param  \Webkul\Customer\Repositories\CustomerRepository  $customerRepository
     * @param  \Webkul\Product\Repositories\ProductCustomerGroupPriceRepository  $productCustomerGroupPriceRepository
     * @param  \Webkul\CatalogRule\Helpers\CatalogRuleProductPrice  $catalogRuleProductPriceHelper
     * @return void
     */
    public function __construct(
        protected CustomerRepository $customerRepository,
        protected ProductCustomerGroupPriceRepository $productCustomerGroupPriceRepository,
        protected CatalogRuleProductPrice $catalogRuleProductPriceHelper
    )
    {
    }

    /**
     * Set current product
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return \Webkul\Product\Helpers\ProductPriceIndex\AbstractPriceIndex
     */
    public function setProduct($product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Set customer group
     *
     * @param  \Webkul\Customer\Contracts\CustomerGroup  $customerGroup
     * @return \Webkul\Product\Helpers\ProductPriceIndex\AbstractPriceIndex
     */
    public function setCustomerGroup($customerGroup)
    {
        $this->customerGroup = $customerGroup;

        return $this;
    }

    /**
     * Returns product specific pricing for customer group
     *
     * @param  \Webkul\Customer\Contracts\CustomerGroup  $customerGroup
     * @return array
     */
    public function getIndices($customerGroup)
    {
        $this->customerGroup = $customerGroup;

        return [
            'min_price'         => ($minPrice = $this->getMinimalPrice()) ?? 0,
            'regular_min_price' => $this->product->price ?? 0,
            'max_price'         => $minPrice ?? 0,
            'regular_max_price' => $this->product->price ?? 0,
        ];
    }

    /**
     * Get product minimal price.
     *
     * @return float
     */
    public function getMinimalPrice()
    {
        $customerGroupPrice = $this->getCustomerGroupPrice();

        $rulePrice = $this->catalogRuleProductPriceHelper
            ->setCustomerGroup($this->customerGroup)
            ->getRulePrice($this->product);

        $discountedPrice = $this->product->special_price;

        if (
            empty($discountedPrice)
            && ! $rulePrice
            && $customerGroupPrice == $this->product->price
        ) {
            return $this->product->price;
        }

        $haveDiscount = false;

        if (! (float) $discountedPrice) {
            if (
                $rulePrice
                && $rulePrice->price < $this->product->price
            ) {
                $discountedPrice = $rulePrice->price;

                $haveDiscount = true;
            }
        } else {
            if (
                $rulePrice
                && $rulePrice->price <= $discountedPrice
            ) {
                $discountedPrice = $rulePrice->price;

                $haveDiscount = true;
            } else {
                if (core()->isChannelDateInInterval(
                    $this->product->special_price_from,
                    $this->product->special_price_to
                )) {
                    $haveDiscount = true;
                } elseif ($rulePrice) {
                    $discountedPrice = $rulePrice->price;

                    $haveDiscount = true;
                }
            }
        }

        if ($haveDiscount) {
            $discountedPrice = min($discountedPrice, $customerGroupPrice);
        } else {
            if ($customerGroupPrice !== $this->product->price) {
                $discountedPrice = $customerGroupPrice;
            }
        }

        return $discountedPrice;
    }

    /**
     * Get product group price.
     *
     * @return float
     */
    public function getCustomerGroupPrice()
    {
        $customerGroupPrices = $this->productCustomerGroupPriceRepository
            ->checkInLoadedCustomerGroupPrice($this->product, $this->customerGroup->id);

        if ($customerGroupPrices->isEmpty()) {
            return $this->product->price;
        }

        $lastQty = 1;

        $lastPrice = $this->product->price;

        $lastCustomerGroupId = null;

        foreach ($customerGroupPrices as $customerGroupPrice) {
            if ($customerGroupPrice->qty > 1) {
                continue;
            }

            if ($customerGroupPrice->qty < $lastQty) {
                continue;
            }

            if (
                $customerGroupPrice->qty == $lastQty
                && ! empty($lastCustomerGroupId)
                && empty($customerGroupPrice->customer_group_id)
            ) {
                continue;
            }

            if ($customerGroupPrice->value_type == 'discount') {
                if (
                    $customerGroupPrice->value >= 0
                    && $customerGroupPrice->value <= 100
                ) {
                    $lastPrice = $this->product->price - ($this->product->price * $customerGroupPrice->value) / 100;

                    $lastQty = $customerGroupPrice->qty;

                    $lastCustomerGroupId = $customerGroupPrice->customer_group_id;
                }
            } else {
                if (
                    $customerGroupPrice->value >= 0
                    && $customerGroupPrice->value < $lastPrice
                ) {
                    $lastPrice = $customerGroupPrice->value;

                    $lastQty = $customerGroupPrice->qty;

                    $lastCustomerGroupId = $customerGroupPrice->customer_group_id;
                }
            }
        }

        return $lastPrice;
    }
}