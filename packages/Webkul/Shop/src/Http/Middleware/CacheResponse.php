<?php

namespace Webkul\Shop\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\ResponseCache\Middlewares\CacheResponse as BaseCacheResponseMiddleware;
use Spatie\ResponseCache\ResponseCache as BaseResponseCache;
use Symfony\Component\HttpFoundation\Response;
use Webkul\Marketing\Repositories\SearchTermRepository;
use Webkul\Marketing\Repositories\URLRewriteRepository;

class CacheResponse extends BaseCacheResponseMiddleware
{
    /**
     * Create a middleware instance.
     *
     * @return void
     */
    public function __construct(protected BaseResponseCache $responseCache)
    {
        $this->responseCache = $responseCache;

        parent::__construct($responseCache);
    }

    /**
     * Handle an incoming request.
     *
     * @param  mixed  ...$args
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$args): Response
    {
        if (! $this->responseCache->enabled($request)) {
            return parent::handle($request, $next, ...$args);
        }

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

        if ($request->route()->getName() == 'shop.product_or_category.index') {
            $slugOrPath = urldecode(trim($request->getPathInfo(), '/'));

            $categoryURLRewrite = app(URLRewriteRepository::class)->findOneWhere([
                'entity_type'  => 'category',
                'request_path' => $slugOrPath,
                'locale'       => app()->getLocale(),
            ]);

            if ($categoryURLRewrite) {
                return redirect()->to($categoryURLRewrite->target_path, $categoryURLRewrite->redirect_type);
            }

            $productURLRewrite = app(URLRewriteRepository::class)->findOneWhere([
                'entity_type'  => 'product',
                'request_path' => $slugOrPath,
            ]);

            if ($productURLRewrite) {
                return redirect()->to($productURLRewrite->target_path, $productURLRewrite->redirect_type);
            }
        }

        return parent::handle($request, $next, ...$args);
    }
}
