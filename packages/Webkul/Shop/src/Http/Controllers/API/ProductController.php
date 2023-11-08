<?php

namespace Webkul\Shop\Http\Controllers\API;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Resources\Json\JsonResource;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Marketing\Repositories\SearchTermRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Shop\Http\Resources\ProductResource;

class ProductController extends APIController
{
    /**
     * Create a controller instance.
     *
     * @return void
     */
    public function __construct(
        protected CategoryRepository $categoryRepository,
        protected SearchTermRepository $searchTermRepository,
        protected ProductRepository $productRepository
    ) {
    }

    /**
     * Product listings.
     */
    public function index(): JsonResource
    {
        $products = $this->productRepository->getAll();

        if (! empty(request()->query('query'))) {
            $this->searchTermRepository->updateOrCreate([
                'term'       => request()->query('query'),
                'channel_id' => core()->getCurrentChannel()->id,
                'locale'     => app()->getLocale(),
            ], [
                'results' => $products->total(),
                'uses'    => DB::raw('uses + 1'),
            ]);
        }

        return ProductResource::collection($products);
    }

    /**
     * Related product listings.
     *
     * @param  int  $id
     */
    public function relatedProducts($id): JsonResource
    {
        $product = $this->productRepository->findOrFail($id);

        $relatedProducts = $product->related_products()
            ->take(core()->getConfigData('catalog.products.product_view_page.no_of_related_products'))
            ->get();

        return ProductResource::collection($relatedProducts);
    }

    /**
     * Up-sell product listings.
     *
     * @param  int  $id
     */
    public function upSellProducts($id): JsonResource
    {
        $product = $this->productRepository->findOrFail($id);

        $upSellProducts = $product->up_sells()
            ->take(core()->getConfigData('catalog.products.product_view_page.no_of_up_sells_products'))
            ->get();

        return ProductResource::collection($upSellProducts);
    }
}
