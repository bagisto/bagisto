<?php

namespace Webkul\Core\Traits;

use ArPHP\I18N\Arabic;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Mpdf\Mpdf;

trait PDFHandler
{
    /**
     * Download PDF as a streamed response.
     */
    protected function downloadPDF(string $html, ?string $fileName = null)
    {
        $fileName = $this->resolvePdfFileName($fileName);
        $html = $this->preparePdfHtml($html);

        if ($this->isRtlLocale()) {
            $mpdf = $this->buildMpdf($html);

            return response()->streamDownload(
                fn () => print ($mpdf->Output('', 'S')),
                $fileName.'.pdf'
            );
        }

        return PDF::loadHTML($html)
            ->setPaper('A4', 'portrait')
            ->set_option('defaultFont', 'Courier')
            ->download($fileName.'.pdf');
    }

    /**
     * Generate raw PDF content (e.g. for email attachments).
     */
    protected function generatePdf(string $html): string
    {
        $html = $this->preparePdfHtml($html);

        if ($this->isRtlLocale()) {
            return $this->buildMpdf($html)->Output('', 'S');
        }

        return PDF::loadHTML($html)
            ->setPaper('A4', 'portrait')
            ->set_option('defaultFont', 'Courier')
            ->output();
    }

    /**
     * Build and configure an mPDF instance with the given HTML.
     */
    private function buildMpdf(string $html): Mpdf
    {
        $mpdf = new Mpdf([
            'margin_left' => 0,
            'margin_right' => 0,
            'margin_top' => 0,
            'margin_bottom' => 0,
        ]);

        $mpdf->SetDirectionality('rtl');
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML($html);

        return $mpdf;
    }

    /**
     * Prepare HTML for PDF rendering: encode and adjust Arabic/Persian glyphs.
     */
    private function preparePdfHtml(string $html): string
    {
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');

        return $this->adjustArabicAndPersianContent($html);
    }

    /**
     * Determine if the current locale uses RTL direction.
     */
    private function isRtlLocale(): bool
    {
        return core()->getCurrentLocale()->direction === 'rtl';
    }

    /**
     * Resolve a PDF file name, generating a random one if not provided.
     */
    private function resolvePdfFileName(?string $fileName): string
    {
        return $fileName ?? Str::random(32);
    }

    /**
     * Adjust Arabic and Persian glyph rendering for PDF output.
     */
    private function adjustArabicAndPersianContent(string $html): string
    {
        $arabic = new Arabic;

        $positions = $arabic->arIdentify($html);

        for ($i = count($positions) - 1; $i >= 0; $i -= 2) {
            $segment = substr($html, $positions[$i - 1], $positions[$i] - $positions[$i - 1]);
            $converted = $arabic->utf8Glyphs($segment);
            $html = substr_replace($html, $converted, $positions[$i - 1], $positions[$i] - $positions[$i - 1]);
        }

        return $html;
    }
}
