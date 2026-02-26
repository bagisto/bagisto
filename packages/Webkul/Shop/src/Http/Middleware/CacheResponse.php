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

    /**
     * Redirect to the search term redirect url if the search term is found.
     */
    if ($request->route()->getName() === 'shop.search.index') {
        if ($request->query('suggest') === '0') {
            return $next($request);
        }

        $searchTerm = app(SearchTermRepository::class)->findOneWhere([
            'term'       => $request->query('query'),
            'channel_id' => core()->getCurrentChannel()->id,
            'locale'     => app()->getLocale(),
        ]);

        if ($searchTerm?->redirect_url && $searchTerm->redirect_url !== $request->fullUrl()) {
            \Log::info('Redirecting search term', [
                'query' => $request->query('query'),
                'redirect_url' => $searchTerm->redirect_url,
            ]);

            return redirect()->to($searchTerm->redirect_url);
        }
    }

    /**
     * Redirect to the target path if the URL rewrite is found.
     */
    if ($request->route()->getName() === 'shop.product_or_category.index') {
        $slugOrPath = urldecode(trim($request->getPathInfo(), '/'));

        $categoryURLRewrite = app(URLRewriteRepository::class)->findOneWhere([
            'entity_type' => 'category',
            'request_path' => $slugOrPath,
            'locale' => app()->getLocale(),
        ]);

        if ($categoryURLRewrite && $categoryURLRewrite->target_path !== $request->path()) {
            \Log::info('Redirecting category URL rewrite', [
                'request_path' => $request->path(),
                'target_path' => $categoryURLRewrite->target_path,
                'redirect_type' => $categoryURLRewrite->redirect_type,
            ]);

            return redirect()->to($categoryURLRewrite->target_path, $categoryURLRewrite->redirect_type);
        }

        $productURLRewrite = app(URLRewriteRepository::class)->findOneWhere([
            'entity_type' => 'product',
            'request_path' => $slugOrPath,
        ]);

        if ($productURLRewrite && $productURLRewrite->target_path !== $request->path()) {
            \Log::info('Redirecting product URL rewrite', [
                'request_path' => $request->path(),
                'target_path' => $productURLRewrite->target_path,
                'redirect_type' => $productURLRewrite->redirect_type,
            ]);

            return redirect()->to($productURLRewrite->target_path, $productURLRewrite->redirect_type);
        }
    }

    /**
     * Redirect to the target path if the CMS page URL rewrite is found.
     */
    if ($request->route()->getName() === 'shop.cms.page') {
        $slug = last(explode('/', $request->getPathInfo()));

        $pageURLRewrite = app(URLRewriteRepository::class)->findOneWhere([
            'entity_type' => 'cms_page',
            'request_path' => $slug,
            'locale' => app()->getLocale(),
        ]);

        if ($pageURLRewrite && $pageURLRewrite->target_path !== $request->path()) {
            \Log::info('Redirecting CMS page URL rewrite', [
                'request_path' => $request->path(),
                'target_path' => $pageURLRewrite->target_path,
                'redirect_type' => $pageURLRewrite->redirect_type,
            ]);

            return redirect()->to($pageURLRewrite->target_path, $pageURLRewrite->redirect_type);
        }
    }

    return parent::handle($request, $next, ...$args);
}
}
