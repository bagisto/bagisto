<?php

namespace Webkul\Theme;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Vite;
use Webkul\Theme\Exceptions\ViterNotFound;

class Themes
{
    /**
     * The theme the current request renders with.
     *
     * @var Theme|null
     */
    protected $activeTheme = null;

    /**
     * The themes of the panel the request is for.
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
     * Create a new themes instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->laravelViewsPath = Config::get('view.paths');

        $this->loadThemes();
    }

    /**
     * Return the themes of the panel the request is for.
     *
     * @return array
     */
    public function all()
    {
        return $this->themes;
    }

    /**
     * Return the storefront themes a channel can run, whichever panel the request is for.
     *
     * @return array
     */
    public function getChannelThemes()
    {
        $themes = config('themes.shop', []);

        $channelThemes = [];

        foreach ($themes as $code => $data) {
            $channelThemes[] = new Theme(
                $code,
                $data['name'] ?? '',
                $data['assets_path'] ?? '',
                $data['views_path'] ?? '',
                $data['views_namespace'] ?? null,
                $data['vite'] ?? [],
            );
        }

        return $channelThemes;
    }

    /**
     * Check whether a theme with the given code is among those of the panel the request is for.
     *
     * @return bool
     */
    public function exists(string $themeName)
    {
        foreach ($this->themes as $theme) {
            if ($theme->code == $themeName) {
                return true;
            }
        }

        return false;
    }

    /**
     * Prepare all themes, the admin ones for an admin request and the storefront ones for any other.
     *
     * @return void
     */
    public function loadThemes()
    {
        $parentThemes = [];

        if ($this->isAdminRequest()) {
            $themes = config('themes.admin', []);
        } else {
            $themes = config('themes.shop', []);
        }

        foreach ($themes as $code => $data) {
            $this->themes[] = new Theme(
                $code,
                $data['name'] ?? '',
                $data['assets_path'] ?? '',
                $data['views_path'] ?? '',
                $data['views_namespace'] ?? null,
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
     * @return Theme
     */
    public function set(string $themeName)
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
     * @return Theme|null
     */
    public function current()
    {
        return $this->activeTheme ?? null;
    }

    /**
     * Get current theme's name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->current()?->name ?? '';
    }

    /**
     * Find a theme of the panel the request is for by its code.
     *
     * @return Theme
     */
    public function find(string $themeName)
    {
        foreach ($this->themes as $theme) {
            if ($theme->code == $themeName) {
                return $theme;
            }
        }

        throw new Exceptions\ThemeNotFound($themeName);
    }

    /**
     * Return the view paths configured when this registry was created, which set() puts after a theme's own.
     *
     * @return array
     */
    public function getLaravelViewPaths()
    {
        return $this->laravelViewsPath;
    }

    /**
     * Return an asset url from the current theme, initialising one when none is set yet, or, given a
     * namespace, from the vite registered for it under `bagisto-vite.viters`.
     *
     * @return string
     */
    public function url(string $filename, ?string $namespace = null)
    {
        $url = trim($filename, '/');

        if (empty($namespace)) {
            $currentTheme = $this->current();

            if (! $currentTheme) {
                $this->ensureThemeInitialized();

                $currentTheme = $this->current();
            }

            return $currentTheme->url($url);
        }

        $viters = config('bagisto-vite.viters');

        if (empty($viters[$namespace])) {
            throw new ViterNotFound($namespace);
        }

        $viteUrl = trim($viters[$namespace]['package_assets_directory'], '/').'/'.$url;

        return Vite::useHotFile($viters[$namespace]['hot_file'])
            ->useBuildDirectory($viters[$namespace]['build_directory'])
            ->asset($viteUrl);
    }

    /**
     * Set bagisto vite from the current theme, initialising one when none is set yet, or, given a
     * namespace, from the vite registered for it under `bagisto-vite.viters`.
     *
     * @param  mixed  $entryPoints
     * @return mixed
     */
    public function setBagistoVite($entryPoints, ?string $namespace = null)
    {
        if (empty($namespace)) {
            $currentTheme = $this->current();

            if (! $currentTheme) {
                $this->ensureThemeInitialized();

                $currentTheme = $this->current();
            }

            return $currentTheme->setBagistoVite($entryPoints);
        }

        $viters = config('bagisto-vite.viters');

        if (empty($viters[$namespace])) {
            throw new ViterNotFound($namespace);
        }

        return Vite::useHotFile($viters[$namespace]['hot_file'])
            ->useBuildDirectory($viters[$namespace]['build_directory'])
            ->withEntryPoints($entryPoints);
    }

    /**
     * Reload the themes for the current request and set the first one when none is active, as under
     * Octane, where a worker can reach a view before any theme is set.
     *
     * @return void
     */
    protected function ensureThemeInitialized()
    {
        if (! $this->activeTheme) {
            $this->loadThemes();

            if (
                ! $this->activeTheme
                && ! empty($this->themes)
            ) {
                $defaultTheme = $this->themes[0];

                $this->set($defaultTheme->code);
            }
        }
    }

    /**
     * Whether the current request is for the admin panel, taking one whose context cannot be read, as can
     * happen under Octane, for the storefront.
     */
    protected function isAdminRequest(): bool
    {
        try {
            return is_admin_request();
        } catch (\Exception) {
            return false;
        }
    }
}
