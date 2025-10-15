<?php

namespace Webkul\Admin\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Webkul\Core\Traits\Sanitizer;

class TinyMCEController extends Controller
{
    use Sanitizer;

    /**
     * Storage folder path.
     *
     * @var string
     */
    private $storagePath = 'tinymce';

    /**
     * Upload file from tinymce.
     *
     * @return void
     */
    public function upload()
    {
        $media = $this->storeMedia();

        if (! empty($media)) {
            return response()->json([
                'location' => $media['file_url'],
            ]);
        }

        return response()->json([]);
    }

    /**
     * Store media.
     *
     * @return array
     */
    public function storeMedia()
    {
        if (! request()->hasFile('file')) {
            return [];
        }

        $path = request()->file('file')->store($this->storagePath);

        $this->sanitizeSVG($path, request()->file('file')->getMimeType());

        return [
            'file'      => $path,
            'file_name' => request()->file('file')->getClientOriginalName(),
            'file_url'  => Storage::url($path),
        ];
    }
}
