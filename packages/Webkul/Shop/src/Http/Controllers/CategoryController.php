<?php

namespace Webkul\Shop\Http\Controllers;

use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Product\Repositories\ProductFlatRepository;

class CategoryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Category\Repositories\CategoryRepository  $categoryRepository
     * @param  \Webkul\Product\Repositories\ProductFlatRepository  $productFlatRepository
     * @return void
     */
    public function __construct(
        protected CategoryRepository $categoryRepository,
        protected ProductFlatRepository $productFlatRepository
        
    )
    {
        parent::__construct();
    }

    /**
     * Get filter attributes for product.
     *
     * @return \Illuminate\Http\Response
     */
    public function getFilterAttributes($categoryId = null, AttributeRepository $attributeRepository)
    {
        $category = $this->categoryRepository->findOrFail($categoryId);

        if (empty($filterAttributes = $category->filterableAttributes)) {
            $filterAttributes = $attributeRepository->getFilterAttributes();
        }

        return response()->json([
            'filter_attributes' => $filterAttributes,
        ]);
    }

    /**
     * Get category product maximum price.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCategoryProductMaximumPrice($categoryId = null)
    {
        $category = $this->categoryRepository->findOrFail($categoryId);

        $maxPrice = $this->productFlatRepository->handleCategoryProductMaximumPrice($category);

        return response()->json([
            'max_price' => $maxPrice,
        ]);
    }
}
