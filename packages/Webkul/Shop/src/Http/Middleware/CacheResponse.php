<?php

namespace Webkul\Shop\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\ResponseCache\Middlewares\CacheResponse as BaseCacheResponse;
use Symfony\Component\HttpFoundation\Response;
use Webkul\Marketing\Repositories\SearchTermRepository;

class CacheResponse extends BaseCacheResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  mixed  ...$args
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$args): Response
    {
        if ($request->route()->getName() == 'shop.search.index') {
            $searchTerm = app(SearchTermRepository::class)->findOneWhere([
                'term'       => request()->query('query'),
                'channel_id' => core()->getCurrentChannel()->id,
                'locale'     => app()->getLocale(),
            ]);

            if ($searchTerm?->redirect_url) {
                return redirect()->to($searchTerm->redirect_url);
            }
        }

        return parent::handle($request, $next, ...$args);
    }
}
