<?php

namespace Webkul\Discount\Helpers\Catalog;

use Webkul\Discount\Repositories\CatalogRepository as CatalogRule;
use Webkul\Discount\Helpers\Catalog\ConvertXToProductId;

abstract class Sale
{
    /**
     * To hold the catalog rule repository instance
     */
    protected $catalogRule;

    /**
     * ConvertXToProductId instance
     */
    protected $convertX;

    /**
     * To hold the rule classes
     */
    protected $rules;

    /**
     * @param CatalogRule $catalogRule
     */
    public function __construct(CatalogRule $catalogRule, ConvertXToProductId $convertX)
    {
        $this->catalogRule = $catalogRule;

        $this->convertX = $convertX;

        $this->rules = config('discount-rules.catalog');
    }

    abstract function apply();

    /**
     * To validate rule
     *
     * @param CatalogRule $rule
     *
     * @return Boolean
     */
    public function checkApplicability($rule)
    {
        $cart = \Cart::getCart();

        $timeBased = false;

        $status = false;

        // time based constraints
        if ($rule->starts_from != null && $rule->ends_till == null) {
            if (Carbon::parse($rule->starts_from) < now()) {
                $timeBased = true;
            }
        } else if ($rule->starts_from == null && $rule->ends_till != null) {
            if (Carbon::parse($rule->ends_till) > now()) {
                $timeBased = true;
            }
        } else if ($rule->starts_from != null && $rule->ends_till != null) {
            if (Carbon::parse($rule->starts_from) < now() && now() < Carbon::parse($rule->ends_till)) {
                $timeBased = true;
            }
        } else {
            $timeBased = true;
        }

        if ($rule->status) {
            $status = true;
        }

        if ($timeBased && $status) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Function to maintain products and catalog rule
     */
    public function getProductIds($rule)
    {
        $productIDs = $rule->conditions;

        if ($rule->conditions) {
            $conditions = $rule->conditions;

            // $convertX = new ConvertXToProductId();

            dd($this->convertX);
            $productIDs = $this->convertX->convertX($rule->conditions);
        } else {
            // apply on all products
        }

        dd($productIDs);
    }

     /**
      * Function to maintain products, catalog rule, price
      */

}