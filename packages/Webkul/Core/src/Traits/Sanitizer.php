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
