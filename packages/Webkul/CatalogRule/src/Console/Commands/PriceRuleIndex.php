<?php

namespace Webkul\CatalogRule\Console\Commands;

use Illuminate\Console\Command;
use Webkul\Product\Repositories\ProductFlatRepository;
use Webkul\CatalogRule\Repositories\CatalogRuleRepository;
use Webkul\CatalogRule\Repositories\CatalogRuleProductPriceRepository;

class PriceRuleIndex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:price-rule:index';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically updates catalog rule price index information (eg. rule_price)';

    /**
     * ProductFlatRepository object
     *
     * @var Object
    */
    protected $productFlatRepository;

    /**
     * CatalogRuleRepository object
     *
     * @var Object
    */
    protected $catalogRuleRepository;

    /**
     * CatalogRuleProductPriceRepository object
     *
     * @var Object
    */
    protected $catalogRuleProductPriceRepository;

    /**
     * Create a new command instance.
     *
     * @param  Webkul\Product\Repositories\ProductFlatRepository                 $productFlatRepository
     * @param  Webkul\CatalogRule\Repositories\CatalogRuleRepository             $catalogRuleRepository
     * @param  Webkul\CatalogRule\Repositories\CatalogRuleProductPriceRepository $catalogRuleProductPriceRepository
     * @return void
     */
    public function __construct(
        ProductFlatRepository $productFlatRepository,
        CatalogRuleRepository $catalogRuleRepository,
        CatalogRuleProductPriceRepository $catalogRuleProductPriceRepository
    )
    {
        $this->productFlatRepository = $productFlatRepository;

        $this->catalogRuleRepository = $catalogRuleRepository;

        $this->catalogRuleProductPriceRepository = $catalogRuleProductPriceRepository;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

    }
}