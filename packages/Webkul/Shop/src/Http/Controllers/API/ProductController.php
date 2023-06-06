<?php

namespace Webkul\Shop\Http\Controllers\API;

use Illuminate\Http\Resources\Json\JsonResource;
use Webkul\Category\Repositories\CategoryRepository;
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
    ) {
    }

    /**
     * Product listings.
     */
    public function index(): JsonResource
    {
        if (request()->has('category_id')) {
            $category = $this->categoryRepository->findOrFail(request()->input('category_id'));

            return ProductResource::collection(
                $this->productRepository->getAll($category->id)->withPath($category->slug)
            );
        }

        return ProductResource::collection($this->productRepository->getAll());
    }
}
