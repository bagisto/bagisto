<?php

namespace Webkul\Core\Traits;

use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

trait PDFHandler
{
    /**
     * Download PDF.
     *
     * @param  string  $html
     * @param  string  $fileName
     * @return \Illuminate\Http\Response
     */
    protected function downloadPDF(string $html, ?string $fileName = null)
    {
        if (is_null($fileName)) {
            $fileName = Str::random(32);
        }

        $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');

        return PDF::loadHTML($this->adjustArabicAndPersianContent($html))
            ->setPaper('a4')
            ->download($fileName . '.pdf');
    }

    /**
     * Adjust arabic and persian content.
     *
     * @param  string  $html
     * @return string
     */
    protected function adjustArabicAndPersianContent(string $html)
    {
        $arabic = new \ArPHP\I18N\Arabic();

        $p = $arabic->arIdentify($html);

        for ($i = count($p) - 1; $i >= 0; $i -= 2) {
            $utf8ar = $arabic->utf8Glyphs(substr($html, $p[$i - 1], $p[$i] - $p[$i - 1]));
            $html = substr_replace($html, $utf8ar, $p[$i - 1], $p[$i] - $p[$i - 1]);
        }

        return $html;
    }
}
