<?php

namespace Webkul\ImageCache;

use Illuminate\Support\Facades\Storage;
use League\Flysystem\Local\LocalFilesystemAdapter;

class ImageUrlBuilder
{
    /**
     * The image sizes every image carries a url for, whether or not a theme registers them.
     */
    public const CORE_TEMPLATES = ['small', 'medium', 'large'];

    /**
     * Create a new builder instance.
     */
    public function __construct(protected TemplateRegistry $templateRegistry) {}

    /**
     * Get a stored image's url through every template it carries, keyed as `{name}_image_url`, linking
     * each to the stored file instead on a disk the image cache cannot read.
     */
    public function urls(string $path, ?string $key = null): array
    {
        $isDriverLocal = $this->isDriverLocal();

        $urls = [];

        foreach ($this->templateNames($key) as $template) {
            $urls[$template.'_image_url'] = $isDriverLocal
                ? url('cache/'.$template.'/'.$path)
                : Storage::url($path);
        }

        return $urls;
    }

    /**
     * Names of the templates an image carries: the core sizes, those the current theme lists under the
     * given key of `customize.image_cache`, and the original.
     */
    public function templateNames(?string $key = null): array
    {
        $listed = $key
            ? $this->templateRegistry->listed($this->templateRegistry->currentTheme(), $key)
            : [];

        return array_values(array_unique([
            ...self::CORE_TEMPLATES,
            ...$listed,
            'original',
        ]));
    }

    /**
     * Whether images are stored on a local disk, the only kind the image cache reads and resizes.
     */
    protected function isDriverLocal(): bool
    {
        return Storage::getAdapter() instanceof LocalFilesystemAdapter;
    }
}
