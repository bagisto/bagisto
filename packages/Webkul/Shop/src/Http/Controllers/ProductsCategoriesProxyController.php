<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Marketing\Repositories\URLRewriteRepository;
use Webkul\Product\Enums\SearchContextEnum;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Theme\Repositories\SectionRepository;

class ProductsCategoriesProxyController extends Controller
{
    /**
     * Using const variable for status.
     */
    const STATUS = 1;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected CategoryRepository $categoryRepository,
        protected ProductRepository $productRepository,
        protected SectionRepository $sectionRepository,
        protected URLRewriteRepository $urlRewriteRepository
    ) {}

    /**
     * Show the category or product a storefront slug names, or the home page for a path that is no slug, redirecting a
     * nested category path or a rewritten url, and answer not found otherwise, a path under the admin url included.
     *
     * @return View|RedirectResponse
     */
    public function index(Request $request)
    {
        if (is_admin_request($request)) {
            abort(404);
        }

        $slugOrURLKey = urldecode(trim($request->getPathInfo(), '/'));

        if (! $this->isSlug($slugOrURLKey)) {
            $sections = $this->sectionRepository->getRenderable(
                core()->getCurrentChannel()->id,
                core()->getCurrentChannel()->theme
            );

            return view('shop::home.index', compact('sections'));
        }

        $category = $this->categoryRepository->findBySlug($slugOrURLKey);

        if ($category) {
            if (! $category->isAvailableInChannel()) {
                abort(404);
            }

            return view('shop::categories.view', [
                'category' => $category,
                'params' => [
                    'sort' => request()->query('sort'),
                    'limit' => request()->query('limit'),
                    'mode' => request()->query('mode'),
                ],
            ]);
        }

        $product = $this->productRepository
            ->setSearchContext(SearchContextEnum::STOREFRONT)
            ->findBySlug($slugOrURLKey);

        if ($product) {
            if (
                ! $product->url_key
                || ! $product->visible_individually
                || ! $product->status
                || ! $product->isAvailableInChannel()
            ) {
                abort(404);
            }

            $productURLRewrite = $this->urlRewriteRepository->findOneWhere([
                'entity_type' => 'product',
                'request_path' => $slugOrURLKey,
                'locale' => app()->getLocale(),
            ]);

            if ($productURLRewrite) {
                return redirect()->to($productURLRewrite->target_path, $productURLRewrite->redirect_type);
            }

            return view('shop::products.view', compact('product'));
        }

        if (str_contains($slugOrURLKey, '/')) {
            $trimmedSlug = last(explode('/', $slugOrURLKey));

            $category = $this->categoryRepository->findBySlug($trimmedSlug);

            if ($category?->isAvailableInChannel()) {
                return redirect()->to($trimmedSlug, 301);
            }
        }

        $urlRewrite = $this->urlRewriteRepository->findOneWhere([
            'request_path' => $slugOrURLKey,
            'locale' => app()->getLocale(),
        ]);

        if ($urlRewrite) {
            return redirect()->to($urlRewrite->target_path, $urlRewrite->redirect_type);
        }

        abort(404);
    }

    /**
     * Whether a path is a slug of letters, digits and marks in any script, such as English, Chinese, Japanese or
     * Arabic, joined by dashes and underscores, its segments separated by slashes.
     */
    protected function isSlug(string $path): bool
    {
        return (bool) preg_match('/^([\p{L}\p{N}\p{M}\x{0900}-\x{097F}\x{0590}-\x{05FF}\x{0600}-\x{06FF}\x{0400}-\x{04FF}_-]+\/?)+$/u', $path);
    }
}
