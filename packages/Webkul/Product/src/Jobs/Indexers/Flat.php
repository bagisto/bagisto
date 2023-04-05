<?php
 
namespace Webkul\Product\Jobs\Indexers;
 
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Bus\Batchable;
use Illuminate\Queue\SerializesModels;
use Webkul\Product\Helpers\Indexers\Flat as FlatIndexHelper;
use Webkul\Product\Helpers\ProductType;
 
class Flat implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;
 
    /**
     * @var \Webkul\Product\Models\Product  $model
     */
    protected $model;

    /**
     * Product model
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
        $flatIndexHelper = app(FlatIndexHelper::class);

        $flatIndexHelper->updateOrCreate($this->model);

        if (! ProductType::hasVariants($this->model->type)) {
            return;
        }

        foreach ($this->model->variants()->get() as $variant) {
            $flatIndexHelper->updateOrCreate($variant, $this->model);
        }
    }
}