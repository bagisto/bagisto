<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Http\Request;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Marketing\Repositories\URLRewriteRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Theme\Repositories\ThemeCustomizationRepository;

class ProductsCategoriesProxyController extends Controller
{
    /**
     * Using const variable for status
     *
     * @var int Status
     */
    const STATUS = 1;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected CategoryRepository $categoryRepository,
        protected ProductRepository $productRepository,
        protected ThemeCustomizationRepository $themeCustomizationRepository,
        protected URLRewriteRepository $urlRewriteRepository
    ) {}

    /**
     * Show product or category view. If neither category nor product matches, abort with code 404.
     *
     * @return \Illuminate\View\View|\Exception
     */
    public function index(Request $request)
    {
        $slugOrURLKey = urldecode(trim($request->getPathInfo(), '/'));

        /**
         * Support url for chinese, japanese, arabic and english with numbers.
         */
        if (! preg_match('/^([\p{L}\p{N}\p{M}\x{0900}-\x{097F}\x{0590}-\x{05FF}\x{0600}-\x{06FF}\x{0400}-\x{04FF}_-]+\/?)+$/u', $slugOrURLKey)) {
            visitor()->visit();

            $customizations = $this->themeCustomizationRepository->orderBy('sort_order')->findWhere([
                'status'     => self::STATUS,
                'channel_id' => core()->getCurrentChannel()->id,
            ]);

            return view('shop::home.index', compact('customizations'));
        }

        $category = $this->categoryRepository->findBySlug($slugOrURLKey);

        if ($category) {
            visitor()->visit($category);

            return view('shop::categories.view', [
                'category' => $category,
                'params'   => [
                    'sort'  => request()->query('sort'),
                    'limit' => request()->query('limit'),
                    'mode'  => request()->query('mode'),
                ],
            ]);
        }

        if (core()->getConfigData('catalog.products.search.engine') == 'elastic') {
            $searchEngine = core()->getConfigData('catalog.products.search.storefront_mode');
        }

        $product = $this->productRepository
            ->setSearchEngine($searchEngine ?? 'database')
            ->findBySlug($slugOrURLKey);

        if ($product) {
            if (
                ! $product->url_key
                || ! $product->visible_individually
                || ! $product->status
            ) {
                abort(404);
            }

            visitor()->visit($product);

            return view('shop::products.view', compact('product'));
        }

        /**
         * If category is not found, try to find it by slug.
         * If category is found by slug, redirect to category path.
         */
        $trimmedSlug = last(explode('/', $slugOrURLKey));

        $category = $this->categoryRepository->findBySlug($trimmedSlug);

        if ($category) {
            return redirect()->to($trimmedSlug, 301);
        }

        /**
         * If neither category nor product matches,
         * try to find it by url rewrite for category.
         */
        $categoryURLRewrite = $this->urlRewriteRepository->findOneWhere([
            'entity_type'  => 'category',
            'request_path' => $slugOrURLKey,
            'locale'       => app()->getLocale(),
        ]);

        if ($categoryURLRewrite) {
            return redirect()->to($categoryURLRewrite->target_path, $categoryURLRewrite->redirect_type);
        }

        /**
         * If neither category nor product matches,
         * try to find it by url rewrite for product.
         */
        $productURLRewrite = $this->urlRewriteRepository->findOneWhere([
            'entity_type'  => 'product',
            'request_path' => $slugOrURLKey,
        ]);

        if ($productURLRewrite) {
            return redirect()->to($productURLRewrite->target_path, $productURLRewrite->redirect_type);
        }

        abort(404);
    }
}
