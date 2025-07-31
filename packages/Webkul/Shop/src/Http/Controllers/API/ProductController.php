<?php

namespace Webkul\Shop\Http\Controllers\API;

use Illuminate\Http\Resources\Json\JsonResource;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Marketing\Jobs\UpdateCreateSearchTerm as UpdateCreateSearchTermJob;
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
        protected ProductRepository $productRepository
    ) {}

    /**
     * Product listings.
     */
    public function index(): JsonResource
    {
        if (core()->getConfigData('catalog.products.search.engine') == 'elastic') {
            $searchEngine = core()->getConfigData('catalog.products.search.storefront_mode');
        }

        $searchData = $this->getSearchQueryData();

        $products = $this->productRepository
            ->setSearchEngine($searchEngine ?? 'database')
            ->getAll(array_merge(request()->query(), [
                'query' => $searchData['effective_query'],
                'channel_id'           => core()->getCurrentChannel()->id,
                'status'               => 1,
                'visible_individually' => 1,
            ]));

        $this->trackSearchTermIfApplicable($products, $searchData['original_query']);

        if (! empty(request()->query('query'))) {
            /**
             * Update or create search term only if
             * there is only one filter that is query param
             */
            if (count(request()->except(['mode', 'sort', 'limit'])) == 1) {
                UpdateCreateSearchTermJob::dispatch([
                    'term'       => $searchData['original_query'],
                    'results'    => $products->total(),
                    'channel_id' => core()->getCurrentChannel()->id,
                    'locale'     => app()->getLocale(),
                ]);
            }
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
        return $this->getAssociatedProducts($id, 'related_products');
    }

    /**
     * Up-sell product listings.
     *
     * @param  int  $id
     */
    public function upSellProducts($id): JsonResource
    {
        return $this->getAssociatedProducts($id, 'up_sells');
    }

    /**
     * Get associated products (related/up-sell).
     */
    protected function getAssociatedProducts(int $id, string $relationType): JsonResource
    {
        $product = $this->productRepository->findOrFail($id);

        $configKey = match ($relationType) {
            'related_products' => 'no_of_related_products',
            'up_sells'         => 'no_of_up_sells_products'
        };

        $products = $product->{$relationType}()
            ->take(core()->getConfigData("catalog.products.product_view_page.{$configKey}"))
            ->get();

        return ProductResource::collection($products);
    }
}
