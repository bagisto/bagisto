<?php

namespace Webkul\Core\Traits;

use Illuminate\Support\Facades\Storage;
use enshrined\svgSanitize\Sanitizer as MainSanitizer;

trait Sanitizer
{
    /**
     * List of mime types which needs to check.
     */
    public $mimeTypes = [
        'image/svg',
        'image/svg+xml'
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
            /* sanitizer instance */
            $sanitizer = new MainSanitizer();

            /* grab svg file */
            $dirtySVG = Storage::disk(config('bagisto_filesystem.default'))->get($path);

            /* save sanitized svg */
            Storage::disk(config('bagisto_filesystem.default'))->put($path, $sanitizer->sanitize($dirtySVG));
        }
    }

    /**
     * Sanitize SVG file.
     *
     * @param  string  $path
     * @return void
     */
    public function checkMimeType($mimeType)
    {
        return in_array($mimeType, $this->mimeTypes);
    }
}