<?php

namespace Webkul\Cart\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Cart\Repositories\CartRepository;
use Webkul\Cart\Repositories\CartItemsRepository;
use Session;

/**
 * Cart controller for the customer
 * and guest users for adding and
 * removing the products in the
 * cart.
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CartController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $_config;

    protected $cart;


    public function __construct(CartRepository $cart)
    {
        $this->middleware(['customer', 'guest']);

        $this->_config = request('_config');

        $this->cart = $cart;
    }

    public function add() {
        return "Adding Items to Cart";
    }
}