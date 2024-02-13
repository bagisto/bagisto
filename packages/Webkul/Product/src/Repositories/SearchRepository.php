<?php

namespace Webkul\Product\Repositories;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Webkul\Core\Traits\Sanitizer;

class SearchRepository extends ProductRepository
{
    use Sanitizer;

    /**
     * Upload provided image.
     */
    public function uploadSearchImage(array $data): mixed
    {
        if (
            ! empty($data['image'])
            && ! $data['image'] instanceof UploadedFile
        ) {
            return null;
        }

        $path = $data['image']->store('product-search');

        $this->sanitizeSVG($path, $data['image']->getMimeType());

        return Storage::url($path);
    }
}
