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
	 * @param string $path
	 * @param string $mimeType
	 * @return void
	 */
    public function sanitizeSVG(string $path, string $mimeType): void
    {
        if ($this->checkMimeType($mimeType)) {
            /* sanitizer instance */
            $sanitizer = new MainSanitizer();

            /* grab svg file */
            $dirtySVG = Storage::get($path);

            /* save sanitized svg */
            Storage::put($path, $sanitizer->sanitize($dirtySVG));
        }
    }

    /**
     * Check mime type of SVG file.
     *
     * @param  string  $mimeType name
     * @return bool true if matches
	 */
	public function checkMimeType(string $mimeType): bool
	{
        return in_array($mimeType, $this->mimeTypes, true);
    }
}
