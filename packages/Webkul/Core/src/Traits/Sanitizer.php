<?php

namespace Webkul\Core\Traits;

use enshrined\svgSanitize\Sanitizer as SVGSanitizer;
use Illuminate\Support\Facades\Storage;

trait Sanitizer
{
    /**
     * Sanitize content.
     */
    public function sanitizeContent(string $dirtyContent): string
    {
        $allowedTags = '<div><p><b><strong><i><em><a><ul><ol><li>';

        $cleanContent = strip_tags($dirtyContent, $allowedTags);

        $cleanContent = $this->sanitizeBlade($cleanContent);

        return $cleanContent;
    }

    /**
     * Sanitize blade file.
     */
    public function sanitizeBlade(string $dirtyContent): string
    {
        $pattern = '/\{\{.*?\}\}|@.*?(?=\s|\(|\{)/s';

        $cleanContent = preg_replace($pattern, '', $dirtyContent);

        return $cleanContent;
    }

    /**
     * Sanitize SVG file.
     *
     * @param  string  $path
     * @return void
     */
    public function sanitizeSVG($path, $mimeType)
    {
        if (in_array($mimeType, [
            'image/svg',
            'image/svg+xml',
        ])) {
            $dirtySVG = Storage::get($path);

            Storage::put($path, (new SVGSanitizer())->sanitize($dirtySVG));
        }
    }
}
