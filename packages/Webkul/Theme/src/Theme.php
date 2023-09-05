<?php

namespace Webkul\Theme;

use Illuminate\Support\Facades\Vite;
use Webkul\Theme\Exceptions\ViterNotFound;

class Theme
{
    /**
     * Contains theme parent.
     *
     * @var \Webkul\Theme\Theme
     */
    public $parent;

    /**
     * Create a new theme instance.
     *
     * @param  string  $code
     * @param  string  $name
     * @param  string  $assetsPath
     * @param  string  $viewsPath
     * @return void
     */
    public function __construct(
        public $code,
        public $name = null,
        public $assetsPath = null,
        public $viewsPath = null,
        public $vite = []
    ) {
        $this->assetsPath = $assetsPath === null ? $code : $assetsPath;

        $this->viewsPath = $viewsPath === null ? $code : $viewsPath;
    }

    /**
     * Sets the parent.
     *
     * @param  \Webkul\Theme\Theme
     * @return void
     */
    public function setParent(Theme $parent)
    {
        $this->parent = $parent;
    }

    /**
     * Return the parent.
     *
     * @return \Webkul\Theme\Theme
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Return all the possible view paths.
     *
     * @return array
     */
    public function getViewPaths()
    {
        $paths = [];

        $theme = $this;

        do {
            if (substr($theme->viewsPath, 0, 1) === DIRECTORY_SEPARATOR) {
                $path = base_path(substr($theme->viewsPath, 1));
            } else {
                $path = $theme->viewsPath;
            }

            if (! in_array($path, $paths)) {
                $paths[] = $path;
            }
        } while ($theme = $theme->parent);

        return $paths;
    }

    /**
     * Convert to asset url based on current theme.
     *
     * @return string
     */
    public function url(string $url, ?string $namespace)
    {
        $url = trim($url, '/');

        /**
         * If the namespace is null, it means the theming system is activated. We use the request URI to
         * detect the theme and provide Vite assets based on the current theme.
         */
        if (empty($namespace)) {
            $viteUrl = trim($this->vite['package_assets_directory'], '/') . '/' . $url;

            return Vite::useHotFile($this->vite['hot_file'])
                ->useBuildDirectory($this->vite['build_directory'])
                ->asset($viteUrl);
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
     * Set bagisto vite.
     *
     * @param  array  $entryPoints
     * @return \Illuminate\Foundation\Vite
     */
    public function setBagistoVite($entryPoints)
    {
        return Vite::useHotFile($this->vite['hot_file'])
            ->useBuildDirectory($this->vite['build_directory'])
            ->withEntryPoints($entryPoints);
    }
}
