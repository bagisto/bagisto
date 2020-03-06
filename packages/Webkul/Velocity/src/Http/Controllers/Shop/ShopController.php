<?php

namespace Webkul\Velocity\Http\Controllers\Shop;

use Illuminate\Http\Request;
use Webkul\Velocity\Http\Shop\Controllers;
use Webkul\Checkout\Contracts\Cart as CartModel;
use Cart;

class ShopController extends Controller
{
    /**
     * Index to handle the view loaded with the search results
     *
     * @return \Illuminate\View\View
     */
    public function search()
    {
        $results = $this->velocityProductRepository->searchProductsFromCategory(request()->all());

        return view($this->_config['view'])->with('results', $results ? $results : null);
    }

    public function fetchProductDetails($slug)
    {
        $product = $this->productRepository->findBySlug($slug);

        if ($product) {
            $productReviewHelper = app('Webkul\Product\Helpers\Review');
            
            $galleryImages = $this->productImageHelper->getProductBaseImage($product);

            $response = [
                'status'  => true,
                'details' => [
                    'name'         => $product->name,
                    'urlKey'       => $product->url_key,
                    'priceHTML'    => $product->getTypeInstance()->getPriceHtml(),
                    'totalReviews' => $productReviewHelper->getTotalReviews($product),
                    'rating'       => ceil($productReviewHelper->getAverageRating($product)),
                    'image'        => $galleryImages['small_image_url'],
                ]
            ];
        } else {
            $response = [
                'status' => false,
                'slug'   => $slug,
            ];
        }

        return $response;
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function categoryDetails()
    {
        $slug = request()->get('category-slug');

        switch ($slug) {
            case 'new-products':
            case 'featured-products':
                $formattedProducts = [];
                $count = request()->get('count');

                if ($slug == "new-products") {
                    $products = $this->velocityProductRepository->getNewProducts($count);
                } else if ($slug == "featured-products") {
                    $products = $this->velocityProductRepository->getFeaturedProducts($count);
                }

                foreach ($products as $product) {
                    array_push($formattedProducts, $this->velocityHelper->formatProduct($product));
                }

                $response = [
                    'status'   => true,
                    'products' => $formattedProducts,
                ];

                break;
            default:
                $categoryDetails = $this->categoryRepository->findByPath($slug);

                if ($categoryDetails) {
                    $list = false;
                    $customizedProducts = [];
                    $products = $this->productRepository->getAll($categoryDetails->id);

                    foreach ($products as $product) {
                        $productDetails = [];

                        $productDetails = array_merge($productDetails, $this->velocityHelper->formatProduct($product));
                        
                        array_push($customizedProducts, $productDetails);
                    }

                    $response = [
                        'status'           => true,
                        'list'             => $list,
                        'categoryDetails'  => $categoryDetails,
                        'categoryProducts' => $customizedProducts,
                    ];
                }

                break;
        }

        return $response ?? [
            'status' => false,
        ];
    }

    /**
     * @return array
     */
    public function fetchCategories()
    {
        $formattedCategories = [];
        $categories = $this->categoryRepository->getVisibleCategoryTree(core()->getCurrentChannel()->root_category_id);

        foreach ($categories as $category) {
            array_push($formattedCategories, $this->getCategoryFilteredData($category));
        }

        return [
            'status'     => true,
            'categories' => $formattedCategories,
        ];
    }

    /**
     * @param  string  $slug
     * @return array
     */
    public function fetchFancyCategoryDetails($slug)
    {
        $categoryDetails = $this->categoryRepository->findByPath($slug);

        if ($categoryDetails) {
            $response = [
                'status'          => true,
                'categoryDetails' => $this->getCategoryFilteredData($categoryDetails)
            ];
        }

        return $response ?? [
            'status' => false,
        ];
    }

    /**
     * @param  \Webkul\Category\Contracts\Category  $category
     * @return array
     */
    private function getCategoryFilteredData($category)
    {
        $formattedChildCategory = [];

        foreach ($category->children as $child) {
            array_push($formattedChildCategory, $this->getCategoryFilteredData($child));
        }

        return [
            'id'                 => $category->id,
            'slug'               => $category->slug,
            'name'               => $category->name,
            'children'           => $formattedChildCategory,
            'category_icon_path' => $category->category_icon_path,
        ];
    }

    /**
     * @return \Illuminate\View\View
     */
    public function getWishlistList()
    {
        return view($this->_config['view']);
    }

    /**
     * this function will provide the count of wishlist and comparison for logged in user
     *
     * @return \Illuminate\Http\Response
     */
    public function getItemsCount()
    {
        if ($customer = auth()->guard('customer')->user()) {
            $wishlistItemsCount = $this->wishlistRepository->count([
                'customer_id' => $customer->id,
                'channel_id' => core()->getCurrentChannel()->id,
            ]);

            $comparedItemsCount = $this->compareProductsRepository->count([
                'customer_id' => $customer->id,
            ]);

            $response = [
                'status' => true,
                'compareProductsCount'    => $comparedItemsCount,
                'wishlistedProductsCount' => $wishlistItemsCount,
            ];
        }

        return response()->json($response ?? [
            'status' => false
        ]);
    }

    /**
     * This function will provide details of multiple product
     *
     * @return \Illuminate\Http\Response
     */
    public function getDetailedProducts()
    {
        // for product details
        if ($items = request()->get('items')) {
            $productCollection = $this->velocityHelper->fetchProductCollection($items);

            $response = [
                'status' => 'success',
                'products' => $productCollection,
            ];
        }

        return response()->json($response ?? [
            'status' => false
        ]);
    }
}
