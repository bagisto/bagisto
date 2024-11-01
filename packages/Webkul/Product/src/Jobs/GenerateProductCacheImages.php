<?php

namespace Webkul\Product\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Webkul\Product\Models\Product;
use Webkul\Product\Repositories\ProductRepository;

class GenerateProductCacheImages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected int $productId
    ) {}

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /** @var Product $product */
        $product = app(ProductRepository::class)->find($this->productId);
        if (! $product) {
            return;
        }

        foreach (product_image()->getGalleryImages($product) as $image) {
            if (! $image) {
                continue;
            }

            foreach ($image as $key => $value) {
                $image[$key] = str_replace(Storage::url(''), '', $value);
            }

            if (! Storage::exists($image['original_image_url'])) {
                logger()->debug('Original image not found', ['image' => $image]);

                continue;
            }

            foreach (config('imagecache.templates') as $template => $templateClass) {
                if (
                    ! Arr::has($image, $template.'_image_url') ||
                    Storage::exists($image[$template.'_image_url'])
                ) {
                    continue;
                }

                // Simply touch
                Http::get(url($image[$template.'_image_url']))->close();
            }
        }
    }
}
