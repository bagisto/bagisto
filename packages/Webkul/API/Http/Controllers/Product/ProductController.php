<?php

namespace Webkul\API\Http\Controllers\Product;

use Webkul\API\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Product\Repositories\ProductRepository as Product;
use Auth;

/**
 * Product controller for the APIs of Getting Products
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ProductController extends Controller
{
    protected $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function getAllProducts() {
        $products = $this->product->all();

        return response()->json($products, 200);
    }

    public function getNewProducts() {
        $newProducts = $this->product->getNewProduct();

        return response()->json($newProducts, 200);
    }
}