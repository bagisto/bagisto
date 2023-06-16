<?php
 
namespace Webkul\Product\Jobs\Indexers;
 
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Bus\Batchable;
use Illuminate\Queue\SerializesModels;
use Webkul\Product\Helpers\Indexers\Flat as Indexer;
use Webkul\Product\Helpers\ProductType;
 
class Flat implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;
 
    /**
     * @var \Webkul\Product\Models\Product  $model
     */
    protected $model;

    /**
     * Create a new job instance.
     *
     * @param  \Webkul\Product\Models\Product  $model
     * @return void
     */
    public function __construct($model)
    {
        $this->model = $model;
    }
 
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $indexer = app(Indexer::class);

        $indexer->updateOrCreate($this->model);

        if (! ProductType::hasVariants($this->model->type)) {
            return;
        }

        foreach ($this->model->variants()->get() as $variant) {
            $indexer->updateOrCreate($variant, $this->model);
        }
    }
}