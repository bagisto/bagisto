<?php

namespace Webkul\Product\Helpers;

class Downloadable extends AbstractProduct
{
    /**
     * Get downloadable product links.
     *
     * @param  \Webkul\Product\Models\Product  $product
     * @return \Illuminate\Support\Collection
     */
    public function getProductLink($product)
    {
        if ($product->downloadable_links->isEmpty()) {
            return collect([
                [
                    'title'       => '',
                    'type'        => 'file',
                    'file'        => '',
                    'file_name'   => '',
                    'url'         => '',
                    'sample_type' => 'file',
                    'sample_file' => '',
                    'sample_file_name' => '',
                    'sample_url'       => '',
                    'downloads'        => 0,
                    'sort_order'       => 0
                ]
            ]);
        }

        return  $product->downloadable_links;
    }
}
