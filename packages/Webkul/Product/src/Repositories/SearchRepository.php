<?php

namespace Webkul\Product\Repositories;

use Illuminate\Support\Facades\Storage;
use Webkul\Core\Traits\Sanitizer;

class SearchRepository extends ProductRepository
{
    use Sanitizer;

    const SUGGEST_DISABLED = '0';


    /**
     * Upload provided image
     *
     * @param  array  $data
     * @return string
     */
    public function uploadSearchImage($data)
    {
        $path = request()->file('image')->store('product-search');

        $this->sanitizeSVG($path, $data['image']->getMimeType());

        return Storage::url($path);
    }
}
