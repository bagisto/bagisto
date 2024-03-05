<?php

namespace Webkul\Product\Repositories;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Webkul\Core\Eloquent\Repository;

class ProductDownloadableSampleRepository extends Repository
{
    /**
     * Specify Model class name
     */
    public function model(): string
    {
        return 'Webkul\Product\Contracts\ProductDownloadableSample';
    }

    /**
     * @param  array  $data
     * @param  int  $productId
     * @return mixed
     */
    public function upload($data, $productId)
    {
        if (! request()->hasFile('file')) {
            return [];
        }

        return [
            'file'      => $path = request()->file('file')->store('product_downloadable_links/'.$productId),
            'file_name' => request()->file('file')->getClientOriginalName(),
            'file_url'  => Storage::url($path),
        ];
    }

    /**
     * @param  Webkul\Product\Contracts\Product  $product
     * @return void
     */
    public function saveSamples(array $data, $product)
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
