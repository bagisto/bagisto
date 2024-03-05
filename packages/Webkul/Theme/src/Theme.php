<?php

namespace Webkul\Theme;

use Illuminate\Support\Facades\Vite;

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
    public function url(string $url)
    {
        $viteUrl = trim($this->vite['package_assets_directory'], '/').'/'.$url;

        return Vite::useHotFile($this->vite['hot_file'])
            ->useBuildDirectory($this->vite['build_directory'])
            ->asset($viteUrl);
    }

    /**
     * Set bagisto vite.
     *
     * @return \Illuminate\Foundation\Vite
     */
    public function setBagistoVite(array $entryPoints)
    {
        return Vite::useHotFile($this->vite['hot_file'])
            ->useBuildDirectory($this->vite['build_directory'])
            ->withEntryPoints($entryPoints);
    }
}
