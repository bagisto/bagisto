<?php

namespace Webkul\Discount\Helpers\Catalog;

use Webkul\Discount\Repositories\CatalogRuleRepository as CatalogRule;
use Webkul\Discount\Repositories\CatalogRuleProductsRepository as CatalogRuleProducts;
use Webkul\Discount\Repositories\CatalogRuleProductsPriceRepository as CatalogRuleProductsPrice;
use Webkul\Discount\Helpers\Catalog\ConvertXToProductId as ConvertX;
use Webkul\Discount\Helpers\Catalog\Sale;

/**
 * Apply - Applies catalog rule to products intended in the rules
 *
 * @author  Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class Apply extends Sale
{
    /**
     * To hold the catalog rule repository instance
     */
    protected $catalogRule;

    /**
     * To maintain the list of deceased rules and remove their records if present
     */
    protected $activeRules;

    /**
     * To maintain the list of deceased rules and remove their records if present
     */
    protected $deceased;

    /**
     * Holds convertXToProductId instance
     */
    protected $convertX;

    /**
     * Hold catalog rule products repository instance
     */
    protected $catalogRuleProduct;

    /**
     * Hold catalog rule products price repository instance
     */
    protected $catalogRuleProductPrice;

    /**
     * To hold the rule classes
     */
    protected $rules;

    /**
     * @param CatalogRule $catalogRule
     */
    public function __construct(
        CatalogRule $catalogRule,
        ConvertX $convertX,
        CatalogRuleProducts $catalogRuleProduct,
        CatalogRuleProductsPrice $catalogRuleProductPrice
    ) {
        $this->catalogRule = $catalogRule;

        $this->convertX = $convertX;

        $this->catalogRuleProduct = $catalogRuleProduct;

        $this->catalogRuleProductPrice = $catalogRuleProductPrice;

        $this->active = collect();

        $this->activeRules = collect();

        $this->deceased = collect();

        $this->rules = config('discount-rules.catalog');
    }

    /**
     * To apply the new catalog rules and sync previous rules changes,
     */
    public function apply()
    {
        $rules = $this->catalogRule->all();

        foreach ($rules as $rule) {
            $validated = $this->checkApplicability($rule);

            if ($validated) {
                $this->active->push($rule->id);

                $this->activeRules->push($rule);
            } else {
                $this->deceased->push($rule->id);

                // Job execution for deceased rules
            }
        }

        if ($this->active->count()) {
            $productIDs = array();
            $temp = collect();

            foreach ($this->activeRules as $rule) {
                $productIDs = $this->getProductIds($rule);

                $productIDs = $this->expandProducts($productIDs);

                $result = $this->setSale($rule, $productIDs);
            }

            dd($result, 'processing done');
        } else {
            // handle the deceased rules here
            dd($this->deceased);
        }
    }

    /**
     * To set the sale price for products falling catalog rule criteria
     *
     * @param CatalogRule $catalogRule
     *
     * @return Void
     */
    public function setSale($rule, $productIDs)
    {
        if (is_array($productIDs)) {
            // apply on selected products
            foreach ($productIDs as $productID) {
                // catalog rule product resource is updated here
                $this->catalogRuleProduct->createOrUpdate($rule, $productID);

                // catalog rule product price resource is updated here
                $this->catalogRuleProductPrice->createOrUpdate($rule, $productID);
            }
        } else if ($productIDs == '*') {
            $this->catalogRuleProduct->createOrUpdate($rule, $productIDs);

            $this->catalogRuleProductPrice->createOrUpdate($rule, $productIDs);
        }
    }

    /**
     * To expand the productIDs of configurable products
     *
     * @param Array $productIDs
     *
     * @return Array
     */
    protected function expandProducts($productIDs)
    {
        $products = app('Webkul\Product\Repositories\ProductRepository');

        $newProductIDs = collect();

        foreach ($productIDs as $productID) {
            $product = $products->find($productID);

            if ($product->type == 'configurable') {
                $variants = $product->variants;

                foreach($variants as $variant) {
                    $newProductIDs->push($variant->id);
                }
            } else {
                $newProductIDs->push($productID);
            }
        }

        if ($newProductIDs->count()) {
            return $newProductIDs->toArray();
        } else {
            return [];
        }
    }

    /**
     * To break tie between two rules
     *
     * @param Integer $previousRuleID
     * @param Integer $newRuleID
     *
     * @return String $id
     */
    public function breakTie($previousRuleID, $newRuleID)
    {
        $oldRule = $this->catalogRule->find($previousRuleID);

        $newRule = $this->catalogRule->find($newRuleID);

        dd($oldRule->name, $newRule->name);
    }

    /**
     * To declutter the catalog rules that are either inactive or deleted
     *
     * @return Boolean
     */
    public function deClutter()
    {
        $rules = $this->catalogRule->all();

        foreach ($rules as $rule) {
            $validated = $this->checkApplicability($rule);

            if (! $validated) {
                $this->deceased->push($rule->id);
            }
        }

        if (count($this->deceased)) {
            $count = 0;

            foreach ($this->deceased as $deceased)  {
                $cartRuleProducts = $this->catalogRuleProduct->findWhere([
                    'catalog_rule_id' => $deceased
                ]);

                $cartRuleProductsPrice = $this->catalogRuleProductPrice->findWhere([
                    'catalog_rule_id' => $deceased
                ]);

                // obvious logic for removing entries as entries in both storage needs to be exactly equal for a product
                foreach ($cartRuleProducts as $cartRuleProduct) {
                    // deletes cartRuleProducts resource
                    $cartRuleProduct->delete();

                    // deletes cartRuleProductsPrice resource
                    $cartRuleProductsPrice->delete();
                }
            }

            return true;
        } else {
            return false;
        }
    }
}