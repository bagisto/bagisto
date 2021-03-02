<?php

namespace Webkul\Core\Traits;

use Illuminate\Support\Facades\Storage;
use enshrined\svgSanitize\Sanitizer as MainSanitizer;

trait Sanitizer
{
    /**
     * Sanitize SVG file.
     *
     * @param  string  $path
     * @return void
     */
    public function sanitizeSVG($path)
    {
        /* sanitizer instance */
        $sanitizer = new MainSanitizer();

        /* grab svg file */
        $dirtySVG = Storage::get($path);

        /* save sanitized svg */
        Storage::put($path, $sanitizer->sanitize($dirtySVG));
    }
}