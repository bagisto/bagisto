<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Webkul\Product\Repositories\ProductAttributeValueRepository;
use Webkul\Product\Repositories\ProductDownloadableLinkRepository;
use Webkul\Product\Repositories\ProductDownloadableSampleRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Shop\Traits\ValidatesExternalUrl;

class ProductController extends Controller
{
    use ValidatesExternalUrl;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected ProductRepository $productRepository,
        protected ProductAttributeValueRepository $productAttributeValueRepository,
        protected ProductDownloadableSampleRepository $productDownloadableSampleRepository,
        protected ProductDownloadableLinkRepository $productDownloadableLinkRepository
    ) {}

    /**
     * Download image or file.
     *
     * @param  int  $productId
     * @param  int  $attributeId
     * @return Response
     */
    public function download($productId, $attributeId)
    {
        $productAttribute = $this->productAttributeValueRepository->findOneWhere([
            'product_id' => $productId,
            'attribute_id' => $attributeId,
        ]);

        return isset($productAttribute['text_value'])
            ? Storage::download($productAttribute['text_value'])
            : null;
    }

    /**
     * Download the for the specified resource.
     *
     * @return Response|\Exception
     */
    public function downloadSample()
    {
        try {
            if (request('type') == 'link') {
                $productDownloadableLink = $this->productDownloadableLinkRepository->findOrFail(request('id'));

                if ($productDownloadableLink->sample_type == 'file') {
                    $privateDisk = Storage::disk('private');

                    return $privateDisk->exists($productDownloadableLink->sample_file)
                        ? $privateDisk->download($productDownloadableLink->sample_file)
                        : abort(404);
                } else {
                    $fileName = substr($productDownloadableLink->sample_url, strrpos($productDownloadableLink->sample_url, '/') + 1);

                    $tempImage = tempnam(sys_get_temp_dir(), $fileName);

                    if (! $this->validateExternalUrl($productDownloadableLink->sample_url)) {
                        abort(404);
                    }

                    copy($productDownloadableLink->sample_url, $tempImage);

                    return response()->download($tempImage, $fileName);
                }
            } else {
                $productDownloadableSample = $this->productDownloadableSampleRepository->findOrFail(request('id'));

                if ($product = $this->productRepository->findOrFail($productDownloadableSample->product_id)) {
                    if (! $product->visible_individually) {
                        return redirect()->back();
                    }
                }

                if ($productDownloadableSample->type == 'file') {
                    return Storage::download($productDownloadableSample->file);
                } else {
                    $fileName = substr($productDownloadableSample->url, strrpos($productDownloadableSample->url, '/') + 1);

                    $tempImage = tempnam(sys_get_temp_dir(), $fileName);

                    if (! $this->validateExternalUrl($productDownloadableSample->url)) {
                        abort(404);
                    }

                    copy($productDownloadableSample->url, $tempImage);

                    return response()->download($tempImage, $fileName);
                }
            }
        } catch (\Exception $e) {
            abort(404);
        }
    }
}
