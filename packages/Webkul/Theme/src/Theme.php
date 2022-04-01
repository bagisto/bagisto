<?php

namespace Webkul\Theme;

use Illuminate\Support\Facades\Config;
use Webkul\Theme\Facades\Themes as Themes;

class Theme
{
    /**
     * Contains theme parent
     *
     * @var Theme
     */
    public $parent;

    /**
     * Create a new Theme instance.
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
        public $viewsPath = null
    )
    {
        $this->assetsPath = $assetsPath === null ? $code : $assetsPath;

        $this->viewsPath = $viewsPath === null ? $code : $viewsPath;
    }

    /**
     * Sets the parent
     *
     * @param  \Webkul\Theme\Theme
     * @return void
     */
    public function setParent(Theme $parent)
    {
        $this->parent = $parent;
    }

    /**
     * Return the parent
     *
     * @return \Webkul\Theme\Theme
     */
    public function getParent()
    {
        return $this->parent;
    }
    
    /**
     * Return all the possible view paths
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
     * Convert to asset url based on current theme
     *
     * @param  string  $url
     * @param  bool|null  $secure
     * @return string
     */
    public function url($url, $secure = null)
    {
        $url = ltrim($url, '/');

        if (preg_match('/^((http(s?):)?\/\/)/i', $url)) {
            return $url;
        }
        
        if (preg_match('/^((http(s?):)?\/\/)/i', $this->assetsPath)) {
            return $this->assetsPath . '/' . $url;
        }

        $fullUrl = str_replace("public/", "", $this->assetsPath) . '/' . $url;

        if (file_exists(public_path($fullUrl))) {
            return asset($fullUrl, $secure);
        }

        if ($parentTheme = $this->getParent()) {
            return $parentTheme->url($url);
        }

        return asset($url, $secure);
    }
}