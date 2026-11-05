<?php

namespace Webkul\Product;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Webkul\Product\Contracts\Product;

class ProductVideo
{
    /**
     * The video formats a product takes, each extension keyed to the MIME type it is uploaded as.
     */
    public const FORMATS = [
        'mp4' => 'video/mp4',
        'mov' => 'video/quicktime',
        'webm' => 'video/webm',
    ];

    /**
     * Get the extensions a product video may have, each with its leading dot.
     */
    public function getAllowedExtensions(): Collection
    {
        return collect(self::FORMATS)
            ->keys()
            ->map(fn (string $extension) => '.'.$extension);
    }

    /**
     * Get the MIME types a product video may be uploaded as.
     */
    public function getAllowedMimeTypes(): Collection
    {
        return collect(self::FORMATS)->values();
    }

    /**
     * Get the largest video that can be uploaded for a product, in kilobytes, which is the
     * configured limit capped by what PHP itself accepts.
     */
    public function getMaxUploadSize(): int
    {
        $configured = (int) (core()->getConfigData('catalog.products.attribute.file_attribute_upload_size') ?: 10240);

        return min($configured, core()->getMaxUploadSize());
    }

    /**
     * Get the product's videos whose files are still in storage, each with its type and URL.
     *
     * @return array<int, array{type: string, video_url: string}>
     */
    public function getVideos(?Product $product): array
    {
        if (! $product) {
            return [];
        }

        $videos = [];

        foreach ($product->videos as $video) {
            if (! Storage::has($video->path)) {
                continue;
            }

            $videos[] = [
                'type' => $video->type,
                'video_url' => Storage::url($video->path),
            ];
        }

        return $videos;
    }
}
