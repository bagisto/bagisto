<?php

namespace Webkul\Product\Repositories;

use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Webkul\Core\Traits\Sanitizer;

class SearchRepository extends ProductRepository
{
    use Sanitizer;

    /**
     * Upload provided image
     *
     * @param  array  $data
     * @return string
     */
    public function uploadSearchImage($data)
    {
        $path = request()->file('image')->store('product-search');

        if (! $this->sanitizeSVG($path, $data['image']->getMimeType())) {
            throw ValidationException::withMessages([
                'image' => trans('core::app.errors.invalid-svg'),
            ]);
        }

        return Storage::url($path);
    }
}
