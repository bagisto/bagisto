<?php

namespace Webkul\Core\Traits;

use enshrined\svgSanitize\Sanitizer as MainSanitizer;
use Illuminate\Support\Facades\Storage;

trait Sanitizer
{
    /**
     * The leading characters a spreadsheet reads as the start of a formula.
     */
    public const FORMULA_TRIGGERS = ['=', '+', '-', '@', '|', '%'];

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
     * Quote a value a spreadsheet would otherwise read as a formula, leaving its own spacing intact.
     */
    public function sanitizeSpreadsheetValue(mixed $value): mixed
    {
        if (! is_string($value)) {
            return $value;
        }

        $trimmed = ltrim($value, " \t\n\r\0\x0B\f");

        if ($trimmed === '') {
            return $value;
        }

        return in_array(mb_substr($trimmed, 0, 1), self::FORMULA_TRIGGERS, true)
            ? "'".$value
            : $value;
    }

    /**
     * Check whether the mime type is allowed.
     *
     * @param  string  $mimeType
     * @return bool
     */
    protected function checkMimeType($mimeType)
    {
        return in_array($mimeType, $this->mimeTypes);
    }
}
