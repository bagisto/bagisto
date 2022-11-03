<?php

namespace Webkul\Product\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Jobs\Indexer as IndexerJob;

class Indexer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:index {--type=*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically updates product price and inventory indices';

    /**
     * Create a new command instance.
     *
     * @param  \Webkul\Product\Repositories\ProductRepository  $productRepository
     * @return void
     */
    public function __construct(protected ProductRepository $productRepository)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $indexers = ['inventory', 'price', 'elastic'];

        if (! empty($this->option('type'))) {
            $indexers = $this->option('type');
        }

        $batch = Bus::batch([])->dispatch();

        while (true) {
            $paginator = $this->productRepository->cursorPaginate(50);

            $batch->add(new IndexerJob($paginator->items(), $indexers));

            if (! $cursor = $paginator->nextCursor()) {
                break;
            }

            request()->query->add(['cursor' => $cursor->encode()]);
        }
    }
}