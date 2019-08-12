<?php

namespace Webkul\Discount\Helpers\Catalog;

use Webkul\Discount\Repositories\CatalogRuleRepository as CatalogRule;
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
     * To hold the rule classes
     */
    protected $rules;

    /**
     * @param CatalogRule $catalogRule
     */
    public function __construct(CatalogRule $catalogRule)
    {
        $this->catalogRule = $catalogRule;

        $this->active = collect();

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

                // Job execution for active rules
                $products = $this->getProductIds($rule);

                $this->setSalePrice($products);
            } else {
                $this->deceased->push($rule->id);

                // Job execution for deceased rules
            }
        }
    }
}