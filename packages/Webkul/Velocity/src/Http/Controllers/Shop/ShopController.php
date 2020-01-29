<?php

namespace Webkul\Velocity\Http\Controllers\Shop;

use Webkul\Velocity\Helpers\Helper;
use Webkul\Velocity\Http\Shop\Controllers;
use Webkul\Product\Repositories\SearchRepository;
use Webkul\Velocity\Repositories\Product\ProductRepository;

/**
 * Search controller
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
     * Create a new controller instance.
     *
     * @param  \Webkul\Product\Repositories\SearchRepository $searchRepository
     * @return void
    */
    public function __construct(
        SearchRepository $searchRepository,
        ProductRepository $productRepository
    ) {
        $this->_config = request('_config');

        $this->searchRepository = $searchRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * Index to handle the view loaded with the search results
     *
     * @return \Illuminate\View\View
     */
    public function search()
    {
        $results = $this->productRepository->searchProductsFromCategory(request()->all());

        return view($this->_config['view'])->with('results', $results ? $results : null);
    }

    public function fetchProductDetails($slug)
    {
        $product = $this->productRepository->findBySlug($slug);

        if ($product) {
            $productReviewHelper = app('Webkul\Product\Helpers\Review');
            $productImageHelper = app('Webkul\Product\Helpers\ProductImage');

            $response = [
                'status' => true,
                'details' => [
                    'name' => $product->name,
                    'urlKey' => $product->url_key,
                    'priceHTML' => $product->getTypeInstance()->getPriceHtml(),
                    'totalReviews' => $productReviewHelper->getTotalReviews($product),
                    'rating' => ceil($productReviewHelper->getAverageRating($product)),
                    'image' => $productImageHelper->getProductBaseImage($product)['small_image_url'],
                ]
            ];
        } else {
            $response = [
                'status' => false
            ];
        }

        return $response;
    }
}
