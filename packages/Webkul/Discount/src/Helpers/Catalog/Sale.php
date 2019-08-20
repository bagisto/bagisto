<?php

namespace Webkul\Discount\Helpers\Catalog;

use Webkul\Discount\Repositories\CatalogRepository as CatalogRule;
use Carbon\Carbon;

/**
 * Sale - Abstract class designed to initiate the application of Catalog Rules
 *
 * @author  Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
abstract class Sale
{
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
     *
     * @param CatalogRule $rule
     *
     * @return String|NULL
     */
    public function getProductIds($rule)
    {
        if ($rule->conditions) {
            $conditions = $rule->conditions;

            $productIDs = $this->convertX->convertX($rule->conditions);
        } else {
            $productIDs = '*';
        }

        return $productIDs;
    }

     /**
      * Function to maintain products, catalog rule, price
      */

}