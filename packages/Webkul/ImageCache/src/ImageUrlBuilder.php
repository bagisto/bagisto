<?php

namespace Webkul\ImageCache;

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
     * Get a stored image's url through every template it carries, keyed as `{name}_image_url`, served by
     * the image cache, which reads through the configured disk whichever disk that is.
     */
    public function urls(string $path, ?string $key = null): array
    {
        $urls = [];

        foreach ($this->templateNames($key) as $template) {
            $urls[$template.'_image_url'] = url('cache/'.$template.'/'.$path);
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
}
