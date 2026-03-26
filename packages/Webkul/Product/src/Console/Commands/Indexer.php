<?php

namespace Webkul\Product\Console\Commands;

use Illuminate\Console\Command;
use Webkul\Product\Helpers\Indexers\Flat;
use Webkul\Product\Helpers\Indexers\Inventory;
use Webkul\Product\Helpers\Indexers\Price;
use Webkul\Product\Services\Search\SearchEngineManager;

class Indexer extends Command
{
    protected $indexers = [
        'inventory' => Inventory::class,
        'price' => Price::class,
        'flat' => Flat::class,
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
    public function handle(SearchEngineManager $manager)
    {
        $start = microtime(true);

        $indexerIds = ['inventory', 'price', 'flat', 'search'];

        if (! empty($this->option('type'))) {
            $indexerIds = $this->option('type');
        }

        $mode = 'selective';

        if (! empty($this->option('mode'))) {
            $mode = current($this->option('mode'));
        }

        foreach ($indexerIds as $indexerId) {
            if ($indexerId === 'search') {
                if ($manager->isExternalEngineEnabled()) {
                    $searchIndexer = $manager->indexer();

                    if ($mode == 'full') {
                        $searchIndexer->reindexFull();
                    }
                }

                continue;
            }

            if (! isset($this->indexers[$indexerId])) {
                $this->components->warn("Unknown indexer: {$indexerId}");

                continue;
            }

            $indexer = app($this->indexers[$indexerId]);

            if ($mode == 'full') {
                $indexer->reindexFull();
            } else {
                if ($indexerId != 'inventory') {
                    $indexer->reindexSelective();
                }
            }
        }

        $end = microtime(true);

        $this->components->success('The code took '.(round($end - $start, 2)).' seconds to complete.');
    }
}
