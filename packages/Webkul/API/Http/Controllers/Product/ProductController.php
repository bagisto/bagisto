<?php

namespace Webkul\API\Http\Controllers\Product;

use Webkul\API\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Product\Repositories\ProductRepository as Product;

/**
 * Product controller
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ProductController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * ProductRepository object
     *
     * @var array
     */
    protected $product;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Product\Repositories\ProductRepository $product
     * @return void
     */
    public function __construct( Product $product)
    {
        $this->product = $product;

        $this->_config = request('_config');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  string $slug
     * @return \Illuminate\Http\Response
     */
    public function getBySlug($slug)
    {
        $product = $this->product->findBySlugOrFail($slug);

        return response()->json(['message' => 'success', 'product' => $product]);
    }

    public function getAll() {
        $products = $this->product->all();

        return response()->json($products, 200);
    }

    public function getNew() {
        $newProducts = $this->product->getNewProducts();

        return response()->json($newProducts, 200);
    }

    public function getFeatured() {
        $featuredProducts = $this->product->getFeaturedProducts();

        return response()->json($featuredProducts, 200);
    }
}
