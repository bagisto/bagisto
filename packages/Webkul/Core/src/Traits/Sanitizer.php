<?php

namespace Webkul\Core\Traits;

use enshrined\svgSanitize\Sanitizer as MainSanitizer;
use Illuminate\Support\Facades\Storage;

trait Sanitizer
{
    /**
     * List of mime types which needs to check.
     */
    public $mimeTypes = [
        'image/svg',
        'image/svg+xml',
    ];

    /**
     * Sanitize SVG file.
     *
     * @param  string  $path
     * @return void
     */
    public function sanitizeSVG($path, $mimeType)
    {
        if ($this->checkMimeType($mimeType)) {
            $sanitizer = new MainSanitizer;

            $sanitizer->removeRemoteReferences(true);

            $dirtySVG = Storage::get($path);

            Storage::put($path, $sanitizer->sanitize($dirtySVG));
        }
    }

    /**
     * Check whether the mime type is allowed.
     *
     * @param  string  $mimeType
     * @return bool
     */
    public function checkMimeType($mimeType)
    {
        return in_array($mimeType, $this->mimeTypes);
    }
}
