<?php

namespace Webkul\Shop\Http\Controllers;

use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Product\Repositories\ProductRepository;

class CategoryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Attribute\Repositories\AttributeRepository  $attributeRepository
     * @param  \Webkul\Category\Repositories\CategoryRepository  $categoryRepository
     * @param  \Webkul\Product\Repositories\ProductRepository  $productRepository
     * @return void
     */
    public function __construct(
        protected AttributeRepository $attributeRepository,
        protected CategoryRepository $categoryRepository,
        protected ProductRepository $productRepository
        
    )
    {
        parent::__construct();
    }

    /**
     * Get filterable attributes for category
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFilterableAttributes($categoryId)
    {
        $category = $this->categoryRepository->findOrFail($categoryId);

        if (empty($filterableAttributes = $category->filterableAttributes)) {
            $filterableAttributes = $this->attributeRepository->getFilterableAttributes();
        }

        return response()->json([
            'filter_attributes' => $filterableAttributes,
        ]);
    }

    /**
     * Get category product maximum price.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCategoryProductMaximumPrice($categoryId)
    {
        $maxPrice = $this->productRepository->getCategoryProductMaximumPrice($categoryId);

        return response()->json([
            'max_price' => core()->convertPrice($maxPrice),
        ]);
    }
}
