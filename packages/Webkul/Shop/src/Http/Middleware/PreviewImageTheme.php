<?php

namespace Webkul\Shop\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Webkul\ImageCache\TemplateRegistry;

class PreviewImageTheme
{
    /**
     * The query parameter the appearance preview names its theme in, on the catalog requests it makes.
     */
    public const PARAMETER = 'preview_theme';

    /**
     * Pin a catalog request's images to the theme the appearance preview names, for an admin who may edit
     * sections, dropping the parameter so it never reaches the catalog filters.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $themeCode = $request->query(self::PARAMETER);

        $request->query->remove(self::PARAMETER);

        if (
            is_string($themeCode)
            && bouncer()->hasPermission('appearance.sections')
        ) {
            $request->attributes->set(TemplateRegistry::PINNED_THEME, $themeCode);
        }

        return $next($request);
    }
}
