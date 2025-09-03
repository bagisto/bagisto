<?php

namespace Webkul\Theme;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\View\FileViewFinder;
use Webkul\Theme\Facades\Themes;

class ThemeViewFinder extends FileViewFinder
{
    /**
     * The namespace for admin package views.
     *
     * @var string
     */
    public const ADMIN_PACKAGE_VIEWS_NAMESPACE = 'admin';

    /**
     * The namespace for shop package views.
     *
     * @var string
     */
    public const SHOP_PACKAGE_VIEWS_NAMESPACE = 'shop';

    /**
     * Find a namespaced view considering the active theme.
     *
     * @param  string  $name
     * @return string
     */
    protected function findNamespacedView($name)
    {
        [$namespace, $view] = $this->parseNamespaceSegments($name);

        $isAdmin = Str::contains(request()->url(), config('app.admin_url').'/');

        $this->setActiveTheme($isAdmin);

        $paths = $this->addThemeNamespacePaths($namespace);

        try {
            return $this->findInPaths($view, $paths);
        } catch (\Exception $e) {
            $view = $this->getThemedViewName($namespace, $view, $isAdmin);

            return $this->findInPaths($view, $paths);
        }
    }

    /**
     * Sets the active theme depending on request context.
     */
    protected function setActiveTheme(bool $isAdmin)
    {
        if ($isAdmin) {
            themes()->set(config('themes.admin-default'));
        }
    }

    /**
     * Get the theme-specific view name if not found on first try.
     *
     * @param  string  $namespace
     * @param  string  $view
     * @param  bool  $isAdmin
     * @return string
     */
    protected function getThemedViewName($namespace, $view, $isAdmin)
    {
        $themeCode = themes()->current()->code;

        if (
            ! $isAdmin
            && $namespace !== self::SHOP_PACKAGE_VIEWS_NAMESPACE
            && Str::contains($view, 'shop.')
        ) {
            return Str::replaceFirst('shop.', "shop.$themeCode.", $view);
        }

        if (
            $isAdmin
            && $namespace !== self::ADMIN_PACKAGE_VIEWS_NAMESPACE
            && Str::contains($view, 'admin.')
        ) {
            return Str::replaceFirst('admin.', "admin.$themeCode.", $view);
        }

        return $view;
    }

    /**
     * Add possible paths for this namespace, including theme overlays.
     *
     * @param  string  $namespace
     * @return array
     */
    public function addThemeNamespacePaths($namespace)
    {
        if (! isset($this->hints[$namespace])) {
            return [];
        }

        $paths = [];

        $theme = themes()->current();

        if (
            $theme
            && $theme->code !== 'default'
            && in_array($namespace, [
                self::SHOP_PACKAGE_VIEWS_NAMESPACE,
                self::ADMIN_PACKAGE_VIEWS_NAMESPACE,
            ])
        ) {
            $themeNamespace = $theme->viewsNamespace ?? $theme->code;

            if (isset($this->hints[$themeNamespace])) {
                $paths = [...$this->hints[$themeNamespace]];
            }
        }

        $paths = [...$paths, ...$this->hints[$namespace]];

        $searchPaths = array_diff($this->paths, Themes::getLaravelViewPaths());

        foreach (array_reverse($searchPaths) as $path) {
            $paths = Arr::prepend($paths, base_path($path));
        }

        return $paths;
    }

    /**
     * Add paths for custom error/mails pages in the theme.
     *
     * @param  string  $namespace
     * @param  string|array  $hints
     * @return void
     */
    public function replaceNamespace($namespace, $hints)
    {
        $this->hints[$namespace] = (array) $hints;

        if (in_array($namespace, ['errors', 'mails'])) {
            $searchPaths = array_diff($this->paths, Themes::getLaravelViewPaths());

            $addPaths = array_map(fn ($path) => base_path("$path/$namespace"), $searchPaths);

            $this->prependNamespace($namespace, $addPaths);
        }
    }

    /**
     * Set base paths and clear cache.
     */
    public function setPaths($paths)
    {
        $this->paths = $paths;

        $this->flush();
    }
}
