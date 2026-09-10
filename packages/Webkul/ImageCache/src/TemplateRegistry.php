<?php

namespace Webkul\ImageCache;

use ReflectionClass;
use ReflectionMethod;
use Webkul\ImageCache\Exceptions\InvalidTemplate;

class TemplateRegistry
{
    /**
     * Names the image cache controller answers itself, which no template may take.
     */
    public const RESERVED = ['original', 'download', 'logo'];

    /**
     * The characters a template name may use, since it becomes a url segment and an array key.
     */
    public const NAME_PATTERN = '/^[A-Za-z0-9_-]+$/';

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
     * The templates a theme lists under `customize.image_cache.product_images`, which product image
     * urls carry besides the core sizes, keeping only the names it can resolve.
     */
    public function productImages(?string $themeCode): array
    {
        $declared = $themeCode
            ? config('themes.shop.'.$themeCode.'.customize.image_cache.product_images')
            : null;

        if (! is_array($declared)) {
            return [];
        }

        $templates = $this->all($themeCode);

        return array_values(array_unique(array_filter(
            $declared,
            fn ($name) => $this->isResolvable($themeCode, $name, $templates)
        )));
    }

    /**
     * The storefront theme the requesting channel renders, or none for an admin request, which uses
     * the core templates only.
     */
    public function currentTheme(): ?string
    {
        if ($this->isAdminRequest()) {
            return null;
        }

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
     * Whether a theme's template entry has a usable name and a class the image cache can apply, reporting it otherwise.
     */
    protected function isValid(string $themeCode, mixed $name, mixed $template): bool
    {
        if (
            is_string($name)
            && preg_match(self::NAME_PATTERN, $name)
            && ! in_array($name, self::RESERVED, true)
            && is_string($template)
            && $this->isApplicable($template)
        ) {
            return true;
        }

        report(new InvalidTemplate(
            $themeCode,
            (string) $name,
            'a name of letters, digits, dashes or underscores other than '.implode(', ', self::RESERVED).', mapped to a class with a public applyFilter() method, is required; ['.(is_string($template) ? $template : get_debug_type($template)).'] given'
        ));

        return false;
    }

    /**
     * Whether a name listed for product images is a template the theme resolves, reporting it otherwise.
     */
    protected function isResolvable(string $themeCode, mixed $name, array $templates): bool
    {
        if (
            is_string($name)
            && array_key_exists($name, $templates)
        ) {
            return true;
        }

        report(new InvalidTemplate(
            $themeCode,
            is_string($name) ? $name : get_debug_type($name),
            'it is listed under product_images but is not a registered template'
        ));

        return false;
    }

    /**
     * Whether the request is for the admin panel, which no storefront theme applies to.
     */
    protected function isAdminRequest(): bool
    {
        $adminUrl = trim((string) config('app.admin_url'), '/');

        return request()->is($adminUrl, $adminUrl.'/*');
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
