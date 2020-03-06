<?php

namespace Webkul\Product\Repositories;

use Illuminate\Support\Facades\Storage;
use Webkul\Core\Eloquent\Repository;
use Illuminate\Support\Str;

class ProductDownloadableLinkRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return 'Webkul\Product\Contracts\ProductDownloadableLink';
    }

    /**
     * @param  array  $data
     * @param  integer $productId
     * @return array
     */
    public function upload($data, $productId)
    {
        foreach ($data as $type => $file) {
            if (request()->hasFile($type)) {
                return [
                    $type           => $path = request()->file($type)->store('product_downloadable_links/' . $productId),
                    $type . '_name' => $file->getClientOriginalName(),
                    $type . '_url'  => Storage::url($path),
                ];
            }
        }

        return [];
    }

    /**
     * @param  array  $data
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return void
     */
    public function saveLinks(array $data, $product)
    {
        $previousLinkIds = $product->downloadable_links()->pluck('id');

        if (isset($data['downloadable_links'])) {
            foreach ($data['downloadable_links'] as $linkId => $data) {
                if (Str::contains($linkId, 'link_')) {
                    $this->create(array_merge([
                        'product_id' => $product->id,
                    ], $data));
                } else {
                    if (is_numeric($index = $previousLinkIds->search($linkId))) {
                        $previousLinkIds->forget($index);
                    }

                    $this->update($data, $linkId);
                }
            }
        }

        foreach ($previousLinkIds as $linkId) {
            $this->delete($linkId);
        }
    }
}