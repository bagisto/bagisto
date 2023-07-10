<?php

namespace Webkul\Product\Jobs\Indexers;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Helpers\Indexers\Price as Indexer;

class Price implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    /**
     * @var Collection $products
     */
    protected $products;

    /**
     * Accept price index methods reindexRows, reindexRow
     *
     * @var string $method
     */
    protected $method;

    /**
     * Create a new job instance.
     *
     * @param \Illuminate\Support\Collection $products
     * @param string $method
     * @return void
     */
    public function __construct(
        Collection $products,
        $method
    ) {
        $this->products = $products;

        $this->method = $method;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->products->map(function ($product_id, $index) {
            $this->products[$index] = app(
                ProductRepository::class
            )
                ->findOrFail($product_id);
        });

        app(Indexer::class)
            ->{$this->method}(
                $this->products->all()
            );
    }
}
