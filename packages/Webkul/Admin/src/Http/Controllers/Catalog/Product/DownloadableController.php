<?php

namespace Webkul\Admin\Http\Controllers\Catalog\Product;

use Illuminate\Http\JsonResponse;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Core\Helpers\StoredFile;
use Webkul\Product\Repositories\ProductDownloadableLinkRepository;
use Webkul\Product\Repositories\ProductDownloadableSampleRepository;
use Webkul\Product\Repositories\ProductRepository;

class DownloadableController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected ProductRepository $productRepository,
        protected ProductDownloadableLinkRepository $productDownloadableLinkRepository,
        protected ProductDownloadableSampleRepository $productDownloadableSampleRepository
    ) {}

    /**
     * Send a downloadable link's own file or its sample, which are kept out of the web root.
     */
    public function downloadLinkFile(int $id, string $type)
    {
        if (! in_array($type, ['file', 'sample_file'], true)) {
            abort(404);
        }

        $link = $this->productDownloadableLinkRepository->findOrFail($id);

        if (! $link->{$type}) {
            abort(404);
        }

        return app(StoredFile::class)->download($link->{$type}, $link->{$type.'_name'});
    }

    /**
     * Send a downloadable sample's file.
     */
    public function downloadSampleFile(int $id)
    {
        $sample = $this->productDownloadableSampleRepository->findOrFail($id);

        if (! $sample->file) {
            abort(404);
        }

        return app(StoredFile::class)->download($sample->file, $sample->file_name);
    }

    /**
     * Returns the compare items of the customer.
     */
    public function options(int $id): JsonResponse
    {
        $product = $this->productRepository->findOrFail($id);

        $links = [];

        foreach ($product->downloadable_links as $link) {
            $links[] = [
                'id' => $link->id,
                'title' => $link->title,
                'price' => $link->price,
                'formatted_price' => core()->formatPrice($link->price),
            ];
        }

        return new JsonResponse([
            'data' => $links,
        ]);
    }
}
