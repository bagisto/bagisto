<?php

namespace Webkul\ImageCache;

use Closure;
use Illuminate\Support\Facades\Vite;
use ReflectionClass;
use ReflectionMethod;
use Throwable;
use Webkul\ImageCache\Exceptions\InvalidTemplate;
use Webkul\ImageCache\Templates\Large;
use Webkul\ImageCache\Templates\Medium;
use Webkul\ImageCache\Templates\Small;

class TemplateRegistry
{
    /**
     * Names the image cache controller answers itself in any letter case, which no template may take.
     */
    public const RESERVED = ['original', 'download', 'logo'];

    /**
     * The characters a template name may use, since it becomes a url segment and an array key.
     */
    public const NAME_PATTERN = '/^[A-Za-z0-9_-]+$/';

    /**
     * The mark joining a template name to the theme a url names for it, as in `small@fashion`, which the name
     * pattern keeps out of every template name.
     */
    public const THEME_SEPARATOR = '@';

    /**
     * Set on the request's internal attribute bag to cut its images for a theme its host may not run, which
     * every image url then names, as the appearance preview does.
     */
    public const PINNED_THEME = 'imagecache_pinned_theme';

    /**
     * The package's own templates, serving a core size the configuration leaves out or maps to something the
     * image cache cannot apply.
     */
    public const DEFAULTS = [
        'small' => Small::class,
        'medium' => Medium::class,
        'large' => Large::class,
    ];

    /**
     * The key under `customize.image_cache` listing the templates product images carry.
     */
    public const PRODUCT_IMAGES = 'product_images';

    /**
     * The key under `customize.image_cache` listing the templates category logos and banners carry.
     */
    public const CATEGORY_IMAGES = 'category_images';

    /**
     * The key under `customize.image_cache` listing the templates attribute option image swatches carry.
     */
    public const SWATCH_IMAGES = 'swatch_images';

    /**
     * The template sets already checked, each beside the configured value it was checked from, so a page full of
     * image urls checks and reports each set once.
     */
    protected array $checked = [];

