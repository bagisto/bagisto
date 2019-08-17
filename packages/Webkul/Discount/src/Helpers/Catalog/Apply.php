<?php

namespace Webkul\Discount\Helpers\Catalog;

use Webkul\Discount\Repositories\CatalogRuleRepository as CatalogRule;
use Webkul\Discount\Repositories\CatalogRuleProductsRepository as CatalogRuleProducts;
use Webkul\Discount\Repositories\CatalogRuleProductsPriceRepository as CatalogRuleProductsPrice;
use Webkul\Discount\Helpers\Catalog\ConvertXToProductId as ConvertX;
use Webkul\Discount\Helpers\Catalog\Sale;

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
            foreach ($this->activeRules as $rule) {
                $productIDs = $this->getProductIds($rule);

                $this->setSale($rule, $productIDs);
            }
        } else {
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
        dd($productIDs);

        if (is_array($productIDs)) {
            // apply on selected products
            foreach ($productIDs as $productID) {
                // $this->catalogRuleProduct->createOrUpdate($rule, $productID);

                // $this->catalogRuleProductPrice->createOrUpdate($rule, $productID);
            }
        } else if ($productIDs == '*') {
            $this->catalogRuleProduct->createOrUpdate($rule, $productIDs);

            $this->catalogRuleProductPrice->createOrUpdate($rule, $productIDs);
        }
    }
}