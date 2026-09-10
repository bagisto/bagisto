<?php

namespace Webkul\ImageCache;

use ReflectionClass;
use ReflectionMethod;
use Webkul\ImageCache\Exceptions\InvalidTemplate;

class TemplateRegistry
{
    /**
     * The core templates, as configured under `imagecache.templates`.
     */
    public function core(): array
    {
        return config('imagecache.templates', []);
    }

    /**
     * The valid templates a theme registers under `customize.image_cache.templates` in `config/themes.php`.
     */
    public function theme(?string $themeCode): array
    {
        $declared = $themeCode
            ? config('themes.shop.'.$themeCode.'.customize.image_cache.templates')
            : null;

        if (! is_array($declared)) {
            return [];
        }

        return array_filter(
            $declared,
            fn ($template, $name) => $this->isValid($themeCode, $name, $template),
            ARRAY_FILTER_USE_BOTH
        );
    }

    /**
     * The templates a theme renders with: the core ones, overridden and extended by its own.
     */
    public function all(?string $themeCode = null): array
    {
        return array_replace($this->core(), $this->theme($themeCode));
    }

    /**
     * The template registered under a name for a theme, or null when there is none.
     */
    public function find(string $name, ?string $themeCode = null): mixed
    {
        return $this->all($themeCode)[$name] ?? null;
    }

    /**
     * Whether a template is registered under a name for a theme.
     */
    public function has(string $name, ?string $themeCode = null): bool
    {
        return ! is_null($this->find($name, $themeCode));
    }

    /**
     * The theme the channel serving the request renders, falling back to the default storefront theme.
     */
    public function currentTheme(): ?string
    {
        $themeCode = core()->getCurrentChannel()?->theme;

        if (
            $themeCode
            && is_array(config('themes.shop.'.$themeCode))
        ) {
            return $themeCode;
        }

        return config('themes.shop-default');
    }

    /**
     * Whether a theme's template entry names a class the image cache can apply, reporting it otherwise.
     */
    protected function isValid(string $themeCode, mixed $name, mixed $template): bool
    {
        if (
            is_string($name)
            && is_string($template)
            && $this->isApplicable($template)
        ) {
            return true;
        }

        report(new InvalidTemplate($themeCode, (string) $name, is_string($template) ? $template : get_debug_type($template)));

        return false;
    }

    /**
     * Whether a class can be instantiated and exposes a public applyFilter() method.
     */
    protected function isApplicable(string $class): bool
    {
        if (
            ! class_exists($class)
            || ! method_exists($class, 'applyFilter')
        ) {
            return false;
        }

        return (new ReflectionClass($class))->isInstantiable()
            && (new ReflectionMethod($class, 'applyFilter'))->isPublic();
    }
}
