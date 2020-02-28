<?php

namespace Webkul\Velocity\Http\Controllers\Shop;

use Illuminate\Http\Request;

use Cart;
use Webkul\Product\Helpers\ProductImage;
use Webkul\Velocity\Http\Shop\Controllers;
use Webkul\Checkout\Contracts\Cart as CartModel;
use Webkul\Product\Repositories\SearchRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Velocity\Repositories\VelocityCustomerCompareProductRepository;
use Webkul\Velocity\Repositories\Product\ProductRepository as VelocityProductRepository;

/**
 * Shop controller
 *
 * @author  Shubham Mehrotra <shubhammehrotra.symfony@webkul.com> @shubhwebkul
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
 class ShopController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * Webkul\Product\Helpers\ProductImage object
     *
     * @var ProductImage
    */
    protected $productImageHelper;

    /**
     * SearchRepository object
     *
     * @var SearchRepository
    */
    protected $searchRepository;

    /**
     * ProductRepository object
     *
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * ProductRepository object of velocity package
     *
     * @var ProductRepository
     */
    protected $velocityProductRepository;

    /**
     * CategoryRepository object of velocity package
     *
     * @var CategoryRepository
     */
    protected $categoryRepository;

    /**
     * VelocityCustomerCompareProductRepository object of velocity package
     *
     * @var VelocityCustomerCompareProductRepository
     */
    protected $velocityCompareProductsRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Product\Helpers\ProductImage $productImageHelper
     * @param  \Webkul\Product\Repositories\SearchRepository $searchRepository
     * @param  \Webkul\Product\Repositories\ProductRepository $productRepository
     * @param  \Webkul\Category\Repositories\CategoryRepository $categoryRepository
     * @param  \Webkul\Velocity\Repositories\Product\ProductRepository $velocityProductRepository
     * @param  \Webkul\Velocity\Repositories\VelocityCustomerCompareProductRepository $velocityCompareProductsRepository
     * @return void
    */
    public function __construct(
        ProductImage $productImageHelper,
        SearchRepository $searchRepository,
        ProductRepository $productRepository,
        CategoryRepository $categoryRepository,
        VelocityProductRepository $velocityProductRepository,
        VelocityCustomerCompareProductRepository $velocityCompareProductsRepository
    ) {
        $this->_config = request('_config');

        $this->searchRepository = $searchRepository;
        $this->productRepository = $productRepository;
        $this->productImageHelper = $productImageHelper;
        $this->categoryRepository = $categoryRepository;
        $this->velocityProductRepository = $velocityProductRepository;
        $this->velocityCompareProductsRepository = $velocityCompareProductsRepository;
    }

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
                    'name'          => $product->name,
                    'urlKey'        => $product->url_key,
                    'image'         => $galleryImages['small_image_url'],
                    'priceHTML'     => $product->getTypeInstance()->getPriceHtml(),
                    'totalReviews'  => $productReviewHelper->getTotalReviews($product),
                    'rating'        => ceil($productReviewHelper->getAverageRating($product)),
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

    public function categoryDetails()
    {
        $slug = request()->get('category-slug');

        switch ($slug) {
            case 'new-products':
            case 'featured-products':
                $formattedProducts = [];
                $count = request()->get('count');

                $productRepository = app('Webkul\Velocity\Repositories\Product\ProductRepository');

                if ($slug == "new-products") {
                    $products = $productRepository->getNewProducts($count);
                } else if ($slug == "featured-products") {
                    $products = $productRepository->getFeaturedProducts($count);
                }

                foreach ($products as $product) {
                    array_push($formattedProducts, $this->formatProduct($product));
                }

                $response = [
                    'status' => true,
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

                        $productDetails = array_merge($productDetails, $this->formatProduct($product));
                        array_push($customizedProducts, $productDetails);
                    }

                    $response = [
                        'status'            => true,
                        'list'              => $list,
                        'categoryDetails'   => $categoryDetails,
                        'categoryProducts'  => $customizedProducts,
                    ];
                }

                break;
        }

        return $response ?? [
            'status' => false,
        ];
    }

    public function fetchCategories()
    {
        $formattedCategories = [];
        $categories = $this->categoryRepository->getVisibleCategoryTree(core()->getCurrentChannel()->root_category_id);

        foreach ($categories as $category) {
            array_push($formattedCategories, $this->getCategoryFilteredData($category));
        }

        return [
            'status'        => true,
            'categories'    => $formattedCategories,
        ];
    }

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

    private function getCategoryFilteredData($category)
    {
        $formattedChildCategory = [];

        foreach ($category->children as $child) {
            array_push($formattedChildCategory, $this->getCategoryFilteredData($child));
        }

        return [
            'id'                    => $category->id,
            'slug'                  => $category->slug,
            'name'                  => $category->name,
            'children'              => $formattedChildCategory,
            'category_icon_path'    => $category->category_icon_path,
        ];
    }

    private function formatProduct($product, $list = false)
    {
        $reviewHelper = app('Webkul\Product\Helpers\Review');

        $totalReviews = $reviewHelper->getTotalReviews($product);

        $avgRatings = ceil($reviewHelper->getAverageRating($product));

        $galleryImages = $this->productImageHelper->getGalleryImages($product);
        $productImage = $this->productImageHelper->getProductBaseImage($product)['medium_image_url'];

        return [
            'avgRating'         => $avgRatings,
            'totalReviews'      => $totalReviews,
            'image'             => $productImage,
            'galleryImages'     => $galleryImages,
            'name'              => $product->name,
            'slug'              => $product->url_key,
            'description'       => $product->description,
            'shortDescription'  => $product->short_description,
            'firstReviewText'   => trans('velocity::app.products.be-first-review'),
            'priceHTML'         => view('shop::products.price', ['product' => $product])->render(),
            'addToCartHtml'     => view('shop::products.add-to-cart', [
                'product'           => $product,
                'showCompare'       => true,
                'addWishlistClass'  => !(isset($list) && $list) ? '' : '',
                'addToCartBtnClass' => !(isset($list) && $list) ? 'small-padding' : '',
            ])->render(),
        ];
    }

    /**
     * Function for guests user to add the product in the cart.
     *
     * @return Mixed
     */
    public function addProductToCart()
    {
        try {
            $cart = Cart::getCart();
            $formattedBeforeItems = [];
            $id = request()->get('product_id');
            $velocityHelper = app('Webkul\Velocity\Helpers\Helper');

            if ($cart) {
                $beforeItems = $cart->items;

                foreach ($beforeItems as $item) {
                    array_push($formattedBeforeItems, $velocityHelper->formatCartItem($item));
                }
            }

            $cart = Cart::addProduct($id, request()->all());

            if (is_array($cart) && isset($cart['warning'])) {
                $response = [
                    'status' => 'warning',
                    'message' => $cart['warning'],
                ];
            }

            if ($cart instanceof CartModel) {
                $items = $cart->items;
                $formattedItems = [];

                foreach ($items as $item) {
                    array_push($formattedItems, $velocityHelper->formatCartItem($item));
                }

                $response = [
                    'status'            => 'success',
                    'totalCartItems'    => sizeof($items),
                    'message'           => trans('shop::app.checkout.cart.item.success'),
                    'addedItems'        => array_slice($formattedItems, sizeof($formattedBeforeItems)),
                ];

                if ($customer = auth()->guard('customer')->user()) {
                    $this->wishlistRepository->deleteWhere(['product_id' => $id, 'customer_id' => $customer->id]);
                }

                if (request()->get('is_buy_now')) {
                    return redirect()->route('shop.checkout.onepage.index');
                }
            }
        } catch(\Exception $exception) {
            $product = $this->productRepository->find($id);

            $response = [
                'status'            => 'false',
                'message'           => trans($exception->getMessage()),
                'redirectionRoute'  => route('shop.productOrCategory.index', $product->url_key),
            ];
        }

        return $response ?? [
            'status'    => 'error',
            'message'   => trans('velocity::app.error.something-went-wrong'),
        ];
    }

    /**
     * function for customers to get products in comparison.
     *
     * @return Mixed
     */
    public function getComparisonList(Request $request)
    {
        if (request()->get('data')) {
            $productSlugs = null;
            $productCollection = [];

            if (auth()->guard('customer')->user()) {
                $productCollection = $this->velocityCompareProductsRepository
                    ->leftJoin(
                        'product_flat',
                        'velocity_customer_compare_products.product_flat_id',
                        'product_flat.id'
                    )
                    ->where('customer_id', auth()->guard('customer')->user()->id)
                    ->get()
                    ->toArray();

                foreach ($productCollection as $index => $customerCompare) {
                    $product = $this->productRepository->find($customerCompare['product_id']);
                    $formattedProduct = $this->formatProduct($product);

                    $productCollection[$index]['image'] = $formattedProduct['image'];
                    $productCollection[$index]['priceHTML'] = $formattedProduct['priceHTML'];
                    $productCollection[$index]['addToCartHtml'] = $formattedProduct['addToCartHtml'];
                }
            } else {
                // for product details
                if ($items = request()->get('items')) {
                    $productSlugs = explode('&', $items);

                    foreach ($productSlugs as $slug) {
                        $product = $this->productRepository->findBySlug($slug);

                        array_push($productCollection, $this->formatProduct($product));
                    }
                }
            }

            $response = [
                'status'    => 'success',
                'products'  => $productCollection,
            ];
        } else {
            $response = view($this->_config['view']);
        }

        return $response;
    }

    /**
     * function for customers to add product in comparison.
     *
     * @return json
     */
    public function addCompareProduct()
    {
        $productId = request()->get('productId');
        $customerId = auth()->guard('customer')->user()->id;

        $compareProduct = $this->velocityCompareProductsRepository->findOneByField([
            'customer_id' => $customerId,
            'product_flat_id' => $productId,
        ]);

        if (! $compareProduct) {
            // insert new row
            $this->velocityCompareProductsRepository->create([
                'customer_id' => $customerId,
                'product_flat_id'  => $productId,
            ]);

            return response()->json([
                'status'    => 'success',
                'message'   => trans('velocity::app.customer.compare.added'),
                'label'     => trans('velocity::app.shop.general.alert.success'),
            ], 201);
        } else {
            return response()->json([
                'status'    => 'success',
                'label'     => trans('velocity::app.shop.general.alert.success'),
                'message'   => trans('velocity::app.customer.compare.already_added'),
            ], 200);
        }
    }

    /**
     * function for customers to delete product in comparison.
     *
     * @return json
     */
    public function deleteComparisonProduct()
    {
        // either delete all or individual
        if (request()->get('productId') == 'all') {
            // delete all
            $customerId = auth()->guard('customer')->user()->id;
            $this->velocityCompareProductsRepository->deleteWhere([
                'customer_id' => auth()->guard('customer')->user()->id,
            ]);
        } else {
            // delete individual
            $this->velocityCompareProductsRepository->deleteWhere([
                'product_flat_id' => request()->get('productId'),
                'customer_id' => auth()->guard('customer')->user()->id,
            ]);

            // $comparedList = json_decode($customer->compared_product, true);

            // $index = array_search($productId, $comparedList);

            // if ($index > -1) {
            //     unset($comparedList[$index]);

            //     $this->velocityCustomerDataRepository->update([
            //         'compared_product' => json_encode($comparedList),
            //     ], $customer->id);
            // }
        }

        return [
            'status'  => 'success',
            'message' => trans('velocity::app.customer.compare.removed'),
            'label'   => trans('velocity::app.shop.general.alert.success'),
        ];
    }
}
