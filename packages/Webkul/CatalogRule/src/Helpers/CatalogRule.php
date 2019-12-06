<?php

namespace Webkul\CatalogRule\Helpers;

use Carbon\Carbon;
use Webkul\CatalogRule\Repositories\CatalogRuleRepository;
use Webkul\Rule\Helpers\Validator;

class CatalogRule
{
    /**
     * CatalogRuleRepository object
     *
     * @var CatalogRuleRepository
     */
    protected $catalogRuleRepository;

    /**
     * Validator object
     *
     * @var Validator
     */
    protected $validator;

    /**
     * Create a new helper instance.
     *
     * @param  Webkul\CatalogRule\Repositories\CatalogRuleRepository $catalogRuleRepository
     * @param  Webkul\Rule\Helpers\Validator                         $validator
     * @return void
     */
    public function __construct(
        CatalogRuleRepository $catalogRuleRepository,
        Validator $validator
    )
    {
        $this->catalogRuleRepository = $catalogRuleRepository;

        $this->validator = $validator;
    }

    /**
     * Collect discount on cart
     *
     * @return void
     */
    public function collect()
    {
    }

    /**
     * Returns catalog rules
     *
     * @return Collection
     */
    public function getCatalogRules()
    {
        static $catalogRules;

        if ($catalogRules)
            return $catalogRules;

        $catalogRules = $this->catalogRuleRepository->scopeQuery(function($query) {
            return $query->where(function ($query1) {
                        $query1->where('catalog_rules.starts_from', '<=', Carbon::now()->format('Y-m-d'))->orWhereNull('catalog_rules.starts_from');
                    })
                    ->where(function ($query2) {
                        $query2->where('catalog_rules.ends_till', '>=', Carbon::now()->format('Y-m-d'))->orWhereNull('catalog_rules.ends_till');
                    })
                    ->orderBy('sort_order', 'asc');
        })->findWhere(['status' => 1]);

        return $catalogRules;
    }
}