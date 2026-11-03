<?php

namespace Webkul\Shop\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PreviewChannel
{
    /**
     * The query parameter the appearance preview names its channel in, on the page and its catalog requests.
     */
    public const PARAMETER = 'preview_channel';

    /**
     * The query parameter the appearance preview names the locale being edited in.
     */
    public const LOCALE_PARAMETER = 'preview_locale';

    /**
     * Switch to the channel the appearance preview names, for an admin who may edit sections, keeping the
     * locale and currency to ones that channel offers, and drop the parameters.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $channelCode = $request->query(self::PARAMETER);

        $localeCode = $request->query(self::LOCALE_PARAMETER);

        $request->query->remove(self::PARAMETER);

        $request->query->remove(self::LOCALE_PARAMETER);

        if (
            is_string($channelCode)
            && bouncer()->hasPermission('appearance.sections')
        ) {
            if ($channel = core()->getAllChannels()->firstWhere('code', $channelCode)) {
                core()->setCurrentChannel($channel);

                if ($channel->locales->contains('code', $localeCode)) {
                    app()->setLocale($localeCode);
                } elseif (! $channel->locales->contains('code', app()->getLocale())) {
                    app()->setLocale($channel->default_locale->code);
                }

                if (! $channel->currencies->contains('code', core()->getCurrentCurrencyCode())) {
                    core()->setCurrentCurrency($channel->base_currency->code);
                }
            }
        }

        return $next($request);
    }
}
