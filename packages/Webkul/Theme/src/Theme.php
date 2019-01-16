<?php

namespace Webkul\Theme;

use Illuminate\Support\Facades\Config;
use Webkul\Theme\Facades\Themes as Themes;

class Theme
{
    /**
     * Contains theme code
     *
     * @var string
     */
    public $code;

    /**
     * Contains theme name
     *
     * @var string
     */
    public $name;
    
    /**
     * Contains theme views path
     *
     * @var string
     */
    public $viewsPath;

    /**
     * Contains theme assets path
     *
     * @var string
     */
    public $assetsPath;

    /**
     * Contains theme parent
     *
     * @var Theme
     */
    public $parent;

    /**
     * Create a new Theme instance.
     *
     * @param  string $code
     * @param  string $name
     * @param  string $assetsPath
     * @param  string $viewsPath
     * @return void
     */
    public function __construct($code, $name = null, $assetsPath = null, $viewsPath = null)
    {
        $this->code = $code;

        $this->name = $name;

        $this->assetsPath = $assetsPath === null ? $code : $assetsPath;

        $this->viewsPath = $viewsPath === null ? $code : $viewsPath;
    }

    /**
     * Sets the parent
     *
     * @return void
     */
    public function setParent(Theme $parent)
    {
        $this->parent = $parent;
    }

    /**
     * Return the parent
     *
     * @return mixed
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
     * @return mixed
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