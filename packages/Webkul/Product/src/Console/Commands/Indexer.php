<?php

namespace Webkul\Product\Console\Commands;

use Illuminate\Console\Command;
use Webkul\Product\Helpers\Indexers\{Inventory, Price, Elastic};

class Indexer extends Command
{
    protected $indexers = [
        'inventory' => Inventory::class,
        'price'     => Price::class,
        'elastic'   => Elastic::class,
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
        $indexerIds = ['inventory', 'price', 'elastic'];

        if (! empty($this->option('type'))) {
            $indexerIds = $this->option('type');
        }

        $mode = 'full';

        if (! empty($this->option('mode'))) {
            $mode = current($this->option('mode'));
        }

        foreach ($indexerIds as $indexerId) {
            $indexer = app($this->indexers[$indexerId]);

            if ($mode == 'full') {
                $indexer->reindexFull();
            } else {
                $indexer->reindexSelective();
            }
        }
    }
}