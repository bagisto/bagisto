<?php

namespace Webkul\Theme;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\View\FileViewFinder;
use Webkul\Theme\Facades\Themes;

class ThemeViewFinder extends FileViewFinder
{
    /**
     * Override findNamespacedView() to add "resources/themes/theme_name/views/..." paths
     *
     * @param  string  $name
     * @return string
     */
    protected function findNamespacedView($name)
    {
        // Extract the $view and the $namespace parts
        [$namespace, $view] = $this->parseNamespaceSegments($name);

        if (! Str::contains(request()->url(), config('app.admin_url').'/')) {
            $paths = $this->addThemeNamespacePaths($namespace);

            try {
                return $this->findInPaths($view, $paths);
            } catch (\Exception $e) {
                if ($namespace !== 'shop') {
                    if (strpos($view, 'shop.') !== false) {
                        $view = str_replace('shop.', 'shop.'.Themes::current()->code.'.', $view);
                    }
                }

                return $this->findInPaths($view, $paths);
            }
        } else {
            $themes = themes();

            $themes->set(config('themes.admin-default'));

            $paths = $this->addThemeNamespacePaths($namespace);

            try {
                return $this->findInPaths($view, $paths);
            } catch (\Exception $e) {
                if ($namespace != 'admin') {
                    if (strpos($view, 'admin.') !== false) {
                        $view = str_replace('admin.', 'admin.'.Themes::current()->code.'.', $view);
                    }
                }

                return $this->findInPaths($view, $paths);
            }
        }
    }

    /**
     * @param  string  $namespace
     * @return array
     */
    public function addThemeNamespacePaths($namespace)
    {
        if (! isset($this->hints[$namespace])) {
            return [];
        }

        $paths = $this->hints[$namespace];

        $searchPaths = array_diff($this->paths, Themes::getLaravelViewPaths());

        foreach (array_reverse($searchPaths) as $path) {
            $newPath = base_path().'/'.$path;

            $paths = Arr::prepend($paths, $newPath);
        }

        return $paths;
    }

    /**
     * Override replaceNamespace() to add path for custom error pages "resources/themes/theme_name/views/errors/..."
     *
     * @param  string  $namespace
     * @param  string|array  $hints
     * @return void
     */
    public function replaceNamespace($namespace, $hints)
    {
        $this->hints[$namespace] = (array) $hints;

        // Overide Error Pages
        if (
            $namespace == 'errors'
            || $namespace == 'mails'
        ) {
            $searchPaths = array_diff($this->paths, Themes::getLaravelViewPaths());

            $addPaths = array_map(function ($path) use ($namespace) {
                return base_path().'/'."$path/$namespace";
            }, $searchPaths);

            $this->prependNamespace($namespace, $addPaths);
        }
    }

    /**
     * Set the array of paths where the views are being searched.
     *
     * @param  array  $paths
     * @return void
     */
    public function setPaths($paths)
    {
        $this->paths = $paths;

        $this->flush();
    }
}
