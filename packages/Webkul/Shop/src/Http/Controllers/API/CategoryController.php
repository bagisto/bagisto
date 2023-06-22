<?php

namespace Webkul\Shop\Http\Controllers\API;

use Illuminate\Http\Resources\Json\JsonResource;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Shop\Http\Resources\AttributeResource;
use Webkul\Shop\Http\Resources\CategoryResource;

class CategoryController extends APIController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected AttributeRepository $attributeRepository,
        protected CategoryRepository $categoryRepository,
        protected ProductRepository $productRepository

    ) {
    }

    /**
     * Get all categories.
     */
    public function index(): JsonResource
    {
        $categories = $this->categoryRepository->scopeQuery(function ($query) {
            return $query->whereNotNull('parent_id')->where('status', 1);
        })->paginate();

        return CategoryResource::collection($categories);
    }

    /**
     * Get filterable attributes for category.
     */
    public function getAttributes($categoryId): JsonResource
    {
        $category = $this->categoryRepository->findOrFail($categoryId);

        if (empty($filterableAttributes = $category->filterableAttributes)) {
            $filterableAttributes = $this->attributeRepository->getFilterableAttributes();
        }

        return AttributeResource::collection($filterableAttributes);
    }

    /**
     * Get product maximum price.
     */
    public function getProductMaxPrice($categoryId): JsonResource
    {
        $maxPrice = $this->productRepository->getMaxPriceByCategory($categoryId);

        return new JsonResource([
            'max_price' => core()->convertPrice($maxPrice),
        ]);
    }
}
