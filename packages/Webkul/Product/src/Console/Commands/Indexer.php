<?php

namespace Webkul\Product\Console\Commands;

use Illuminate\Console\Command;
use Webkul\Product\Helpers\Indexers\{Inventory, Price, ElasticSearch};

class Indexer extends Command
{
    protected $indexers = [
        'inventory' => Inventory::class,
        'price'     => Price::class,
        'elastic'   => ElasticSearch::class,
    ];
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'indexer:index {--type=*} {--mode=*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically updates product price and inventory indices';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $start = microtime(TRUE);
        
        $indexerIds = ['inventory', 'price', 'elastic'];

        if (! empty($this->option('type'))) {
            $indexerIds = $this->option('type');
        }

        $mode = 'selective';

        if (! empty($this->option('mode'))) {
            $mode = current($this->option('mode'));
        }

        foreach ($indexerIds as $indexerId) {
            if (
                $indexerId == 'elastic'
                && core()->getConfigData('catalog.products.storefront.search_mode') != 'elastic'
            ) {
                continue;
            }
            
            $indexer = app($this->indexers[$indexerId]);

            if ($mode == 'full') {
                $indexer->reindexFull();
            } else {
                $indexer->reindexSelective();
            }
        }

        $end = microtime(TRUE);

        echo "The code took " . ($end - $start) . " seconds to complete.\n";
    }
}