    /**
     * The core templates: the package's own, overridden and extended by the valid ones configured under
     * `imagecache.templates`, reporting any other.
     */
    public function core(): array
    {
        $configured = config('imagecache.templates');

        if (! is_array($configured)) {
            return self::DEFAULTS;
        }

        return $this->checkedOnce('core', $configured, fn () => array_replace(self::DEFAULTS, array_filter(
            $configured,
            fn ($template, $name) => $this->isValid(null, $name, $template),
            ARRAY_FILTER_USE_BOTH
        )));
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

        return $this->checkedOnce('theme:'.$themeCode, $declared, fn () => array_filter(
            $declared,
            fn ($template, $name) => $this->isValid($themeCode, $name, $template),
            ARRAY_FILTER_USE_BOTH
        ));
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
     * The distinct templates a theme lists under a key of `customize.image_cache`, such as
     * `product_images`, keeping only the names it can resolve.
     */
    public function listed(?string $themeCode, string $key): array
    {
        $declared = $themeCode
            ? config('themes.shop.'.$themeCode.'.customize.image_cache.'.$key)
            : null;

        if (! is_array($declared)) {
            return [];
        }

        $templates = $this->all($themeCode);

        return array_values(array_unique(array_filter(
            $declared,
            fn ($name) => $this->isResolvable($themeCode, $key, $name, $templates)
        )));
    }

    /**
     * The url of the placeholder a theme ships in its Vite build for a template, or null when it ships
     * none or one its build cannot resolve, reporting that.
     */
    public function placeholderUrl(?string $themeCode, string $template): ?string
    {
        $path = $themeCode
            ? config('themes.shop.'.$themeCode.'.customize.image_cache.placeholders.'.$template)
            : null;

        if (is_null($path)) {
            return null;
        }

        if (
            is_string($path)
            && filled($path)
            && $url = $this->themeAssetUrl($themeCode, $path)
        ) {
            return $url;
        }

        report(new InvalidTemplate(
            $themeCode,
            $template,
            'its placeholder must be the path of an image in the theme Vite build; ['.(is_string($path) ? $path : get_debug_type($path)).'] given'
        ));

        return null;
    }

    /**
     * The installed theme the request's images are pinned to, if there is one.
     */
    public function pinnedTheme(): ?string
    {
        $themeCode = request()->attributes->get(self::PINNED_THEME);

        if (
            is_string($themeCode)
            && $this->isInstalled($themeCode)
        ) {
            return $themeCode;
        }

        return null;
    }

    /**
     * The theme the request's images are cut for: the one pinned on it, otherwise the one its channel renders,
     * or none for an admin request, which uses the core templates only.
     */
    public function currentTheme(): ?string
    {
        if (is_admin_request()) {
            return null;
        }

        $pinned = $this->pinnedTheme();

        if ($pinned) {
            return $pinned;
        }

        $themeCode = core()->getCurrentChannel()?->theme;

        if (
            $themeCode
            && $this->isInstalled($themeCode)
        ) {
            return $themeCode;
        }

        return config('themes.shop-default');
    }

    /**
     * The url segment of a template, naming the pinned theme so the image cache cuts it as that theme does on
     * any host, while a reserved name, which no theme cuts, stays bare.
     */
    public function qualify(string $template): string
    {
        $themeCode = $this->pinnedTheme();

        if (
            is_null($themeCode)
            || in_array(strtolower($template), self::RESERVED, true)
        ) {
            return $template;
        }

        return $template.self::THEME_SEPARATOR.$themeCode;
    }

    /**
     * The template name and theme a url segment asks for: an installed theme it names, as in `small@fashion`,
     * or else the current one, under which a segment naming any other theme matches no template.
     *
     * @return array{0: string, 1: ?string}
     */
    public function resolve(string $segment): array
    {
        [$name, $themeCode] = array_pad(explode(self::THEME_SEPARATOR, $segment, 2), 2, null);

        if (
            is_null($themeCode)
            || ! $this->isInstalled($themeCode)
        ) {
            return [$segment, $this->currentTheme()];
        }

        return [$name, $themeCode];
    }

    /**
     * Check a configured template set only when it differs from the value it was last checked from, returning
     * what that check kept.
     */
    protected function checkedOnce(string $key, array $configured, Closure $check): array
    {
        if (
            isset($this->checked[$key])
            && $this->checked[$key]['configured'] === $configured
        ) {
            return $this->checked[$key]['templates'];
        }

        $this->checked[$key] = [
            'configured' => $configured,
            'templates' => $check(),
        ];

        return $this->checked[$key]['templates'];
    }

    /**
     * Whether a template entry of a theme, or of the core when no theme is given, has a usable name and a class
     * the image cache can apply, reporting it otherwise.
     */
    protected function isValid(?string $themeCode, mixed $name, mixed $template): bool
    {
        if (
            is_string($name)
            && preg_match(self::NAME_PATTERN, $name)
            && ! in_array(strtolower($name), self::RESERVED, true)
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
     * Whether a name listed under a key of `customize.image_cache` is a template the theme resolves,
     * reporting it otherwise.
     */
    protected function isResolvable(string $themeCode, string $key, mixed $name, array $templates): bool
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
            'it is listed under '.$key.' but is not a registered template'
        ));

        return false;
    }

    /**
     * The url of an asset in a theme's Vite build, or null when the build does not have it.
     */
    protected function themeAssetUrl(string $themeCode, string $path): ?string
    {
        $vite = config('themes.shop.'.$themeCode.'.vite');

        try {
            return Vite::useHotFile($vite['hot_file'])
                ->useBuildDirectory($vite['build_directory'])
                ->asset(trim($vite['package_assets_directory'], '/').'/'.trim($path, '/'));
        } catch (Throwable) {
            return null;
        }
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

    /**
     * Whether a theme is registered for the storefront, looked up by its exact code rather than as a config path.
     */
    protected function isInstalled(string $themeCode): bool
    {
        return is_array(config('themes.shop', [])[$themeCode] ?? null);
    }
}
