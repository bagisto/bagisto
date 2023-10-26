<?php

namespace Webkul\Core\Listeners;

use Spatie\ResponseCache\Events\ResponseCacheHit as ResponseCacheHitEvent;
use Webkul\Core\Jobs\UpdateCreateVisitableIndex;
use Webkul\Core\Jobs\UpdateCreateVisitIndex;

class ResponseCacheHit
{
    /**
     * @param  \Spatie\ResponseCache\Events\ResponseCacheHit  $request
     * @return void
     */
    public function handle(ResponseCacheHitEvent $event)
    {
        $log = visitor()->getLog();

        if (request()->route()->getName() == 'shop.home.index') {
            UpdateCreateVisitIndex::dispatch(null, $log);

            return;
        }

        UpdateCreateVisitableIndex::dispatch(array_merge($log, [
            'path_info' => $event->request->getPathInfo(),
        ]));
    }
}
