<?php

namespace Webkul\Theme;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\Str;
use Webkul\Theme\Exceptions\ViterNotFound;

class Themes
{
    /**
     * Contains current activated theme code.
     *
     * @var string
     */
    protected $activeTheme = null;

    /**
     * Contains all themes.
     *
     * @var array
     */
    protected $themes = [];

    /**
     * Contains laravel default view paths.
     *
     * @var array
     */
    protected $laravelViewsPath;

    /**
     * Contains default theme code.
     *
     * @var string
     */
    protected $defaultThemeCode = 'default';

    /**
     * Create a new themes instance.
     *
     * @return void
     */
    public function __construct()
    {
        if (! Str::contains(request()->url(), config('app.admin_url') . '/')) {
            $this->defaultThemeCode = Config::get('themes.admin-default', null);
        } else {
            $this->defaultThemeCode = Config::get('themes.default', null);
        }

        $this->laravelViewsPath = Config::get('view.paths');

        $this->loadThemes();
    }

    /**
     * Return list of all registered themes.
     *
     * @return array
     */
    public function all()
    {
        return $this->themes;
    }

    /**
     * Return list of registered themes.
     *
     * @return array
     */
    public function getChannelThemes()
    {
        $themes = config('themes.themes', []);

        $channelThemes = [];

        foreach ($themes as $code => $data) {
            $channelThemes[] = new Theme(
                $code,
                $data['name'] ?? '',
                $data['assets_path'] ?? '',
                $data['views_path'] ?? '',
                isset($data['vite']) ? $data['vite'] : [],
            );

            if (! empty($data['parent'])) {
                $parentThemes[$code] = $data['parent'];
            }
        }

        return $channelThemes;
    }

    /**
     * Check if specified exists.
     *
     * @param  string  $themeName
     * @return bool
     */
    public function exists($themeName)
    {
        foreach ($this->themes as $theme) {
            if ($theme->code == $themeName) {
                return true;
            }
        }

        return false;
    }

    /**
     * Prepare all themes.
     *
     * @return \Webkul\Theme\Theme
     */
    public function loadThemes()
    {
        $parentThemes = [];

        if (Str::contains(request()->url(), config('app.admin_url') . '/')) {
            $themes = config('themes.admin-themes', []);
        } else {
            $themes = config('themes.themes', []);
        }

        foreach ($themes as $code => $data) {
            $this->themes[] = new Theme(
                $code,
                $data['name'] ?? '',
                $data['assets_path'] ?? '',
                $data['views_path'] ?? '',
                $data['vite'] ?? [],
            );

            if (! empty($data['parent'])) {
                $parentThemes[$code] = $data['parent'];
            }
        }

        foreach ($parentThemes as $childCode => $parentCode) {
            $child = $this->find($childCode);

            if ($this->exists($parentCode)) {
                $parent = $this->find($parentCode);
            } else {
                $parent = new Theme($parentCode);
            }

            $child->setParent($parent);
        }
    }

    /**
     * Enable theme.
     *
     * @param  string  $themeName
     * @return \Webkul\Theme\Theme
     */
    public function set($themeName)
    {
        if ($this->exists($themeName)) {
            $theme = $this->find($themeName);
        } else {
            $theme = new Theme($themeName);
        }

        $this->activeTheme = $theme;

        $paths = $theme->getViewPaths();

        foreach ($this->laravelViewsPath as $path) {
            if (! in_array($path, $paths)) {
                $paths[] = $path;
            }
        }

        Config::set('view.paths', $paths);

        $themeViewFinder = app('view.finder');

        $themeViewFinder->setPaths($paths);

        return $theme;
    }

    /**
     * Get current theme.
     *
     * @return \Webkul\Theme\Theme
     */
    public function current()
    {
        return $this->activeTheme ? $this->activeTheme : null;
    }

    /**
     * Get current theme's name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->current() ? $this->current()->name : '';
    }

    /**
     * Find a theme by it's name.
     *
     * @param  string  $themeName
     * @return \Webkul\Theme\Theme
     */
    public function find($themeName)
    {
        foreach ($this->themes as $theme) {
            if ($theme->code == $themeName) {
                return $theme;
            }
        }

        throw new Exceptions\ThemeNotFound($themeName);
    }

    /**
     * Original view paths defined in `config.view.php`.
     *
     * @return array
     */
    public function getLaravelViewPaths()
    {
        return $this->laravelViewsPath;
    }

    /**
     * Return the asset URL of the current theme if a theme is found; otherwise, check from the namespace.
     *
     * @return string
     */
    public function url(string $filename, ?string $namespace = null)
    {
        $url = trim($filename, '/');

        /**
         * If the namespace is null, it means the theming system is activated. We use the request URI to
         * detect the theme and provide Vite assets based on the current theme.
         */
        if (empty($namespace)) {
            return $this->current()->url($url);
        }

        /**
         * If a namespace is provided, it means the developer knows what they are doing and must create the
         * registry in the provided configuration. We will analyze based on that.
         */
        $viters = config('bagisto-vite.viters');

        if (empty($viters[$namespace])) {
            throw new ViterNotFound($namespace);
        }

        $viteUrl = trim($viters[$namespace]['package_assets_directory'], '/') . '/' . $url;

        return Vite::useHotFile($viters[$namespace]['hot_file'])
            ->useBuildDirectory($viters[$namespace]['build_directory'])
            ->asset($viteUrl);
    }

    /**
     * Set bagisto vite in current theme.
     *
     * @param  mixed  $entryPoints
     * @return mixed
     */
    public function setBagistoVite($entryPoints, ?string $namespace = null)
    {
        /**
         * If the namespace is null, it means the theming system is activated. We use the request URI to
         * detect the theme and provide Vite assets based on the current theme.
         */
        if (empty($namespace)) {
            return $this->current()->setBagistoVite($entryPoints);
        }

        /**
         * If a namespace is provided, it means the developer knows what they are doing and must create the
         * registry in the provided configuration. We will analyze based on that.
         */
        $viters = config('bagisto-vite.viters');

        if (empty($viters[$namespace])) {
            throw new ViterNotFound($namespace);
        }

        return Vite::useHotFile($viters[$namespace]['hot_file'])
            ->useBuildDirectory($viters[$namespace]['build_directory'])
            ->withEntryPoints($entryPoints);
    }
}
