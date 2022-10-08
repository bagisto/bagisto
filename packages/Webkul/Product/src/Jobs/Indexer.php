<?php
 
namespace Webkul\Product\Jobs;
 
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Bus\Batchable;
use Illuminate\Queue\SerializesModels;
use Webkul\Product\Helpers\Indexer as IndexerHelper;
 
class Indexer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;
 
    /**
     * Product collection
     *
     * @var array
     */
    protected $products;

    /**
     * Create a new job instance.
     *
     * @param  array  $products
     * @return void
     */
    public function __construct($products)
    {
        $this->products = $products;
    }
 
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $indexer = app(IndexerHelper::class);

        foreach ($this->products as $product) {
            $indexer->refresh($product);
        }
    }
}