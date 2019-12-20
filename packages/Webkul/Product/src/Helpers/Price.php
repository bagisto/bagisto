<?php

namespace Webkul\Product\Helpers;

use Carbon\Carbon;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductFlat;
use Webkul\Customer\Repositories\CustomerGroupRepository;
use Webkul\CatalogRule\Repositories\CatalogRuleProductPriceRepository;
use Webkul\CatalogRule\Helpers\CatalogRuleProductPrice;

class Price extends AbstractProduct
{
    /**
     * CustomerGroupRepository object
     */
    protected $customerGroupRepository;

    /**
     * CatalogRuleProductPrice object
     */
    protected $catalogRuleProductPriceHelper;

    /**
     * Create a new helper instance.
     *
     * @param  Webkul\Customer\Repositories\CustomerGroupRepository              $customerGroupRepository
     * @param  Webkul\CatalogRule\Repositories\CatalogRuleProductPriceRepository $catalogRuleProductPriceRepository
     * @param  Webkul\CatalogRule\Repositories\CatalogRuleProductPrice           $catalogRuleProductPriceHelper
     * @return void
     */
    public function __construct(
        CustomerGroupRepository $customerGroupRepository,
        CatalogRuleProductPriceRepository $catalogRuleProductPriceRepository,
        CatalogRuleProductPrice $catalogRuleProductPriceHelper
    )
    {
        $this->customerGroupRepository = $customerGroupRepository;

        $this->catalogRuleProductPriceRepository = $catalogRuleProductPriceRepository;

        $this->catalogRuleProductPriceHelper = $catalogRuleProductPriceHelper;
    }

    /**
     * Returns the product's minimal price
     *
     * @param Product $product
     * @return float
     */
    public function getMinimalPrice($product)
    {
        static $price = [];

        if(array_key_exists($product->id, $price))
            return $price[$product->id];

        if ($product->type == 'configurable') {
            return $price[$product->id] = $this->getVariantMinPrice($product);
        } else {
            if ($this->haveSpecialPrice($product))
                return $price[$product->id] = $product->special_price;

            return $price[$product->id] = $product->price;
        }
    }

    /**
     * Returns the product's minimal price
     *
     * @param Product $product
     * @return float
     */
    public function getVariantMinPrice($product)
    {
        static $price = [];

        $finalPrice = [];

        if (array_key_exists($product->id, $price))
            return $price[$product->id];

        if ($product instanceof ProductFlat) {
            $productId = $product->product_id;
        } else {
            $productId = $product->id;
        }

        $qb = ProductFlat::join('products', 'product_flat.product_id', '=', 'products.id')
                ->where('products.parent_id', $productId);

        $result = $qb
                ->distinct()
                ->selectRaw('IF( product_flat.special_price_from IS NOT NULL
                AND product_flat.special_price_to IS NOT NULL , IF( NOW( ) >= product_flat.special_price_from
                AND NOW( ) <= product_flat.special_price_to, IF( product_flat.special_price IS NULL OR product_flat.special_price = 0 , product_flat.price, LEAST( product_flat.special_price, product_flat.price ) ) , product_flat.price ) , IF( product_flat.special_price_from IS NULL , IF( product_flat.special_price_to IS NULL , IF( product_flat.special_price IS NULL OR product_flat.special_price = 0 , product_flat.price, LEAST( product_flat.special_price, product_flat.price ) ) , IF( NOW( ) <= product_flat.special_price_to, IF( product_flat.special_price IS NULL OR product_flat.special_price = 0 , product_flat.price, LEAST( product_flat.special_price, product_flat.price ) ) , product_flat.price ) ) , IF( product_flat.special_price_to IS NULL , IF( NOW( ) >= product_flat.special_price_from, IF( product_flat.special_price IS NULL OR product_flat.special_price = 0 , product_flat.price, LEAST( product_flat.special_price, product_flat.price ) ) , product_flat.price ) , product_flat.price ) ) ) AS final_price')
                ->where('product_flat.channel', core()->getCurrentChannelCode())
                ->where('product_flat.locale', app()->getLocale())
                ->get();

        foreach ($result as $price) {
            $finalPrice[] = $price->final_price;
        }

        $rulePrice =  null;

        if (request()->route()->getPrefix() != 'admin/catalog') {
            $rulePrice = $this->catalogRuleProductPriceRepository->scopeQuery(function($query) use($product) {
                return $query->selectRaw('min(price) as price')
                            ->whereIn('product_id', $product->variants()->pluck('product_id')->toArray())
                            ->where('channel_id', core()->getCurrentChannel()->id)
                            ->where('customer_group_id', $this->getCurrentCustomerGroupId())
                            ->where('rule_date', Carbon::now()->format('Y-m-d'));
            })->first();
        }

        if (empty($finalPrice) && ! $rulePrice)
            return $price[$productId] = 0;

        if ($rulePrice && $rulePrice->price && min($finalPrice) > $rulePrice->price)
            return $price[$productId] = $rulePrice->price;

        return $price[$productId] = min($finalPrice);
    }

    /**
     * Returns the product's minimal price
     *
     * @param Product $product
     * @return float
     */
    public function getSpecialPrice($product)
    {
        static $price = [];

        if(array_key_exists($product->id, $price))
            return $price[$product->id];

        if ($this->haveSpecialPrice($product)) {
            return $price[$product->id] = $product->special_price;
        } else {
            return $price[$product->id] = $product->price;
        }
    }

    /**
     * @param Product $product
     * @return boolean
     */
    public function haveSpecialPrice($product)
    {
        if ($product instanceof ProductFlat) {
            $rulePrice = $this->catalogRuleProductPriceHelper->getRulePrice($product->product);
        } else {
            $rulePrice = $this->catalogRuleProductPriceHelper->getRulePrice($product);
        }

        if ((is_null($product->special_price) || ! (float) $product->special_price) && ! $rulePrice)
            return false;

        if (! (float) $product->special_price) {
            if ($rulePrice) {
                $product->special_price = $rulePrice->price;

                return true;
            }
        } else {
            if ($rulePrice && $rulePrice->price <= $product->special_price) {
                $product->special_price = $rulePrice->price;

                return true;
            } else {
                if (core()->isChannelDateInInterval($product->special_price_from, $product->special_price_to))
                    return true;
            }
        }

        return false;
    }

    /**
     * Returns current customer group id
     *
     * @return integer|null
     */
    public function getCurrentCustomerGroupId()
    {
        $guard = request()->has('token') ? 'api' : 'customer';

        if (auth()->guard($guard)->check()) {
            $customerGroupId = auth()->guard($guard)->user()->customer_group_id;
        } else {
            $customerGroupId = $this->customerGroupRepository->findOneByField('code', 'guest')->id;
        }

        return $customerGroupId;
    }
}