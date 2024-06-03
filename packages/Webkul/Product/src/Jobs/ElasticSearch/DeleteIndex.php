<?php

namespace Webkul\Product\Jobs\ElasticSearch;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Webkul\Product\Helpers\Indexers\ElasticSearch;

class DeleteIndex implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @param  array  $productIds
     * @return void
     */
    public function __construct(protected $productIds)
    {
        $this->productIds = $productIds;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (core()->getConfigData('catalog.products.search.engine') != 'elastic') {
            return;
        }

        $removeIndices = [];

        foreach (core()->getAllChannels() as $channel) {
            foreach ($channel->locales as $locale) {
                $index = 'products_'.$channel->code.'_'.$locale->code.'_index';

                $removeIndices[$index] = $this->productIds;
            }
        }

        app(ElasticSearch::class)->deleteIndices($removeIndices);
    }
}
