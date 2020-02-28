<?php 

namespace Webkul\Theme;

use Webkul\Theme\Facades\Themes;
use Illuminate\Support\Arr;
use Illuminate\View\FileViewFinder;

class ThemeViewFinder extends FileViewFinder
{
    /*
     * Override findNamespacedView() to add "resources/themes/theme_name/views/..." paths
     *
     * @param  string  $name
     * @return string
     */
    protected function findNamespacedView($name)
    {
        // Extract the $view and the $namespace parts
        list($namespace, $view) = $this->parseNamespaceSegments($name);
        
        if ($namespace != 'admin') {
            $paths = $this->addThemeNamespacePaths($namespace);

            // Find and return the view
            return $this->findInPaths($view, $paths);
        } else {
            return $this->findInPaths($view, $this->hints[$namespace]);
        }
    }

    public function addThemeNamespacePaths($namespace)
    {
        if (! isset($this->hints[$namespace])) {
            return [];
        }

        $paths = $this->hints[$namespace];

        $searchPaths = array_diff($this->paths, Themes::getLaravelViewPaths());

        foreach (array_reverse($searchPaths) as $path) {
            $newPath = base_path() . '/' . $path;

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
        if ($namespace == 'errors' || $namespace == 'mails') {
            $searchPaths = array_diff($this->paths, Themes::getLaravelViewPaths());

            $addPaths = array_map(function ($path) use ($namespace) {
                return base_path() . '/' . "$path/$namespace";
            }, $searchPaths);

            $this->prependNamespace($namespace, $addPaths);
        }
    }

    /**
     * Set the array of paths where the views are being searched.
     *
     * @param  array  $paths
     */
    public function setPaths($paths)
    {
        $this->paths = $paths;
        $this->flush();
    }
}