<?php

namespace Webkul\Core\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Product\Repositories\ProductRepository;

class UpdateCreateVisitableIndex implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @param  array  $log
     * @return void
     */
    public function __construct(protected $log) {}

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $slugOrURLKey = urldecode(trim($this->log['path_info'], '/'));

        /**
         * Support url for chinese, japanese, arabic and english with numbers.
         */
        if (! preg_match('/^([\x{0621}-\x{064A}\x{4e00}-\x{9fa5}\x{3402}-\x{FA6D}\x{3041}-\x{30A0}\x{30A0}-\x{31FF}_a-z0-9-]+\/?)+$/u', $slugOrURLKey)) {
            UpdateCreateVisitIndex::dispatch(null, $this->log);

            return;
        }

        $category = app(CategoryRepository::class)->findBySlug($slugOrURLKey);

        if ($category) {
            UpdateCreateVisitIndex::dispatch($category, $this->log);

            return;
        }

        $product = app(ProductRepository::class)->findBySlug($slugOrURLKey);

        if (
            ! $product
            || ! $product->visible_individually
            || ! $product->url_key
            || ! $product->status
        ) {
            return;
        }

        UpdateCreateVisitIndex::dispatch($product, $this->log);
    }
}
