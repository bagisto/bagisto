<?php

namespace Webkul\Product\Repositories;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Webkul\Core\Eloquent\Repository;
use Webkul\Product\Contracts\Product;
use Webkul\Product\Contracts\ProductDownloadableSample;

class ProductDownloadableSampleRepository extends Repository
{
    /**
     * Specify Model class name.
     */
    public function model(): string
    {
        return ProductDownloadableSample::class;
    }

    /**
     * Upload files related to downloadable products
     */
    public function upload(array $data, int $productId): array
    {
        if (
            ! empty($data['file'])
            && ! $data['file'] instanceof UploadedFile
        ) {
            return [];
        }

        return [
            'file'      => $path = $data['file']->store('product_downloadable_links/'.$productId),
            'file_name' => $data['file']->getClientOriginalName(),
            'file_url'  => Storage::url($path),
        ];
    }

    /**
     * Save samples.
     */
    public function saveSamples(array $data, Product $product): void
    {
        $previousSampleIds = $product->downloadable_samples()->pluck('id');

        if (isset($data['downloadable_samples'])) {
            foreach ($data['downloadable_samples'] as $sampleId => $data) {
                if (Str::contains($sampleId, 'sample_')) {
                    $this->create(array_merge([
                        'product_id' => $product->id,
                    ], $data));
                } else {
                    if (is_numeric($index = $previousSampleIds->search($sampleId))) {
                        $previousSampleIds->forget($index);
                    }

                    $this->update($data, $sampleId);
                }
            }
        }

        foreach ($previousSampleIds as $sampleId) {
            $this->delete($sampleId);
        }
    }
}
