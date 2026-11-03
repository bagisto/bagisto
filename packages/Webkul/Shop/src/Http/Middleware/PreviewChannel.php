<?php

namespace Webkul\Shop\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PreviewChannel
{
    /**
     * The query parameter the appearance preview names its channel in, on the catalog requests it makes.
     */
    public const PARAMETER = 'preview_channel';

    /**
     * Scope a catalog request to the channel the appearance preview names, for an admin who may edit
     * sections, dropping the parameter so it never reaches the catalog filters.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $channelCode = $request->query(self::PARAMETER);

        $request->query->remove(self::PARAMETER);

        if (
            is_string($channelCode)
            && bouncer()->hasPermission('appearance.sections')
        ) {
            if ($channel = core()->getAllChannels()->firstWhere('code', $channelCode)) {
                core()->setCurrentChannel($channel);
            }
        }

        return $next($request);
    }
}
