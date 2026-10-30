<?php

namespace Webkul\Product\Repositories;

use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Webkul\Core\Traits\Sanitizer;

class SearchRepository extends ProductRepository
{
    use Sanitizer;

    /**
     * The folder image search uploads are kept in, under a subfolder for the day of each upload, so that every day
     * before today can be pruned at once.
     */
    public const SEARCH_IMAGES_DIRECTORY = 'search-images';

    /**
     * Store an image search upload in the folder of the day it was made, returning the url it is served from.
     *
     * @param  array  $data
     * @return string
     */
    public function uploadSearchImage($data)
    {
        $path = request()->file('image')->store(self::SEARCH_IMAGES_DIRECTORY.'/'.now()->toDateString());

        if (! $this->sanitizeSVG($path, $data['image']->getMimeType())) {
            throw ValidationException::withMessages([
                'image' => trans('core::app.errors.invalid-svg'),
            ]);
        }

        return Storage::url($path);
    }
}
