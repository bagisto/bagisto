<?php

namespace Webkul\CatalogRule\Console\Commands;

use Illuminate\Console\Command;
use Webkul\CatalogRule\Helpers\CatalogRule;

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
     * CatalogRule object
     *
     * @var Object
    */
    protected $catalogRuleHelper;

    /**
     * Create a new command instance.
     *
     * @param  Webkul\CatalogRule\Helpers\CatalogRule $catalogRuleHelper
     * @return void
     */
    public function __construct(CatalogRule $catalogRuleHelper)
    {
        $this->catalogRuleHelper = $catalogRuleHelper;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->catalogRuleHelper->applyAll();
    }
}