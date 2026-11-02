<?php

namespace Webkul\ImageCache;

use Illuminate\Support\Facades\Storage;

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
     * The path the image cache route sits under, `imagecache.route` trimmed of slashes, or null when it names
     * none, which leaves the image cache off.
     */
    public static function route(): ?string
    {
        $route = config('imagecache.route');

        if (! is_string($route)) {
            return null;
        }

        $route = trim($route, '/');

        return $route === '' ? null : $route;
    }

    /**
     * Get a stored image's url through one template, on the image cache route and naming any theme the request's
     * images are pinned to, or null while the image cache route is off.
     */
    public function url(string $path, string $template): ?string
    {
        $route = static::route();

        if (is_null($route)) {
            return null;
        }

        return url($route.'/'.$this->templateRegistry->qualify($template).'/'.$path);
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
     * Get a stored image's urls through every template it carries, keyed as `{name}_image_url`, served by
     * the image cache from any disk, or linking the stored file itself while the image cache route is off.
     */
    public function urls(string $path, ?string $key = null): array
    {
        $urls = [];

        foreach ($this->templateNames($key) as $template) {
            $urls[$template.'_image_url'] = $this->url($path, $template) ?? Storage::url($path);
        }

        return $urls;
    }
}
