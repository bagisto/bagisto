<?php

namespace Webkul\Core\Traits;

use enshrined\svgSanitize\Sanitizer as MainSanitizer;
use Illuminate\Support\Facades\Storage;

trait Sanitizer
{
    /**
     * List of mime types which needs to check.
     *
     * @var array
     */
    public $mimeTypes = [
        'image/svg',
        'image/svg+xml',
    ];

    /**
     * The parse issues the last refused SVG reported, for a caller that wants to log them.
     *
     * @var array
     */
    public $svgSanitizeIssues = [];

    /**
     * Sanitize the SVG file in place, reporting whether it could be read at all.
     * A file that cannot be parsed is removed rather than left unsanitized or empty.
     *
     * @param  string  $path
     * @param  string  $mimeType
     */
    public function sanitizeSVG($path, $mimeType): bool
    {
        $this->svgSanitizeIssues = [];

        if (! $this->checkMimeType($mimeType)) {
            return true;
        }

        $sanitizer = new MainSanitizer;

        $sanitizer->removeRemoteReferences(true);

        $dirtySVG = Storage::get($path);

        $cleanSVG = $sanitizer->sanitize($dirtySVG);

        if (
            ! is_string($cleanSVG)
            || $cleanSVG === ''
        ) {
            $this->svgSanitizeIssues = $sanitizer->getXmlIssues();

            Storage::delete($path);

            return false;
        }

        Storage::put($path, $cleanSVG);

        return true;
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
